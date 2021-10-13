"""
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

class Server:

	HOST = 'localhost'
	PORT = 50000
	
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
		self.client_mqtt = mqtt.Client()
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
		
		content_parts = request.split(' ')
		method = content_parts[0].replace(' ', '')
		path = content_parts[1].replace(' ', '')

		request = request.replace('\r', '')

		# Buscando o campo 'Authorization' na requisicao
		for itr in str(request).split('\n'):
			if('Authorization:' in itr):
				itr = itr.replace(' ', '')
				token = str(re.sub('Basic|Bearer|Authorization:', '', itr))
				# auth_decode = base64.b64decode(auth).decode('utf-8')

		# Buscando por dados enviados na requisicao
		for index in request:
			if(index == '{'):
				data = json.loads(request[request.find('{') :])
				
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
		if(method == ''):
			self.sendToClientError('Requisicao invalida')
		
		# Verificando autenticação
		if(((method in ['PUT', 'PATCH', 'DELETE', 'GET'] or path == '/register/patient') and (not path == '/')) and (not self.middleware(token))):
			return
		
		# Requisições do tipo POST: para a criação de novos dados.
		if(method == 'POST'):
			if(path == '/register/patient'):
				self.registerPatient(token, data)
			elif(path == '/register/doctor'):
				self.registerDoctor(data)
			elif(path == '/login'):
				self.login(data)
			else:
				self.routeNotFound()
				
		# Requisições do tipo GET: retornar dados.
		elif(method == 'GET'):
			if(path == '/'):
				self.sendToClientOk('Bem vindo ao sistema!')
			elif(path == '/get/patients'):
				self.getPatients(token)
			elif('/get/patient/' in path):
				patient_id = path.replace('/get/patient/', '')
				self.getPatient(token, patient_id)
			elif('/get/list/priority'):
				self.getListPriority(token)
			elif(path == '/close-connection'):
				self.closeConnection()
			elif(path == '/close-socket'):
				self.closeSocket()
			else:
				self.routeNotFound()
				
		# Requisições do tipo PATCH: para atualizações parciais de dados.
		elif(method == 'PATCH'):
			if('/update/' in path):
				attribute = path.replace('/update/', '') # /saturacao/batimento/pressao/temperatura
				if(attribute in ['saturacao', 'batimento', 'pressao', 'temperatura']):
					self.updateAttribute(token, data, attribute)
				else:
					self.routeNotFound()
			else:
				self.routeNotFound()
				
		# Requisições do tipo PUT: para atualizações completas.
		elif(method == 'PUT'):
			if(path == '/update/patient'):
				self.updatePatient(token, data)
			else:
				self.routeNotFound()
				
		# Requisições do tipo DELETE: para deleções.
		elif(method == 'DELETE'):
			if(path == '/delete/patient'):
				self.deletePatient(token, data)
			else:
				self.routeNotFound()
		else:
			self.routeNotFound()
		
		return
		# self.server_socket.close()
		# sys.exit()
	
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
		return self.client_mqtt.publish(self.topic, bytes(response.encode('utf-8')))
	
	# Envia dados para o cliente em caso de erro
	def sendToClientError(self, msg):
		response = json.dumps({'success': False, 'error': msg})
		print(response)
		return self.client_mqtt.publish(self.topic, bytes(response.encode('utf-8')))
	
	# Autoriza ou não a autenticação do usuário
	def middleware(self, token):
		if(token == None):
			response = json.dumps({'success': False, 'error': 'Usuario nao autenticado.'})
			self.client_mqtt.publish(self.topic, bytes(response.encode('utf-8')))
			return False
		if(not self.controldb.checkToken(token)):
			response = json.dumps({'success': False, 'error': 'Autenticacao invalida.'})
			self.client_mqtt.publish(self.topic, bytes(response.encode('utf-8')))
			return False
		return True
	
	# Caso a rota informada não esteja dentre as disponiveis
	def routeNotFound(self):
		return self.sendToClientError('Rota nao encontrada')
	
	# Registra um novo medico.
	def registerDoctor(self, data):
		auth = "{}:{}".format(data['username'], data['password'])
		token = base64.b64encode(auth.encode('utf-8')).decode('utf-8')
		success = self.controldb.createDoctor(data['username'], token)
		response = {'token': token}
		
		if(not success):
			return self.sendToClientError('Este nome ja esta em uso! Por favor, escolha outro.')
			
		return self.sendToClientOk(response)
	
	# Loga o medico retornando o token de acesso
	def login(self, data):
		auth = "{}:{}".format(data['username'], data['password'])
		token = self.controldb.getTokenByLogin(data['username'], base64.b64encode(auth.encode('utf-8')).decode('utf-8'))
		response = {'token': token}
		
		if(token == None):
			return self.sendToClientError('Credenciais invalidas!')
			
		return self.sendToClientOk(response)
	
	# Registra um novo paciente
	def registerPatient(self, token, data):
		if(not ('nome' in data and 'sexo' in data and 'idade' in data)):
			return self.sendToClientError("Parametros 'nome', 'idade' e 'sexo' são necessários para criar um paciente")
		doctor = self.controldb.getDoctorByToken(token)
		return self.sendToClientOk({'id': self.controldb.createPatient(doctor['_id'], data)})
	
	# Retorna todos os pacientes
	def getPatients(self, token):
		doctor = self.controldb.getDoctorByToken(token)
		if(doctor == None):
			return self.sendToClientError("Doutor nao existe na base de dados")
		
		return self.sendToClientOk({'patients': self.controldb.getPatients(doctor['_id'])})
	
	# Retorna um paciente em específico
	def getPatient(self, token, data):
		return self.sendToClientOk(self.controldb.getPatient(data))
	
	# Atualiza determinado atributo de um paciente
	def updateAttribute(self, token, data, attr):
		if(not ('id' in data and 'value' in data)):
			return self.sendToClientError("Parametros 'id' e 'value' são necessários para atualizar o paciente")
		doctor = self.controldb.getDoctorByToken(token)
		patient_id = data['id']
		values = {attr: data['value']}
		success = self.controldb.updatePatient(patient_id, values)
		if(success == False):
			return self.sendToClientError('Nao foi possivel atualizar medicao.')
		
		patients = self.controldb.getPatients(doctor['_id'])
		controllevels = ControlLevels(patients)
		list_priority = controllevels.process()
		
		return self.sendToClientOk(list_priority)
	
	# Atualiza todos os atributos de um paciente
	def updatePatient(self, token, data):
		doctor = self.controldb.getDoctorByToken(token)
		patient_id = data['id']
		success = self.controldb.updatePatient(patient_id, data)
		if(success == False):
			return self.sendToClientError('Nao foi possivel atualizar medicoes.')
		
		patients = self.controldb.getPatients(doctor['_id'])
		controllevels = ControlLevels(patients)
		list_priority = controllevels.process()
		
		return self.sendToClientOk(list_priority)
	
	# Deleta um paciente
	def deletePatient(self, token, data):
		if(not ('id' in data)):
			return self.sendToClientError("Parametros 'id' necessários para deletar o paciente")
		doctor = self.controldb.getDoctorByToken(token)
		patient_id = data['id']
		success = self.controldb.deletePatient(patient_id)
		if(success == False):
			return self.sendToClientError('Nao foi possivel deletar o paciente.')
		return self.sendToClientOk(success)
	
	def getListPriority(self, token):
		doctor = self.controldb.getDoctorByToken(token)
		patients = self.controldb.getPatientsByDoctor(doctor['_id'])
		controllevels = ControlLevels(patients)
		list_priority = controllevels.process()
		self.sendToClientOk(list_priority)

if __name__ == '__main__':
	server = Server()
	server.work()
			