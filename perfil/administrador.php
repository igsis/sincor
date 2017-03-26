<?php
	//includes para administrador
	include "administrador/includes/menu.php";
	include "administrador/includes/funcoesAdministrador.php";
	
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "administrador/".$p.".php";
?>