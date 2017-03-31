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
	$orgao = $_POST['orgao'];
	$unidade = $_POST['unidade'];
	$funcao = $_POST['funcao'];
	$subfuncao = $_POST['subfuncao'];
	$programa = $_POST['programa'];
	$modalidade = $_POST['modalidade'];
	$fonte = $_POST['fonte'];
	$dotacao = trim($_POST['dotacao']);

	if($orgao == "" AND $unidade == "" AND $funcao == "" AND $subfuncao == "" AND $programa == "" AND $modalidade == "" AND $fonte == "" AND dotacao == "")
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
		if($orgao != '')
		{
			$filtro_orgao = " AND idOrgao = '$orgao'";
		}
		else
		{
			$filtro_orgao = '';
		}
		
		if($unidade != '')
		{
			$filtro_unidade = " AND idUnidade = '$unidade'";
		}
		else
		{
			$filtro_unidade = '';
		}
		
		if($funcao != '')
		{
			$filtro_funcao = " AND idFuncao = '$funcao'";
		}
		else
		{
			$filtro_funcao = '';
		}
		
		if($subfuncao != '')
		{
			$filtro_subfuncao = " AND idSubfuncao = '$subfuncao'";
		}
		else
		{
			$filtro_subfuncao = '';
		}
		
		if($programa != '')
		{
			$filtro_programa = " AND idPrograma = '$programa'";
		}
		else
		{
			$filtro_programa = '';
		}
		
		if($natureza != '')
		{
			$filtro_natureza = " AND idNatureza = '$natureza'";
		}
		else
		{
			$filtro_natureza = '';
		}
		
		if($modalidade != '')
		{
			$filtro_modalidade = " AND idModalidade = '$modalidade'";
		}
		else
		{
			$filtro_modalidade = '';
		}
		
		if($fonte != '')
		{
			$filtro_fonte = " AND idFonte = '$fonte'";
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
		/*
		if($nomeEvento != '')
		{
			$filtro_nomeEvento = " AND nomeEvento LIKE '%$nomeEvento%' OR autor LIKE '%$nomeEvento%' ";
		}
		else
		{
			$filtro_nomeEvento = "";			
		}		
				
		if($fiscal != 0)
		{
			$filtro_fiscal = " AND (idResponsavel = '$fiscal' OR suplente = '$fiscal' OR idUsuario = '$fiscal' )";	
		}
		else
		{
			$filtro_fiscal = "";	
		}	
		*/
		
		$sql_orcamento = "SELECT * FROM orcamento_central WHERE id != '' $filtro_orgao $filtro_unidade $filtro_funcao $filtro_subfuncao $filtro_programa $filtro_natureza $filtro_modalidade $filtro_fonte $filtro_dotacao";
		$query_orcamento = mysqli_query($con,$sql_orcamento);
					
		$i = 0;
		

		while($orcamento = mysqli_fetch_array($query_orcamento))
		{/*
			$orgao = recuperaDados("orgao","id",$orcamento['idOrgao']);
			$unidade = recuperaDados("unidade","id",$orcamento['idUnidade']);	
			$acao = recuperaDados("acao","id",$orcamento['idAcao']);			
						
			$x[$i]['orgao'] = $orgao['descricao'];
			$x[$i]['unidade'] = $unidade['descricao'];
			$x[$i]['idAcao'] = $acao['id'];
			$x[$i]['descricaoSimplificada'] = $acao['descricaoSimplificada'];*/
			$x[$i]['saldoDotacao'] = $orcamento['saldoDotacao'];
			$i++;			
		}
		$x['num'] = $i;				
	}
	$mensagem = "Total de eventos encontrados: ".$x['num'].".";
?>
	<br /><br />
	<section id="list_items">
		<div class="container">
			<h3>Resultado da busca</h3>
			<?php
			var_dump($orcamento);
			if ($x['num'] == 1)
			{
				echo "<h5>Foi encontrado ".$x['num']." registro</h5>";
			}
			else
			{
				echo "<h5>Foram encontrados ".$x['num']." registros</h5>";
			}
			?>
			<h5><a href="?perfil=gabinete&p=buscar">Fazer outra busca</a></h5>
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
					$link="index.php?perfil=gestao_eventos&p=detalhe_evento&id_eve=";
					$data=date('Y');
					for($h = 0; $h < $x['num']; $h++)
					{		
						echo "<tr><td class='list_description'> <a target=_blank href='".$link.$x[$i]['orgao']."'>".$x[$i]['orgao']."</a></td>";
						echo '<td class="list_description">'.$x[$i]['descricaoSimplificada'].'</td>';
						echo '<td class="list_description">'.$x[$i]['saldoDotacao'] .'</td> ';
						echo '<td class="list_description">'.$x[$i]['idAcao'].'</td> ';
						echo '<td class="list_description">'.$x[$i]['unidade'].'</td> </tr>';
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
					<h2>BUSCAR</h2>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?>
					</div>
				</div>
				
				<form method="POST" action="?perfil=gabinete&p=buscar" class="form-horizontal" role="form">	
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Orgão</label>
						<select class="form-control" name="orgao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("orgao","descricao"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Unidade</label>
						<select class="form-control" name="unidade" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("unidade","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Função</label>
						<select class="form-control" name="funcao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("funcao","descricao"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Subfunção</label>
						<select class="form-control" name="subfuncao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("subfuncao","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><label>Programa</label>
						<select class="form-control" name="programa" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("programa","descricao"); ?>
						</select>
					</div>
					<div class="col-md-6"><label>Modalidade Aplicada</label>
						<select class="form-control" name="modalidade" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("modalidade_aplicada","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><label>Fonte</label>
						<select class="form-control" name="fonte" id="inputSubject" >
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