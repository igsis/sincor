<?php
	if (isset ($_POST ['resetSenha']))
	{
		$senha = MD5 ('sincor2017');
		$login = $_POST ['editarUser'];		
		$sql_atualizar = "UPDATE `usuario` SET `senha` = '$senha' WHERE `id` = '$login'";
		$con = bancoMysqli();
		if(mysqli_query ($con,$sql_atualizar))
		{
			$mensagem = "Senha reiniciada com sucesso";
		}
		else
		{
			$mensagem = "Erro ao reiniciar. Tente novamente.";
		}
	}
	// Atualiza o banco com as informações do post
	if(isset($_POST['atualizar']))
	{
		$usuario= $_POST ['id'];
		$nomeCompleto = $_POST['nome'];
		$nomeUsuario = $_POST['nomeUsuario'];
		$existe = verificaExiste("ig_usuario","nomeUsuario",$usuario,"0");
		$telefone = $_POST['telefone'];
		$instituicao = $_SESSION['id_usuario = 1'] = $_POST['ig_instituicao_idInstituicao'];
		$local = $_POST['local'];
		$perfil = $_POST['papelusuario'];
		$rf	=	$_POST['rf'];
		$email = $_POST['email'];	
		if(isset($_POST['receberEmail']))
		{
			$receberEmail =	1;
		}
		else
		{
			$receberEmail =	0;
		}
		if($existe['numero'] == 0)
		{
			$sql_atualizar = "UPDATE `ig_usuario`SET
				`nomeCompleto`= '$nomeCompleto',
				`nomeUsuario`= '$nomeUsuario', 
				`telefone`= '$telefone',
				`idInstituicao` = '$instituicao',
				`local`= '$local',
				`ig_papelusuario_idPapelUsuario`= '$perfil',
				`rf`= '$rf',	
				`email`= '$email', 
				`receberNotificacao`= '$receberEmail'			
				WHERE `idUsuario` = '$usuario' ";
			$con = bancoMysqli();
			if(mysqli_query($con,$sql_atualizar))
			{ 
				$mensagem = "Usuário atualizado com sucesso";
			}
			else
			{
				$mensagem = "Erro ao editar. Tente novamente.";
			}
		}
		else
		{
			$mensagem = "Tente novamente.";
		}
	} 
	$recuperaUsuario = recuperaDados("usuario",$_POST['editarUser'],"id"); 
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
								<input type="text" name="nome" class="form-control" value="<?php echo $recuperaUsuario['nomeCompleto'] ?>"/>
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
								<label>Email para cadastro:</label>
								<input type="text" name="email" class="form-control" value="<?php echo $recuperaUsuario ['email']?>" />
							</div>
						</div>

						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<label>Perfil de acesso:</label>
								<select name="perfil" class="form-control"  >
									<option>Escolha um perfil</option>
									<?php geraOpcao("perfil",""); ?>
								</select>
							</div> 
						</div>
						
						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="editarUser" value="<?php echo $_POST['editarUser'] ?>"  />
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Atualizar Usuário" onclick="return confirm('Tem certeza que deseja realizar essa ação?')" />
							</div>
						</div>
					</div>
				</form>				
				<form method="POST" action="?perfil=administrador&p=usuario_editar" class="form-horizontal" role="form">
					<div class="col-md-offset-1 col-md-10">
						<input type="hidden" name="editarUser" value="<?php echo $_POST['editarUser'] ?>"  />
						<input type="hidden" name="resetSenha" value="1"  />
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" name="resetar_senha" value="Resetar Senha do usuario" /> <p> </p>
					</div> 
				</form>	
			</div>	
		</div>    
	</div>
</section> 