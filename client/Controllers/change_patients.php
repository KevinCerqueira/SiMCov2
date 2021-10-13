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
include_once($_SERVER['DOCUMENT_ROOT'] . '/SiMCov/client/Controllers/ClientController.php');
$client = new ClientController();
if(isset($_POST['id_patient']) && isset($_POST['updown']) && isset($_POST['attribute'])){
	$id_patient = $_POST['id_patient'];
	$updown = intval($_POST['updown']);
	$attr = $_POST['attribute'];
	$client->updateAttributeOne($id_patient, $attr, $updown);
}
