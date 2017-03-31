<?php			
	if(isset($_POST['atualizar']))
	{
		$nome = $_POST['nome'];
		$login = $_POST['login'];
		$existe = verificaExiste("usuario","login",$login,"0");
		$senha = MD5 ('sincor2017');
		$email = $_POST['email'];
		$perfil = $_POST['perfil'];
		$publicado = "1";
		
		if($existe['numero'] == 0)
		{
			$sql_inserir = "INSERT INTO `usuario`(`id`, `idPerfil`, `nome`, `login`, `senha`, `email`, `publicado`) VALUES (NULL, '$perfil', '$nome', '$login', '$senha', '$email', '$publicado')";
			$query_inserir = mysqli_query($con,$sql_inserir);
			if($query_inserir)
			{
				$mensagem = "Usuário inserido com sucesso";
			}
			else
			{
				$mensagem = "Erro ao inserir. Tente novamente.";
			}
		}
		else
		{
			$mensagem = "Usuário já existente. Tente novamente.";
		}
	}
?>
<section id="inserirUser" class="home-section bg-white">
	<div class="container">
		<div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Inserir Usuário</h3>
					<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=administrador&p=usuario_cadastrar" class="form-horizontal" role="form">
					<!-- // Usuario !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Nome Completo:</label>
								<input type="text" name="nome" class="form-control"/>
							</div> 
						</div>

						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Login:</label>
								<input type="text" name="login" class="form-control" />
							</div> 
						</div>
						
						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<label>Email para Cadastro:</label>
								<input type="text" name="email" class="form-control" />
							</div>
						</div>

						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<label>Perfil de Acesso:</label>
								<select name="perfil" class="form-control"  >
									<option>Escolha um perfil</option>
									<?php geraOpcao("perfil",""); ?>
								</select>
							</div> 
						</div>
						
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Senha padrão:</label>
								<label>sincor2017</label>
							</div> 
						</div>
						<!-- Botão de Confirmar cadastro !-->
						<div class="form-group">	
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Inserir Usuário"  />
							</div>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</section> 