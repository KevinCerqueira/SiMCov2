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

import os
import sys
import json
from pymongo import MongoClient
from bson.objectid import ObjectId

# Classe para controlar a base de dados.
class ControlDB:
	
	cluster = None
	
	database = None
	doctor_db = None
	patient_db = None
	patient_db = None
	
	# Construtor
	def __init__(self):
		self.cluster = MongoClient(self.env('CLUSTER'))
		
		self.database = self.cluster.simcov
		self.doctor_db = self.database.doctor 
		self.patient_db = self.database.patient
		self.mqtt = self.database.mqtt
			
	def env(self, var):
		with open(os.path.dirname(os.path.realpath(__file__)) + '\\.env', 'r', encoding='utf-8') as file_env:
			line = file_env.readline()
			while(line):
				content = line.split('=')
				if(content[0] == var):
					return content[1]
				line = file_env.readline()
	
	def insertMessage(self, msg):
		try:
			msg = {'message': msg}
			self.mqtt.insert_one(msg)
			return True
		except:
			return False
	
	def createDoctor(self, username, auth):
		new_doctor = {'username': username, 'auth': auth}
		try:
			self.doctor_db.insert_one(new_doctor)
			return True
		except:
			return False
	
	def findDoctor(self, att, value):
		if(att == '_id'):
			value = ObjectId(value)
		try:
			doctor = self.doctor_db.find_one({att: value})
			return doctor
		except:
			return None
	
	def createPatient(self, doctor_id, data):
		if(self.findDoctor('_id', doctor_id) == None):
			return 'Doutor nao existe'
		try:
			new_patient = {'doctor': ObjectId(doctor_id), 'nome': data['nome'], 'idade': data['idade'], 'sexo': data['sexo'], 'medicao': False, 'saturacao': 0, 'pressao': 0, 'batimento': 0, 'temperatura': 0.0}
			response = self.patient_db.insert_one(new_patient)
			return True
		except:
			return 'Nao foi possivel inserir o paciente'
	
	def getPatient(self, id):
		id = ObjectId(id)
		try:
			patient = self.patient_db.find_one({'_id': id})
			return patient
		except:
			return None
	
	def getPatients(self, doctor_id):
		doctor_id = ObjectId(doctor_id)
		try:
			patients = self.patient_db.find({'doctor': doctor_id})
			return json.load(patients)
		except:
			return None
	
	def updatePatient(self, id, values):
		try:
			values = {'$set': values}
			id = ObjectId(id)
			self.patient_db.update_one({'_id': id}, {'$set': {'medicao': True}})
			self.patient_db.update_one({'_id': id}, values)
			return True
		except:
			return False
			
	def deletePatient(self, id):
		try:
			id = ObjectId(id)
			self.patient_db.delete_one({'_id':id})
			return True
		except:
			return False
	
	def getTokenByLogin(self, username, auth):
		try:
			doctor = self.doctor_db.find_one({'username': username, 'auth': auth})
			return doctor['auth']
		except:
			return None
	
	def checkToken(self, auth):
		try:
			doctor = self.doctor_db.find_one({'auth': auth})
			return True
		except:
			return False
	
	def getDoctorByToken(self, auth):
		try:
			doctor = self.doctor_db.find_one({'auth': auth})
			return doctor
		except:
			return None
	