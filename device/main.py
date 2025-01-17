import os
import time
import ujson
import machine
import network
import ntptime
from I2C_LCD import I2cLcd
from stepmotor import mystepmotor
from umqtt.simple import MQTTClient

# Enter your wifi SSID and password below.
wifi_ssid = "TWU"
wifi_password = ""

# Enter your AWS IoT endpoint. You can find it in the Settings page of
# your AWS IoT Core console. 
# https://docs.aws.amazon.com/iot/latest/developerguide/iot-connect-devices.html 
aws_endpoint = b'a3g64zddycx1fg-ats.iot.us-west-2.amazonaws.com'

thing_name = "PillThing"
client_id = "PillClient"
private_key = "private.pem.key"
private_cert = "cert.pem.crt"

# Read the files used to authenticate to AWS IoT Core
with open(private_key, 'r') as f:
    key = f.read()
with open(private_cert, 'r') as f:
    cert = f.read()

# These are the topics we will subscribe to. We will publish updates to /update.
# We will subscribe to the /update/delta topic to look for changes in the device shadow.
topic_pub = "$aws/things/" + thing_name + "/shadow/update"
topic_sub = "$aws/things/" + thing_name + "/shadow/update/delta"
ssl_params = {"key":key, "cert":cert, "server_side":False}
lwt_pub = "$aws/things/" + thing_name + "/status"

# ----------------- HARDWARE STUFF -----------------
led = machine.Pin(2, machine.Pin.OUT)
info = os.uname()

activeBuzzer=machine.Pin(4, machine.Pin.OUT)
activeBuzzer.value(0)

button = machine.Pin(14, machine.Pin.IN, machine.Pin.PULL_UP)

i2c = machine.I2C(scl=machine.Pin(19), sda=machine.Pin(18), freq=400000)
devices = i2c.scan()
if len(devices) == 0:
    print("No i2c device !")
else:
    for device in devices:
        lcd = I2cLcd(i2c, device, 2, 16)

def lcd_clear():
    if 'lcd' in globals():
        global lcd
        lcd.clear()
        lcd.backlight_off()
        lcd.display_off()

def lcd_message(message):
    if 'lcd' in globals():
        global lcd
        lcd.clear()
        lcd.backlight_on()
        lcd.display_on()
        lcd.move_to(0, 0)
        lcd.putstr(message)


stepper = mystepmotor(32, 33, 25, 26)

# ----------------- WIFI CONNECTION -----------------
wlan = network.WLAN(network.STA_IF)
wlan.active(True)
if not wlan.isconnected():
    print('Connecting to network...')
    lcd_message("Connecting to   network...")

    wlan.connect(wifi_ssid, wifi_password)
    while not wlan.isconnected():
        pass

    print('Connection successful')
    print('Network config:', wlan.ifconfig())
    lcd_message("Connection successful")

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

def get_day_of_week(year, month, day):
    if month < 3:
        month += 12
        year -= 1
    k = year % 100
    j = year // 100
    f = day + ((13 * (month + 1)) // 5) + k + (k // 4) + (j // 4) - 2 * j
    day_of_week = f % 7
    days = ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]
    return days[day_of_week]

class LimitedQueue:
    def __init__(self, limit=5):
        self.queue = []
        self.limit = limit

    def enqueue(self, item):
        self.queue.append(item)
        if len(self.queue) > self.limit:
            self.dequeue()

    def dequeue(self):
        if self.queue:
            return self.queue.pop(0)

    def get_queue(self):
        return self.queue

# ----------------- MQTT CONNECTION -----------------
doses = LimitedQueue()
schedule = []

def mqtt_connect(client=client_id, endpoint=aws_endpoint, sslp=ssl_params):
    mqtt = MQTTClient(client_id=client, server=endpoint, port=8883, keepalive=300, ssl=True, ssl_params=sslp)
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


lcd_message("Connecting to   AWS IoT Core")
# We use our helper function to connect to AWS IoT Core.
# The callback function mqtt_subscribe is what will be called if we 
# get a new message on topic_sub.
try:
    mqtt = mqtt_connect()
    mqtt.set_callback(mqtt_subscribe)
    mqtt.subscribe(topic_sub)
        
    connected_msg = ujson.dumps({"status": "connected"})
    try:
        mqtt_publish(client=mqtt, topic=lwt_pub, message=connected_msg)
    except:
        print("Unable to publish message.")
    
    lcd_message("Connected to AWSIoT Core")
        
except:
    print("Unable to connect to MQTT.")
    lcd_message("Unable to connect to MQTT.")

time.sleep(2)
lcd_clear()

while True:

    # ----------------- DISPENSER STUFF -----------------
    current_time_str = get_time()
    year, month, day = map(int, current_time_str[:10].split('-'))
    current_day = get_day_of_week(year, month, day)
    current_hour_minute = current_time_str[11:16]  # Extract HH:MM

    # Compare with schedule
    for x in schedule:
        schedule_time = x['time'][:5]  # Extract HH:MM from the schedule time
        if x['day'] == current_day and schedule_time == current_hour_minute:
            print(f"It's time for {x['pillCount']} pill(s) as per schedule ID {x['id']}")

            # dispense amount of pills
            for i in range(0, x['pillCount']):
                stepper.moveAround(1, 1, 2000)

            lcd_message(f"Time to take    your {x['pillCount']} pills!")
            for i in range(0,4):
                activeBuzzer.value(1)
                time.sleep_ms(50)
                activeBuzzer.value(0)
                time.sleep_ms(50)
                activeBuzzer.value(1)
                time.sleep_ms(50)
                activeBuzzer.value(0)
                time.sleep_ms(200)

            while True:
                if not button.value():
                    time.sleep_ms(20)
                    if not button.value():

                        doses.enqueue({'time':get_time(), 'schedule_id': x['id']})
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
                                    "doses": doses.get_queue()
                                }
                            }
                        })
                        
                        # Using the message above, the device shadow is updated.
                        try:
                            mqtt_publish(client=mqtt, message=mesg)
                        except:
                            print("Unable to publish message.")
                            
                        while not button.value():
                            time.sleep_ms(20)

                        break

            activeBuzzer.value(1)
            time.sleep_ms(50)
            activeBuzzer.value(0)
            lcd_clear()
            time.sleep(60)


    # ----------------- MQTT STUFF LOOP-----------------
    # Check for messages.
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
                "doses": doses.get_queue()
            }
        }
    })
    
    # Using the message above, the device shadow is updated.
    try:
        mqtt_publish(client=mqtt, message=mesg)
    except:
        print("Unable to publish message.")
        

    # Wait for 10 seconds before checking for messages and publishing a new update, reducing power and cpu usage.
    print("Sleep for 10 seconds")
    time.sleep(10)