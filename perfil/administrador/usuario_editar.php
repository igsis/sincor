<?php
	if (isset ($_POST ['resetSenha']))
	{
		$senha = MD5 ('sincor2017');
		$login = $_POST ['editarUser'];		
		$sql_atualizar = "UPDATE `usuario` SET `senha` = '$senha' WHERE `id` = '$login'";
		$con = bancoMysqli();
		if(mysqli_query ($con,$sql_atualizar))
		{
			$mensagem = "Senha reiniciada com sucesso!";
		}
		else
		{
			$mensagem = "Erro ao reiniciar senha! Tente novamente.";
		}
	}
	// Atualiza o banco com as informações do post
	if(isset($_POST['atualizar']))
	{
		$nome = $_POST['nome'];
		$login = $_POST['login'];
		$existe = verificaExiste("usuario","login",$login,"0");
		$email = $_POST['email'];
		$perfil = $_POST['perfil'];

		if($existe['numero'] == 0)
		{
			$sql_atualizar = "UPDATE INTO `usuario` (`nome`= '$nome', `login`= '$login', `email`= '$email', `perfil`= '$perfil') VALUES ('$nome', '$login', '$email', '$perfil')";
			$con = bancoMysqli();
			if(mysqli_query($con,$sql_atualizar))
			{ 
				$mensagem = "Usuário atualizado com sucesso!";
			}
			else
			{
				$mensagem = "Erro ao editar! Tente novamente.";
			}
		}
		else
		{
			$mensagem = "Tente novamente.";
		}
	} 
	$idUsuario = $_GET['idUsuario'];
	$recuperaUsuario = recuperaDados("usuario","id",$idUsuario); 
?>
<section id="inserirUser" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Editar Usuário</h3>
					<h3><?php if(isset($mensagem)){echo $mensagem;} ?></h3>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=administrador&p=usuario_editar" class="form-horizontal" role="form">
					<input type="hidden" name="id"  value=<?php  echo $recuperaUsuario['id'] ?> />
					<!-- // Usuario !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Nome Completo:</label>
								<input type="text" name="nome" class="form-control" value="<?php echo $recuperaUsuario['nome'] ?>"/>
							</div> 
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Login:</label>
								<input type="text" name="login" class="form-control" value="<?php echo $recuperaUsuario['login'] ?>" />
							</div> 
						</div>
						
						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<label>Email para Cadastro:</label>
								<input type="text" name="email" class="form-control" value="<?php echo $recuperaUsuario ['email']?>" />
							</div>
						</div>

						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<label>Perfil de Acesso:</label>
								<select name="perfil" class="form-control"  >
									<?php geraOpcao("perfil",$recuperaUsuario['idPerfil'],""); ?>
								</select>
							</div> 
						</div>
						
						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="atualizar" value="<?php echo $_POST['atualizar'] ?>"  />
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Atualizar Usuário" onclick="return confirm('Tem certeza que deseja realizar alterar?')" />
							</div>
						</div>
					</div>
				</form>				
				<form method="POST" action="?perfil=administrador&p=usuario_editar" class="form-horizontal" role="form">
					<div class="col-md-offset-1 col-md-10">
						<input type="hidden" name="resetSenha" value="<?php echo $_POST['resetSenha'] ?>"  />
						<input type="hidden" name="resetSenha" value="1"  />
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" name="resetSenha" value="Resetar Senha" /> <p> </p>
					</div> 
				</form>	
			</div>	
		</div>    
	</div>
</section> 