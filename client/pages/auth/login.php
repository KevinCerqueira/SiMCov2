<?php
/**
 * Componente Curricular: MI Concorrência e Conectividade
 * Autor: Kevin Cerqueira Gomes e Esdras Abreu Silva
 *
 * Declaro que este código foi elaborado por mim de forma individual e
 * não contém nenhum trecho de código de outro colega ou de outro autor,
 * tais como provindos de livros e apostilas, e páginas ou documentos
 * eletrônicos da Internet. Qualquer trecho de código de outra autoria que
 * uma citação para o  não a minha está destacado com  autor e a fonte do
 * código, e estou ciente que estes trechos não serão considerados para fins
 * de avaliação. Alguns trechos do código podem coincidir com de outros
 * colegas pois estes foram discutidos em sessões tutorias.
*/
define('MYPATH', '../../');
session_start();
if (isset($_SESSION['auth'])) {
	header('Location: ' . MYPATH . 'index.php');
	exit;
}
include_once('../templates/header.php');
?>
<title>Entrar</title>
<div class="container p-5">
	<div class="card">
		<div class="card-body">
			<form id="form-login" method="post">
				<p class="h2 text-center">SiMCov
					<i class="fa fa-user-md" style="color: #000;"></i>
				</p>
				<p class="h4 text-center mt-4">Entrar</p>
				<div hidden id="alert-error" class="alert alert-danger" role="alert">
					<p id="alert-text-error" class="h5 m-0"></p>
				</div>
				<div class="mb-3">
					<label for="username" class="form-label">Seu nome de usuário:</label>
					<input required minlength="4" maxlength="16" type="text" name="username" id="username" class="form-control" placeholder="fulanodasilva">
				</div>
				<div class="mb-3">
					<label for="username" class="form-label">Senha:</label>
					<input required minlength="4" maxlength="6" type="password" name="password" id="password" class="form-control" placeholder="******">
				</div>
				<div class="mb-3">
					<button id="btn-entrar" type="submit" class="btn btn-primary">
						<p id="txt-entrar" class="m-0">Entrar</p>
						<div hidden id="spinner" class="spinner-border text-light" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</button>
				</div>
				<p>Não tem uma conta? <a href="register.php">Crie uma aqui.</a></p>
			</form>
		</div>
	</div>
</div>
<script>
	$('#username').keyup(() => {
		$('#username').val($('#username').val().replace(' ', ''));
	});
	$('#form-login').submit(function(event) {
		event.preventDefault();
		
		$.ajax({
			type: "POST",
			url: "<?php echo MYPATH; ?>Controllers/login.php",
			data: $('#form-login').serialize(),
			beforeSend: function() {
				$('#alert-error').attr('hidden', '');
				$('#alert-text-error').text('');
				$('#txt-entrar').attr('hidden', '');
				$('#btn-entrar').attr('disabled', '');
				$('#spinner').removeAttr('hidden');
			},
			success: function(data) {
				response = JSON.parse(data);
				console.log(response);
				if (response.success) {
					return window.location = '<?php echo MYPATH; ?>index.php'
				} else {
					$('#alert-error').removeAttr('hidden');
					$('#alert-text-error').text(response.error);
				}
			},
			error: function(data) {
				console.log(data);
				$('#alert-error').removeAttr('hidden');
				$('#alert-text-error').text('Parece que estamos offline. Chame o TI!');
			},
			complete: function() {
				$('#txt-entrar').removeAttr('hidden');
				$('#btn-entrar').removeAttr('disabled');
				$('#spinner').attr('hidden', '');
			}
		});
	});
</script>
<?php include_once('../templates/footer.php'); ?>