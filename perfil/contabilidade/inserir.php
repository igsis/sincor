<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<div class="sub-title">
				<h2>Integração SOF / SINCOR</h2>
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
				<form method="POST" action="?perfil=admin&p=sof" enctype="multipart/form-data">
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

<?php
if(isset($_GET['contabilidade']))
	{
		//Primeiro é preciso importar as tabelas no formado ods e colocar como a primeira linha como nome dos campos
		$con = bancoMysqli(); //conecta ao banco
		$mensagem = "<i>Atualizando tabela SOF com SINCOR</i><br /><br />";
		//verifica se há no banco de dados a tabela saldo_unidade2
		$result_upload = mysqli_query($con,"SHOW TABLES LIKE 'saldo_unidade2'");
		$tableExists_upload = mysqli_num_rows($result_upload);
		if($tableExists_upload > 0)
		{
			//verifica se a tabela existe e apaga se for o caso
			$table = 'saldo_unidade';
			$result = mysqli_query($con,"SHOW TABLES LIKE '$table'");
			$tableExists = mysqli_num_rows($result);
			if($tableExists > 0)
			{
				$apagar_tabela = "DROP TABLE 'saldo_unidade'";
				$query_apagar_tabela = mysqli_query($con,$apagar_tabela);
				if($query_apagar_tabela)
				{
					$re_tabela = "RENAME TABLE `saldo_unidade2` TO `saldo_unidade`";
					$query_re_tabela = mysqli_query($con,$re_tabela);
					if($query_re_tabela)
					{
						$mensagem .= "Tabela saldo_unidade renomeada com sucesso!<br />";	
					}
					else
					{
						$mensagem .= "Erro ao renomear tabela saldo_unidade (1)<br />";	
					}	
				}
				else
				{
					$mensagem .= "Erro ao apagar tabela saldo_unidade (2)<br />";	
				}	
			}
			else
			{
				$re_tabela = "RENAME TABLE `sincor`.`saldo_unidade2` TO `sincor`.`saldo_unidade`;";
				$query_re_tabela = mysqli_query($con,$re_tabela);
				if($query_re_tabela)
				{
					$mensagem .= "Tabela saldo_unidade renomeada com sucesso!<br />";	
				}
				else
				{
					$mensagem .= "Erro ao renomear tabela saldo_unidade (3)<br />";	
				}			
			}
			if($query_re_tabela)
			{
				$sql_id = "ALTER TABLE saldo_unidade ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
				$query_id = mysqli_query($con,$sql_id);
				if($query_id)
				{
					$mensagem .= "Criação de campo id realizada com sucesso!<br />";
				}
				else
				{
					$mensagem .= "Falha na criação de campo id...<br />";	
				}
				$sql_re_campos[1] = "ALTER TABLE `saldo_unidade` CHANGE `DATA EMPENHO` `data_empenho` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[2] = "ALTER TABLE `saldo_unidade` CHANGE `ANO_` `ano` INT(4) NULL DEFAULT NULL;";
				$sql_re_campos[3] = "ALTER TABLE `saldo_unidade` CHANGE `EMPENHO` `empenho` INT(12) NULL DEFAULT NULL;";
				$sql_re_campos[4] = "ALTER TABLE `saldo_unidade` CHANGE `DOTAÇÃO` `dotacao` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[5] =	"ALTER TABLE `saldo_unidade` CHANGE `PROCESSO` `processo` BIGINT(16) NULL DEFAULT NULL;";
				$sql_re_campos[6] =	"ALTER TABLE `saldo_unidade` CHANGE `DESCRIÇÃO` `descrica` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[7] =	"ALTER TABLE `saldo_unidade` CHANGE `VALOR` `valor` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[8] =	"ALTER TABLE `saldo_unidade` CHANGE `CANCELAMENTO` `cancelamento` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[9] =	"ALTER TABLE `saldo_unidade` CHANGE `LIQUIDADO` `liquidado` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[10] = "ALTER TABLE `saldo_unidade` CHANGE `PAGO` `pago` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[11] = "ALTER TABLE `saldo_unidade` CHANGE `VALOR A LIQUIDAR` `valor_a_liquidar` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[12] = "ALTER TABLE `saldo_unidade` CHANGE `TOTAL A PAGAR` `total` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[13] = "ALTER TABLE `saldo_unidade` CHANGE `RAZÃO SOCIAL` `razao_social` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$sql_re_campos[14] = "ALTER TABLE `saldo_unidade` CHANGE `CPF/CNPJ` `cpf_cnpj` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
				$s = 0;
				$e = 0;
				for($i = 1; $i < 15; $i++)
				{
					$query_re_campos = mysqli_query($con,$sql_re_campos[$i]);
					if($query_re_campos)
					{
						$s++;			
					}
					else
					{
						$e++;	
					}
				}
				$mensagem .= "$s campos renomeados e $e com erros ao nomear <br />";	
			}
		}
		else
		{
			$mensagem .= "É preciso fazer upload da tabela em formato ODS pelo PHPMYADMIN.<br />
			Não se esqueça de escolhar a opção <i>'A primeira linha contém o nome dos campos'</i> ao importar.<br />
			Aproveite para fazer um backup geral do banco.<br />";	
		}
	}
?>