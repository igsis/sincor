<?php
			include "../include/menuAdministradorLocal.php";
			if (isset ($_POST ['resetSenha']))
			{
				$senha = MD5 ('igsis2015');
				$usuario = $_POST ['editarUser'];		
				$sql_atualizar = "UPDATE `ig_usuario` SET
					`senha` = '$senha'
					WHERE `idUsuario` = '$usuario'";
				$con = bancoMysqli();
				if(mysqli_query ($con,$sql_atualizar))
				{
					$mensagem = "Senha reiniciado com sucesso";
				}
				else
				{
					$mensagem = "Erro ao reiniciar. Tente novamente.";
				}
			}
			// Atualiza o banco com as informações do post
			if(isset($_POST['atualizar']))
			{
				$usuario= $_POST ['idUsuario'];
				$nomeCompleto = $_POST['nomeCompleto'];
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
			$recuperaUsuario = recuperaDados("ig_usuario",$_POST['editarUser'],"idUsuario"); 
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
				<form method="POST" action="?perfil=admin&p=editarUser" class="form-horizontal" role="form">
					<input type="hidden" name="idUsuario"  value=<?php  echo $recuperaUsuario['idUsuario'] ?> />
					<!-- // Usuario !-->
					<div class="col-md-offset-1 col-md-10">  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<label>Nome Completo:</label>
								<input type="text" name="nomeCompleto" class="form-control"id="nomeCompleto" value="<?php echo $recuperaUsuario['nomeCompleto'] ?>" />
							</div> 
							<div class="col-md-offset-2 col-md-8">
								<label>Usuario:</label>
								<input type="text" name="nomeUsuario" class="form-control"id="nomeUsuario" value="<?php echo $recuperaUsuario['nomeUsuario'] ?>" />
							</div>  <!-- // SENHA !-->
							<!-- // Departamento !-->
							<div class="col-md-offset-2 col-md-8">	
								<label>telefone:</label>
								<input type="text" name="telefone" class="form-control"id="departamento" value="<?php echo $recuperaUsuario['telefone'] ?>" />
							</div>  <!-- // Perfil de Usuario !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Instituição:</label>
								<select name="ig_instituicao_idInstituicao" class="form-control"  >
									<?php instituicaoLocal("ig_instituicao",$recuperaUsuario['idInstituicao'],""); ?>
								</select>
							</div>  <!-- // Perfil de Usuario !-->
							<div class="col-md-offset-2 col-md-8">
								<label>Local:</label>
								<select name="local" class="form-control"  >
									<?php acessoLocal("ig_local",$recuperaUsuario['local'],""); ?>
								</select>
							</div>
							<div class="col-md-offset-2 col-md-8">
								<div class="col-md-offset-2 col-md-8">
									<label>Acesso aos Perfil's :</label>
								</div>
								<select name="papelusuario" class="form-control"  >
									<?php acessoPerfilUser("ig_papelusuario",$recuperaUsuario['ig_papelusuario_idPapelUsuario'],""); ?>
								</select>
							</div>  <!--  // Regristro Funcional 'RF' !-->
							<div class="col-md-offset-2 col-md-8">  
								<label>RF:</label>
								<input type="text" name="rf" class="form-control" value="<?php echo $recuperaUsuario ['rf']?>"/>
							</div> <!--  // Email !-->
							<div class="col-md-offset-2 col-md-8">  
								<label>Email para cadastro:</label>
								<input type="text" name="email" class="form-control" id="email" value="<?php echo $recuperaUsuario ['email']?>"/>
							</div>
							<div class="col-md-offset-2 col-md-8"> <!-- // Confirmação de Recebimento de Email !-->
								<label style="padding:0 10px 0 5px;">Receber Email de atualizações: </label><input type="checkbox" name="receberEmail" id="diasemana01"/>
							</div> <!-- Fim de Preenchemento !-->  
							<!-- Botão de Confirmar cadastro !-->
							<div class="col-md-offset-2 col-md-8">
								<script type="application/javascript">
									$(function() {
										/* caixa-confirmacao representa a id onde o caixa de confirmação deve ser criada no html */
										$( "#caixa-confirmacao" ).dialog({
										  resizable: false,
										  height:500,

										  /* 
										   * Modal desativa os demais itens da tela, impossibilitando interação com eles,
										   * forçando usuário a responder à pergunta da caixa de confirmação
										   */ 
										  modal: true,

										  /* Os botões que você quer criar */
										  buttons: {
											"Sim": function() {
											  $( this ).dialog( "close" );
											  alert("Você clicou em Sim");
											},
											"Não": function() {
											  $( this ).dialog( "close" );
											  alert("Você clicou em Não");
											}
										  }
										});
									  });
								</script>
								<input type="hidden" name="editarUser" value="<?php echo $_POST['editarUser'] ?>"  />
								<input type="hidden" name="atualizar" value="1"  />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Atualizar Usuário" onclick="return confirm('Tem certeza que deseja realizar essa ação?')" />
							</div>
						</div>
					</div>
				</form>				
				<form method="POST" action="?perfil=admin&p=editarUser" class="form-horizontal" role="form">
					<div class="col-md-offset-1 col-md-10">
						<input type="hidden" name="editarUser" value="<?php echo $_POST['editarUser'] ?>"  />
						<input type="hidden" name="resetSenha" value="1"  />
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" name="resetar_senha" value="Resetar Senha do usuario" /> <p> </p>
					</div> 
				</form>	
				<form method="POST" action="?perfil=admin&p=users" class="form-horizontal" >
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Lista de Usuário" />
					</div>
				</form>
			</div>	
		</div>    
	</div>
</section> 