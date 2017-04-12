<?php 
	$con = bancoMysqli();
	function listarAtualizacoes()
	{
		$con = bancoMysqli();
		$sql = "SELECT * FROM atualizacao ORDER BY 'id' LIMIT 0,29";
		$query = mysqli_query($con,$sql);
		echo
			"<table class='table table-condensed'>
				<thead>
					<tr class='list_menu'>
						<td align='center'>Texto</td>
					</tr>
				</thead>
				<tbody>";
		while($campo = mysqli_fetch_array($query))
		{
			echo
					"<tr>
						<td align='center' class='list_description'>".$campo['texto']."</td>
					</tr>";
		}
		echo "</tbody>
			</table>";
	}
?>