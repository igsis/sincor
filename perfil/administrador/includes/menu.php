<?php
//geram o insert pro framework
$pasta = "?perfil=administrador&p=";
 ?>
<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="#">Usuários</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>usuario_cadastrar">Cadastrar</a></li>
					<li><a href="<?php echo $pasta ?>usuario_listar">Listar</a></li>
				</ul>
			</li>
			<!--
			<li><a href="<?php echo $pasta ?>contabilidade">Verificar tabela contabilidade</a></li>
			<li><a href="<?php echo $pasta ?>sof"> SOF </a></li>
			-->
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="?perfil=sobre">Sobre</a></li>
			<li><a href="../include/logoff.php">Sair </a></li>
		</ul>
	</div>
</div>