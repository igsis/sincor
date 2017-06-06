<?php 
$order = $_GET['order'];

$con = bancoMysqli();
$sql_contratos = "SELECT `id`, `idOrgao`, `idUnidade`, `numeroSei`, `numeroProcessoAdm`, `idPessoaJuridica`, `idNatureza`, `objeto`, `dataLimite`, `termoContrato`, `idFiscal`, `idSuplente`, `anual`, `valorInicial`, `valorReajuste`, `valorMensal`, `valorAnual` FROM `contratos` ORDER BY $order";
$query_contratos = mysqli_query($con,$sql_contratos);
			
$i = 0;		

while($contratos = mysqli_fetch_array($query_contratos))
{	
	$orgao = recuperaDados("orgao","id",$contratos['idOrgao']);
	$unidade = recuperaDados("unidade","id",$contratos['idUnidade']);
	$pj = recuperaDados("pessoa_juridica","id",$contratos['idPessoaJuridica']);
	$natureza = recuperaDados("natureza","id",$contratos['idNatureza']);
	$fiscal = recuperaDados("funcionarios","id",$contratos['idFiscal']);
	//$suplente = recuperaDados("funcionarios","id",$contratos['idSuplente']);
	$x[$i]['id'] = $contratos['id'];
	$x[$i]['idOrgao'] = $orgao['descricao'];
	$x[$i]['idUnidade'] = $unidade['descricao'];
	$x[$i]['numeroSei'] = $contratos['numeroSei'];
	$x[$i]['numeroProcessoAdm'] = $contratos['numeroProcessoAdm'];
	$x[$i]['idPessoaJuridica'] = $pj['razaoSocial'];
	$x[$i]['idNatureza'] = $natureza['natureza'];
	$x[$i]['objeto'] = $contratos['objeto'];
	$x[$i]['dataLimite'] = $contratos['dataLimite'];
	$x[$i]['termoContrato'] = $contratos['termoContrato'];
	$x[$i]['idFiscal'] = $fiscal['nome'];
	//$x[$i]['idSuplente'] = $suplente['nome'];
	$x[$i]['anual'] = $contratos['anual'];
	$x[$i]['valorInicial'] = $contratos['valorInicial'];
	$x[$i]['valorReajuste'] = $contratos['valorReajuste'];
	$x[$i]['valorMensal'] = $contratos['valorMensal'];
	$x[$i]['valorAnual'] = $contratos['valorAnual'];
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
						<tr>
							<td colspan="11">Ordernar por: 
								<a href="index.php?perfil=contratos&p=contratos&order=id">ID</a> |
								<a href="index.php?perfil=contratos&p=contratos&order=idOrgao">Orgão</a> | 
								<a href="index.php?perfil=contratos&p=contratos&order=idUnidade">Unidade</a> | 
								<a href="index.php?perfil=contratos&p=contratos&order=idOrgao">Razão Social</a> | 
								<a href="index.php?perfil=contratos&p=contratos&order=idOrgao">Natureza</a> | 
								<a href="index.php?perfil=contratos&p=contratos&order=dataLimite">Data Limite</a>
							</td>
						</tr>	
						<tr class="list_menu">
							<td>ID</td>
							<td>Orgão / Unidade</td>
							<td>Processo SEI</td>
							<td>Razão Social</td>
							<td>Natureza</td>
							<td>Objeto</td>
							<td>Data Limite</td>
							<td>Termo de Contrato</td>
							<td>Fiscal</td>
							<td>Valor Mensal</td>
							<td>Valor Anual</td>
						</tr>
					</thead>
				<tbody>
			<?php
				$link1="index.php?perfil=contratos&p=detalhes&id="; 
				$data=date('Y');
				for($h = 0; $h < $x['num']; $h++)
				{		
					echo '<tr>';
					echo '<td class="list_description">'.$x[$h]['id'].'</td>';
					echo '<td class="list_description">'.$x[$h]['idOrgao']." - ".$x[$h]['idUnidade'].'</td>';
					echo "<td class='list_description'><a target=_blank href='".$link1.$x[$h]['id']."'>".$x[$h]['numeroSei']."</a></td>";
					echo '<td class="list_description">'.$x[$h]['idPessoaJuridica'].'</td>';
					echo '<td class="list_description">'.$x[$h]['idNatureza'].'</td>';
					echo '<td class="list_description">'.$x[$h]['objeto'].'</td>';
					echo '<td class="list_description">'.exibirDataBr($x[$h]['dataLimite']).'</td>';
					echo '<td class="list_description">'.$x[$h]['termoContrato'].'</td>';
					echo '<td class="list_description">'.$x[$h]['idFiscal'].'</td>';
					echo '<td class="list_description">R$ '.dinheiroParaBr($x[$h]['valorMensal']).'</td>';
					echo '<td class="list_description">R$ '.dinheiroParaBr($x[$h]['valorAnual']).'</td>';
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
