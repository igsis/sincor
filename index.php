<?php 

include "funcoes/funcoesGerais.php";
require "funcoes/funcoesConecta.php";

session_start();

if(isset($_POST['login']))
{
	$login = $_POST['login'];
	$senha = $_POST['senha'];
	autenticalogin($login,$senha);	
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SINCOR - v0.1 - 2017</title>
		<link href="visual/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="visual/css/style.css" rel="stylesheet" media="screen">
		<link href="visual/color/default.css" rel="stylesheet" media="screen">
		<script src="visual/js/modernizr.custom.js"></script>
	</head>
	<body>
		<section id="contact" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<div class="text-hide">
							<h2>SINCOR - SMC</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-1 col-md-10">
					<form method="POST" action="index.php" class="form-horizontal" role="form">
						<div class="form-group">
							<div class="col-md-offset-2 col-md-6">
								<input type="text" name="login" class="form-control" placeholder="Usuário">
							</div>				  
							<div class=" col-md-6">
								<input type="password" name="senha" class="form-control" placeholder="Senha">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<button type="submit" class="btn btn-theme btn-lg btn-block">Entrar</button>
							</div>
						</div>
					</form>
					
					<br />
					
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<p>Dúvidas? Envie e-mail para: <strong>stidesenvolvimento@prefeitura.sp.gov.br</strong></p>								
								<br />
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>   
		<?php include "visual/rodape.php" ?>
    </body>
</html>