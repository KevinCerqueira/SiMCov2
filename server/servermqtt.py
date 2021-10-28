"""
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
"""
import paho.mqtt.client as mqtt
import json
import sys
import os
import threading
import base64
import re
from collections import deque
from controldb import ControlDB
from controllevels import ControlLevels

class ServerMQTT:

	HOST = 'mqtt.eclipseprojects.io'
	PORT = 1883
	
	# Servidor
	server_socket = None
	
	client_mqtt = None
	
	topic = ''
	
	# Controlador da base de dados
	database = None
	
	queue_request = None
	thread_request = None
	
	controllevels = None
	
	close = False
	
	def __init__(self):
		# Iniciando o Server
		self.client_mqtt = mqtt.Client('SiMCov_subscriber')
		# self.client_mqtt.connect(self.HOST)
		self.client_mqtt.connect(self.HOST, self.PORT)
		self.topic = 'SIMCOV/channel1'
		self.controldb = ControlDB()
		self.queue_request = deque()
		self.thread_request = threading.Thread(target=self.queueRequest)
		self.thread_request.start()
		print('SERVER ON\n')
		self.work()
	
	def on_connect(self, client, userdata, flags, rc):
		self.client_mqtt.subscribe(self.topic)
		print("Connected to a broker!")

	def on_message(self, client, userdata, message):
		self.receptor(message.payload.decode())
	
	# Função principal, onde o servidor irá receber as conexões
	def work(self):
		while not self.close:
			self.client_mqtt.on_connect = self.on_connect
			self.client_mqtt.on_message = self.on_message
			self.client_mqtt.loop_forever()
		
		if(self.close and len(self.queue_request) == 0):
			print('SERVER OFF')
			return sys.exit()
	
	# Trata os dados recebidos
	def receptor(self, request):
		method = ''
		path = ''
		data = None
		token = ''
		
		# Verificando se é realmente o cliente
		if(not 'ClientController' in request):
			print('Conexão externa.')
			return
		
		content_parts = request.replace('\\', '').split(' ')
		
		method = (content_parts[0].replace('"', '')).replace(' ', '')
		path = content_parts[1].replace(' ', '')

		request = request.replace('\r', '')

		# Buscando o campo 'Authorization' na requisicao
		for itr in str(request).split('\\r\\n'):
			if('Authorization:' in itr):
				itr = itr.replace(' ', '')
				token = str(re.sub('Basic|Bearer|Authorization:', '', itr))
				# auth_decode = base64.b64decode(auth).decode('utf-8')
		# Buscando por dados enviados na requisicao
		for index in request:
			if(index == '{'):
				data = json.loads(request[request.find('{') : request.find('}') + 1].replace("\\", ""))
		
		# Adicionando a requisição a fila de requisições
		self.queue_request.append({'method': method, 'path': path, 'data': data, 'token': token})
	
	# Consome a fila de requisições
	def queueRequest(self):
		while not (self.close and len(self.queue_request) == 0):
			if(len(self.queue_request) > 0):
				print('conn: ' + str(len(self.queue_request)))
				request = self.queue_request.popleft()
				self.routing(request['method'], request['path'], request['data'], request['token'])
	
	# Função responsável pelo roteamente, identifica os metodos e as rotas requisitadas
	def routing(self, method, path, data, token):
		print('...')
		print({'method': method, 'path': path, 'data': data, 'token': token})
		print('...')
		
		if(method == ''):
			self.sendToClientError('Requisicao invalida')
		
		# Verificando autenticação
		if(not self.middleware(token)):
			return
				
		# Requisições do tipo PATCH: para atualizações parciais de dados.
		if(method == 'PATCH'):
			if('/update/' in path):
				attribute = path.replace('/update/', '') # /saturacao/batimento/pressao/temperatura
				if(attribute in ['saturacao', 'batimento', 'pressao', 'temperatura']):
					self.updateAttribute(token, data, attribute)
				else:
					self.routeNotFound()
			else:
				self.routeNotFound()
		
		else:
			self.routeNotFound()
		
		return
	
	# Fecha a conexão do cliente
	def closeConnection(self, client):
		return
	
	# Desliga o servidor
	def closeSocket(self, client):
		print('SERVIDOR FECHARA AO TERMINAR AS CONEXOES EXISTENTES')
		self.close = True
	
	# Envia dados para o cliente
	# def sendToClient(self, client, obj):
	# 	return client.sendall(bytes(obj.encode('utf-8')))
	
	# Envia dados para o cliente em caso de sucesso
	def sendToClientOk(self, obj):
		response = json.dumps({'success': True, 'data': obj})
		print(response)
		return
		# return self.client_mqtt.publish(self.topic, bytes(response.encode('utf-8')))
	
	# Envia dados para o cliente em caso de erro
	def sendToClientError(self, msg):
		response = json.dumps({'success': False, 'error': msg})
		print(response)
		return
		# return self.client_mqtt.publish(self.topic, bytes(response.encode('utf-8')))
	
	# Autoriza ou não a autenticação do usuário
	def middleware(self, token):
		if(token == None):
			self.sendToClientError('Usuario nao autenticado.')
			return False
		if(not self.controldb.checkToken(token)):
			self.sendToClientError('Autenticacao invalida.')
			return False
		return True
	
	# Caso a rota informada não esteja dentre as disponiveis
	def routeNotFound(self):
		return self.sendToClientError('Rota nao encontrada')
	
	# Atualiza determinado atributo de um paciente
	def updateAttribute(self, token, data, attr):
		if(not ('id' in data and 'value' in data)):
			return self.sendToClientError("Parametros 'id' e 'value' são necessários para atualizar o paciente")
		
		patient_id = data['id']
		value = {attr: data['value']}
		success = self.controldb.updatePatient(patient_id, value)
		if(success == False):
			return self.sendToClientError('Nao foi possivel atualizar medicao.')
		
		return self.sendToClientOk(success)
	
	# Atualiza todos os atributos de um paciente
	def updatePatient(self, token, data):
		patient_id = data['id']
		success = self.controldb.updatePatient(patient_id, data)
		if(success == False):
			return self.sendToClientError('Nao foi possivel atualizar medicoes.')
		
		return self.sendToClientOk(success)

if __name__ == '__main__':
	server = ServerMQTT()
	server.work()
			