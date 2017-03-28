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
	$id = trim($_POST['id']);
	$evento = trim($_POST['nomeEvento']);
	$fiscal = $_POST['fiscal'];
	$projeto = $_POST['projeto'];

	if($id == "" AND $evento == "" AND $fiscal == 0 AND $projeto == 0)
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
							
							<form method="POST" action="?perfil=gestao_eventos&p=frm_reabertura" class="form-horizontal" role="form">
							<label>Id do Evento</label>
							<input type="text" name="id" class="form-control" id="palavras" placeholder="Insira o Id do Evento" ><br />
							
							<label>Nome do Evento</label>
							<input type="text" name="nomeEvento" class="form-control" id="palavras" placeholder="Insira o objeto" ><br />
							
							<label>Fiscal, suplente ou usuário que cadastrou o evento</label>
							<select class="form-control" name="fiscal" id="inputSubject" >
								<option value="0"></option>	
								<?php echo opcaoUsuario($_SESSION['idInstituicao'],"") ?>
							</select>
							<br />	
								
							<label>Tipo de Projeto</label>
							<select class="form-control" name="projeto" id="inputSubject" >
								<option value='0'></option>
								<?php  geraOpcaoOrdem("ig_projeto_especial","projetoEspecial"); ?>
							</select>
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
		
		$sql_orcamento = "SELECT * FROM orcamento_central WHERE $filtro_orgao $filtro_unidade $filtro_funcao $filtro_subfuncao $filtro_programa $filtro_natureza $filtro_modalidade $filtro_fonte";
		$query_orcamento = mysqli_query($con,$sql_orcamento);
					
		$i = 0;

		while($orcamento = mysqli_fetch_array($query_orcamento))
		{
			$idEvento = $evento['idEvento'];	
			$evento = recuperaDados("ig_evento",$idEvento,"idEvento"); 			
			$usuario = recuperaDados("ig_usuario",$evento['idUsuario'],"idUsuario");
			$local = listaLocais($idEvento);
			$periodo = retornaPeriodo($idEvento);
			$fiscal = recuperaUsuario($evento['idResponsavel']);			
			
			$x[$i]['id']= $evento['idEvento'];
			$x[$i]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
			$x[$i]['local'] = substr($local,1);
			$x[$i]['periodo'] = $periodo;
			$x[$i]['fiscal'] = $fiscal['nomeCompleto'];			
			$i++;			
		}
		$x['num'] = $i;				
	}
	$mensagem ="Total de eventos encontrados: ".$x['num'].".";
?>
	<br /><br />
	<section id="list_items">
		<div class="container">
			<h3>Resultado da busca</h3>
			<?php
			if ($x['num'] == 1)
			{
				echo "<h5>Foi encontrado ".$x['num']." evento</h5>";
			}
			else
			{
				echo "<h5>Foram encontrados ".$x['num']." eventos</h5>";
			}
			?>
			<h5><a href="?perfil=gestao_eventos&p=frm_reabertura">Fazer outra busca</a></h5>
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
							<td>Id Evento</td>	
							<td>Nome Evento</td>
							<td>Local</td>
							<td>Periodo</td>
							<td>Fiscal</td>
							</tr>
						</thead>
					<tbody>
				<?php
					$link="index.php?perfil=gestao_eventos&p=detalhe_evento&id_eve=";
					$data=date('Y');
					for($h = 0; $h < $x['num']; $h++)
					{		
						echo "<tr><td class='list_description'> <a target=_blank href='".$link.$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
						echo '<td class="list_description">'.$x[$h]['objeto'].'</td>';
						echo '<td class="list_description">'.$x[$h]['local'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['periodo'].'</td> ';
						echo '<td class="list_description">'.$x[$h]['fiscal'].'</td> </tr>';
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
				
				<form method="POST" action="?perfil=gestao_eventos&p=frm_reabertura" class="form-horizontal" role="form">	
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
					<div class="col-md-offset-2 col-md-8"><label>Função</label>
						<select class="form-control" name="funcao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("funcao","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><label>Subfunção</label>
						<select class="form-control" name="subfuncao" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("subfuncao","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><label>Programa</label>
						<select class="form-control" name="programa" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("programa","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><label>Natureza Econômica</label>
						<select class="form-control" name="natureza" id="inputSubject" >
							<option value='0'></option>
							<?php  geraOpcao("natureza_economica","descricao"); ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><label>Modalidade Aplicada</label>
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