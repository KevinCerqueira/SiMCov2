# SiMCov - Sistema de Monitoramento de COVID-19
O intuito deste problema foi a criação de uma comunicação utilizando MQTT (além de uma API REST utilizando socket puro), onde sensores como oxímetro, termômetro, esfigmomanômetro e frequencímetro (hardwares) podessem enviar dados via MQTT para o servidor, e o servidor enviasse via socket TCP os dados para o cliente, que nesse caso seria um médico (a), e o mesmo visse os dados de forma ordenada e prioritária as informações e fosse alertado em casos de emergências.
## Tecnologias utilizadas:
- Python 3.8 (Necessário ser essa versão)
- PHP 7.4.16
- Javascript
- HTML5 & CSS3
### Biblotecas utilizadas
- Python:
    - paho.mqtt.client (comunicação MQTT)
    - pymongo (comunicação com banco de dados MongoDB)
    - socket (servidor com socket puro)
        - TCP e UDP
    - threading (threads)
    - base64 (codificação)
    - re (regex)
    - sys (comandos do sistema)
    - os (pastas e rotas do sistema)
- PHP
    - MqttClient (comunicação MQTT)
    - ConnectionSettings (configuração da comunicação MQTT)
    - funções:
        - socket_create (criar cliente com socket puro)
        - socket_connect (conectar com o servidor)
        - socket_write (enviar dados ao servidor)
        - socket_recv (receber dados do servidor)
        - socket_close (fechar conexão com o servidor)
- Javascript
    - JQuery 3.6.0
        - Ajax
    - SweetAlert2
    - Select2
- HTML5 & CSS3
    - Boostrap 5.1
    - FontAwesome
## Como rodar:
1. Antes de tudo é necessário ter instalado o Python (versão 3.8) e o XAMPP (versão 3.3.0) + PHP (v7.4)
    - Python (v3.8.0): https://www.python.org/downloads/
    - XAMPP (v3.3.0) + PHP 7.4: https://www.apachefriends.org/pt_br/download.html
        - **ATENÇÃO**: Baixar o que consta a versão **7.4** do PHP. Não é necessário instalar o php à parte, pois o o mesmo já vem pré configurado no xampp
2. Caso utilize windows, é necessário verificar se o PHP do XAMPP está setado nas variáveis ambiente do seu computador.
    - ![variaveis](https://github.com/kevincerqueira/simcov2/blob/main/telas/variaveis.png?raw=true)
3. Para utilizar o socket do PHP é necessário habilita-lo no php.ini (C:\xampp\php\php.ini):
    - Basta pesquisar dentro do arquivo o nome 'sockets' e apagar o ponto e virgula (;) que fica na frente do mesmo.
        - ![phpini](https://github.com/kevincerqueira/simcov2/blob/main/telas/phpini.png?raw=true)
4. Feito tudo isso, confirme que está tudo funcionando, basta abrir o terminar e digitar 'python --version' para verificar se o Python foi instalado corretamente, e para verificar o PHP basta digitar no mesmo terminal 'php -v'. Feito isso confirme se as versões aparecem devidamente.
5. Após configurado, está na hora de mover o repositório para dentro da pasta C:\xampp\htdocs, como mostrado na imagem:
    - ![htdocs](https://github.com/kevincerqueira/simcov2/blob/main/telas/htdocs.png?raw=true)
6. Agora abra o XAMPP e dê start na opção Apache (o mesmo deve ficar verde):
    - ![xampp](https://github.com/kevincerqueira/simcov2/blob/main/telas/xampp.png?raw=true)
7. É necessário instalar as dependencias do PHP com o 'composer' (instale ele via https://getcomposer.org/), certificado de que esteja instalado, rode o seguinte comando na pasta 'SiMCov/client/Controllers':
```sh
composer install
```
8. Agora instale as importações necessárias para o python:
```sh
pip install pymongo
```

```sh
pip install "pymongo[srv]"
```

```sh
pip install paho-mqtt
```

9. Após isso, certifique-se que você tem a variável de ambiente CLUSTER no arquivo 'SiMCov/server/.env', como no exemplo do .env.example:

   ```
   CLUSTER=mongodb+srv://<username>:<password>@kcluster.meacr.mongodb.net/test
   ```

   - caso não tenha acesso ao banco de dados, será necessário nos solicitar, ou criar um no site do MongoDB.

10. Pronto, agora o front-end da aplicação está rodando, agora é a hora de rodar os servidores. Vá no terminal e execute o arquivo 'servertcp.py' e deixe-o rodando, abra um novo terminal para executar 'servermqtt.py' da mesma 
```sh
python servertcp.py
```

```sh
python servermqtt.py
```

 - E caso queira que os sensores fiquem mudando os valores automaticamente, execute o 'simulator.py'

```sh
python simulator.py
```

11. Pronto! agora é só acessar a tela inicial do sistema, basta acessar o link 'http://localhost/SiMCov2/client' em um navegador

## Telas:
- Entrar: http://localhost/SiMCov2/client/pages/auth/login.php
    - ![login](https://github.com/kevincerqueira/simcov2/blob/main/telas/login.png?raw=true)
- Cadastro: http://localhost/SiMCov2/client/pages/auth/register.php
    - ![cadastro](https://github.com/kevincerqueira/simcov2/blob/main/telas/cadastro.png?raw=true)
- Dashboard: http://localhost/SiMCov2/client/index.php
    - ![dashboard](https://github.com/kevincerqueira/simcov2/blob/main/telas/dashboard.png?raw=true)
- Monitorar Paciente: Selecione o paciente via modal no dashboard
    - Selecione o paciente no Modal:
        - ![monitorar1](https://github.com/kevincerqueira/simcov2/blob/main/telas/monitorar_paciente1.png?raw=true)
    - Monitorando o paciente:
        - ![monitorar2](https://github.com/kevincerqueira/simcov2/blob/main/telas/monitorar_paciente2.png?raw=true)
- Lista de prioridade: http://localhost/SiMCov2/client/pages/list_priority.php
    - Sem pacientes ativos:
        - ![lista1](https://github.com/kevincerqueira/simcov2/blob/main/telas/lista1.png?raw=true)
    - Com pacientes ativos:
        - ![lista2](https://github.com/kevincerqueira/simcov2/blob/main/telas/lista2.png?raw=true)
- Medir paciente: http://localhost/SiMCov2/client/pages/change_patients.php
    - ![simular_sensores](https://github.com/kevincerqueira/simcov2/blob/main/telas/simular_sensores.png?raw=true)
- Cadstrar/Deletar paciente: são modais do dashboard
    - Cadastrar:
        - ![novo_paciente](https://github.com/kevincerqueira/simcov2/blob/main/telas/novo_paciente.png?raw=true)
    - Deletar:
        - ![deletar_paciente1](https://github.com/kevincerqueira/simcov2/blob/main/telas/deletar_paciente1.png?raw=true)
        - ![deletar_paciente2](https://github.com/kevincerqueira/simcov2/blob/main/telas/deletar_paciente2.png?raw=true)
## Construção das pastas
![contrucaodaspastas](https://github.com/kevincerqueira/simcov2/blob/main/telas/contrucaodaspastas.png?raw=true)