<?php
	//includes para contabilidade
	include "contabilidade/includes/menu.php";
	include "contabilidade/includes/funcoesContabilidade.php";
	
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "contabilidade/".$p.".php";
?>