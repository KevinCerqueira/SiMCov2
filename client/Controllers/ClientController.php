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
include_once('PublisherController.php');
/** Classe responsável por enviar e receber informações do servidor. */
class ClientController
{
    public $host = 'localhost';
    public $port = 50000;
    public $count_bytes = 8192;
    public $socket;
    private $token;
    public $publisher;

    /** Construtor */
    public function __construct()
    {
        // Caso o usuario esteja autentica no sistema.
        if (isset($_SESSION['auth']))
            $this->token = $_SESSION['auth'];

        $this->publisher = new \PublisherController();
    }

    /** Coneceta no servidor */
    private function connect()
    {

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        socket_connect($this->socket, $this->host, $this->port);
    }

    /** Fecha a conexão com o servidor */
    private function close()
    {
        return socket_close($this->socket);
    }

    /** Envia determinada informação e recebe do servidor */
    public function send(string $method, string $url, array $data = null)
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
        $response = socket_write($this->socket, $request, strlen($request));
        
        socket_recv($this->socket, $response, $this->count_bytes, MSG_WAITALL);
        $this->close();
        return json_decode($response);
    }

    /** Para registrar um novo médico */
    public function register($username, $password)
    {
        $this->connect();
        $response = $this->send('POST', '/register/doctor', ['username' => $username, 'password' => $password]);
        if ($response->success) {
            $this->token = $response->data->token;
            // Deixando o usuário autenticado no sistema
            $_SESSION["auth"] = $this->token;
            $_SESSION["username"] = $username;
            return $response;
        }
        return $response;
    }

    /** Para fazer login do médico */
    public function login($username, $password)
    {
        $this->connect();
        $response = $this->send('POST', '/login', ['username' => $username, 'password' => $password]);
        if ($response->success) {
            $this->token = $response->data->token;
            $_SESSION["username"] = $username;
            $_SESSION["auth"] = $this->token;
            return $response;
        }
        return $response;
    }

    /** Para fazer logout do médico */
    public function logout()
    {
        unset($_SESSION["auth"]);
        unset($_SESSION["username"]);
        $this->token = null;
        return true;
    }

    /** Atualiza todos os sensores do paciente */
    public function updateAttribute($id_patient, $att, $value)
    {
        $this->publisher->updateAttribute($id_patient, $att, $value);
    }

    /** Atualiza um unico sensor do paciente */
    public function updateAttributeOne($id_patient, $att, $updown)
    {
        $this->connect();

        $response = $this->send('GET', "/get/patient" . '/' . $id_patient);

        if ($response->success) {
            $patient = $response->data;
            if ($att != 'temperatura')
                $new_value = intval($patient->{$att}) + (1 * $updown);
            else
                $new_value = doubleval($patient->temperatura) + (0.1 * doubleval($updown));
            
            $this->publisher->updateAttribute($id_patient, $att, $new_value);
        }
    }

    /** Retorna todos os pacientes */
    public function getAll()
    {
        $this->connect();
        return $this->send('GET', "/get/patients");
    }

    /** Retorna a lista de prioridade */
    public function getListPriority()
    {
        $this->connect();
        return $this->send('GET', "/get/list/priority");
    }

    /** Registra um novo paciente */
    public function registerPatient($nome, $idade, $sexo)
    {
        $this->connect();
        return $this->send('POST', '/register/patient', ['nome' => $nome, 'idade' => $idade, 'sexo' => $sexo]);
    }

    /** Deleta um paciente */
    public function deletePatient($id_patient)
    {
        $this->connect();
        return $this->send('DELETE', '/delete/patient', ['id' => $id_patient]);
    }
}
