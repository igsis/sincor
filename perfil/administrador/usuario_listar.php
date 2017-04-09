<?php
	$idUsuario = $_GET['idUsuario'];
	
	if (isset ($_POST ['apagar']))
	{
		$senha = MD5('sincor2017');	
		$sql_atualizar = "UPDATE `usuario` SET `publicado` = '0' WHERE `id` = '$idUsuario'";
		$con = bancoMysqli();
		if(mysqli_query ($con,$sql_atualizar))
		{
			$mensagem = "Usuário apagado!";
		}
		else
		{
			$mensagem = "Erro ao apagar usuário! Tente novamente.";
		}
	}
?>	
<section id="list_items" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">					
					<h4>Selecione o usuário para editar.</h4>
                    <h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
				</div>
			</div>
		</div>  
		<div class="table-responsive list_info">
		<?php
			
			$sql = "SELECT * FROM usuario WHERE  publicado = '1' AND nome <> '' ORDER BY nome ASC";
			$query = mysqli_query($con,$sql);
			$num_user = mysqli_num_rows($query);
		?>
			
			<p><?php echo $num_user ?> usuários encontrados.</p>
			<table class='table table-condensed'>
				<thead>					
					<tr class='list_menu'> 
						<td>Nome Completo</td>
						<td>Nome Usuário</td>
						<td>Email</td>
						<td>Perfil</td>
						<td width='15%'></td>
						<td width='15%'></td>
					</tr>	
				</thead>
				<tbody>
				
				<?php 
					while($campo = mysqli_fetch_array($query))
					{
						$perfil = recuperaDados("perfil","id",$campo['idPerfil']);
						
						echo "<tr>";
						echo "<td class='list_description'>".$campo['nome']."</td>";
						echo "<td class='list_description'>".$campo['login']."</td>";
						echo "<td class='list_description'>".$campo['email']."</td>";
						echo "<td class='list_description'>".$perfil['nomePerfil']."</td>";
						echo "
							<td class='list_description'>
								<form method='POST' action='?perfil=administrador&p=usuario_editar&idUsuario=".$campo['id']."'>
								<input type='hidden' name='editarUser' value='".$campo['id']."' />
								<input type ='submit' class='btn btn-theme btn-block' value='Editar usuário'>
								</form>
							</td>"	;
						echo "
							<td class='list_description'>
								<form method='POST' action='?perfil=administrador&p=usuario_editar&idUsuario=".$campo['id']."'>
								<input type='hidden' name='resetSenha' value='".$campo['id']."' />
								<input type ='submit' class='btn btn-theme  btn-block' value='Resetar senha' onclick='return confirm('Tem certeza que deseja realizar alterar?')'>
								</form>
							</td>"	;
						echo "</tr>";
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</section> 