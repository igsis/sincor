<?php
//geram o insert pro framework
$pasta = "?perfil=gabinete&p=";
 ?>
<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<a href=<?php echo $pasta ?>buscar">Buscar</a></li>
			<li><a href="<a href=<?php echo $pasta ?>listar">Listar</a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="../include/logoff.php">Sair </a></li>
		</ul>
	</div>
</div>