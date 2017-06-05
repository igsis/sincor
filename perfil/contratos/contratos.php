<?php 
$con = bancoMysqli();
$sql_contratos = "SELECT `id`, `idOrgao`, `idUnidade`, `numeroSei`, `numeroProcessoAdm`, `idPessoaJuridica`, `idNatureza`, `objeto`, `dataLimite`, `termoContrato`, `idFiscal`, `idSuplente`, `anual`, `valorInicial`, `valorReajuste`, `valorMensal`, `valorAnual` FROM `contratos`";
$query_contratos = mysqli_query($con,$sql_contratos);
			
$i = 0;		

while($contratos = mysqli_fetch_array($query_contratos))
{	
	$x[$i]['id'] = $contratos['id'];
	$x[$i]['numeroSei'] = $contratos['numeroSei'];
	$x[$i]['numeroProcessoAdm'] = $contratos['numeroProcessoAdm'];
	$i++;					
}
$x['num'] = $i;				

?>
<script language="JavaScript">
<!--
var TRange=null

function findString (str) 
{
if (parseInt(navigator.appVersion)<4) return;
var strFound;
if (window.find) {

// CODE FOR BROWSERS THAT SUPPORT window.find

strFound=self.find(str);
if (strFound && self.getSelection && !self.getSelection().anchorNode) {
strFound=self.find(str)
}
if (!strFound) {
strFound=self.find(str,0,1)
while (self.find(str,0,1)) continue
}
}
else if (navigator.appName.indexOf("Microsoft")!=-1) {

// EXPLORER-SPECIFIC CODE

if (TRange!=null) {
TRange.collapse(false)
strFound=TRange.findText(str)
if (strFound) TRange.select()
}
if (TRange==null || strFound==0) {
TRange=self.document.body.createTextRange()
strFound=TRange.findText(str)
if (strFound) TRange.select()
}
}
else if (navigator.appName=="Opera") {
alert ("Opera browsers not supported, sorry...")
return;
}
if (!strFound) alert ("String '"+str+"' not found!")
return;
}
//-->
</script>

<br /><br />
<section id="list_items">
	<div class="container">
		<br/>	
		<p align="left"><strong><?php echo saudacao(); ?>, <?php echo $_SESSION['nome']; ?></strong></p>		
		<p>&nbsp;</p>
		<div class="form-group">
			<div class="col-md-offset-1 col-md-3"><a href="?perfil=contratos&p=cadastro_contratos" class="btn btn-theme btn-block">Cadastrar</a></div>
			<div class="col-md-2"><br/></div>
			<div class="col-md-5">
				<form name="f1" action="" onSubmit="if(this.t1.value!=null && this.t1.value!='') findString(this.t1.value);return false">
					<input type="text" name=t1 value="" size=30 placeholder="Pesquisar..."> <i><b>Pressione Enter</b></i>
				<!--<input type="submit" name=b1 value="Pesquisar">-->
				</form><br/>
			</div>
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
		<div class="form-group">
			<div class="col-md-offset-1 col-md-10">
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
									<td>Processo SEI</td>
									<td>Processo Adm.</td>
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
							echo "<td class='list_description'><a target=_blank href='".$link.$x[$h]['id']."'>".$x[$h]['numeroSei']."</a></td>";
							echo '<td class="list_description">'.$x[$h]['numeroProcessoAdm'].'</td> ';
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
