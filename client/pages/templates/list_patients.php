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
include_once('Controllers/list_patients_priority.php');
?>
<div class="modal fade" id="list-patients-priority" tabindex="-1" aria-labelledby="list-patients-priorityLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="list-patients-priorityLabel"><i class="fa fa-user" style="color: #000; font-size: 25px !important;"></i> Selecione o paciente por prioridade:</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<?php if ($search_patients->success) { ?>
					<?php if (!empty($search_patients->data->high)) { ?>
						<div class="row">
							<div class="col-md-12 p-3 rounded mt-2 bg-danger"><label for="select-patient-high" class="text-white fw-bold">Alta</label>
								<select class="select-patient" id="select-patient-high" required name="id">
									<option selected id="default" value=""></option>
									<?php foreach ($search_patients->data->high as $patient) { ?>
										<option value="<?php echo $patient->id; ?>"><?php echo $patient->nome; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>
					<?php if (!empty($search_patients->data->medium)) { ?>
						<div class="row">
							<div class="col-md-12 p-3 rounded mt-2 bg-warning"><label for="select-patient-medium" class="text-white fw-bold">Media</label>
								<select class="select-patient" id="select-patient-medium" required name="id">
									<option selected id="default" value=""></option>
									<?php foreach ($search_patients->data->medium as $patient) { ?>
										<option value="<?php echo $patient->id; ?>"><?php echo $patient->nome; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>
					<?php if (!empty($search_patients->data->normal)) { ?>
						<div class="row">
							<div class="col-md-12 p-3 rounded mt-2 bg-primary"><label for="select-patient-normal" class="text-white fw-bold">Normal</label>
								<select class="select-patient" id="select-patient-normal" required name="id">
									<option selected id="default" value=""></option>
									<?php foreach ($search_patients->data->normal as $patient) { ?>
										<option value="<?php echo $patient->id; ?>"><?php echo $patient->nome; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
					<div hidden id="alert-error" class="alert alert-danger" role="alert">
						<p id="alert-text-error" class="h5 m-0"><? echo $search_patients->error; ?></p>
					</div>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button id="btn-monitorar" class="btn btn-success">
					<p id="txt-monitorar" class="m-0">Monitorar</p>
					<div hidden id="spinner" class="spinner-border text-light" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
				</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {
		$('.select-patient').select2({
			dropdownParent: $('#list-patients-priority'),
			placeholder: 'Selecione um paciente',
			dropdownAutoWidth: true,
			escapeMarkup: function(text) {
				return text;
			},
			width: "100%",
			language: "pt-BR",
			cache: true
		});
		$('#btn-monitorar').click(() => {
			let id_paciente = '';
			if ($('#select-patient-high').val() != '')
				id_paciente = $('#select-patient-high').val();
			else if ($('#select-patient-medium').val() != '')
				id_paciente = $('#select-patient-medium').val();
			else if ($('#select-patient-normal').val() != '')
				id_paciente = $('#select-patient-normal').val();

			if (id_paciente == '') {
				alert('Por favor, selecione um paciente para monitorar.');
				return;
			}else{
				return window.location = '<?php echo MYPATH;?>pages/monitor_patient.php?id=' + id_paciente
			}

		});
		let trava = false;
		$('#select-patient-high').change(() => {
			if (!trava) {
				if ($('#select-patient-medium').val() != '') {
					trava = true;
					$('#select-patient-medium').val('');
					$('#select-patient-medium').trigger('change');
				} else if ($('#select-patient-normal').val() != '') {
					trava = true;
					$('#select-patient-normal').val('');
					$('#select-patient-normal').trigger('change');
				}
			} else {
				trava = false;
			}
		});
		$('#select-patient-medium').change(() => {
			if (!trava) {
				if ($('#select-patient-high').val() != '') {
					trava = true;
					$('#select-patient-high').val('');
					$('#select-patient-high').trigger('change');
				} else if ($('#select-patient-normal').val() != '') {
					trava = true;
					$('#select-patient-normal').val('');
					$('#select-patient-normal').trigger('change');
				}
			} else {
				trava = false;
			}
		});
		$('#select-patient-normal').change(() => {
			if (!trava) {
				if ($('#select-patient-high').val() != '') {
					trava = true;
					$('#select-patient-high').val('');
					$('#select-patient-high').trigger('change');
				} else if ($('#select-patient-medium').val() != '') {
					trava = true;
					$('#select-patient-medium').val('');
					$('#select-patient-medium').trigger('change');
				}
			} else {
				trava = false;
			}

		});
	});
</script>