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
if (isset($_GET['id']) && $_GET['id']) {
	$id_patient = $_GET['id'];
} else {
	header('Location: ' . MYPATH . 'index.php');
	exit;
}
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
<title>Monitorar paciente</title>
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
			<div hidden class="row" id="list">
				<input type="hidden" id="id" value="<?php echo $id_patient;?>">
				<i class="fa fa-user text-dark" style="font-size: 50px;"></i>

				<div class="text-center">
					<p id="nome" class="h1 m-0"></p>
					<p class="h2 m-0"><label id="idade"></label> anos</p>
				</div>
				<div class="h1 row">
					<div class="col-md-6">
					</div>
				</div>
				<div id="medicoes">
					<div class='bg-info m-0 text-white p-3 row'>
						<div class="col-md-3">
							<label class='fw-bold' style="font-size: 30px;">SpO2: <label id="saturacao"></label>%</label>&nbsp;&nbsp;&nbsp;
						</div>
						<div class="col-md-3" style='font-size: 35px !important;'>
							<i class='fa fa-thermometer-half'></i> <label id="temperatura"></label>
						</div>
						<div class="col-md-3" style='font-size: 35px !important;'>
							<i class='fa fa-clock-o'></i> <label id="pressao"></label>
						</div>
						<div class="col-md-3" style='font-size: 35px !important;'>
							<i class='fa fa-heartbeat'></i> <label id="batimento"></label>
						</div>
					</div>
				</div>
				<div id="sensores">
					<div id="change-medicoes" class="mt-3 bg-secondary p-2">
						<p class="h5 text-light mb-2">Sensores:</p>

						<div class="btns">
							<div class="p-0 row text-white">
								<div class="col-md-3 text-center mb-3">
									<label class='fw-bold'>&nbsp;&nbsp;&nbsp;SpO2 Oximet.&nbsp;&nbsp;&nbsp;
										<button class='btn btn-light p-1 btn-up' attr='saturacao' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
										</button>
										<button class='btn btn-light p-1 btn-down' attr='saturacao' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
										</button>
										<input class='form-control p-1 mt-2' id='input-saturacao' type="number" patient='<?php echo $id_patient ?>' />
									</label>
								</div>
								<div class="col-md-3 text-center mb-3">
									<label class='fw-bold'><i class='fa fa-thermometer-half' style='font-size: 22px !important;'></i>&nbsp;Termômet.&nbsp;&nbsp;
										<button class='btn btn-light p-1 btn-up' attr='temperatura' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
										</button>
										<button class='btn btn-light p-1 btn-down' attr='temperatura' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
										</button>
										<input class='form-control p-1 mt-2' type="text" id='input-temperatura' patient='<?php echo $id_patient ?>' />
									</label>
								</div>
								<div class="col-md-3 text-center mb-3">
									<label class='fw-bold'><i class='fa fa-clock-o' style='font-size: 22px !important;'></i>&nbsp;Esfigmo.&nbsp;&nbsp;
										<button class='btn btn-light p-1 btn-up' attr='pressao' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
										</button>
										<button class='btn btn-light p-1 btn-down' attr='pressao' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
										</button>
										<input class='form-control p-1 mt-2' id='input-pressao' type="number" patient='<?php echo $id_patient ?>' />
									</label>
								</div>
								<div class="col-md-3 text-center mb-3">
									<label class='fw-bold'><i class='fa fa-heartbeat' style='font-size: 20px !important;'></i>&nbsp;Frequencí.&nbsp;&nbsp;
										<button class='btn btn-light p-1 btn-up' attr='batimento' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
										</button>
										<button class='btn btn-light p-1 btn-down' attr='batimento' patient='<?php echo $id_patient ?>'>
											<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
										</button>
										<input class='form-control p-1 mt-2' id='input-batimento' type="number" patient='<?php echo $id_patient ?>' />
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(event) {
		const id_patient = $('#id').val();
		setInterval(requestData, 3000);
		async function requestData() {
			await $.ajax({
				type: "GET",
				url: "<?php echo MYPATH; ?>Controllers/patient.php?id="+id_patient,
				dataType: "html",
				beforeSend: function() {
					$('#alert-error').attr('hidden', '');
					$('#alert-text-error').text('');

					if ($('#count').text() == '0') {
						$('#loading').removeAttr('hidden');
					}
				},
				success: function(data) {
					response = JSON.parse(data);
					if (response.success) {
						if ($('#count').text() == '0') {
							$('#list').removeAttr('hidden');
						}
						$('.lists').empty();
						$('#count').text(parseInt($('#count').text()) + 1);

						$("#nome").text(response.data.nome);
						$("#idade").text(response.data.idade);
						$("#saturacao").text(response.data.saturacao);
						$("#temperatura").text(response.data.temperatura);
						$("#pressao").text(response.data.pressao);
						$("#batimento").text(response.data.batimento);
					} else {
						$('#alert-error').removeAttr('hidden');
						$('#alert-text-error').text('Não foi possível carregar o paciente: ' + response.error);
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
		$('.btn-down').click((event) => {
			const attr = $(event.currentTarget).attr('attr');
			const updown = -1;
			$.ajax({
				type: "POST",
				url: "<?php echo MYPATH; ?>Controllers/change_patients.php",
				dataType: "json",
				data: {
					attribute: attr,
					id_patient: id_patient,
					updown: updown
				},
				beforeSend: function() {
					$(event.currentTarget).attr('disabled', '');
				},
				complete: function() {
					$(event.currentTarget).removeAttr('disabled', '');
				}
			});
		});
		$('.btn-up').click((event) => {
			const attr = $(event.currentTarget).attr('attr');
			const updown = 1;
			$.ajax({
				type: "POST",
				url: "<?php echo MYPATH; ?>Controllers/change_patients.php",
				dataType: "json",
				data: {
					attribute: attr,
					id_patient: id_patient,
					updown: updown
				},
				beforeSend: function() {
					$(event.currentTarget).attr('disabled', '');
				},
				complete: function() {
					$(event.currentTarget).removeAttr('disabled', '');
				}
			});
		});

		$("#input-saturacao").mask('000');
		$("#input-temperatura").mask('00.00');
		$("#input-pressao").mask('000');
		$("#input-batimento").mask('000');

		$('#input-saturacao').keyup(() => {
			$.ajax({
				type: "POST",
				url: "<?php echo MYPATH; ?>Controllers/update_attribute.php",
				dataType: "json",
				data: {
					attribute: 'saturacao',
					id_patient: id_patient,
					value: $('#input-saturacao').val()
				},
				beforeSend: function() {
					// $('#input-saturacao').attr('disabled', '');
				},
				complete: function() {
					$('#input-saturacao').removeAttr('disabled', '');
				}
			});
		});
		$('#input-temperatura').keyup(() => {
			$.ajax({
				type: "POST",
				url: "<?php echo MYPATH; ?>Controllers/update_attribute.php",
				dataType: "json",
				data: {
					attribute: 'temperatura',
					id_patient: id_patient,
					value: $('#input-temperatura').val()
				},
				beforeSend: function() {
					// $('#input-temperatura').attr('disabled', '');
				},
				complete: function() {
					$('#input-temperatura').removeAttr('disabled', '');
				}
			});
		});
		$('#input-pressao').keyup(() => {
			$.ajax({
				type: "POST",
				url: "<?php echo MYPATH; ?>Controllers/update_attribute.php",
				dataType: "json",
				data: {
					attribute: 'pressao',
					id_patient: id_patient,
					value: $('#input-pressao').val()
				},
				beforeSend: function() {
					// $('#input-pressao').attr('disabled', '');
				},
				complete: function() {
					$('#input-pressao').removeAttr('disabled', '');
				}
			});
		});
		$('#input-batimento').keyup(() => {
			$.ajax({
				type: "POST",
				url: "<?php echo MYPATH; ?>Controllers/update_attribute.php",
				dataType: "json",
				data: {
					attribute: 'batimento',
					id_patient: id_patient,
					value: $('#input-batimento').val()
				},
				beforeSend: function() {
					// $('#input-batimento').attr('disabled', '');
				},
				complete: function() {
					$('#input-batimento').removeAttr('disabled', '');
				}
			});
		});
	});
</script>
<?php include_once('templates/footer.php'); ?>