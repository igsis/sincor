<section id="list_items" class="home-section bg-white">
	<div class="form-group">
		<div class="col-md-offset-2 col-md-8">		
			<h2>Usuários Cadastrados</h2>
			<a href="?perfil=admin&p=novoUser" class="btn btn-theme btn-lg btn-block">Inserir novo usuário</a>
		</div>
	</div> 
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
			listuserAdministrador("");
		?>
		</div>
	</div>
</section> 