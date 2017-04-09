<?php 
	$con = bancoMysqli();
		
	function geraUsuario($tabela,$select,$instituicao)
	{
		//gera os options de um select
		$con = bancoMysqli();
		$sql = "SELECT id, nome FROM usuario ";
		$query = mysqli_query($con,$sql);
		while($option = mysqli_fetch_row($query))
		{
			if($option[0] == $select)
			{
				echo "<option value='".$option[0]."' selected >".$option[1]."</option>";	
			}
			else
			{
				echo "<option value='".$option[0]."'>".$option[1]."</option>";	
			}
		}
	}
			
	function listuserAdministrador($id)
	{
		$con = bancoMysqli();
		$sql = "SELECT * FROM usuario WHERE  publicado = '1' AND nome <> '' ORDER BY nome ASC";
		$query = mysqli_query($con,$sql);
		$num_user = mysqli_num_rows($query);
		echo "<p>$num_user usuários encontrados.</p>";
		echo"<table class='table table-condensed'>
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
			<tbody>";
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
					<form method='POST' action='??perfil=administrador&p=usuario_editar&idUsuario=".$campo['id']."'>
					<input type='hidden' name='apagar' value='".$campo['id']."' />
					<input type ='submit' class='btn btn-theme  btn-block' value='Apagar usuário' onclick='return confirm('Tem certeza que deseja realizar alterar?')'>
					</form>
				</td>"	;
			echo "</tr>";		
		}
			echo "</tbody>
				</table>"; 
	}
	
	function listaLogAdministrador()
	{ 
		$con = bancoMysqli();
		$sql = "SELECT * FROM log ORDER BY data DESC";
		$query = mysqli_query($con,$sql);
		echo "
			<table class='table table-condensed'>
				<thead>
					<tr class='list_menu'>
						<td>Endereço de IP</td>
						<td>data</td>
						<td>Descrição</td>
					</tr>
				</thead>
				<tbody>";
		while($campo = mysqli_fetch_array($query))
		{
				echo "<tr>";				
				echo "<td class='list_description'>".$campo['ip']."</td>";
				echo "<td class='list_description'>".exibirDataHoraBr($campo['data'])."</td>";
				echo "<td class='list_description'>".$campo['descricao']."</td>";
				echo "<td class='list_description'></td>";
				/*echo " 
					<td class='list_description'>
						<form method='POST' action='?perfil='>
							<input type='hidden' name='carregar' value='".$campo['id']."' />
							<input type ='submit' class='btn btn-theme btn-block' value='carregar'></td></form>" ;*/
		}
		echo "
			</tbody>
			</table>";
	}	
?>