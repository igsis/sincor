<?php
	//include para gabinete
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