<?php
	if(isset($_FILES['arquivo']))
	{
		$mensagem = "";
		// Pasta onde o arquivo vai ser salvo
		$_UP['pasta'] = '../uploads/';
		// Tamanho máximo do arquivo (em Bytes)
		$_UP['tamanho'] = 1024 * 1024 * 50; // 2Mb
		// Array com as extensões permitidas
		$_UP['extensoes'] = array('xls', 'xlsx');
		// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
		$_UP['renomeia'] = true;
		// Array com os tipos de erros de upload do PHP
		$_UP['erros'][0] = 'Não houve erro';
		$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
		$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
		$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
		$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
		// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
		if ($_FILES['arquivo']['error'] != 0)
		{
		  die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']]);
		  $mensagem .= "Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']];
		  exit; // Para a execução do script
		}
		// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
		// Faz a verificação da extensão do arquivo
		// Faz a verificação do tamanho do arquivo
		if ($_UP['tamanho'] < $_FILES['arquivo']['size'])
		{
		  $mensagem .= "O arquivo enviado é muito grande, envie arquivos de até 50Mb.";
		  exit;
		}
		// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
		// Primeiro verifica se deve trocar o nome do arquivo
		if ($_UP['renomeia'] == true)
		{
			// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
			$dataUnique = date('YmdHis');
			$arquivo_final = $dataUnique."_".semAcento($_FILES['arquivo']['name']);
		}
		else
		{
			// Mantém o nome original do arquivo
			$nome_final = $_FILES['arquivo']['name'];
		}  
		// Depois verifica se é possível mover o arquivo para a pasta escolhida
		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $arquivo_final))
		{
			// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
			$mensagem .=  "Upload efetuado com sucesso!<br />";
			$mensagem .= '<a href="' . $_UP['pasta'] . $arquivo_final . '">Clique aqui para acessar o arquivo</a>';
			require_once("../include/phpexcel/Classes/PHPExcel.php");
			$inputFileName = $_UP['pasta'] . $arquivo_final;	
			//  Read your Excel workbook
			try
			{
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			}
			catch(Exception $e)
			{
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			//Apagamos a tabela saldo_unidade
			$sql_limpa = "TRUNCATE TABLE saldo_unidade";
			if(mysqli_query($con,$sql_limpa))
			{
				$mensagem .= "<br />Tabela saldo_unidade limpa <br />";	
			}
			else
			{
				$mensagem .= "Erro ao limpar a tabela saldo_unidade <br />";	
			}		
			//  Loop through each row of the worksheet in turn
			for ($row = 1; $row <= $highestRow; $row++)
			{ 
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					NULL,
					TRUE,
					FALSE);
				//  Insert row data array into your database of choice here
				if($row == 1)
				{
					// Dados da Tabela
					$mensagem .= "Situação de projeto atividade";
					$data = date("Y-m-d H:i:s");
					$mens_sof = "Situação de projeto atividade - ".$data;
					$sql_atualizacao = "INSERT INTO `atualizacao` (`id`, `data`, `texto`) VALUES (NULL, '$data', '$mens_sof')";
					$query_atualizacao = mysqli_query($con,$sql_atualizacao);
					if($query_atualizacao)
					{
						$mensagem .= " Atualização registrada.<br />";	
					}
				}	
				if($row > 2)
				{
					// Insere na tabela saldo_unidade
					$detalhes = $rowData[0][0];
					$b = $rowData[0][1];
					$c = $rowData[0][2];
					$d = $rowData[0][3];
					$e = $rowData[0][4];
					$saldoorcado = $rowData[0][5];
					$g = $rowData[0][6];
					$h = $rowData[0][7];
					$i = $rowData[0][8];
					$creditotramitacao = $rowData[0][9];
					$k = $rowData[0][10];
					$l = $rowData[0][11];
					$m = $rowData[0][12];
					$totalcongelados = $rowData[0][13];
					$o = $rowData[0][14];
					$p = $rowData[0][15];
					$saldodotacao = $rowData[0][16];
					$r = $rowData[0][17];
					$arquivo = $rowData[0][18];
					$exercicio = $rowData[0][19];
					$saldoreservas = $rowData[0][20];
					$sql_insere = "INSERT INTO  `saldo_unidade` 
						(`detalhes` , 
						`B` , 
						`C` , 
						`D` , 
						`E` , 
						`saldoOrcado` , 
						`G` , 
						`H` , 
						`I` , 
						`creditoTramitacao` , 
						`K` , 
						`L` , 
						`M` , 
						`totalCongelados` , 
						`O` ,
						`P` ,
						`saldoDotacao` ,
						`R` ,
						`arquivo` ,
						`exercicio` ,
						`saldoReservas` )
						VALUES 
						('$detalhes' , 
						'$b' , 
						'$c' , 
						'$d' , 
						'$e' , 
						'$saldoorcado' , 
						'$g' , 
						'$h' , 
						'$i' , 
						'$creditotramitacao' , 
						'$k' , 
						'$l' , 
						'$m' , 
						'$totalcongelados' , 
						'$o' ,
						'$p' ,
						'$saldodotacao' ,
						'$r' ,
						'$arquivo' ,
						'$exercicio' ,
						'$saldoreservas') ";
					$query_insere = mysqli_query($con,$sql_insere);
				}
			}
			if($query_insere)
			{
				$mensagem .= "Arquivo inserido na tabela empenhado_raw. <br />";
				$sql_insere_orcamentoCentral = "INSERT orcamento_central 
					(dotacao, 
					saldoOrcado, 
					creditoTramitacao, 
					totalCongelado, 
					saldoDotacao, 
					saldoReservas)
					SELECT detalhes, 
					saldoOrcado, 
					creditoTramitacao, 
					totalCongelados, 
					saldoDotacao, 
					saldoReservas 
					FROM `saldo_unidade` 
					WHERE `detalhes` LIKE '______13%'";
				$query_orcamentoCentral = mysqli_query($con,$sql_insere_orcamentoCentral);
			}
			else
			{
				$mensagem .= "erro ao inserir. <br />";
			}
		}
		else
		{
			// Não foi possível fazer o upload, provavelmente a pasta está incorreta
			$mensagem =  "Não foi possível enviar o arquivo, tente novamente";
		}	
	}
?>
<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<div class="sub-title">
				<h2>Integração SOF / SINCOR</h2>
				<h4>Aqui você pode subir arquivos de saldo por unidade</h4>
				<h3></h3>
			</div>       
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
		<?php
			if(isset($rowData))
			{
				if(isset($mensagem))
				{
					echo $mensagem;
				} 	
			}
			else
			{
		?>
				<form method="POST" action="?perfil=contabilidade&p=sof_saldoPorUnidade" enctype="multipart/form-data">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Arquivo em EXCEL (Máximo 50M)</strong><br/>
							<input type="file" class="form-control" name="arquivo" /	>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="enviado" />
							<input type="submit" value="Fazer upload" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form> 
		<?php
			}
		?>                  
	  		</div>	
	  	</div>
	</div>
</section> 