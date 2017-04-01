﻿<?php 
$con = bancoMysqli();
$sql_orcamento = "SELECT * FROM orcamento_central WHERE idFuncao = '13' ORDER BY idOrgao, idUnidade";
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

?>
<br /><br />
<section id="list_items">
	<div class="container">
		<br/>
		<h6 align="left"><?php echo saudacao(); ?>, <?php echo $_SESSION['nome']; ?></h6>
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
		<p><a href="?perfil=gabinete&p=filtrar">Aplicar filtro</a></p>
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