<?php
/**
 * Componente Curricular: MI Concorrência e Conectividade
 * Autor: Kevin Cerqueira Gomes
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
define('MYPATH', '../');
include_once(MYPATH . 'auth.php');
include_once('templates/header.php');
?>
<style>
	#menu .btn {
		width: 15rem;
		height: 10rem;
	}

	.container {
		position: relative;
		top: 50%;
		transform: translateY(-50%);
	}

	.vertical {
		position: relative !important;
		top: 50% !important;
		transform: translateY(-50%) !important;
	}

	a {
		text-decoration: none;
		color: #FFF !important;
	}

	i {
		color: #FFF;
		font-size: 30px !important;
	}
</style>
<title>Dashboard</title>
<div class="p-5 bg-light m-0" style="height: 100vh;">
	<div class="row bg-white p-3 rounded shadow-sm mb-5">

		<div class="row text-center mb-3">
			<div class="col-md-1">
				<a id="back" class="btn btn-block" href="../index.php"><i class="fa fa-arrow-left float-left" style="color: #000;"></i></a>
			</div>
			<div class="col-md-10 text-center">
				<p class="h2">
					SiMCov
					<i class="fa fa-user-md" style="color: #000;"></i>
				</p>
			</div>
			<div class="col-md-1">
				Refresh: #</i><span id="count">0</span>
			</div>
			<div hidden id="alert-error" class="alert alert-danger mt-3" role="alert">
				<p id="alert-text-error" class="h5 m-0"></p>
			</div>
		</div>
		<div class="text-center bg-white p-3 rounded shadow">
			<div id="loading">
				<div class="spinner-grow text-dark" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
				<div class="spinner-grow text-dark" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
				<div class="spinner-grow text-dark" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
				<div class="spinner-grow text-dark" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
				<div class="spinner-grow text-dark" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
				<div class="spinner-grow text-dark" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
			</div>
			<div hidden id="priorities" class="row">
				<div class="col-md-4">
					<p class="h5 text-danger">Prioridade alta</p>
				</div>
				<div class="col-md-4">
					<p class="h5 text-warning">Prioridade média</p>
				</div>
				<div class="col-md-4">
					<p class="h5 text-primary">Prioridade normal</p>
				</div>
			</div>
			<div class="row" id="list">
				<div class="col-md-4 p-0">
					<div id="high" class="lists m-1 text-white">

					</div>
				</div>
				<div class="col-md-4 p-0">
					<div id="medium" class="lists m-1 text-white">

					</div>
				</div>
				<div class="col-md-4 p-0">
					<div id="normal" class="lists m-1 text-white">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="../vendor/buzz/buzz.min.js"></script>
<script>
	$(function(event) {

		const sound = new buzz.sound("../assets/alert.mp3");
		
		// Atualiza a lista de prioridades a cada 3 segundos.
		setInterval(requestData, 3000);
		async function requestData() {
			await $.ajax({
				type: "GET",
				url: "<?php echo MYPATH; ?>Controllers/list_priority.php",
				dataType: "html",
				beforeSend: function() {
					$('#alert-error').attr('hidden', '');
					$('#alert-text-error').text('');
					$('.lists').empty();
					if ($('#count').text() == '1') {
						$('#loading').removeAttr('hidden');
					}
				},
				success: function(data) {
					response = JSON.parse(data);
					if (response.success) {
						$("#high").append(response.data.high);
						$("#medium").append(response.data.medium);
						$("#normal").append(response.data.normal);
						$('#count').text(parseInt($('#count').text()) + 1);
						if (response.alert) {
							console.log(sound.play());
						}
					} else {
						$('#alert-error').removeAttr('hidden');
						$('#alert-text-error').text('Não foi possível carregar a lista: ' + response.error);
					}

				},
				error: function(data) {
					console.log(data);
					$('#alert-error').removeAttr('hidden');
					$('#alert-text-error').text('Parece que estamos offline. Chame o TI!');
				},
				complete: function() {
					$('#priorities').removeAttr('hidden');
					$('#loading').attr('hidden', '');
				}
			});
		}

	});
</script>
<?php include_once('templates/footer.php'); ?>