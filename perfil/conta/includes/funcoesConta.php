<?php 
	$con = bancoMysqli();
	
	function recuperaUsuarioCompleto($id)
	{
		//retorna dados do usuário
		$recupera = recuperaDados('usuario','id',$id);
		if($recupera)
		{
			$perfil = recuperaDados("perfil","id",$recupera['idPerfil']);
			$modulos = retornaModulos($recupera['idPerfil']);
			
			$x = array(
				"nome" => $recupera['nome'],
				"email" => $recupera['email'],
				"login" => $recupera['login'],
				"modulos" => $modulos,
				"perfil" => $perfil['nomePerfil']);				
			return $x;
		}
		else
		{
			return NULL;
		}
	}
?>