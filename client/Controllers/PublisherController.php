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
require_once('vendor/autoload.php');

require_once('vendor/php-mqtt/client/src/MqttClient.php');
require_once('vendor/php-mqtt/client/src/ConnectionSettings.php');

/** Classe responsável por enviar e receber informações do servidor. */
class PublisherController
{
    public $host = 'mqtt.eclipseprojects.io';
    public $port = 1883;
    private $token;
    private $clean_session = false;
    private $connectionSettings;
    private $topic = 'SIMCOV/channel1';
    private $mqtt;
    private $clientId = 'SiMCov_publisher_';

    /** Construtor */
    public function __construct()
    {
        // Caso o usuario esteja autentica no sistema.
        if (isset($_SESSION['auth']))
            $this->token = $_SESSION['auth'];
        $this->clientId .= strval(rand(1, 1000) * rand(1, 1000)) . strval(rand(1, 1000));
        $connectionSettings  = new \PhpMqtt\Client\ConnectionSettings();
        $connectionSettings->setUsername(null)
            ->setPassword(null)
            ->setKeepAliveInterval(100000)
            ->setLastWillTopic($this->topic)
            ->setLastWillMessage('client disconnect')
            ->setLastWillQualityOfService(1);
        $this->mqtt = new \PhpMqtt\Client\MqttClient($this->host, $this->port, $this->clientId);
    }

    /** Coneceta no servidor */
    private function connect()
    {
        $this->mqtt->connect($this->connectionSettings, $this->clean_session);
    }

    /** Fecha a conexão com o servidor */
    private function close()
    {
        return $this->mqtt->disconnect();
    }

    /** Envia determinada informação e recebe do servidor */
    protected function send(string $method, string $url, array $data = null)
    {
        $request = $method . " " . $url . " HTTP/1.1\r\nHost: " . $this->host . $this->port;

        $request .= "\r\nUser-Agent: ClientController\r\n";
        $request .= "Content-Type: application/json\r\n";

        if (!empty($this->token))
            $request .= "Authorization: Bearer " . $this->token;

        $request .= "\r\nAccept: */*\r\nContent-Length: " . strlen($request) . "\r\n\r\n";
        if (!empty($data))
            $request .= strval(json_encode($data));

        // Envia informação para o servidor
        var_dump($request);
        $this->mqtt->publish($this->topic, json_encode($request), 0, true);
        $this->close();
        return;
    }

    /** Atualiza todos os sensores do paciente */
    public function updateAttribute($id_patient, $att, $value)
    {
        $this->connect();
        return $this->send('PATCH', "/update" . '/' . $att, ['id' => $id_patient, 'value' => $value]);
    }

    /** Atualiza um unico sensor do paciente */
    // public function updateAttributeOne(int $id_patient, string $att, int $updown)
    // {
    //     $this->connect($udp = false);

    //     $response = $this->send(false, 'GET', "/get/patient" . '/' . $id_patient);
    //     if ($response->success) {
    //         $patient = $response->data;
    //         if ($att != 'temperatura')
    //             $new_value = intval($patient->{$att}) + (1 * $updown);
    //         else
    //             $new_value = doubleval($patient->temperatura) + (0.1 * doubleval($updown));
    //         $this->connect($udp = true);
    //         return $this->send(true, 'PATCH', "/update" . '/' . $att, ['id' => $id_patient, 'value' => $new_value]);
    //     }
    // }
}
