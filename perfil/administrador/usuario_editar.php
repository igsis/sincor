<?php
	$idUsuario = $_GET['idUsuario'];
	
	if (isset ($_POST ['resetSenha']))
	{
		$senha = MD5('sincor2017');	
		$sql_atualizar = "UPDATE `usuario` SET `senha` = '$senha' WHERE `id` = '$idUsuario'";
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
	
	if(isset($_POST['atualizar']))
	{
		$idUsuario = $_GET['idUsuario'];
		$nome = $_POST['nome'];
		$login = $_POST['login'];
		$email = $_POST['email'];
		$perfil = $_POST['perfil'];
		
		$sql_atualizar = "UPDATE `usuario` SET `idPerfil`='$perfil',`nome`='$nome',`login`='$login',`email`='$email' WHERE id = '$idUsuario'";
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
	$recuperaUsuario = recuperaDados("usuario","id",$idUsuario); 
?>
<section id="inserirUser" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h4>EDITAR USUÁRIO</h4>
					<h6><?php if(isset($mensagem)){echo $mensagem;} ?></h6>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">				
				<div class="col-md-offset-1 col-md-10">  
					<form method="POST" action="?perfil=administrador&p=usuario_editar&idUsuario=<?php echo $idUsuario ?>" class="form-horizontal" role="form">	
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
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Atualizar Usuário" />
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-offset-1 col-md-10"> 
					<div class="form-group">	
						<div class="col-md-offset-2 col-md-6">
							<form method="POST" action="?perfil=administrador&p=usuario_editar&idUsuario=<?php echo $idUsuario ?>" class="form-horizontal" role="form">					
								<input type="hidden" name="resetSenha" value="<?php echo $_POST['resetSenha'] ?>"  />
								<input type="hidden" name="resetSenha" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-blcok" name="resetSenha" value="Resetar Senha" onclick="return confirm('Tem certeza que deseja realizar alterar?')"/> 					
							</form>
						</div>
						<div class="col-md-6">
							<form method="POST" action="?perfil=administrador&p=usuario_listar&idUsuario=<?php echo $idUsuario ?>" class="form-horizontal" role="form">					
								<input type="hidden" name="apagar" value="<?php echo $_POST['apagar'] ?>"  />
								<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Apagar" onclick="return confirm('Tem certeza que deseja realizar alterar?')"/> 					
							</form>
						</div>
					</div>
				</div>	
			</div>	
		</div>    
	</div>
</section> 