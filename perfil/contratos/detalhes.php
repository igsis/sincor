<?php 
$id = $_GET['id'];

$con = bancoMysqli();
$sql_vigencia = "
	SELECT `id`, `idContratos`, `dataInicio`, `dataFinal`, `anual`, `valorIncial`, `taxaReajuste`, `valorReajuste`, `valorMensal`, `valorAnual`, `publicado` FROM `vigencia` WHERE idContratos = $id";
$query_vigencia = mysqli_query($con,$sql_vigencia);
			
$i = 0;		

while($vigencia = mysqli_fetch_array($query_vigencia))
{
	$x[$i]['id'] = $vigencia['id'];
	$x[$i]['idContratos'] = $vigencia['idContratos'];
	$x[$i]['dataInicio'] = $vigencia['dataInicio'];
	$x[$i]['dataFinal'] = $vigencia['dataFinal'];
	$x[$i]['anual'] = $vigencia['anual'];
	$x[$i]['valorInicial'] = $vigencia['valorInicial'];
	$x[$i]['taxaReajuste'] = $vigencia['taxaReajuste'];
	$x[$i]['valorMensal'] = $vigencia['valorMensal'];
	$x[$i]['valorAnual'] = $vigencia['valorAnual'];
	$i++;					
}
$x['num'] = $i;	

$contratos = recuperaDados("contratos","id",$id);
$orgao = recuperaDados("orgao","id",$contratos['idOrgao']);
$unidade = recuperaDados("unidade","id",$contratos['idUnidade']);
$pj = recuperaDados("pessoa_juridica","id",$contratos['idPessoaJuridica']);
$natureza = recuperaDados("natureza","id",$contratos['idNatureza']);
$fiscal = recuperaDados("funcionario","id",$contratos['idFiscal']);
$suplente = recuperaDados("funcionario","id",$contratos['idSuplente']);			

?>
<br /><br />
<section id="list_items">
	<div class="container">
		<br/>
		<p align="left"><strong><?php echo saudacao(); ?>, <?php echo $_SESSION['nome']; ?></strong></p>
		<p>&nbsp;</p>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="form-group">
					<div class="col-md-offset-2 col-md-2"><strong>Orgão:</strong><br/>
						<input type="text" class="form-control" name="nome" value="<?php echo $orgao['descricao']; ?>" >
					</div>
					<div class="col-md-3"><strong>Unidade:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $unidade['descricao']; ?>" >
					</div>
					<div class="col-md-3"><strong>Processo SEI:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $contratos['numeroSei']; ?>" ><br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Processo Administrativo:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $contratos['numeroProcessoAdm']; ?>" >
					</div>
					<div class="col-md-6"><strong>CNPJ:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $pj['cnpj']; ?>" ><br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Razão Social:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $pj['razaoSocial']; ?>" ><br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Natureza:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $natureza['natureza']; ?>" >
					</div>
					<div class="col-md-6"><strong>Termo de Contrato:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $contratos['termoContrato']; ?>" ><br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><strong>Objeto:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $contratos['objeto']; ?>" ><br/>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6"><strong>Fiscal:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $fiscal['nome']; ?>" >
					</div>
					<div class="col-md-6"><strong>Suplente:</strong><br/>
						<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $suplente['nome']; ?>" ><br/>
					</div>
				</div>			
			</div>
		</div>	
		<p>&nbsp;</p>
		<?php
			if ($x['num'] == 0)
			{
				echo "<p><b>Nenhum registro foi encontrado</b></p>";
			}
			elseif ($x['num'] == 1)
			{
				echo "<p><b>Foi encontrado ".$x['num']." registro</b></p>";
			}
			else
			{
				echo "<p><b>Foram encontrados ".$x['num']." registros</b></p>";
			}
		?>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-8">
				<div class="col-md-offset-2 col-md-6"><strong>Data Limite:</strong>
				</div>
				<div class="col-md-6">
					<input type="text" class="form-control" id="nome" name="nome" value="<?php echo exibirDataBr($contratos['dataLimite']); ?>" >
				</div>
			
				<div class="table-responsive list_info">
				<?php 
					if($x['num'] == 0)
					{  
						echo "<br/><p><b>Nenhum registro foi encontrado</b></p>";
					}
					else
					{ 
				?>
						<table class="table table-condensed">
							<thead>
								<tr class="list_menu">
									<td>ID</td>
									<td>idContratos</td>
									<td>Data Início</td>
									<td>Data Final</td>
									<td>Valor Inicial</td>
									<td>Valor Mensal</td>
									<td>Valor Anual</td>						
								</tr>
							</thead>
							<tbody>
						<?php
							for($h = 0; $h < $x['num']; $h++)
							{		
								echo '<tr>';
								echo '<td class="list_description">'.$x[$h]['id'].'</td>';
								echo '<td class="list_description">'.$x[$h]['idContratos'].'</td>';
								echo '<td class="list_description">'.exibirDataBr($x[$h]['dataInicio']).'</td>';
								echo '<td class="list_description">'.exibirDataBr($x[$h]['dataFinal']).'</td>';
								echo '<td class="list_description">'.dinheiroParaBr($x[$h]['valorInicial']).'</td>';
								echo '<td class="list_description">'.dinheiroParaBr($x[$h]['valorMensal']).'</td>';
								echo '<td class="list_description">'.dinheiroParaBr($x[$h]['valorAnual']).'</td>';
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
		</div>
	</div>
</section>