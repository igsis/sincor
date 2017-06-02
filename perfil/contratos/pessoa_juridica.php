<?php 
$con = bancoMysqli();
$sql_pj = "SELECT `id`, `razaoSocial`, `cnpj` FROM `pessoa_juridica";
$query_pj = mysqli_query($con,$sql_pj);
			
$i = 0;		

while($pj = mysqli_fetch_array($query_pj))
{	
	$x[$i]['id'] = $pj['id'];
	$x[$i]['razaoSocial'] = $pj['razaoSocial'];
	$x[$i]['cnpj'] = $pj['cnpj'];
	$i++;					
}
$x['num'] = $i;				

?>
<br /><br />
<section id="list_items">
	<div class="container">
		<br/>	
		<p align="left"><strong><?php echo saudacao(); ?>, <?php echo $_SESSION['nome']; ?></strong></p>		
		<p>&nbsp;</p>
		<div class="form-group">
			<div class="col-md-offset-1 col-md-3"><a href="?perfil=contratos&p=cadastro_pj" class="btn btn-theme btn-block">Cadastrar</a></div>
			<div class="col-md-3"><br/></div>
			<div class="col-md-4"><span class="notranslate" onMouseOver="_tipon(this)" onMouseOut="_tipoff()"><span class="google-src-text" style="direction: ltr; text-align: left"><script type=text/javascript language=JavaScript src=js/find2.js> </script></span><br/></div>
		</div>
		<p>&nbsp;</p>
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
							<td>ID</td>
							<td>CNPJ</td>							
							<td>Razão Social</td>
						</tr>
					</thead>
				<tbody>
			<?php
				$link="index.php?perfil=contratos&p=detalhes&id="; 
				$data=date('Y');
				for($h = 0; $h < $x['num']; $h++)
				{		
					echo '<tr>';
					echo '<td class="list_description">'.$x[$h]['id'].'</td>';
					echo "<td class='list_description'><a target=_blank href='".$link.$x[$h]['id']."'>".$x[$h]['cnpj']."</a></td>";
					echo '<td class="list_description">'.$x[$h]['razaoSocial'].'</td> ';
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