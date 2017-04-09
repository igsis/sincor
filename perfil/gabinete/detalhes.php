<?php 
$idDescr = $_GET['idDescr'];

$con = bancoMysqli();
$sql_orcamento = "SELECT `id`, `dotacao`, `idOrgao`, `idUnidade`, `idFuncao`, `idSubfuncao`, `idPrograma`, `idRamo`, `idAcao`, `idCategoriaEconomica`, `idGrupoDespesa`, `idModalidadeAplicada`, `idElementoDespesa`, `idFonte`, `idDescricaoSimplificada`, `idDescricaoCompleta`, `projetoAtividade`,  saldoOrcado, `creditoTramitacao`, totalCongelado, saldoDotacao, `saldoReservas`, `empenhado` FROM orcamento_central WHERE idDescricaoSimplificada = '$idDescr' ORDER BY idOrgao, idUnidade";
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

?>
<br /><br />
<section id="list_items">
	<div class="container">
		<br/>
		<p align="left"><strong><?php echo saudacao(); ?>, <?php echo $_SESSION['nome']; ?></strong></p>
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
		<div class="table-responsive list_info">
		<?php 
			if($x['num'] == 0)
			{  
			}
			else
			{ 
		?>
				<table class="table table-condensed">
					<thead><h6><?php echo $descricaoS['descricaoSimplificada']; ?></h6>
						<tr class="list_menu">
						<td>Orgão / Unidade</td>
						<td>Descrição completa</td>
						<td>Saldo Orçado</td>
						<td>Saldo Congelado</td>
						<td>Saldo Descongelado</td>
						</tr>
					</thead>
				<tbody>
			<?php
				for($h = 0; $h < $x['num']; $h++)
				{		
					echo '<tr>';
					echo '<td class="list_description">'.$x[$h]['idOrgao']." - ".$x[$h]['idUnidade'].'</td>';
					echo '<td class="list_description"></td>';
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