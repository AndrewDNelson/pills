import os
import time
import ujson
import machine
import network
import ntptime
from umqtt.simple import MQTTClient

#Enter your wifi SSID and password below.
wifi_ssid = "TWU"
wifi_password = ""

#Enter your AWS IoT endpoint. You can find it in the Settings page of
#your AWS IoT Core console. 
#https://docs.aws.amazon.com/iot/latest/developerguide/iot-connect-devices.html 
aws_endpoint = b'a3g64zddycx1fg-ats.iot.us-west-2.amazonaws.com'

thing_name = "PillThing"
client_id = "PillClient"
private_key = "private.pem.key"
private_cert = "cert.pem.crt"

#Read the files used to authenticate to AWS IoT Core
with open(private_key, 'r') as f:
    key = f.read()
with open(private_cert, 'r') as f:
    cert = f.read()

#These are the topics we will subscribe to. We will publish updates to /update.
#We will subscribe to the /update/delta topic to look for changes in the device shadow.
topic_pub = "$aws/things/" + thing_name + "/shadow/update"
topic_sub = "$aws/things/" + thing_name + "/shadow/update/delta"
ssl_params = {"key":key, "cert":cert, "server_side":False}
lwt_pub = "$aws/things/" + thing_name + "/status"

#Define pins for LED.
#The LED is built into the board, and no external connections are required.
led = machine.Pin(2, machine.Pin.OUT)
info = os.uname()

#Connect to the wireless network
wlan = network.WLAN(network.STA_IF)
wlan.active(True)
if not wlan.isconnected():
    print('Connecting to network...')
    wlan.connect(wifi_ssid, wifi_password)
    while not wlan.isconnected():
        pass

    print('Connection successful')
    print('Network config:', wlan.ifconfig())

# Set system time using NTP
ntptime.settime()

def get_time():
    UTC_OFFSET = -8 * 60 * 60
    time_tuple = time.localtime(time.time() + UTC_OFFSET)
    formatted_time = "{:04d}-{:02d}-{:02d} {:02d}:{:02d}:{:02d}".format(
        time_tuple[0],  # year
        time_tuple[1],  # month
        time_tuple[2],  # day
        time_tuple[3],  # hour
        time_tuple[4],  # minute
        time_tuple[5]   # second
    )
    
    return formatted_time

# dispenser stuff
schedule = []
doses = [{'time':get_time(), 'schedule_id': 11}]

def mqtt_connect(client=client_id, endpoint=aws_endpoint, sslp=ssl_params):
    mqtt = MQTTClient(client_id=client, server=endpoint, port=8883, keepalive=1200, ssl=True, ssl_params=sslp)
    mqtt.set_last_will(lwt_pub, ujson.dumps({"status": "disconnected"}))
    print("Connecting to AWS IoT...")
    mqtt.connect()
    print("Done")
    return mqtt

def mqtt_publish(client, topic=topic_pub, message=''):
    print("Publishing message...")
    client.publish(topic, message)
    print(message)

def mqtt_subscribe(topic, msg):
    global schedule
    print("Message received...")
    message = ujson.loads(msg)
    print(topic, message)
    
    if 'schedule' in message['state']:
        schedule = message['state']['schedule']
        print('Updated schedule')
    if 'state' in message and 'led' in message['state']:
        led_state(message['state'])
        print('updated led')
        
    print("Done")


def led_state(message):
    led.value(message['led']['onboard'])
        

    

#We use our helper function to connect to AWS IoT Core.
#The callback function mqtt_subscribe is what will be called if we 
#get a new message on topic_sub.
try:
    mqtt = mqtt_connect()
    mqtt.set_callback(mqtt_subscribe)
    mqtt.subscribe(topic_sub)
        
    connected_msg = ujson.dumps({"status": "connected"})
    try:
        mqtt_publish(client=mqtt, topic=lwt_pub, message=connected_msg)
    except:
        print("Unable to publish message.")
        
except:
    print("Unable to connect to MQTT.")


while True:
    print(get_time())
    
    #Check for messages.
    try:
        mqtt.check_msg()
    except:
        print("Unable to check for messages.")

    # Update state to message
    mesg = ujson.dumps({
        "state":{
            "reported": {
                "device": {
                    "client": client_id,
                    "uptime": time.ticks_ms(),
                    "hardware": info[0],
                    "firmware": info[2]
                },
                "led": {
                    "onboard": led.value()
                },
                "schedule": schedule,
                "doses": doses
            }
        }
    })
    
    #Using the message above, the device shadow is updated.
    try:
        mqtt_publish(client=mqtt, message=mesg)
    except:
        print("Unable to publish message.")
        

    #Wait for 10 seconds before checking for messages and publishing a new update.
    print("Sleep for 10 seconds")
    time.sleep(10)