<?php
//geram o insert pro framework
$pasta = "?perfil=gabinete&p=";
 ?>
<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<?php echo $pasta ?>filtrar">Filtrar</a></li>
			<li><a href="<?php echo $pasta ?>todos">Todos</a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="?perfil=sobre">Sobre</a></li>
			<li><a href="../include/logoff.php">Sair </a></li>
		</ul>
	</div>
</div>