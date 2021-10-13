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
define('MYPATH', '');
include_once('auth.php');
include_once('pages/templates/header.php');
include_once('pages/templates/register_patient.php');
include_once('pages/templates/delete_patient.php');
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

	a {
		text-decoration: none;
		color: #FFF !important;
	}

	i {
		color: #FFF;
		font-size: 50px !important;
	}
</style>
<title>Dashboard</title>
<div class="p-5 bg-light m-0" style="height: 100vh;">
	<div class="m-0">
		<div class="row bg-white p-3 rounded shadow-sm mb-5">
			<div class="row text-center mb-3">
				<div class="col-md-10 text-center">
					<p class="h2 m-0">
						SiMCov
						<i class="fa fa-user-md" style="color: #000;"></i>
					</p>
				</div>
				<div class="col-md-2">User:
					<p class="h5 m-0"><?php echo $_SESSION['username']?></p>
				</div>
				<div hidden id="alert-error" class="alert alert-danger mt-3" role="alert">
					<p id="alert-text-error" class="h5 m-0"></p>
				</div>
			</div>
		</div>
		<div id="menu" class="text-center bg-white p-3 rounded shadow">
			<div class="row mb-3">
				<div class="col-md-4">
					<button class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#register-patient">
						<p class="h2">Registrar novo paciente</p>
						<i class="fa fa-user"></i>
					</button>
				</div>
				<div class="col-md-4">
					<a id="list-priority" class="btn btn-danger" href="pages/list_priority.php">
						<p class="h2">Ver lista de prioridade</p>
						<i class="fa fa-th-list"></i>
					</a>
				</div>
				<div class="col-md-4">
					<a class="btn btn-warning" href="pages/change_patients.php">
						<p class="h2">Medir um paciente</p>
						<i class="fa fa-heartbeat"></i>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<button class="btn btn-danger btn-block btn-apagar-paciente" data-bs-toggle="modal" data-bs-target="#delete-patient">
						<p class="h2">Apagar um paciente</p>
						<i class="fa fa-trash"></i>
					</button>
				</div>
				<div class="col-md-6">
					<button id="logout" class="btn btn-warning">
						<p class="h2 text-white">Sair do sistema
						</p>
						<i class="fa fa-sign-out mt-2"></i>
					</button>
				</div>
			</div>

		</div>
	</div>
</div>
<script>
	$('#logout').click(() => {
		return window.location = 'pages/auth/logout.php';
	});
</script>
<?php include_once('pages/templates/footer.php'); ?>