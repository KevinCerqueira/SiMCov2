import logging
import asyncio
from hbmqtt.broker import Broker
from hbmqtt.client import MQTTClient, ClientException
from hbmqtt.mqtt.constants import QOS_1
from controldb import ControlDB

class MyBroker:

	HOST = 'localhost'
	PORT = 50000
	
	logger = None
	topic = ''
	broker = None
	
	db = None
	
	def __init__(self):
		self.logger = logging.getLogger(__name__)
		self.topic = 'SIMCOV/channel1'
		self.db = ControlDB()
		config = {
			'listeners': {
				'default': {
					'type': 'tcp',
					'bind': '{}:{}'.format(self.HOST, self.PORT)
				}
			},
			'sys_interval': 10,
			'topic-check': {
				'enabled': False
			},
			'keep_alive': 100000
		}
		self.broker = Broker(config)
		self.start()
	
	def start(self):
		formatter = "[%(asctime)s] :: %(levelname)s :: %(name)s :: %(message)s"
		logging.basicConfig(level=logging.INFO, format=formatter)
		asyncio.get_event_loop().run_until_complete(self.startBroker())
		asyncio.get_event_loop().run_until_complete(self.brokerGetMessage())
		asyncio.get_event_loop().run_forever()

	@asyncio.coroutine
	def startBroker(self):
		yield from self.broker.start()

	@asyncio.coroutine
	def brokerGetMessage(self):
		client = MQTTClient()
		yield from client.connect('mqtt://{}:{}/'.format(self.HOST, self.PORT))
		yield from client.subscribe([
			(self.topic, QOS_1)
		])
		self.logger.info('Subscribed!')
		try:
			for i in range(1,100):
				message = yield from client.deliver_message()
				packet = message.publish_packet
				print(packet.payload.data.decode('utf-8'))
				print(self.db.insertMessage(packet.payload.data.decode('utf-8')))
		except ClientException as ce:
			self.logger.error("Client exception : {}".format(ce))

if __name__ == '__main__':
    b = MyBroker()
    