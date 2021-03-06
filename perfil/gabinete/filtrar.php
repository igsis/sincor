﻿<?php 
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
	$idElementoDespesa = $_POST['idElementoDespesa'];
	$idProjetoAtividade = $_POST['idProjetoAtividade'];
	$idDescricaoSimplificada = $_POST['idDescricaoSimplificada'];
	$idFonte = $_POST['idFonte'];
	$dotacao = trim($_POST['dotacao']);
		
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
			
	if($idElementoDespesa != '0')
	{
		$filtro_despesa = " AND idElementoDespesa = '$idElementoDespesa'";
	}
	else
	{
		$filtro_despesa = '';
	}
			
	if($idProjetoAtividade != '0')
	{
		$filtro_projetoAtividade = " AND projetoAtividade = '$idProjetoAtividade'";
	}
	else
	{
		$filtro_projetoAtividade = '';
	}
	
	if($idDescricaoSimplificada != '0')
	{
		$filtro_descricaoSimplificada = " AND idDescricaoSimplificada = '$idDescricaoSimplificada'";
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
	
	$sql_orcamento = "SELECT `id`, `dotacao`, `idOrgao`, `idUnidade`, `idFuncao`, `idSubfuncao`, `idPrograma`, `idElementoDespesa`, `idAcao`, `idCategoriaEconomica`, `idGrupoDespesa`, `idModalidadeAplicada`, `idElementoDespesa`, `idFonte`, `idDescricaoSimplificada`, `idDescricaoCompleta`, `projetoAtividade`,  SUM(saldoOrcado) AS saldoOrcado, `creditoTramitacao`, SUM(totalCongelado) AS totalCongelado, saldoDotacao, `saldoReservas`, `empenhado` FROM orcamento_central WHERE id != '' $filtro_orgao $filtro_unidade $filtro_despesa $filtro_projetoAtividade $filtro_descricaoSimplificada $filtro_fonte $filtro_dotacao GROUP BY idOrgao, idUnidade, idDescricaoSimplificada ORDER BY idOrgao, idUnidade";
	$query_orcamento = mysqli_query($con,$sql_orcamento);
				
	$i = 0;		

	while($orcamento = mysqli_fetch_array($query_orcamento))
	{
		$orgao = recuperaDados("orgao","id",$orcamento['idOrgao']);
		$unidade = recuperaDados("unidade","id",$orcamento['idUnidade']);	
		$projeto = recuperaDados("projeto_atividade","id",$orcamento['projetoAtividade']);
		$descricaoS = recuperaDados("descricao_simplificada","id",$orcamento['idDescricaoSimplificada']);
					
		$x[$i]['id'] = $orcamento['id'];
		$x[$i]['idOrgao'] = $orgao['descricao'];
		$x[$i]['idUnidade'] = $unidade['descricao'];
		$x[$i]['idAcao'] = $projeto['id'];
		$x[$i]['idDescricaoSimplificada'] = $orcamento['idDescricaoSimplificada'];
		$x[$i]['descricaoSimplificada'] = $descricaoS['descricaoSimplificada'];
		$x[$i]['saldoOrcado'] = $orcamento['saldoOrcado'];
		$x[$i]['totalCongelado'] = $orcamento['totalCongelado'];
		$i++;			
	}
	$x['num'] = $i;				

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
					$link="index.php?perfil=gabinete&p=detalhes&idDescr=";
					$data=date('Y');
					for($h = 0; $h < $x['num']; $h++)
					{		
						echo '<tr>';
						echo '<td class="list_description">'.$x[$h]['idOrgao']." - ".$x[$h]['idUnidade'].'</td>';
						echo "<td class='list_description'><a target=_blank href='".$link.$x[$h]['idDescricaoSimplificada']."'>".$x[$h]['descricaoSimplificada']."</a></td>";
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
							<?php  geraOpcao("orgao",""); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Unidade</label>
						<select class="form-control" name="idUnidade" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("unidade",""); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Nome Simplificado</label>
						<select class="form-control" name="idDescricaoSimplificada" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("descricao_simplificada",""); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Número do Projeto / Atividade</label>
						<select class="form-control" name="idProjetoAtividade" id="inputSubject" >
							<option value='0'></option>
							<?php  geraCombobox("projeto_atividade",0,"id"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">					
					<div class="col-md-offset-2 col-md-6"><label>Elemento de Despesa</label>
						<select class="form-control" name="idElementoDespesa" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("despesa",""); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Fonte</label>
						<select class="form-control" name="idFonte" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("fonte",""); ?>
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