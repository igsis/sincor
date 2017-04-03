<?php 
if(isset($_GET['b']))
{
	$b = $_GET['b'];	
}
else
{
	$b = 'inicial';
}

switch($b)
{
case 'inicial':
	
if(isset($_POST['pesquisar']))
{		
	$idOrgao = $_POST['idOrgao'];
	$idUnidade = $_POST['idUnidade'];
	$idFuncao = $_POST['idFuncao'];
	$idSubfuncao = $_POST['idSubfuncao'];
	$idRamo = $_POST['idRamo'];
	$idProjetoAtividade = $_POST['idProjetoAtividade'];
	$descricaoSimplificada = $_POST['descricaoSimplificada'];
	$idFonte = $_POST['idFonte'];
	$dotacao = trim($_POST['dotacao']);

	if($idOrgao == "" AND $idUnidade == "" AND $idFuncao == "" AND $idSubfuncao == "" AND $idRamo == "" AND $idProjetoAtividade == "" AND $idFonte == "" AND $descricaoSimplificada == "" AND dotacao == "")
	{
?>
		<section id="services" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="section-heading">
							<h2>Busca por Evento - Reabertura</h2>
							<p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
														
							<h1>COPIAR O FORMULÁRIO AQUI</h1>
							
						</div>
					</div><br />  
				</div>			
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="pesquisar" value="1" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
						</form>
					</div>
				</div>
			</div>
		</section>	
<?php
	}
	else
	{
		$con = bancoMysqli();
		if($idOrgao != '0')
		{
			$filtro_orgao = " AND idOrgao = '$idOrgao'";
		}
		else
		{
			$filtro_orgao = '';
		}
		
		if($idUnidade != '0')
		{
			$filtro_unidade = " AND idUnidade = '$idUnidade'";
		}
		else
		{
			$filtro_unidade = '';
		}
		
		if($idFuncao != '0')
		{
			$filtro_funcao = " AND idFuncao = '$idFuncao'";
		}
		else
		{
			$filtro_funcao = '';
		}
		
		if($idSubfuncao != '0')
		{
			$filtro_subfuncao = " AND idSubfuncao = '$idSubfuncao'";
		}
		else
		{
			$filtro_subfuncao = '';
		}
			
		if($idRamo != '0')
		{
			$filtro_ramo = " AND idRamo = '$idRamo'";
		}
		else
		{
			$filtro_ramo = '';
		}
				
		if($idProjetoAtividade != '0')
		{
			$filtro_projetoAtividade = " AND projetoAtividade = '$idProjetoAtividade'";
		}
		else
		{
			$filtro_projetoAtividade = '';
		}
		
		if($descricaoSimplificada != '0')
		{
			$filtro_descricaoSimplificada = " AND descricaoSimplificada = '$idescricaoSimplificada'";
		}
		else
		{
			$filtro_descricaoSimplificada = "";
		}
		
		if($idFonte != '0')
		{
			$filtro_fonte = " AND idFonte = '$idFonte'";
		}
		else
		{
			$filtro_fonte = '';
		}
		
		if($dotacao != '')
		{
			$filtro_dotacao = " AND dotacao = '$dotacao'";
		}
		else
		{
			$filtro_dotacao = '';
		}
		
		$sql_orcamento = "SELECT * FROM orcamento_central,projeto_atividade WHERE projetoAtividade = projeto_atividade.id $filtro_orgao $filtro_unidade $filtro_funcao $filtro_subfuncao $filtro_ramo $filtro_projetoAtividade $filtro_descricaoSimplificada $filtro_fonte $filtro_dotacao ORDER BY idOrgao, idUnidade";
		$query_orcamento = mysqli_query($con,$sql_orcamento);
					
		$i = 0;		

		while($orcamento = mysqli_fetch_array($query_orcamento))
		{
			$orgao = recuperaDados("orgao","id",$orcamento['idOrgao']);
			$unidade = recuperaDados("unidade","id",$orcamento['idUnidade']);	
			$acao = recuperaDados("acao","id",$orcamento['idAcao']);			
						
			$x[$i]['id'] = $orcamento['id'];
			$x[$i]['idOrgao'] = $orgao['descricao'];
			$x[$i]['idUnidade'] = $unidade['descricao'];
			$x[$i]['idAcao'] = $acao['id'];
			$x[$i]['descricaoSimplificada'] = $acao['descricaoSimplificada'];
			$x[$i]['saldoOrcado'] = $orcamento['saldoOrcado'];
			$x[$i]['totalCongelado'] = $orcamento['totalCongelado'];
			$i++;			
		}
		$x['num'] = $i;				
	}
	$mensagem = "Total de eventos encontrados: ".$x['num'].".";
?>
	<br /><br />
	<section id="list_items">
		<div class="container">
			<h3>Resultado do filtro</h3>
			<?php
			if ($x['num'] == 1)
			{
				echo "<h5>Foi encontrado ".$x['num']." registro</h5>";
			}
			else
			{
				echo "<h5>Foram encontrados ".$x['num']." registros</h5>";
			}
			?>
			<h5><a href="?perfil=gabinete&p=filtrar">Aplicar outro filtro</a></h5>
			<div class="table-responsive list_info">
			<?php 
				if($x['num'] == 0)
				{  
				}
				else
				{ 
			?>
					<table class="table table-condensed">
						<thead>
							<tr class="list_menu">
							<td>Orgão / Unidade</td>	
							<td>Nome Simplificado</td>
							<td>Saldo Orçado</td>
							<td>Saldo Congelado</td>
							<td>Saldo Descongelado</td>
							</tr>
						</thead>
					<tbody>
				<?php
					$link="index.php?perfil=gabinete&p=filtrar"; //arrumar
					$data=date('Y');
					for($h = 0; $h < $x['num']; $h++)
					{		
						echo '<tr>';
						echo '<td class="list_description">'.$x[$h]['idOrgao']." - ".$x[$h]['idUnidade'].'</td>';
						echo "<td class='list_description'> <a target=_blank href='".$link.$x[$h]['id']."'>".$x[$h]['descricaoSimplificada']."</a></td>";
						echo '<td class="list_description"> R$ '.dinheiroParaBr($x[$h]['saldoOrcado']).'</td> ';
						echo '<td class="list_description">R$ '.dinheiroParaBr($x[$h]['totalCongelado']).'</td> ';
						echo '<td class="list_description"> R$ '.dinheiroParaBr($x[$h]['saldoOrcado']-$x[$h]['totalCongelado']).'</td>';
						echo '</tr>';
					}
				?>					
					</tbody>
				</table>
			<?php 
				} 
			?>		
			</div>
		</div>
	</section>
<?php
}
else
{
?>
	<section id="services" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<h2>FILTRAR</h2>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
					</div>
				</div>
				
				<form method="POST" action="?perfil=gabinete&p=filtrar" class="form-horizontal" role="form">	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Orgão</label>
						<select class="form-control" name="idOrgao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("orgao","descricao"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Unidade</label>
						<select class="form-control" name="idUnidade" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("unidade","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Função</label>
						<select class="form-control" name="idFuncao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("funcao","descricao"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Subfunção</label>
						<select class="form-control" name="idSubfuncao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("subfuncao","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Atividade ou Projeto</label>
						<select class="form-control" name="idRamo" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("ramo","descricao"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Número do Projeto / Atividade</label>
						<select class="form-control" name="idProjetoAtividade" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("Projeto_atividade","id"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Nome Simplificado</label>
						<select class="form-control" name="descricaoSimplificada" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("Projeto_atividade","descricaoSimplificada"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Fonte</label>
						<select class="form-control" name="idFonte" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("fonte","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<label>Dotação Orçamentária</label>
							<input type="text" name="dotacao" class="form-control" placeholder="Insira o número da dotação com a devida pontuação" ><br />
					</div>
				</div>	
				            
	            <div class="form-group">
		            <div class="col-md-offset-2 col-md-8">
						<input type="hidden" name="pesquisar" value="1" />
						<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
						</form>
        	    	</div>
        	    </div>
            </div>
		</div>
	</section>               

<?php 
} 

 break; 
} // fim da switch ?>