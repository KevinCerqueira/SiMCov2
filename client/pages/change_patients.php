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
include_once($_SERVER['DOCUMENT_ROOT'] . '/SiMCov2/client/Controllers/ClientController.php');
$client = new ClientController();
$response = $client->getAll();
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
<title>Alterar Pacientes</title>
<div class="p-5 bg-light m-0" style="height: 100vh;">
	<div class="p-0 bg-white p-3 rounded shadow-sm">

		<div class="row p-0 text-center mb-3">
			<div class="col-md-1">
				<a id="back" class="btn btn-block" href="../index.php"><i class="fa fa-arrow-left float-left" style="color: #000;"></i></a>
			</div>
			<div class="col-md-11 text-center">
				<p class="h2">
					SiMCov
					<i class="fa fa-user-md" style="color: #000;"></i>
				</p>
			</div>
		</div>
		<div class="priorities" class="p-0 row">
			<div class="col-md-12 text-center">
				<p class="h5 text-dark">Pacientes</p>
			</div>
		</div>
		<div hidden id="alert-error" class="alert alert-danger" role="alert">
			<p id="alert-text-error" class="h5 m-0"></p>
		</div>
		<div class="p-0 row">
			<div class="col-md-12 p-0">
				<div id="patients" class="m-1 text-white">

					<?php if ($response->success) {
						foreach ($response->data->patients as $patient) {
					?>
							<div id="<?php echo $patient->id ?>" class="mb-2 <?php echo $patient->medicao ? 'bg-info' : 'bg-secondary'; ?> p-2">
								<p class="h5 text-light mb-2"><?php echo $patient->nome ?></p>
								<div class="btns">
									<div class="p-0 row">
										<div class="col-md-3 text-center mb-3">
											<label class='fw-bold'>&nbsp;&nbsp;&nbsp;SpO2 Oximet.&nbsp;&nbsp;&nbsp;
												<button class='btn btn-light p-1 btn-up' attr='saturacao' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
												</button>
												<button class='btn btn-light p-1 btn-down' attr='saturacao' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
												</button>
											</label>
										</div>
										<div class="col-md-3 text-center mb-3">
											<label class='fw-bold'><i class='fa fa-thermometer-half' style='font-size: 22px !important;'></i>&nbsp;Termômet.&nbsp;&nbsp;
												<button class='btn btn-light p-1 btn-up' attr='temperatura' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
												</button>
												<button class='btn btn-light p-1 btn-down' attr='temperatura' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
												</button>
											</label>
										</div>
										<div class="col-md-3 text-center mb-3">
											<label class='fw-bold'><i class='fa fa-clock-o' style='font-size: 22px !important;'></i>&nbsp;Esfigmo.&nbsp;&nbsp;
												<button class='btn btn-light p-1 btn-up' attr='pressao' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
												</button>
												<button class='btn btn-light p-1 btn-down' attr='pressao' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
												</button>
											</label>
										</div>
										<div class="col-md-3 text-center mb-3">
											<label class='fw-bold'><i class='fa fa-heartbeat' style='font-size: 20px !important;'></i>&nbsp;Frequencí.&nbsp;&nbsp;
												<button class='btn btn-light p-1 btn-up' attr='batimento' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-asc' aria-hidden='true' style='color: #000'></i>
												</button>
												<button class='btn btn-light p-1 btn-down' attr='batimento' patient='<?php echo $patient->id ?>'>
													<i class='fa fa-sort-desc' aria-hidden='true' style='color: #000'></i>
												</button>
											</label>
										</div>
									</div>
								</div>
							</div>
						<?php }
					} else { ?>
						<div id="alert-error" class="alert alert-danger mt-3" role="alert">
							<p id="alert-text-error" class="h5 m-0"><?php echo $response->error ?></p>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
	$(function(event) {
		$('.btn-down').click((event) => {
			const attr = $(event.currentTarget).attr('attr');
			const id_patient = $(event.currentTarget).attr('patient');
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
			const id_patient = $(event.currentTarget).attr('patient');
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
		// $.ajax({
		// 	type: "GET",
		// 	url: "<?php echo MYPATH; ?>Controllers/change_patients.php",
		// 	dataType: "html",
		// 	beforeSend: function() {
		// 		$('#alert-error').attr('hidden', '');
		// 		$('#alert-text-error').text('');
		// 		$('#loading').removeAttr('hidden');
		// 	},
		// 	success: function(data) {
		// 		response = JSON.parse(data);
		// 		if (response.success) {
		// 			// $("#patients").append(response.data.patients);
		// 		} else {
		// 			$('#alert-error').removeAttr('hidden');
		// 			$('#alert-text-error').text('Não foi possível carregar a lista: ' + response.error);
		// 		}

		// 	},
		// 	error: function(data) {
		// 		console.log(data);
		// 		$('#alert-error').removeAttr('hidden');
		// 		$('#alert-text-error').text('Parece que estamos offline. Chame o TI!');
		// 	},
		// 	complete: function() {
		// 		$('.priorities').removeAttr('hidden');
		// 		$('#loading').attr('hidden', '');
		// 	}
		// });
	});
</script>
<?php include_once('templates/footer.php'); ?>