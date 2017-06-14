<?php
$idPj = $_GET['idPj'];

if(isset($_POST['atualizar']))
{
	$razaoSocial = $_POST['razaoSocial'];
	$cnpj = $_POST['cnpj'];
	
	$sql = "UPDATE pessoa_juridica SET 
		razaoSocial = '$razaoSocial', 
		cnpj = '$cnpj' 
		WHERE id = '$idPj'";
	$query = mysqli_query($con,$sql);
	if($query)
	{
		$mensagem = "Pessoa Jurídica inserida com sucesso!";
	}
	else
	{
		$mensagem = "Erro ao inserir! Tente novamente.";
	}
}
$pj = recuperaDados("pessoa_juridica","id","$idPj");
?>
<section class="home-section bg-white">
	<div class="container">
		<div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="text-hide">
                    <h3>Cadastro de Pessoa Jurídica</h3>
					<h6><font color="#4169E1"><?php if(isset($mensagem)){echo $mensagem;} ?></font></h6>
                </div>
            </div>
    	</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=contratos&p=cadastro_pessoa_juridica&idPj=<?php echo $idPj ?>" class="form-horizontal" role="form">					
					 
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>CNPJ:</label>
							<input type="text" name="cnpj" class="form-control"value="<?php echo $pj['cnpj'] ?>" /> 
						</div>
					</div> 
					
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<label>Razão Social:</label>
							<input type="text" name="razaoSocial" class="form-control" value="<?php echo $pj['razaoSocial'] ?>" /> 
						</div>
					</div> 
					 
						<!-- Botão de Confirmar cadastro !-->
						<div class="col-md-offset-2 col-md-8">
							<input type="submit" name="atualizar" class="btn btn-theme btn-lg btn-block" value="Gravar"  />
						</div>
					
				</form>				
			</div>
			
			<div class="col-md-offset-2 col-md-8"><p>&nbsp;</p><p>&nbsp;</p></div>
			
			<div class="col-md-offset-2 col-md-8"><h6><a href="?perfil=contratos&p=pessoa_juridica">Voltar à lista de Pessoa Jurídica</a></h6></div>
			
			<div class="col-md-offset-2 col-md-8"><p>&nbsp;</p><p>&nbsp;</p></div>
			
			<div class="col-md-offset-2 col-md-8">
				<form method="POST" action="?perfil=contratos&p=pessoa_juridica" class="form-horizontal"  role="form">
					<div class="col-md-offset-2 col-md-8">
						<input type="submit" class="btn btn-theme btn-lg btn-blcok" value="Voltar" />
					</div>
				</form>
			</div>
			
		</div>
	</div>
</section> 