from http.server import *
from urllib.parse import *
from datetime import datetime
from matplotlib import pyplot as plt
from matplotlib.animation import FuncAnimation
import numpy as np
from queue import Queue
import time
import pymysql
import threading
q = Queue()
global runtime
runtime = 0
count = 0
result = 0
y=[]
db = pymysql.connect("122.116.196.99","admin","11111111","membership" ) #資料庫檔案名稱
cursor = db.cursor()

#繪圖
def draw():
    fig,a = plt.subplots()
    global Data
    global Title
    xdata, ydata = [], []
    ln, = a.plot([], [], 'r-', animated=True)
    def init():
        a.set_xlim(0, 1000)
        a.set_ylim(0, 3)
        a.set_title("中文")
        return ln,
    def update(frame):
        xdata.append(frame)
        ydata.append(Data)
        a.set_title(Title)
        xmin, xmax = a.get_xlim()
        if frame-1 >= xmax:
            a.set_xlim(xmin,xmax*2)
        ln.set_data(xdata, ydata)
        return ln,
    ani = FuncAnimation(fig, update,interval=300,
                        init_func=init, blit=True)
    plt.show()
#方向轉頭
def direction():
    global Roll
    global Pitch
    global Title
    Title = ""
    if Roll<20:
        Title += "低頭 "
    elif Roll>60:
        Title += "抬頭 "
    if Pitch > 60 or Pitch < -60:
        Title += "轉頭"
    elif Pitch<-20:
        Title += "轉右"
    elif Pitch>20:
        Title += "轉左"
    print(Title)
    time.sleep(1)

#活動檢測
def  Acceleration():
    global q
    temp = []
    for i in range(3):
        temp.append(q.get())
    Max = max(temp[0],temp[1],temp[2])
    Min = min(temp[0],temp[1],temp[2])
    ans = abs(Max-Min)
    global result
    if(ans>0.2):
        result += 1
    global count
    count += 1
    if(count == 15):
        if(result/count > 0.6):
            print("吃飯中")
        elif(result/count > 0.3):
            print("活動中")
        else:
            print("休息中")
        count = 0
        result = 0
    else:
        print("測量次數",count)
        print("測量達標值",result)







def writeDB(data):
    global runtime
    runtime += 1
    now = datetime.now()
    date = now.strftime('%Y-%m-%d')
    time = now.strftime('%H:%M:%S')  #時間格式
    DogParameters = (str(data["id"][0]), float(data["temp"][0]),float(data["hum"][0]),float(data["G"][0]),date,time)
    print(DogParameters)

    cursor.execute('INSERT INTO power(account,temp,hum,G,date,time) VALUES ("%s","%f","%f","%f","%s","%s")' % DogParameters)
    PositionParameters = (str(data["id"][0]),float(data["latitude"][0]),float(data["longtitude"][0]),date,time)
    NonPositionParameters = (str(data["id"][0]),date,time)



    global Data
    Data = float(data["G"][0])
    global Roll
    Roll = float(data["Roll"][0])
    global Pitch
    Pitch = float(data["Pitch"][0])
    global q
    q.put(Data)


    if(float(data["latitude"][0])>0):
        cursor.execute('INSERT INTO position (account,latitude,longtitude,date,time) VALUES("%s","%f","%f","%s","%s")'%PositionParameters)
    else:
        cursor.execute('INSERT INTO position (account,date,time) VALUES("%s","%s","%s")'%NonPositionParameters)
    
    T1_thread = threading.Thread(target=draw)
    T2_thread = threading.Thread(target=direction)
    T3_thread = threading.Thread(target=Acceleration)
    T1_thread.start()
    T2_thread.start()
    if (runtime % 3 == 0):
        T3_thread.start()
        T3_thread.join()
    T2_thread.join()

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
