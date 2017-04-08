<?php 
	if(isset($_POST['senha01']))
	{
		//verifica se há um post
		if(($_POST['senha01'] != "") AND (strlen($_POST['senha01']) > 5))
		{
			if($_POST['senha01'] == $_POST['senha02'])
			{
				// verifica se a nova senha foi digitada corretamente duas vezes
				$senha = recuperaDados("usuario","login",$_SESSION['login']);
				if(md5($_POST['senha03']) == $senha['senha'])
				{
					$usuario = $_SESSION['idUsuario'];
					$senha01 = md5($_POST['senha01']);
					$sql_senha = "UPDATE `usuario` SET `senha` = '$senha01' WHERE `id` = '$usuario';";
					$query_senha = mysqli_query($con,$sql_senha);
					if($query_senha)
					{
						$mensagem = "Senha alterada com sucesso!";	
					}
					else
					{
						$mensagem = "Não foi possível mudar a senha. Tente novamente.";	
					}
				}
				else
				{
						$mensagem = "Senha atual incorreta.";	
				}
			}
			else
			{
				// caso não tenha digitado 2 vezes
				$mensagem = "As senhas não conferem. Tente novamente.";
			}
		}
		else
		{
			$mensagem = "A senha não pode estar em branco e deve conter mais de 5 caracteres";	
		}
	}
?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="text-hide">
					<h4>MUDANÇA DE SENHA</h4>
					<p>&nbsp;</p>
					<h6><?php if(isset($mensagem)){echo $mensagem;} ?></h6>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form method="POST" action="?perfil=conta&p=senha"class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><label>Insira sua senha antiga para confirmar a mudança.</label>
							<input type="password" name="senha03" class="form-control" id="inputName" placeholder="">
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><label>Nova senha</label>
							<input type="password" name="senha01" class="form-control" id="inputName" placeholder="">
						</div>
						<div class=" col-md-6"><label>Redigite a nova senha</label>
							<input type="password" name="senha02" class="form-control" id="inputEmail" placeholder="">
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<button type="submit" class="btn btn-theme btn-lg btn-block">Mudar a senha</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>