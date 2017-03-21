<?php
//geram o insert pro framework da igsis
$pasta = "?perfil=administrador&p=";
 ?>
<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="<?php echo $pasta ?>usuario">Usuários</a></li>
			<li><a href="?perfil=admin&p=scripts&contabilidade=1">Verificar tabela contabilidade</a></li>
			<li><a href="?perfil=admin&p=sof"> SOF </a></li>
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="?secao=ajuda">Ajuda</a></li>
			<li><a href="../include/logoff.php">Sair </a></li>
		</ul>
	</div><!-- /dl-menuwrapper -->
</div>