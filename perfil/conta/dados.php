<?php 
	if(isset($_POST['atualizar']))
	{
		//atualiza os dados
		$nome = $_POST['nome'];
		$email  = $_POST['email'];
		$idUsuario = $_SESSION['idUsuario'];
		$sql_atualiza_dados = "UPDATE usuario SET `nome` = '$nome', email = '$email' WHERE id = '$idUsuario';";
		$con = bancoMysqli();
		$query_atualiza_dados = mysqli_query($con, $sql_atualiza_dados);	
		if($query_atualiza_dados)
		{
			$mensagem = "Dados atualizados!";
			gravarLog($sql_atualiza_dados);	
		}
		else
		{
			$mensagem = "Erro ao atualizar! Tente novamente.";
		}
	}
	$conta = recuperaUsuarioCompleto($_SESSION['idUsuario']);
?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<h3>DADOS DO USUÁRIO</h3>
		<p><strong><?php if(isset($mensagem)){ echo $mensagem; } ?></strong></p> <br/>
		<p>Se necessitar a edição de um campo não permitido neste formulário, contacte o administrador local.</p> 
		
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=conta&p=dados" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome completo:</strong><br/>
							<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $conta['nome']; ?>" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Email:</strong><br/>
							<input type="text"  class="form-control" id="email" name="email" value="<?php echo $conta['email']; ?>"  >
						</div>
						<div class="col-md-6"><strong>Usuário:</strong><br/>
							<input type="text" readonly class="form-control" id="usuario" name="usuario" value="<?php echo $conta['login']; ?>" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Perfil:</strong><br/>
							<input type="text" readonly class="form-control" value="<?php echo $conta['perfil']; ?>">
						</div>
					</div> 
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Módulos habilitados</strong><br/>
							<textarea name="publico" readonly="readonly" class="form-control" rows="5" placeholder=""><?php echo $conta['modulos']; ?></textarea>
						</div>
					</div> 
					<!-- Botão Gravar -->	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="atualizar" value="1" />
							<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>