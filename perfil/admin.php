<?php
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = 'inicial';
	}
	// Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
	function geraTimestamp($data)
	{
		$partes = explode('/', $data);
		return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
	}
	require "../funcoes/funcoesAdministrador.php"; //chamar funcoes do administrador	
	switch($p)
	{
		case "inicial":
		include "../include/menuAdmin.php";
?>
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
				<h3>Bem-vindo(a) ao SINCOR!</h3>
				<p>&nbsp;</p>
				<h2>Módulo Administrador</h2>
				<p>&nbsp;</p>
				<p>Esse módulo possibilita gerenciar usuários.</p>
           </div>
        </div>
    </div>
</section>  
	<?php
		break; 
		case "email":
			include "../include/menuAdministradorGeral.php";
			$con = bancoMysqli();
			$sql = "SELECT DISTINCT idEvento, ig_log_reabertura.data FROM igsis_agenda 
			INNER JOIN ig_log_reabertura ON idEveForm = idEvento
			WHERE idEvento NOT IN ( SELECT idEvento FROM ig_evento WHERE ( dataEnvio IS NOT NULL ) OR ( dataEnvio IS NULL AND ocupacao = 1))";
			$query = mysqli_query($con,$sql);
	?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
		<?php
			$i = 0;
			while($email = mysqli_fetch_array($query))
			{	
				$evento = recuperaDados("ig_evento",$email['idEvento'],"idEvento");
				$usuario = recuperaDados("ig_usuario", $evento['idResponsavel'],"idUsuario");
				$data = exibirDataHoraBr($email['data']);
				echo "<p align='left'>Bom dia!</p><p align='left'>Informamos que sua ig de número <font color='red'><strong>".$email['idEvento']."</strong></font> <strong>(".$evento['nomeEvento'].")</strong> foi reaberta no dia <font color='red'><strong>".$data."</strong></font> e não foi reenviada. Solicitamos que o reenvio seja feito, pois o evento sairá da agenda e só retornará após o mesmo.</p>
				<p align='left'>Att.</p>
				<p>Responsável: ".$usuario['email']."</p>";
				echo "<p>-----------------------------</p>";
				$i++;
			}	
		?>
            </div>
		</div>
	</div>
</section>  
	<?php
		break; //FIM EMAIL
		case "estatistica":
		include "../include/menuAdministradorGeral.php";
	?>
<section id="list_items" class="home-section bg-white">
	<div class="form-group">
		<div class="col-md-offset-2 col-md-8">		
			<h2>Estatística do Sistema</h2>
		</div>
	</div> 
	<div class="container">
      	<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
				</div>
				<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>	  
		<div class="table-responsive list_info">
			<table class='table table-condensed'>
				<thead>	
				</thead>
				<tbody>
					<tr>
						<td>Usuários ativos nos últimos 30 dias</td>
						<td>
		<?php 
			$trinta_dias = date('Y-m-d H:i:s', strtotime("-30 days"));
			$sql_usuarios = "SELECT DISTINCT ig_usuario_idUsuario FROM ig_log WHERE dataLog > '$trinta_dias'";
			$query_usuarios = mysqli_query($con,$sql_usuarios);
			$num_usuarios = mysqli_num_rows($query_usuarios);
			echo $num_usuarios ?>
						</td>
					</tr>
					<tr>
						<td>Total de eventos enviados</td>
						<td>
		<?php
			$con = bancoMysqli();
			$sql_eventos = "SELECT * FROM ig_evento WHERE publicado ='1' AND dataEnvio IS NOT NULL";
			$query_eventos = mysqli_query($con,$sql_eventos);
			$num_eventos = mysqli_num_rows($query_eventos);

			$sql_pedidos = "SELECT * FROM igsis_pedido_contratacao WHERE publicado = '1' AND estado IS NOT NULL";
			$query_pedidos = mysqli_query($con,$sql_pedidos);
			$num_pedidos = mysqli_num_rows($query_pedidos);
			echo $num_eventos
		?>
						</td>
					</tr>
					<tr>
						<td>Média de eventos enviados por dia</td>
						<td>
		<?php
			$data_inicial = '04/01/2016';
			$data_final = date('d/m/Y');

			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp($data_final);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media_eventos  = (int)($num_eventos/$dias);
			echo $media_eventos
		?>
						</td>
					</tr>
					<tr>
						<td>Eventos enviados neste mês</td>
						<td>
		<?php
			$primeiro_dia = mktime(0, 0, 0, date('m') , 1 , date('Y'));
			//$data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
			$primeiro_dia_sql = date('Y/m/d h:i:s',$primeiro_dia);
			
			$con = bancoMysqli();
			$sql_eventos_mes = "SELECT * FROM ig_evento WHERE publicado ='1' AND dataEnvio > '$primeiro_dia_sql'";
			$query_eventos_mes = mysqli_query($con,$sql_eventos_mes);
			$num_eventos_mes = mysqli_num_rows($query_eventos_mes);
			echo $num_eventos_mes;
		?>
						</td>
					</tr>
					<tr>
						<tr>
							<td>Pedidos de contratação </td>
							<td>
		<?php
			echo $num_pedidos
		?>
							</td>
						</tr>
					</tr>
					<tr>
						<td>Média de pedidos enviados por dia</td>
						<td>
		<?php
			$data_inicial = '04/01/2016';
			$data_final = date('d/m/Y');
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp($data_final);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media_pedidos  = (int)($num_pedidos/$dias);
			echo $media_pedidos
		?>
						</td>
					</tr>
					<tr>
						<td>Reaberturas de IG (Desde 13/04/2016)</td>
						<td>
		<?php
			$sql_log = "SELECT * FROM ig_log WHERE  `descricao` LIKE  '%SET dataEnvio = NULL%'";
			$query_log = mysqli_query($con,$sql_log);
			$num_log = mysqli_num_rows($query_log);
			$data_inicial = '13/04/2016';
			$data_final = date('d/m/Y');
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp($data_final);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media  = (int)($num_log/$dias);
			echo $num_log
		?> 
						</td>
					</tr>
					<tr>
						<td> Média por dia de Reaberturas de IG</td>
						<td>
		<?php
			$sql_log = "SELECT * FROM ig_log WHERE  `descricao` LIKE  '%SET dataEnvio = NULL%'";
			$query_log = mysqli_query($con,$sql_log);
			$num_log = mysqli_num_rows($query_log);
			$data_inicial = '13/04/2016';
			$data_final = date('d/m/Y');
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp($data_final);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media  = (int)($num_log/$dias);
			echo $media
		?>
						</td>
					</tr>
					<tr>
						<td> Média de eventos enviados por usuário</td>
						<td>
		<?php
			$sql_usuarios = "SELECT DISTINCT idUsuario FROM ig_evento WHERE publicado = '1' AND (dataEnvio IS NOT NULL OR dataEnvio <> '')";
			$query_usuarios = mysqli_query($con,$sql_usuarios);
			$num_usuarios = mysqli_num_rows($query_usuarios);
			$data_inicial = '13/04/2016';
			$data_final = date('d/m/Y');
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp($data_final);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media  = (int)($num_eventos/$num_usuarios);
			echo $media
		?>
						</td>
					</tr>
					<tr>
						<td>  Média de Ações/apresentações/acontecimentos<br /> agendados por dia até o final do ano</td>
						<td>
		<?php
			$sql_final = "SELECT data FROM igsis_agenda ORDER BY data DESC LIMIT 0,1";
			$query_final = mysqli_query($con,$sql_final);
			$data = mysqli_fetch_array($query_final);
			$data_final = exibirDataBr($data['data']);
			$data_inicial = "01/01/2016";
			$sql_total = "SELECT data FROM igsis_agenda";
			$query_total = mysqli_query($con,$sql_total);
			$num_total = mysqli_num_rows($query_total);
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp($data_final);
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media  = (int)($num_total/$dias);
			echo $media
		?>
						</td>
					</tr>
					<tr>
						<td> Média de Ações/apresentações/acontecimentos realizados até hoje</td>
						<td>
		<?php
			$data_inicial = "01/01/2016";
			$hoje = date('Y-m-d');
			$sql_total = "SELECT data FROM igsis_agenda WHERE data <= '$hoje'";
			$query_total = mysqli_query($con,$sql_total);
			$num_total = mysqli_num_rows($query_total);
			// Usa a função criada e pega o timestamp das duas datas:
			$time_inicial = geraTimestamp($data_inicial);
			$time_final = geraTimestamp(exibirDataBr($hoje));
			// Calcula a diferença de segundos entre as duas datas:
			$diferenca = $time_final - $time_inicial; // 19522800 segundos
			// Calcula a diferença de dias
			$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
			// Exibe uma mensagem de resultado:
			$media  = (int)($num_total/$dias);
			echo $media
		?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>


	<?php
		break; 
		case "visaogeral":
		include "../include/menuAdministradorGeral.php";
	?>
<section id="inserir" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
                    <h3>Administração Geral do Sistema</h3>
                </div>
            </div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
           		<h5>Usuários ativos</h5>
				<div class="form-group">
		<?php 
			// Defino a hora com a qual vou trabalhar
			$agora = date('Y-m-d H:i:s');
			// Somo 5 minutos (resultado em int)
			//$horaNova = strtotime("$hora + 30 minutes");
			// Formato o resultado
			//$horaNovaFormatada = date("H:i:s",$horaNova);
			// Mostro na tela
			$con = bancoMysqli();
			$sql_user = "SELECT DISTINCT idUsuario, ip, time FROM igsis_time";
			$query_user = mysqli_query($con,$sql_user);
			while($x = mysqli_fetch_array($query_user))
			{
				$usuario = recuperaDados("ig_usuario",$x['idUsuario'],"idUsuario");
				$hora = strtotime($x['time']);
				$novaHora = strtotime('+30 minute',$hora);
				$agora = strtotime(date('H:m:i'));
				if($novaHora > $agora)
				{				
		?>                            
					<p><?php echo $usuario['nomeCompleto'] ?> pelo IP: <?php echo $x['ip']; ?> às <?php echo $x['time']; ?> </p>
		<?php
				}
			}
								
		?>
				</div>
			</div>
		</div>
	</div>
</section>  
	<?php	
		break; // FIM EVENTOS
		case "reabertura": // VISUALIZAR REABERTURA DE IGSIS
		include "../include/menuAdministradorGeral.php";
			if(isset($_POST['apagar']))
			{
				$idEvento = $_POST['apagar'];
				$sql_reabrir = "UPDATE ig_evento SET publicado = '0' WHERE idEvento = '$idEvento'";
				$query_reabrir = mysqli_query($con,$sql_reabrir);
				if($query_reabrir)
				{
					$sql_pedido = "UPDATE igsis_pedido_contratacao SET publicado = '0' WHERE idEvento = '$idEvento'";
					$query_pedido = mysqli_query($con,$sql_pedido);
					if($query_pedido)
					{
						$evento = recuperaDados("ig_evento",$idEvento,"idEvento");
						$mensagem = "Evento ".$evento['nomeEvento']."($idEvento) apagado com sucesso";	
					}
				}
			}
			if(isset($_POST['reabertura']))
			{
				$idEvento = $_POST['reabertura'];
				$mensagem = "";
				$sql_reabrir = "UPDATE ig_evento SET dataEnvio = NULL WHERE idEvento = '$idEvento'";
				$query_reabrir = mysqli_query($con,$sql_reabrir);
				if($query_reabrir)
				{
					$evento = recuperaDados("ig_evento",$idEvento,"idEvento");
					$mensagem = $mensagem."O evento ".$evento['nomeEvento']." foi reaberto.<br />";
					$sql_pedido = "UPDATE igsis_pedido_contratacao SET estado = NULL WHERE idEvento = '$idEvento'";
					$query_pedido = mysqli_query($con,$sql_pedido);
					if($query_pedido)
					{
						$mensagem = $mensagem."Os pedidos foram reabertos.<br />";
						$sql_recupera_pedidos_abertos = "SELECT * FROM igsis_pedido_contratacao WHERE publicado = '1' AND idEvento = $idEvento AND estado IS NULL";
						$query_recupera_pedidos_abertos = mysqli_query($con,$sql_recupera_pedidos_abertos);
						$n_recupera = mysqli_num_rows($query_recupera_pedidos_abertos);
						if($n_recupera > 0)
						{
							$mensagem = "O evento ".$evento['nomeEvento']."foi reaberto.";
							$pedido = "";
							while($x = mysqli_fetch_array($query_recupera_pedidos_abertos))
							{
								$pedidos = $pedidos." ".$x['idPedidoContratacao'].","; 	
							}
							$conteudo_email = "
							Olá,<br />
							Por solicitação, o(s) pedido(s) ".trim(substr($pedidos,0,-1))." foi(foram) reaberto(s) e não aparecerá(ão) em suas listas no Módulo Contratação até que seja(m) reenviado(s).<br /><br />
							Att,<br />
							Equipe IGSIS<br />";
							$instituicao = 4;
							$subject = "O evento '".$evento['nomeEvento']."' foi reaberto";
							$email = "sistema.igsis@gmail.com";
							$usuario = "IGSIS";
							$email_envia = enviarEmailContratos($conteudo_email, $instituicao, $subject, $email, $idEvento);
						}
						if($email_envia)
						{
							$mensagem = $mensagem."<br />Foram enviadas notificações à área de Contratos.";	
						}	
					}
				} 
			}
			if(isset($_GET['order']))
			{
				switch($_GET['order'])
				{
					case "dataEnvio":
						$order = " ORDER BY dataEnvio DESC";
						$mensagem .= "<br /> Ordenados pelas últimas datas de envio.<br />(Reaberturas de IGs geram novas datas de envio mas não Números de Evento.)";	
					break;
					case "idEvento":
						$order = " ORDER BY idEvento DESC";	
						$mensagem .= "<br /> Ordenados pelo número de Evento";	
				}	
			}
			else
			{
				$order = " ORDER BY idEvento DESC ";	
				$mensagem .= "<br /> Ordenados pelo últimos números de Evento";	
			}
	?>
<section id="list_items" class="home-section bg-white">
	<div class="form-group">
		<div class="col-md-offset-2 col-md-8">		
			<h2>Lista de eventos</h2>
		</div>
	</div> 
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">	
				</div>
				<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>  
		<div class="table-responsive list_info">
		<?php 		
			//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
			$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
			$idInsituicao = $_SESSION['idInstituicao'];
			$sql_lista = "SELECT * FROM ig_evento WHERE publicado = '1' AND dataEnvio IS NOT NULL $order";
			$query_lista = mysqli_query($con,$sql_lista);
			
			//conta o total de itens
			$total = mysqli_num_rows($query_lista);
			
			//seta a quantidade de itens por página
			$registros = 50;
			   
			//calcula o número de páginas arredondando o resultado para cima
			$numPaginas = ceil($total/$registros);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($registros*$pagina)-$registros;	
			
			//seleciona os itens por página
			$sql_lista = "SELECT * FROM ig_evento WHERE publicado = '1' AND dataEnvio IS NOT NULL $order limit $inicio,$registros ";
			$query_lista = mysqli_query($con,$sql_lista);
			//conta o total de itens
			$total = mysqli_num_rows($query_lista);
			
		?>
			
            <p><a href="?perfil=admin&p=reabertura">Ordenar pelos últimos Números de Evento</a> | <a href="?perfil=admin&p=reabertura&order=dataEnvio">Ordenar pelas últimas datas de envio</a></p>
            <table class='table table-condensed'>
				<thead>					
					<tr class='list_menu'> 
						<td>ID</td>
						<td>Evento</td>
  						<td>Tipo</td>
						<td>Instituição</td>
						<td>Data/Período</td>
                        <td>Pedido</td>
                        <td width="7%"></td>
                        <td width="7%"></td>
                        <td width="7%"></td>
					</tr>	
				</thead>
				<tbody>
		<?php 
			while($campo = mysqli_fetch_array($query_lista))
			{
				$protocolo = recuperaDados("ig_protocolo",$campo['idEvento'],"ig_evento_idEvento");
				$chamado = recuperaAlteracoesEvento($campo['idEvento']);
				$instituicao = recuperaDados("ig_instituicao",$campo['idInstituicao'],"idInstituicao");	
				echo "<tr>";
				echo "<td class='list_description'><a href='?perfil=detalhe&evento=".$campo['idEvento']."' target='_blank'>".$campo['idEvento']."</a></td>";
				echo "<td class='list_description'>".$campo['nomeEvento']." ["; 
				if($chamado['numero'] == '0')
				{
					echo "0";
				}
				else
				{
					echo "<a href='?perfil=chamado&p=evento&id=".$campo['idEvento']."' target='_blank'>".$chamado['numero']."</a>";	
				}	
					echo "]</td>";
					echo "<td class='list_description'>".retornaTipo($campo['ig_tipo_evento_idTipoEvento'])."</td>";
					echo "<td class='list_description'>".$instituicao['instituicao']."</td>";
					echo "<td class='list_description'>".retornaPeriodo($campo['idEvento'])."</td>";
					echo "<td class='list_description'>".substr(retornaPedidos($campo['idEvento']),7)."</td>";
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=admin&p=reabertura'>
						<input type='hidden' name='reabertura' value='".$campo['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='reabrir'></td></form>"	;
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=admin&p=reabertura'>
						<input type='hidden' name='apagar' value='".$campo['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='Apagar'></td></form>"	;
					echo "<td class='list_description'>
						<form method='POST' action='?perfil=evento&p=basica' target='_blank'>
						<input type='hidden' name='carregar' value='".$campo['idEvento']."' >
						<input type ='submit' class='btn btn-theme  btn-block' value='Carregar'></td></form>"	;
					echo "</tr>";	
			}
		?>
					<tr>
						<td colspan="10" bgcolor="#DEDEDE">
						<?php
							//exibe a paginação
							echo "<strong>Páginas</strong>";
							for($i = 1; $i < $numPaginas + 1; $i++) 
							{
								echo "<a href='?perfil=admin&p=reabertura&pagina=$i'> [".$i."]</a> ";
							}
						?>
						</td>
					</tr>		
				</tbody>
			</table>
		</div>
	</div>
</section>


<?php
		break;
		case "sof":
		include "../include/menuAdministradorGeral.php";
?>		
<!-- Contact -->
<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<div class="sub-title">
				<h2>Integração SOF / IGSIS</h2>
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
		break;
		case "scripts":
		include "../include/menuAdministradorGeral.php";
			if(isset($_GET['atualizar']))
			{
				if($_GET['atualizar'] == 'agenda')
				{
					if(reloadAgenda())
					{
						$texto = "Agenda atualizada.";	
					}	
				}
			}
			if(isset($_GET['status']))
			{
				$con = bancoMysqli();
				$sql_pedido = "SELECT * FROM igsis_pedido_contratacao WHERE publicado = '1'";
				$query_pedido = mysqli_query($con,$sql_pedido);
				$texto = "";
				$i = 0;
				while($pedido = mysqli_fetch_array($query_pedido))
				{
					$idPedido = $pedido['idPedidoContratacao'];
					$texto .= $pedido['estado']."<br />";	
					if($pedido['aprovacaoFinanca'] == NULL OR $pedido['aprovacaoFinanca'] == 1 )
					{
					}
					else
					{
						if(trim($pedido['NumeroProcesso']) != "" OR $pedido['NumeroProcesso'] != NULL)
						{
							// Se há número de processo
							if(trim($pedido['NumeroNotaEmpenho']) != "" OR $pedido['NumeroNotaEmpenho'] != NULL)
							{
								// Se há número de Nota de Empenho
								$idStatus = "10";
								$texto .= "O status do pedido $idPedido é 10.<br />";
							}
							else
							{
								$idStatus = "4"; //Só tem número de processo	
								$texto .= "O status do pedido $idPedido é 4.<br />";
							}
						}  		
						// switch
						$switchPedido = $pedido['estado'];
						switch($switchPedido)
						{
							case "Proposta":
								$idStatus = "5";
								$texto .= "O status do pedido $idPedido é Proposta.<br />";
							break;
							case "Análise do Pedido":
								$idStatus = "3";
								$texto .= "O status do pedido $idPedido é Análise.<br />";
							break;
							case "Pedido":
								$idStatus = "1";
								$texto .= "O status do pedido $idPedido é Pedido.<br />";
							break;
							case "Concluído":
								$idStatus = "11";
								$texto .= "O status do pedido $idPedido é Concluído.<br />";
							break;
						}
						$sql_atualiza = "UPDATE igsis_pedido_contratacao SET estado = '$idStatus' WHERE idPedidoContratacao = '$idPedido'";
						$query_atualiza = mysqli_query($con, $sql_atualiza);
						if($query_atualiza)
						{
							$texto .= "OK<br />";
							$i++;	
						}
						else
						{
							$texto .= "Erro<br />";	
						}
					}
				}
				$texto .= "<br /> $i pedidos atualizados.<br />";
			}
			if(isset($_GET['empenho']))
			{
				$sql_pedido = "SELECT * FROM igsis_pedido_contratacao WHERE publicado = '1' and valor > 0";
				$query_pedido = mysqli_query($con,$sql_pedido);
				$texto = "";
				while($pedido = mysqli_fetch_array($query_pedido))
				{
					if($pedido['NumeroNotaEmpenho'] != "" OR $pedido['NumeroNotaEmpenho'] != NULL)
					{
						$con = bancoMysqli();
						$idPedido = $pedido['idPedidoContratacao'];
						$sql_atualiza_status = "UPDATE igsis_pedido_contratacao SET estado = '10' WHERE idPedidoContratacao = '$idPedido'"; 
						$query_atualiza_status = mysqli_query($con,$sql_atualiza_status);
						if($query_atualiza_status)
						{
							$texto .= "Pedido $idPedido atualizado para Entrega N.E.";
						}	
					}
				}
			}
			if(isset($_GET['inst_agenda']))
			{
				$con = bancoMysqli();	
				$sql_data = "SELECT * FROM igsis_agenda";
				$query_data = mysqli_query($con,$sql_data);
				$i = 0;
				$num = mysqli_num_rows($query_data);
				while($agenda = mysqli_fetch_array($query_data))
				{
					$id = $agenda['idAgenda'];
					$inst = recuperaDados("ig_local",$agenda['idLocal'],"idLocal");
					$idInst = $inst['idInstituicao'];
					$sql_atualiza = "UPDATE igsis_agenda SET idInstituicao = '$idInst' WHERE idAgenda = '$id'";
					$query_atualiza = mysqli_query($con,$sql_atualiza);
					if($query_atualiza)
					{
						$i++;	
					}
				}
				$mensagem = "Foram atualizados $i de $num registros.";
			}
			if(isset($_GET['limpar_base']))
			{
				$con = bancoMysqli();	
				$sql_data = "DELETE FROM ig_evento WHERE ig_tipo_evento_idTipoEvento = '0'";
				$query_data = mysqli_query($con,$sql_data);
				$num = mysqli_affected_rows($query_data);
				if($query_data)
				{
					$mensagem = "Base de eventos limpa. Foram deletados $num registros.";
				}
				else
				{
					$mensagem = "Erro ao limpar a base";
				}
			}
			if(isset($_GET['formacao']))
			{
				//script de importação da base de pessoas físicas formação
				$con = bancoMysqli();
				$texto = "";
				function mascara_cpf($cpf)
				{ 
					$a= substr("$cpf", 0,3); 
					$b= substr("$cpf", 3,3); 
					$c= substr("$cpf",6,3); 
					$d= substr("$cpf",9,2); 
					$novo_cpf = $a.'.'.$b.'.'.$c.'-'.$d; 
					return $novo_cpf; 
				}
				function mask($val, $mask)
				{
					$maskared = '';
					$k = 0;
					for($i = 0; $i<=strlen($mask)-1; $i++)
					{
						if($mask[$i] == '#')
						{
							if(isset($val[$k]))
							$maskared .= $val[$k++];
						}
						else
						{
							if(isset($mask[$i]))
							$maskared .= $mask[$i];
						}
					}
					return $maskared;
				}
				function estadoCivil($estado)
				{
					switch($estado)
					{
						case "SOLTEIRA":
							$est = 3;
						break;
						case "DIVORCIADO(A)":
							$est = 2;
						break;
						case "SOLTEIRO(A)":
							$est = 3;
						break;
						case "DIVORCIADO":
							$est = 2;
						break;
						case "CASADA":
							$est = 1;
						break;
						case "CASADO":
							$est = 1;
						break;
						case "VIUVA":
							$est = 4;
						break;
						case "OUTRO":
							$est = 5;
						break;
						case "DIVORCIADA":
							$est = 2;
						break;
						default:
							$est = 5;
					}
					return $est;
				}
				$sql_verificia = "SELECT * FROM tbl_pf";
				$query_verifica = mysqli_query($con,$sql_verificia);
				while($verifica = mysqli_fetch_array($query_verifica))
				{
					$cpf_tbl = mascara_cpf($verifica['CPF']);
					$sql_compara = "SELECT * FROM sis_pessoa_fisica WHERE CPF LIKE '$cpf_tbl'";
					$query_compara = mysqli_query($con,$sql_compara);
					$num_compara = mysqli_num_rows($query_compara);
					if($num_compara > 0)
					{
						//verifica se há registro
						$texto .= "O CPF $cpf_tbl já existe no sistema.<br />";
					}
					else
					{
						$texto .= "O CPF $cpf_tbl não existe no sistema.<br />";
						$Nome = $verifica['Nome'];
						$NomeArtistico = $verifica['Nome_Art'];
						$RG = $verifica['RG'];
						$CPF = $cpf_tbl;
						$CCM = $verifica['CCM'];
						$IdEstadoCivil = estadoCivil($verifica['Est_Civ']); //funcao para verificar
						if($verifica['D_nasc'] == NULL)
						{
							$DataNascimento == NULL;
						}
						else
						{
							$DataNascimento = exibirDataMysql($verifica['D_nasc']); //funcao para data em mysql
						}
						$LocalNascimento = $verifica['Loc_Nasc'];
						$Nacionalidade = "Brasileiro(a)"; 
						$CEP = mask($verifica['Cep'],'#####-###');
						$Telefone1 = $verifica['Telefone'];
						$Telefone2 = $verifica['Telefon2']; 
						$Telefone3 = $verifica['Telefon3']; 
						$Email = $verifica['Email']; 
						$DRT = $verifica['DRT'];
						$Pis = $verifica['Pis'];	
						$DataAtualizacao = date('Y-m-d'); 
						$Observacao = $verifica['Endereco']."\n".$verifica['Regiao']."\n".$verifica['currric']."\n".$verifica['Grau_Ins']."\n";
						$tipoDocumento = "1";
						$sql_insere_cpf = "INSERT INTO `sis_pessoa_fisica` 
						(`Nome`, `NomeArtistico`, `RG`, `CPF`, `CCM`, `IdEstadoCivil`, `DataNascimento`, `LocalNascimento`, `Nacionalidade`, `CEP`,  `Telefone1`, `Telefone2`, `Telefone3`, `Email`, `DRT`,  `Pis`,  `DataAtualizacao`, `Observacao`) VALUES ('$Nome', '$NomeArtistico', '$RG', '$cpf_tbl', '$CCM', '$IdEstadoCivil' ,$DataNascimento, '$LocalNascimento', '$Nacionalidade','$CEP', '$Telefone1', '$Telefone2', '$Telefone3', '$Email', '$DRT', '$Pis',  '$DataAtualizacao', '$Observacao')";
						$query_insere_cpf = mysqli_query($con,$sql_insere_cpf);
						if($query_insere_cpf)
						{
							$texto .= "O CPF $cpf_tbl foi inserido com sucesso no sistema.<br />";
						}
						else
						{
							$texto .= "Erro ao inserir CPF $cpf_tbl no sistema.<br />";
						}
					}
				}
			}
			if(isset($_GET['contabilidade']))
			{
				//Primeiro é preciso importar as tabelas no formado ods e colocar como a primeira linha como nome dos campos
				$con = bancoMysqli(); //conecta ao banco
				$mensagem = "<i>Atualizando tabela SOF com IGSIS</i><br /><br />";
				//verifica se há no banco de dados a tabela 6354
				$result_upload = mysqli_query($con,"SHOW TABLES LIKE '6354'");
				$tableExists_upload = mysqli_num_rows($result_upload);
				if($tableExists_upload > 0)
				{
					//verifica se a tabela existe e apaga se for o caso
					$table = 'igsis_6354';
					$result = mysqli_query($con,"SHOW TABLES LIKE '$table'");
					$tableExists = mysqli_num_rows($result);
					if($tableExists > 0)
					{
						$apagar_tabela = "DROP TABLE 'igsis_6354'";
						$query_apagar_tabela = mysqli_query($con,$apagar_tabela);
						if($query_apagar_tabela)
						{
							$re_tabela = "RENAME TABLE `6354` TO `igsis_6354`";
							$query_re_tabela = mysqli_query($con,$re_tabela);
							if($query_re_tabela)
							{
								$mensagem .= "Tabela igsis_6354 renomeada com sucesso!<br />";	
							}
							else
							{
								$mensagem .= "Erro ao renomear tabela igsis_6354 (1)<br />";	
							}	
						}
						else
						{
							$mensagem .= "Erro ao apagar tabela igsis_6354 (2)<br />";	
						}	
					}
					else
					{
						$re_tabela = "RENAME TABLE `igsis`.`6354` TO `igsis`.`igsis_6354`;";
						$query_re_tabela = mysqli_query($con,$re_tabela);
						if($query_re_tabela)
						{
							$mensagem .= "Tabela igsis_6354 renomeada com sucesso!<br />";	
						}
						else
						{
							$mensagem .= "Erro ao renomear tabela igsis_6354 (3)<br />";	
						}			
					}
					if($query_re_tabela)
					{
						$sql_id = "ALTER TABLE igsis_6354 ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
						$query_id = mysqli_query($con,$sql_id);
						if($query_id)
						{
							$mensagem .= "Criação de campo id realizada com sucesso!<br />";
						}
						else
						{
							$mensagem .= "Falha na criação de campo id...<br />";	
						}
						$sql_re_campos[1] = "ALTER TABLE `igsis_6354` CHANGE `DATA EMPENHO` `data_empenho` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[2] = "ALTER TABLE `igsis_6354` CHANGE `ANO_` `ano` INT(4) NULL DEFAULT NULL;";
						$sql_re_campos[3] = "ALTER TABLE `igsis_6354` CHANGE `EMPENHO` `empenho` INT(12) NULL DEFAULT NULL;";
						$sql_re_campos[4] = "ALTER TABLE `igsis_6354` CHANGE `DOTAÇÃO` `dotacao` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[5] =	"ALTER TABLE `igsis_6354` CHANGE `PROCESSO` `processo` BIGINT(16) NULL DEFAULT NULL;";
						$sql_re_campos[6] =	"ALTER TABLE `igsis_6354` CHANGE `DESCRIÇÃO` `descrica` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[7] =	"ALTER TABLE `igsis_6354` CHANGE `VALOR` `valor` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[8] =	"ALTER TABLE `igsis_6354` CHANGE `CANCELAMENTO` `cancelamento` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[9] =	"ALTER TABLE `igsis_6354` CHANGE `LIQUIDADO` `liquidado` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[10] = "ALTER TABLE `igsis_6354` CHANGE `PAGO` `pago` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[11] = "ALTER TABLE `igsis_6354` CHANGE `VALOR A LIQUIDAR` `valor_a_liquidar` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[12] = "ALTER TABLE `igsis_6354` CHANGE `TOTAL A PAGAR` `total` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[13] = "ALTER TABLE `igsis_6354` CHANGE `RAZÃO SOCIAL` `razao_social` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
						$sql_re_campos[14] = "ALTER TABLE `igsis_6354` CHANGE `CPF/CNPJ` `cpf_cnpj` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
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
<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
					<h2>Administrador do Sistema</h2>
	                <h5>Scripts - use com moderação</h5>
                    <h5><?php if(isset($mensagem)){ echo $mensagem; } ?></h5>
                </div>
            </div>
        <?php
			if(isset($texto))
			{
		?>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<br /><br />
					<p><?php echo $texto;  ?></p>
				</div>
			</div>
  		<?php
			}
		?>	
        </div>
    </div>
</section>
<?php
		break;
		case "users": // LISTA DE USUARIOS 
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['apagar']))
			{
				$idApagar = $_POST['apagar'];
				$sql_apagar_registro = "UPDATE ig_usuario 
					SET publicado = 0 
					WHERE idUsuario = $idApagar";
				if(mysqli_query($con,$sql_apagar_registro))
				{
					$mensagem = "Usuário apagado com sucesso!";
					gravarLog($sql_apagar_registro);
				}
				else
				{
					$mensagem = "Erro ao apagar o usuário...";	
				}		
			}	
	?> 
<section id="list_items" class="home-section bg-white">
	<div class="form-group">
		<div class="col-md-offset-2 col-md-8">		
			<h2>Usuários Cadastrados</h2>
			<a href="?perfil=admin&p=novoUser" class="btn btn-theme btn-lg btn-block">Inserir novo usuário</a>
		</div>
	</div> 
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">					
					<h4>Selecione o usuário para editar.</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>  
		<div class="table-responsive list_info">
		<?php
			listuserAdministrador("");
		?>
		</div>
	</div>
</section> 
	<?php	
		break; // FIM LISTA USUARIOS
		case "novoUser": // INSERIR NOVO USUARIO 
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['carregar']))
			{
				$_SESSION['idUsuario'] = $_POST['carregar'];
			}
			if(isset($_POST['atualizar']))
			{
				$nomeCompleto = $_POST['nomeCompleto'];
				$rf = $_POST['rf'];
				$usuario = $_POST['usuario'];
				$existe = verificaExiste("ig_usuario","nomeUsuario",$usuario,"0");
				//$senha = MD5($_POST['senha']);
				$senha = MD5 ('igsis2015');
				$instituicao = $_POST['instituicao'];
				$local = $_POST['local'];
				$telefone = $_POST['telefone'];
				$perfil = $_POST['papelusuario'];
				$email = $_POST['email'];
				$existe = verificaExiste("ig_usuario","email",$usuario,"0");
				$publicado = "1";
				if(isset($_POST['receberEmail']))
				{
					$receberEmail =	1;
				}
				else
				{
					$receberEmail =	0;
				}	
				if($existe['numero'] == 0)
				{
					$sql_inserir = "INSERT INTO `ig_usuario` (`idUsuario`, `ig_papelusuario_idPapelUsuario`, `senha`, `receberNotificacao`, `nomeUsuario`, `email`, `nomeCompleto`, `idInstituicao`, `telefone`, `publicado`, `rf`, `local`) VALUES (NULL, '$perfil', '$senha', '$receberEmail', '$usuario', '$email', '$nomeCompleto', '$instituicao', '$telefone', '$publicado', '$rf', '$local')";
					$query_inserir = mysqli_query($con,$sql_inserir);
					if($query_inserir)
					{
						$mensagem = "Usuário inserido com sucesso";
					}
					else
					{
						$mensagem = "Erro ao inserir. Tente novamente.";
					}
				}
				else
				{
					$mensagem = "Usuário ou email já existente. Tente novamente.";
				}
			}
	?>
<section id="inserirUser" class="home-section bg-white">
	<div class="container">
		<div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Inserir Usuário</h3>
					<h3><?php if(isset($mensagem)){echo $mensagem;} ?></h3>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=admin&p=novoUser" class="form-horizontal" role="form">
					<!-- // Usuario !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Nome Completo:</label>
								<input type="text" name="nomeCompleto" class="form-control"id="nomeCompleto" value="" /> 
								<label>Registro Funcional:</label>
								<input type="text" name="rf" class="form-control"id="rf" value="" />
							</div> 
							<div class="col-md-offset-2 col-md-8">
								<label>Usuario:</label>
								<input type="text" name="usuario" class="form-control"id="usuario" />
							</div>  <!-- // SENHA !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Senha:</label>
								<label>igsis2015</label>
							</div> 	<!-- // Departamento !-->
							<div class="col-md-offset-2 col-md-8">	
								<label>telefone:</label>
								<input type="text" name="telefone" class="form-control"id="departamento" />
							</div>  <!-- // ig_instituicao Puxada pela SESSASION do "CRIADOR" - Admin Local !-->
							<!-- // Perfil de Usuario !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Instituição:</label>
								<select name="instituicao" class="form-control"  >
									<?php acessoInstituicao("ig_instituicao","",""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8">
								<label>Local:</label>
								<select name="local" class="form-control"  >
									<?php acessoLocal("ig_local","",""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8">
								<label>Acesso aos Perfil's:</label>
								<select name="papelusuario" class="form-control"  >
									<?php acessoPerfilUser("ig_papelusuario","3",""); ?>
								</select>
							</div>  <!--  // Email !-->
							<div class="col-md-offset-2 col-md-8">  
								<label>Email para cadastro:</label>
								<input type="text" name="email" class="form-control" id="email" value=""/>
							</div>
							<div class="col-md-offset-2 col-md-8"> <!-- // Confirmação de Recebimento de Email !-->
								<label style="padding:0 10px 0 5px;">Receber Email de atualizações: </label><input type="checkbox" name="receberEmail" id="diasemana01"/>
							</div> <!-- Fim de Preenchemento !-->  
							<!-- Botão de Confirmar cadastro !-->
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Inserir Usuário"  />
							</div>
						</div>
					</div>
				</form>
			</div>
			<form method="POST" action="?perfil=admin&p=users" class="form-horizontal"  role="form">
				<div class="col-md-offset-2 col-md-8">
					<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Lista de Usuário" />
				</div>
			</form>
		</div>
	</div>
</section>   
	<?php	
		break; // FIM INSERIR USUARIO
		case "editarUser": // ATUALIZAR /EDITAR USUARIO 
			include "../include/menuAdministradorLocal.php";
			if (isset ($_POST ['resetSenha']))
			{
				$senha = MD5 ('igsis2015');
				$usuario = $_POST ['editarUser'];		
				$sql_atualizar = "UPDATE `ig_usuario` SET
					`senha` = '$senha'
					WHERE `idUsuario` = '$usuario'";
				$con = bancoMysqli();
				if(mysqli_query ($con,$sql_atualizar))
				{
					$mensagem = "Senha reiniciado com sucesso";
				}
				else
				{
					$mensagem = "Erro ao reiniciar. Tente novamente.";
				}
			}
			// Atualiza o banco com as informações do post
			if(isset($_POST['atualizar']))
			{
				$usuario= $_POST ['idUsuario'];
				$nomeCompleto = $_POST['nomeCompleto'];
				$nomeUsuario = $_POST['nomeUsuario'];
				$existe = verificaExiste("ig_usuario","nomeUsuario",$usuario,"0");
				$telefone = $_POST['telefone'];
				$instituicao = $_SESSION['id_usuario = 1'] = $_POST['ig_instituicao_idInstituicao'];
				$local = $_POST['local'];
				$perfil = $_POST['papelusuario'];
				$rf	=	$_POST['rf'];
				$email = $_POST['email'];	
				if(isset($_POST['receberEmail']))
				{
					$receberEmail =	1;
				}
				else
				{
					$receberEmail =	0;
				}
				if($existe['numero'] == 0)
				{
					$sql_atualizar = "UPDATE `ig_usuario`SET
						`nomeCompleto`= '$nomeCompleto',
						`nomeUsuario`= '$nomeUsuario', 
						`telefone`= '$telefone',
						`idInstituicao` = '$instituicao',
						`local`= '$local',
						`ig_papelusuario_idPapelUsuario`= '$perfil',
						`rf`= '$rf',	
						`email`= '$email', 
						`receberNotificacao`= '$receberEmail'			
						WHERE `idUsuario` = '$usuario' ";
					$con = bancoMysqli();
					if(mysqli_query($con,$sql_atualizar))
					{ 
						$mensagem = "Usuário atualizado com sucesso";
					}
					else
					{
						$mensagem = "Erro ao editar. Tente novamente.";
					}
				}
				else
				{
					$mensagem = "Tente novamente.";
				}
			} 
			$recuperaUsuario = recuperaDados("ig_usuario",$_POST['editarUser'],"idUsuario"); 
	?>
<section id="inserirUser" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Editar Usuário</h3>
					<h3><?php if(isset($mensagem)){echo $mensagem;} ?></h3>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=admin&p=editarUser" class="form-horizontal" role="form">
					<input type="hidden" name="idUsuario"  value=<?php  echo $recuperaUsuario['idUsuario'] ?> />
					<!-- // Usuario !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Nome Completo:</label>
								<input type="text" name="nomeCompleto" class="form-control"id="nomeCompleto" value="<?php echo $recuperaUsuario['nomeCompleto'] ?>" />
							</div> 
							<div class="col-md-offset-2 col-md-8">
								<label>Usuario:</label>
								<input type="text" name="nomeUsuario" class="form-control"id="nomeUsuario" value="<?php echo $recuperaUsuario['nomeUsuario'] ?>" />
							</div>  <!-- // SENHA !-->
							<!-- // Departamento !-->
							<div class="col-md-offset-2 col-md-8">	
								<label>telefone:</label>
								<input type="text" name="telefone" class="form-control"id="departamento" value="<?php echo $recuperaUsuario['telefone'] ?>" />
							</div>  <!-- // Perfil de Usuario !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Instituição:</label>
								<select name="ig_instituicao_idInstituicao" class="form-control"  >
									<?php instituicaoLocal("ig_instituicao",$recuperaUsuario['idInstituicao'],""); ?>
								</select>
							</div>  <!-- // Perfil de Usuario !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Local:</label>
								<select name="local" class="form-control"  >
									<?php acessoLocal("ig_local",$recuperaUsuario['local'],""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8">
								<div class="col-md-offset-2 col-md-8">
									<label>Acesso aos Perfil's :</label>
								</div>
								<select name="papelusuario" class="form-control"  >
									<?php acessoPerfilUser("ig_papelusuario",$recuperaUsuario['ig_papelusuario_idPapelUsuario'],""); ?>
								</select>
							</div>  <!--  // Regristro Funcional 'RF' !-->
							<div class="col-md-offset-2 col-md-8">  
								<label>RF:</label>
								<input type="text" name="rf" class="form-control" value="<?php echo $recuperaUsuario ['rf']?>"/>
							</div> <!--  // Email !-->
							<div class="col-md-offset-2 col-md-8">  
								<label>Email para cadastro:</label>
								<input type="text" name="email" class="form-control" id="email" value="<?php echo $recuperaUsuario ['email']?>"/>
							</div>
							<div class="col-md-offset-2 col-md-8"> <!-- // Confirmação de Recebimento de Email !-->
								<label style="padding:0 10px 0 5px;">Receber Email de atualizações: </label><input type="checkbox" name="receberEmail" id="diasemana01"/>
							</div> <!-- Fim de Preenchemento !-->  
							<!-- Botão de Confirmar cadastro !-->
							<div class="col-md-offset-2 col-md-8">
								<script type="application/javascript">
									$(function() {
										/* caixa-confirmacao representa a id onde o caixa de confirmação deve ser criada no html */
										$( "#caixa-confirmacao" ).dialog({
										  resizable: false,
										  height:500,

										  /* 
										   * Modal desativa os demais itens da tela, impossibilitando interação com eles,
										   * forçando usuário a responder à pergunta da caixa de confirmação
										   */ 
										  modal: true,

										  /* Os botões que você quer criar */
										  buttons: {
											"Sim": function() {
											  $( this ).dialog( "close" );
											  alert("Você clicou em Sim");
											},
											"Não": function() {
											  $( this ).dialog( "close" );
											  alert("Você clicou em Não");
											}
										  }
										});
									  });
								</script>
								<input type="hidden" name="editarUser" value="<?php echo $_POST['editarUser'] ?>"  />
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Atualizar Usuário" onclick="return confirm('Tem certeza que deseja realizar essa ação?')" />
							</div>
						</div>
					</div>
				</form>				
				<form method="POST" action="?perfil=admin&p=editarUser" class="form-horizontal" role="form">
					<div class="col-md-offset-1 col-md-10">
						<input type="hidden" name="editarUser" value="<?php echo $_POST['editarUser'] ?>"  />
						<input type="hidden" name="resetSenha" value="1"  />
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" name="resetar_senha" value="Resetar Senha do usuario" /> <p> </p>
					</div> 
				</form>	
				<form method="POST" action="?perfil=admin&p=users" class="form-horizontal" >
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Lista de Usuário" />
					</div>
				</form>
			</div>	
		</div>    
	</div>
</section>   
	<?php
		break; // FIM LISTA USUARIOS / INSERIR / ATUALIZAR
		case "novoEspaco": // INSERIR NOVO ESPACO
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['cadastrar']))
			{
				$espaco = $_POST['espaco'];	
				$instituicao = $_POST['instituicao'];
				if($espaco == '')
				{  
					$mensagem = "<p>O campo espaco, está em branco e é obrigatório. Tente novamente.</a></p>"; 
				}
				else
				{
					$sqlverificar = "SELECT sala FROM ig_local WHERE idInstituicao = $instituicao AND sala LIKE '$espaco'";
					$queryverificar = mysqli_query($con,$sqlverificar);
					$existe = mysqli_num_rows ($queryverificar);
					if ($existe == 0) // caso não esteja vazio
					{
						//inserir no banco
						$sqlinserir= "INSERT INTO `ig_local` (`idLocal`,`sala`,`idInstituicao`,`publicado`) VALUES (NULL, '$espaco','$instituicao', 1)";
						$queryinserir = mysqli_query($con,$sqlinserir);
						if($queryinserir)
						{
							$mensagem = "Inserido com sucesso.";
						}
						else
						{
							// erro ao inserir
							$mensagem= "Erro ao inserir.";
						}
					}
					else
					{
						// espaço já existe retirado do comando $sqlverificar 
						$mensagem = "Espaço já existente.";
					}
				}					 
			}
	?>    
<section id="inserirUser" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Administrativo </h3> <h2> Inserir Novo Espaço</h3>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=admin&p=novoEspaco" class="form-horizontal" role="form">
					<!-- // Espaço existente !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Adicionar novo Espaço:</label>
								<input type="text" name="espaco" class="form-control"id="espaco" value="" />
							</div>  
							<div class="col-md-offset-2 col-md-8">
								<label>Instituição:</label>
								<select name="instituicao" class="form-control"  >
									<?php acessoInstituicao("ig_instituicao","",""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8"> 
								<label></label> <!-- Adicionar novo espaço !-->
							</div>
							<!-- Botão de gravar !-->
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="cadastrar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Inserir"  />
							</div>
						</div>
					</div>
				</form>
				<form method="POST" action="?perfil=admin&p=espacos" class="form-horizontal"  role="form">
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="lista de espaço"/>
					</div>
				</form>
			</div>
		</div>
    </div>  <!-- // FIM DE INSERIR ESPACOS !-->
</section>
	<?php
		break; // FIM ADICIONAR NOVO ESPACO
		case "espacos":
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['apagar']))
			{
				$con = bancoMysqli();
				$idApagar = $_POST['apagar'];
				$sql_apagar_registro = "UPDATE ig_local SET publicado = 2 WHERE idLocal = $idApagar";
				if(mysqli_query($con,$sql_apagar_registro))
				{	
					$mensagem = "Espaço apagado com sucesso!";
					gravarLog($sql_apagar_registro);
				}
				else
				{
					$mensagem = "Erro ao apagar o evento...";	
				}
			}// EDITAR / APAGAR ESPACOS
	?>
<section id="list_items" class="home-section bg-white">
	<div class="form-group">
		<div class="col-md-offset-2 col-md-8">		
			<h2>Lista de Espaços</h2>
			<a href="?perfil=admin&p=novoEspaco" class="btn btn-theme btn-lg btn-block">Inserir novo espaço</a>
		</div>
	</div> 
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">	
				</div>
				<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>		  
		<div class="table-responsive list_info">
			<?php espacoExistente ($_SESSION['idInstituicao']); ?>
		</div>
	</div>	
</section> <!--/#list_items-->
	<?php
		break; // FIM LISTA ESPACOS / INSERIR / ATUALIZAR
		case "novoProjetoEspecial": // INSERIR NOVO PROJETO ESPECIAL
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['cadastrar']))
			{
				//$con = bancoMysqli();
				$projetoEspecial = $_POST['projetoEspecial'];	
				$instituicao = $_POST['instituicao'];
				if($projetoEspecial == '')
				{  
					$mensagem = "<p>O campo projeto especial, está em branco e é obrigatório. Tente novamente.</a></p>"; 
				}
				else
				{
					$sqlverificar= "SELECT * FROM ig_projeto_especial WHERE projetoEspecial LIKE '$projetoEspecial'";
					$queryverificar= mysqli_query($con,$sqlverificar);
					$existe = mysqli_num_rows ($queryverificar);				
					if ($existe == 0) // caso não esteja vazio
					{
						//inserir no banco
						$sqlinserir= "INSERT INTO `ig_projeto_especial` (`idProjetoEspecial`,`projetoEspecial`, `idInstituicao`,`publicado`) VALUES (NULL, '$projetoEspecial', '$instituicao', 1)";
						$queryinserir= mysqli_query($con,$sqlinserir);
						if($queryinserir)
						{
							$mensagem= "Inserido com sucesso.";
						}
						else
						{
							$mensagem= "Erro ao inserir.";
						}
					}
					else
					{
						// espaço já existe retirado do comando $sqlverificar 
						$mensagem = "Projeto especial já existente.";
					}
				}					 
			}
	?>
<section id="inserirUser" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Administrativo </h3> <h2> Inserir novo projeto especial</h3>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=admin&p=novoProjetoEspecial" class="form-horizontal" role="form">
					<!-- // Espaço existente !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Instituição:</label>
								<select name="instituicao" class="form-control"  >
									<?php acessoInstituicao("ig_instituicao",""); ?>
								</select>	
							</div>
							<div class="col-md-offset-2 col-md-8">
								<label>Adicionar novo projeto especial:</label>
								<input type="text" name="projetoEspecial" class="form-control"id="espaco" value="" />
							</div>  
							<div class="col-md-offset-2 col-md-8"> 
								<label></label> <!-- Adicionar novo espaço !-->
							</div>
							<!-- Botão de gravar !-->
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="cadastrar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Inserir"  />
							</div>
						</div>
					</div>
				</form>
				<form method="POST" action="?perfil=admin&p=listaprojetoespecial" class="form-horizontal"  role="form">
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="lista de projeto especial"/>
					</div>
				</form>
			</div>
		</div>
    </div>
<!-- // FIM DE INSERIR !-->
</section>	
	<?php
		break; // FIM ADICIONAR NOVO PROJETO ESPECIAL
		case "listaprojetoespecial":
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['apagar']))
			{
				$con = bancoMysqli();
				$idApagar = $_POST['apagar'];
				$sql_apagar_registro = "UPDATE `ig_projeto_especial` SET `publicado` = '0' WHERE idProjetoEspecial = $idApagar";
				if(mysqli_query($con,$sql_apagar_registro))
				{	
					$mensagem = "projeto especial apagado com sucesso!";
					gravarLog($sql_apagar_registro);
				}
				else
				{
					$mensagem = "Erro ao apagar o projeto especial...";	
				}
			}// EDITAR / APAGAR PROJETO ESPECIAL
	?>
<section id="list_items" class="home-section bg-white">
	<div class="form-group">
		<div class="col-md-offset-2 col-md-8">		
			<h2>Lista de projeto especial</h2>
			<a href="?perfil=admin&p=novoProjetoEspecial" class="btn btn-theme btn-lg btn-block">Inserir novo projeto especial</a>
		</div>
	</div> 
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
				</div>
				<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			</div>
		</div>	  
		<div class="table-responsive list_info">
			<?php projetoEspecialExistente ($_SESSION['perfil']); ?>
		</div>
	</div>
</section> <!--/#list_items-->
	<?php	
		break; // FIM PROJETO ESPECIAL
		case "eventos": // LISTAR NOVOS EVENTOS
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['apagar']))
			{
				$idApagar = $_POST['apagar'];
				$sql_apagar_registro = "UPDATE ig_evento SET publicado = 3 WHERE idEvento = $idApagar";
				if(mysqli_query($con,$sql_apagar_registro))
				{	
					$mensagem = "Evento apagado com sucesso!";
					gravarLog($sql_apagar_registro);
				}
				else
				{
					$mensagem = "Erro ao apagar o evento...";	
				}
			}
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Eventos excluidos</h2>
					<h4>Selecione o evento para recuperar.</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>  
		<div class="table-responsive list_info">
			<?php listaEventosAdministrador($_SESSION['idInstituicao']); ?>
		</div>
	</div>
</section> <!--/#list_items-->	
	<?php	
		break; // FIM EVENTOS
		case "logsLocais": // VISUALIZAR LOGS DE USUARIO
			include "../include/menuAdministradorLocal.php";
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Logs de Usuários</h2>
					<h4>Selecione o Log recuperar ou editar.</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>  
		<div class="table-responsive list_info">
			<?php listaLogAdministrador($_SESSION['perfil']); ?>
		</div>
	</div>
</section> <!--/#list_items-->		
	<?php
		break; // FIM LOGS
		case "formularioalteracoes": // inicio dos formularios de alterações
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['carregar']))
			{
				$_SESSION['idChamado'] = $_POST['carregar'];
			}
			// Atualiza o banco com as informações do post
			if(isset ($_POST ['atualizar']))
			{
				$idChamado = $_POST['idChamado'];
				$titulo = $_POST ['listaTitulo'];
				$status = $_POST ['estado'];
				$nota = $_POST ['nota'];
				//$nome = $_POST ['nomeCompleto'];
				$sql_atualizar = "UPDATE `igsis_chamado` SET
					`titulo`= '$titulo',
					`estado`= '$status',
					`nota`= '$nota'
					WHERE `idChamado` ='$idChamado'";
				$con = bancoMysqli();
				if(mysqli_query($con,$sql_atualizar))
				{
					$mensagem = "Atualizado com Sucesso.";
					gravarLog($sql_atualizar);
				}
				else
				{
					$mensagem = "Erro ao gravar atualização... Tente novamente.";
				}
			}
			$recuperaChamado = recuperaDados("igsis_chamado", $_POST['carregaChamado'],"idChamado");
			$recuperaUser = recuperaDados("ig_usuario",$recuperaChamado['idUsuario'],"idUsuario");
			$recuperaEvento = recuperaDados("ig_evento",$recuperaChamado['idEvento'],"idEvento");
	?>
<section id="chamado" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-4 col-md-6">
                <div class="text-hide">
                    <h3>CHAMADO</h3>
					<h3><?php if(isset($mensagem)){echo $mensagem;} ?></h3>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-0 col-md-12">
				<form method="POST" action="?perfil=admin&p=formularioalteracoes" class="form-horizontal" role="form">
					<div class=form-group">
						<div class="col-md-offset-2 col-md-4">
							<label>ID Chamado:</label>		<!-- // numero do chamado !-->		
							<input type="text" readonly name="idChamado" class="form-control"id="idChamado" value="<?php echo $recuperaChamado['idChamado'] ?>" /> 
						</div> 
						<div class=" col-md-6">	
							<label>ID Evento:</label>
							<input type="text" readonly name="idEvento" class="form-control"id="idEvento" value="<?php echo $recuperaChamado['idEvento'] ?>" />
						</div>
						<div class="col-md-offset-2 col-md-8">
							<label>Nome do Evento:</label>
                			<input readonly name="nomeEvento" class="form-control" id="nomeEvento" value="<?php echo $recuperaEvento['nomeEvento'] ?>"/>
						</div>
					</div> 	
					<div class="form-group">
						<div  class="col-md-offset-2 col-md-4">					
							<label>Titulo chamado:</label> 
							<input readonly name="tipoChamado" class="form-control" value="<?php echo $recuperaChamado['titulo']?>"/>
						</div>  
						<div class="col-md-offset- col-md-4">	
							<label>Data do chamado:</label>
							<input type="text" readonly name="data" onblur="validate()" class="form-control"id="data" value="<?php echo $recuperaChamado['data'] ?>" />
						</div>	
						<div  class="col-md-offset-2 col-md-8">
							<label>Tipo chamado:</label>
							<select disabled name="listaTitulo" class="form-control">
								<?php geraTituloChamado("igsis_tipo_chamado",$recuperaChamado ['titulo'],""); ?>
							</select>	
						</div>   
					</div>	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-4">	
							<label>Criado por:</label>
							<input readonly name="nomeCompleto" class="form-control" id="nomeCompleto" value="<?php echo $recuperaUser['nomeCompleto'] ?>"/>
						</div> 
						<div class="col-md-offset-0 col-md-6">
							<label>Email:</label>
							<input readonly name="email" class="form-control" id="email" value="<?php echo $recuperaUser['email'] ?>"/>
						</div>	
					</div>
					<!-- Usuário que preencheou o chamado !--> 
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Descrição:</label>
							<textarea readonly name="descricao" class="form-control" rows="10"> <?php echo $recuperaChamado['descricao'] ?></textarea>
						</div>
						<div class="col-md-offset-2 col-md-8">
							<label>Justificativa:</label>
							<textarea name="justificativa" readonly class="form-control" rows="10"> <?php echo $recuperaChamado['justificativa'] ?></textarea>
						</div> <!-- Preenchemento feito pelo usuário !-->  
					</div>
					<div class="col-md-offset-2 col-md-8">	
                		<label>Status:</label>
                		<select name="estado" class="form-control"  >
							<?php geraStatusChamado("igsis_tipo_chamado",$recuperaChamado['estado'],""); ?>
						</select>
					</div> 
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Notas adicionais:</label>
							<textarea name="nota" class="form-control" rows="10"> <?php echo $recuperaChamado['nota'] ?></textarea>
						</div> <!-- Fim de Preenchemento !-->  
					</div>
					<div class=form-group">
						<div class="col-md-offset-4 col-md-4">
							<input type="hidden" name="carregaChamado" value="<?php echo $_POST['carregaChamado'] ?>"  />
							<input type="hidden" name="atualizar" value="1" />
							<input type="submit" class="btn btn-theme btn-lg btn-block" value="	concluir"  />
						</div>
					</div> 
				</form>				
				<form method="POST" action="?perfil=admin&p=alteracoes" class="form-horizontal"  role="form">
					<div class="col-md-offset-4 col-md-4">
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Lista de chamados" />
					</div>
				</form>
			</div> 
		</div>
	</div>	
</section>
	<?php	
		break; // FIM FORM ALTERÇÕES
		case "alteracoes": // INICIO DE ALTERAÇÕES
			include "../include/menuAdministradorLocal.php";
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Chamados</h2>
					<a href="?perfil=admin&p=alteracoesfinalizadas" class="btn btn-theme btn-lg btn-block">Chamados Fechados</a>
					<h4>Selecione o chamado para visualizar.</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>  
		<div class="table-responsive list_info">
			<?php listaAlteracoes($_SESSION['idInstituicao']); ?>
		</div>
	</div>
</section> <!--/#list_items-->
	<?php 
		break;
		case "alteracoesfinalizadas": // INICIO DE ALTERAÇÕES FINALIZADAS
			include "../include/menuAdministradorLocal.php";
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Chamados</h2>
					<a href="?perfil=admin&p=alteracoes" class="btn btn-theme btn-lg btn-block">Chamados Abertos</a>
					<h4>Selecione o chamado para visualizar.</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>  
		<div class="table-responsive list_info">
			<?php listaAlteracoesFinalizado($_SESSION['idInstituicao']); ?>
		</div>
	</div>
</section> <!--/#list_items-->
		<?php
		break;
	}
?>
