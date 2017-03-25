<?php
			include "../include/menuAdministradorLocal.php";
			if(isset($_POST['carregar']))
			{
				$_SESSION['idUsuario'] = $_POST['carregar'];
			}
			if(isset($_POST['atualizar']))
			{
				$nomeCompleto = $_POST['nomeCompleto'];
				$rf = $_POST['rf'];
				$usuario = $_POST['usuario'];
				$existe = verificaExiste("ig_usuario","nomeUsuario",$usuario,"0");
				//$senha = MD5($_POST['senha']);
				$senha = MD5 ('igsis2015');
				$instituicao = $_POST['instituicao'];
				$local = $_POST['local'];
				$telefone = $_POST['telefone'];
				$perfil = $_POST['papelusuario'];
				$email = $_POST['email'];
				$existe = verificaExiste("ig_usuario","email",$usuario,"0");
				$publicado = "1";
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
					$sql_inserir = "INSERT INTO `ig_usuario` (`idUsuario`, `ig_papelusuario_idPapelUsuario`, `senha`, `receberNotificacao`, `nomeUsuario`, `email`, `nomeCompleto`, `idInstituicao`, `telefone`, `publicado`, `rf`, `local`) VALUES (NULL, '$perfil', '$senha', '$receberEmail', '$usuario', '$email', '$nomeCompleto', '$instituicao', '$telefone', '$publicado', '$rf', '$local')";
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
					$mensagem = "Usuário ou email já existente. Tente novamente.";
				}
			}
	?>
<section id="inserirUser" class="home-section bg-white">
	<div class="container">
		<div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Inserir Usuário</h3>
					<h3><?php if(isset($mensagem)){echo $mensagem;} ?></h3>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=admin&p=novoUser" class="form-horizontal" role="form">
					<!-- // Usuario !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Nome Completo:</label>
								<input type="text" name="nomeCompleto" class="form-control"id="nomeCompleto" value="" /> 
								<label>Registro Funcional:</label>
								<input type="text" name="rf" class="form-control"id="rf" value="" />
							</div> 
							<div class="col-md-offset-2 col-md-8">
								<label>Usuario:</label>
								<input type="text" name="usuario" class="form-control"id="usuario" />
							</div>  <!-- // SENHA !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Senha:</label>
								<label>igsis2015</label>
							</div> 	<!-- // Departamento !-->
							<div class="col-md-offset-2 col-md-8">	
								<label>telefone:</label>
								<input type="text" name="telefone" class="form-control"id="departamento" />
							</div>  <!-- // ig_instituicao Puxada pela SESSASION do "CRIADOR" - Admin Local !-->
							<!-- // Perfil de Usuario !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Instituição:</label>
								<select name="instituicao" class="form-control"  >
									<?php acessoInstituicao("ig_instituicao","",""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8">
								<label>Local:</label>
								<select name="local" class="form-control"  >
									<?php acessoLocal("ig_local","",""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8">
								<label>Acesso aos Perfil's:</label>
								<select name="papelusuario" class="form-control"  >
									<?php acessoPerfilUser("ig_papelusuario","3",""); ?>
								</select>
							</div>  <!--  // Email !-->
							<div class="col-md-offset-2 col-md-8">  
								<label>Email para cadastro:</label>
								<input type="text" name="email" class="form-control" id="email" value=""/>
							</div>
							<div class="col-md-offset-2 col-md-8"> <!-- // Confirmação de Recebimento de Email !-->
								<label style="padding:0 10px 0 5px;">Receber Email de atualizações: </label><input type="checkbox" name="receberEmail" id="diasemana01"/>
							</div> <!-- Fim de Preenchemento !-->  
							<!-- Botão de Confirmar cadastro !-->
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Inserir Usuário"  />
							</div>
						</div>
					</div>
				</form>
			</div>
			<form method="POST" action="?perfil=admin&p=users" class="form-horizontal"  role="form">
				<div class="col-md-offset-2 col-md-8">
					<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Lista de Usuário" />
				</div>
			</form>
		</div>
	</div>
</section> 