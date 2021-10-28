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
import time
import random
from random import randint
import os
import threading
from controldb import ControlDB
from bson.objectid import ObjectId

# Classe responsável por alterar aleatóriamente os valores dos sensores
class Simulator:
	signals = [-1, 1]
	thread = None
	
	def __init__(self):
		self.thread = threading.Thread(target=self.work)
		
	def start(self):
		self.thread.start()
		
	def work(self):
		print('starting...')
		db = ControlDB()
		while True:
			# Atualiza sempre a cada (de 4 a 6 segundos)
			time.sleep(randint(4,6))
			for patient in db.getAllPatients():
				print('LOG :: DEBUG updating patient id: ', str(patient['_id']))
				saturacao = self.change(int(patient['saturacao']), 50, 99)
				pressao = self.change(int(patient['pressao']), 110, 140)
				batimento = self.change(int(patient['batimento']), 50, 130)
				temperatura = self.changeTemperatura(float(patient['temperatura']))
				result = db.updatePatient(patient['_id'], {'saturacao': saturacao, 'pressao': pressao, 'batimento': batimento, 'temperatura': temperatura})
				print('LOG :: DEBUG result: ', result)
				
	# Muda os valores
	def change(self, value, v_start, v_end):
		if(value == 0):
			value = randint(v_start, v_end)
		return value + (randint(0, 1) * self.signals[randint(0, 1)]) 
	
	# Muda o valor da temperatura
	def changeTemperatura(self, value):
		if(value == 0.0):
			value = round(random.uniform(34,38), 1)
		return value + (round(random.uniform(-1,1), 1))
		
if __name__ == '__main__':
	sim = Simulator()
	sim.start()
	
	