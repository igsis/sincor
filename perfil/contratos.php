<?php
	//includes para contabilidade
	include "contratos/includes/menu.php";
	include "contratos/includes/funcoesContratos.php";
	
	if(isset($_GET['p']))
	{
		$p = $_GET['p'];	
	}
	else
	{
		$p = "index";
	}
	include "contratos/".$p.".php";
?>