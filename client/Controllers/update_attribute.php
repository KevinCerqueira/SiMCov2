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
session_start();
include_once('PublisherController.php');
$pub = new PublisherController();
if (isset($_POST['id_patient']) &&  isset($_POST['attribute']) && isset($_POST['value'])) {
	$id_patient = $_POST['id_patient'];
	$attr = $_POST['attribute'];
	$value = $_POST['value'];
	$response = $pub->updateAttribute($id_patient, $attr, $value);
}
die();
