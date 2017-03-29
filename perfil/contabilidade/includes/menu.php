<?php
//geram o insert pro framework
$pasta = "?perfil=contabilidade&p=";
 ?>
<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="#">SOF</a>
				<ul class="dl-submenu">
					<li><a href="<?php echo $pasta ?>sof_empenhado">Empenhado</a></li>
					<li><a href="<?php echo $pasta ?>sof_saldoPorUnidade">Saldo por unidade</a></li>
				</ul>
			</li>
			<li style="color:white;">-------------------------</li>
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="../include/logoff.php">Sair </a></li>
		</ul>
	</div>
</div>