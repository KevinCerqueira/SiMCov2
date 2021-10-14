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
?>
<div class="modal fade" id="register-patient" tabindex="-1" aria-labelledby="register-patientLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="register-patientLabel"><i class="fa fa-user" style="color: #000; font-size: 25px !important;"></i> Registrar novo paciente</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<form id="form-register-patient">
				<div class="modal-body">
					<div class="">
						<div class="mb-3">
							<label for="name-patient">Nome do paciente:</label>
							<input required minlength="4" maxlength="20" type="text" class="form-control inputs" name="nome" placeholder="Fulado da Silva" title="É necessário um nome para o paciente.">
						</div>
						<div class="mb-3 row">
							<div class="col-md-6">
								<label for="age-patient">Idade:</label>
								<input required type="number" class="form-control inputs" name="idade" placeholder="21" title="É necessário informar a idade do paciente.">
							</div>
							<div class="col-md-6">
								<label for="gender-patient">Sexo:</label>
								<select required name="sexo" id="gender-patient" class="form-control">
									<option value="F">Feminino</option>
									<option value="M">Masculino</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button id="btn-cadastrar" type="submit" class="btn btn-primary">
						<p id="txt-cadastrar" class="m-0">Cadastrar</p>
						<div hidden id="spinner" class="spinner-border text-light" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#form-register-patient').submit(function(event) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "<?php echo MYPATH; ?>Controllers/register_patient.php",
			data: $('#form-register-patient').serialize(),
			beforeSend: function() {
				$('#txt-cadastrar').attr('hidden', '');
				$('#btn-cadastrar').attr('disabled', '');
				$('#spinner').removeAttr('hidden');
			},
			success: function(data) {
				response = JSON.parse(data);
				if (response.success) {
					Swal.fire(
						'Paciente cadastrado com sucesso! ',
						'A operação foi bem sucedida, o paciente foi cadastrado.',
						'success'
					);
					$('.inputs').val('');
				} else {
					Swal.fire(
						'Houve um erro ao cadastrar o paciente.',
						'Erro: ' + response.error,
						'error'
					);
				}
			},
			error: function(data) {
				Swal.fire(
					'Parece que estamos offline, chame o TI.',
					'',
					'error'
				);
			},
			complete: function() {
				$('#txt-cadastrar').removeAttr('hidden');
				$('#btn-cadastrar').removeAttr('disabled');
				$('#spinner').attr('hidden', '');
			}
		});
	});
</script>