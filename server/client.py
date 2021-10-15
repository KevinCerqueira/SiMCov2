import paho.mqtt.client as mqtt
import json

client = mqtt.Client('SiMCov_publisher')
client.connect('mqtt.eclipseprojects.io', 8883)
# client.connect('localhost', 60000)
send = {'nome': 'novopat2'}
HOST = 'localhost'
PORT = 60000
# send = {'id': '1'}
# send = {}
# client.sendall(str.encode('Kevin'))
metodo = 'GET'
rota = '/get/patients'
# rota = '/update/patient'
# rota = '/delete/patient'
host = HOST+':'+str(PORT)
auth = 'Authorization: Bearer dXN1YXJpbzoxMjM0'
request = '{} {} HTTP/1.1\r\nHost: {}\r\nUser-Agent: ClientController\r\nContent-Type: application/json\r\n{}\r\nAccept: */*\r\nContent-Length: 21\r\n\r\n{}'.format(metodo, rota, host, auth, json.dumps(send))
# while True:
client.publish("SIMCOV/channel1", request)

# def on_connect(client, userdata, flags, rc):
#     print("Connected to a broker!")
#     client.subscribe("LINTANGtopic/test")

# def on_message(client, userdata, message):
#     print(message.payload.decode())

# while True:
#     client.on_connect = on_connect
#     client.on_message = on_message
#     client.loop_forever()