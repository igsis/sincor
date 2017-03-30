<?php
	//includes para contabilidade
	include "conta/includes/menu.php";
	include "conta/includes/funcoesConta.php";
	
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "conta/".$p.".php";
?>