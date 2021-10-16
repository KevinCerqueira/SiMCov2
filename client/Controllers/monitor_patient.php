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

// Organiza os grupos e deixando o front-end mais agradavél
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/SiMCov2/client/Controllers/ClientController.php');
$client = new ClientController();
$response = $client->getListPriority();
if ($response->success) {
	$high = "<p class='text-sem-pacientes h5 text-white p-1 bg-success'>Sem pacientes para este nível.</p>";
	$medium = $high;
	$normal = $medium;

	// Verificando se houve alterações na lista de prioridade alta para alertar o médico
	$alert = false;
	if (isset($_SESSION['list_priority'])) {
		if ((count($_SESSION['list_priority']->high) != count($response->data->high)))
			$alert = true;
		elseif (isset($_SESSION['list_priority']->high[0]) && isset($response->data->high[0]) && $_SESSION['list_priority']->high[0]->nome != $response->data->high[0]->nome)
			$alert = true;
	}

	$_SESSION['list_priority'] = $response->data;
	foreach ($response->data->high as $index => $patient) {
		if ($index == 0) {
			$high = "";
		}
		$high .= "<div class='mb-2'>";
		$high .= "<p class='bg-danger m-0 text-white fw-bold'>" . $patient->nome . "</p><p class='bg-danger m-0 text-white fw-bold'>" .  $patient->idade . " anos | " . $patient->sexo . "</p>";
		$high .= "<p class='bg-danger m-0 text-white'><label class='fw-bold'>SpO2: " . $patient->saturacao . "%</label>&nbsp;&nbsp;&nbsp;" .
			" <i class='fa fa-thermometer-half' style='font-size: 22px !important;'></i> " . $patient->temperatura .
			" <i class='fa fa-clock-o' style='font-size: 22px !important;'></i> " . $patient->pressao .
			" <i class='fa fa-heartbeat' style='font-size: 20px !important;'></i> " . $patient->batimento . "</p>";
		$high .= "</div>";
	}
	foreach ($response->data->medium as $index => $patient) {
		if ($index == 0) {
			$medium = "";
		}
		$medium .= "<div class='mb-2'>";
		$medium .= "<p class='bg-warning m-0 text-white fw-bold'>" . $patient->nome . "</p><p class='bg-warning m-0 text-white fw-bold'>" .  $patient->idade . " anos | " . $patient->sexo . "</p>";
		$medium .= "<p class='bg-warning m-0 text-white'><label class='fw-bold'>SpO2: " . $patient->saturacao . "%</label>&nbsp;&nbsp;&nbsp;" .
			" <i class='fa fa-thermometer-half' style='font-size: 22px !important;'></i> " . $patient->temperatura .
			" <i class='fa fa-clock-o' style='font-size: 22px !important;'></i> " . $patient->pressao .
			" <i class='fa fa-heartbeat' style='font-size: 20px !important;'></i> " . $patient->batimento . "</p>";
		$medium .= "</div>";
	}
	foreach ($response->data->normal as $index => $patient) {
		if ($index == 0) {
			$normal = "";
		}
		$normal .= "<div class='mb-2'>";
		$normal .= "<p class='bg-primary m-0 text-white fw-bold'>" . $patient->nome . "</p><p class='bg-primary m-0 text-white fw-bold'>" .  $patient->idade . " anos | " . $patient->sexo . "</p>";
		$normal .= "<p class='bg-primary m-0 text-white'><label class='fw-bold'>SpO2: " . $patient->saturacao . "%</label>&nbsp;&nbsp;&nbsp;" .
			" <i class='fa fa-thermometer-half' style='font-size: 22px !important;'></i> " . $patient->temperatura .
			" <i class='fa fa-clock-o' style='font-size: 22px !important;'></i> " . $patient->pressao .
			" <i class='fa fa-heartbeat' style='font-size: 20px !important;'></i> " . $patient->batimento . "</p>";
		$normal .= "</div>";
	}
	echo json_encode(['success' => true, 'data' => ['high' => $high, 'medium' => $medium, 'normal' => $normal], 'alert' => $alert]);
	die();
}
echo json_encode($response);
