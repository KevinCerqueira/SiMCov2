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
// define('MYPATH', '../');
session_start();
// if (isset($_SESSION['auth'])) {
// 	header('Location: ' . MYPATH . 'index.php');
// 	exit;
// }
include_once('PublisherController.php');
$pub = new PublisherController();
var_dump($_SESSION['auth']);
$response = $pub->updateAttribute('61677db9fc5c84664a0bbc6d', 'saturacao', 95);
die();

