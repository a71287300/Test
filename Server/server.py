from http.server import *
from urllib.parse import *
from datetime import datetime
from matplotlib import pyplot as plt
from matplotlib.animation import FuncAnimation
import numpy as np
import pymysql
import threading
from queue import Queue
import math
y=[]
db = pymysql.connect("localhost","root","11111111","temp" ) #資料庫檔案名稱
cursor = db.cursor()


def draw():
    figX,ax = plt.subplots()
    global x
    Xxdata, Xydata = [], []
    lnX, = ax.plot([], [], 'r-', animated=True)
    def initX():
        ax.set_xlim(0, 1000)
        ax.set_ylim(0, 4000)
        ax.set_title("X")
        return lnX,
    def updateX(frame):
        Xxdata.append(frame)
        Xydata.append(x)
        lnX.set_data(Xxdata, Xydata)
        return lnX,
    aniX = FuncAnimation(figX, updateX,frames=1000,interval=200,
                        init_func=initX, blit=True)

    figY, ay = plt.subplots()
    global y
    Yxdata, Yydata = [], []
    lnY, = ay.plot([], [], 'r-', animated=True)
    def initY():
        ay.set_xlim(0, 1000)
        ay.set_ylim(0, 4000)
        ay.set_title("Y")
        return lnY,
    def updateY(frame):
        Yxdata.append(frame)
        Yydata.append(y)
        lnY.set_data(Yxdata, Yydata)
        return lnY,
    aniY = FuncAnimation(figY, updateY, frames=1000, interval=200,
                        init_func=initY, blit=True)

    figZ, az = plt.subplots()
    global z
    Zxdata, Zydata = [], []
    lnZ, = az.plot([], [], 'r-', animated=True)
    def initZ():
        az.set_xlim(0, 1000)
        az.set_ylim(0, 4000)
        az.set_title("Z")
        return lnZ,
    def updateZ(frame):
        Zxdata.append(frame)
        Zydata.append(z)
        lnZ.set_data(Zxdata, Zydata)
        return lnZ,
    aniZ = FuncAnimation(figZ, updateZ, frames=1000, interval=200,
                        init_func=initZ, blit=True)
    plt.show()




def writeDB(data):
    now = datetime.now()
    date = now.strftime('%Y-%m-%d')
    time = now.strftime('%H:%M:%S')  #時間格式
    #parameters = ( float(data["temp"][0]),float(data["hum"][0]), int(data["xpin"][0]), int(data["ypin"][0]),int(data["zpin"][0]), date,time)
    global x
    x = float(data["xpin"][0])
    global y
    y = float(data["ypin"][0])
    global z
    z = float(data["zpin"][0])
    T1_thread = threading.Thread(target=draw)
    T1_thread.start()


    #print(parameters)
    #cursor.execute("INSERT INTO linkit(temp,hum,date) VALUES (?,?,?)",parameters)
    #cursor.execute('INSERT INTO linkit(temp,hum,xpin,ypin,zpin,date,time) VALUES ("%f","%f","%d","%d","%d","%s","%s")' % parameters)

    db.commit()



class ServerHandler(BaseHTTPRequestHandler):
    def _set_headers(self):
        self.send_response(200)
        self.send_header("Content-type", "text/html")
        self.end_headers()

    def do_GET(self):
        self._set_headers()
        header_data = urlparse(self.path).query
        data = parse_qs(header_data)
        writeDB(data)
        data = bytes("OK, data got.", "utf-8") #回傳確認資料
        self.wfile.write(data)



server_address = ("", 8000) #監聽位置
httpd = HTTPServer(server_address, ServerHandler)
httpd.serve_forever()
