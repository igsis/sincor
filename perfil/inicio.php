<div class="menu-area">
	<div id="dl-menu" class="dl-menuwrapper">
		<button class="dl-trigger">Open Menu</button>
		<ul class="dl-menu">
			<li><a href="?secao=perfil">Carregar Módulos</a></li>
			<li><a href="?perfil=sobre">Sobre</a></li>
			<li><a href="../include/logoff.php">Sair</a></li>			
		</ul>
	</div>
</div>
<?php
	if(isset($_GET['secao']))
	{
		$secao = $_GET['secao'];
	}
	else
	{
		$secao = "inicio";
	}
	switch($secao)
	{	
		case "inicio":
?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="section-heading">
				<p align="left"><strong><?php echo saudacao(); ?>, <?php echo $_SESSION['nome']; ?></strong></p><br>
				<h5>Bem-vindo(a) ao SINCOR - Sistema Integrado de Controle Orçamentário.</h5>
			</div>
		</div>	
		<div class="table-responsive list_info">
			<p>Selecione o módulo de trabalho.</p>
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Módulo</td>
						<td>Descrição</td>
						<td width="20%"></td>
					</tr>
				</thead>
				<tbody>
					<?php listaModulosAlfa($_SESSION['perfil']); ?>	
				</tbody>
			</table>
		</div>			
	</div>
</section>
	<?php
		break;
		case "perfil";
	?>
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h2>Módulos</h2>
					<p>Selecione o módulo de trabalho.</p>
				</div>
			</div>
		</div>
		<div class="table-responsive list_info">
			<table class="table table-condensed">
				<thead>
					<tr class="list_menu">
						<td>Módulo</td>
						<td>Descrição</td>
						<td width="20%"></td>
					</tr>
				</thead>
				<tbody>
                    <?php listaModulosAlfa($_SESSION['perfil']); ?>	
				</tbody>
			</table>
		</div>
	</div>
</section> <!--/#list_items-->
	<?php
		break;
	}
	?>