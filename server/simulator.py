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
import time
import random
from random import randint
import os
import json
import threading

# Classe responsável por alterar aleatóriamente os valores dos sensores
class Simulator:
	path = ''
	signals = [-1, 1]
	folders = ''
	thread = None
	
	def __init__(self):
		self.path = os.path.dirname(os.path.realpath(__file__)) + '\\database\\patients'
		self.folders = os.listdir(self.path)
		self.thread = threading.Thread(target=self.work)
		
	def start(self):
		self.thread.start()
		
	def work(self):
		while True:
			# Atualiza sempre a cada (de 4 a 6 segundos)
			time.sleep(randint(4,6))
			for folder in self.folders:
				with open(self.path + '\\' + folder + '\\patients.json', 'r', encoding='utf-8') as db_read:
					data_read = json.load(db_read)
					for patient in data_read:
						data_read[str(patient)]['medicao'] = True
						data_read[str(patient)]['saturacao'] = self.change(int(data_read[str(patient)]['saturacao']), 50, 99)
						data_read[str(patient)]['pressao'] = self.change(int(data_read[str(patient)]['pressao']), 110, 140)
						data_read[str(patient)]['batimento'] = self.change(int(data_read[str(patient)]['batimento']), 50, 130)
						data_read[str(patient)]['temperatura'] = self.changeTemperatura(float(data_read[str(patient)]['temperatura']))
					
					with open(self.path + '\\' + folder + '\\patients.json', 'w', encoding='utf-8') as db_write:
						json.dump(data_read, db_write, ensure_ascii=False, indent=4)
	
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
	
	