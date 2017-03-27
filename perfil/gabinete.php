<?php
	//includes para gabinete
	include "gabinete/includes/menu.php";
	include "gabinete/includes/funcoesGabinete.php";
	
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "gabinete/".$p.".php";
?>