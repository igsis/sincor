<?php
//geram o insert pro framework
$pasta = "?perfil=contratos&p=";
 ?>
<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<?php echo $pasta ?>pessoa_juridica">Pessoa Jurídica</a></li>
			<li><a href="<?php echo $pasta ?>funcionarios">Funcionários</a></li>
			<li><a href="<?php echo $pasta ?>natureza_contrato">Natureza Contrato</a></li>
			<li><a href="<?php echo $pasta ?>contratos">Contratos</a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="?perfil=sobre">Sobre</a></li>
			<li><a href="../include/logoff.php">Sair </a></li>
		</ul>
	</div>
</div>