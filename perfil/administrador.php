<?php
	//include para administrador
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