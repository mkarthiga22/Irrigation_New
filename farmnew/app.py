import numpy as np
import pandas as pd
import datetime
from flask import Flask, request, jsonify, render_template
#from datetime import datetime,timedelta
import datetime


# import pickle
import mysql.connector


db = mysql.connector.connect(host = "localhost", user="root",  database = "ds", port="3306")
cursor = db.cursor()
# inserting data into db
# data = pd.read_csv("result.csv")
# title = data.columns
#
# data = data.values.tolist()
# for i in range(len(data)):
#     data[i][4] = datetime.datetime.strptime(data[i][4], '%d-%m-%Y %H:%M').strftime('%Y-%m-%d %H:%M:%S')
#
#  cursor.executemany("INSERT INTO irrigation_table  VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", data[::-1])
# db.commit()
app = Flask(__name__)
# model = pickle.load(open('model.pkl', 'rb'))

@app.route('/')
def home():
    return render_template('data.html')

@app.route('/predict',methods=['POST'])
def predict():
    today = datetime.datetime.now()
    yesterday = today - datetime.timedelta(hours=24)
    yesterday = yesterday.replace(second=0, microsecond=0)
    moisture = []
    yesterday = datetime.datetime(2022, 1, 18, 10, 25, 0)  # while giving real time values, it  need to be commented
    print(yesterday)
    for i in range(289):
        sq = """SELECT AVG(Moisture) from irrigation_table where Time_log between %s and %s"""
        var1 = yesterday + datetime.timedelta(minutes=3)  # minutes=1 to be replaced with minutes=5
        params = [str(yesterday), str(var1)]
        yesterday = yesterday + datetime.timedelta(minutes=1)  # minutes=1 to be replaced with minutes=5
        cursor.execute(sq, params)
        val = cursor.fetchall()
        moisture.extend(val[0])
        # yesterday+=datetime.timedelta(minutes=5)
    data = len(moisture)
    moisture1=[]
    for i in moisture:
        if i!=None:
            moisture1.append(i)
    moisture=moisture1


    result = ""
    fields = request.form.values()
    soil_type = [int(x) for x in request.form.values()].pop()
    data = ''
    filtered = ''

    if(soil_type == 1 ):
        # cursor.execute("SELECT * from irrigation_table")
        # data = len(cursor.fetchall())
        # cursor.execute("SELECT * from irrigation_table where Moisture < 35")
        # filtered = len(cursor.fetchall())
        data = len(moisture)
        filtered1=[]
        for i in moisture:
            if(i>35): # here, moisture threshold value need to set appropraitely
                filtered1.append(i)
        print(filtered1)
        filtered=len(filtered1)

    else:
        # cursor.execute("SELECT * from irrigation_table")
        # data = len(cursor.fetchall())
        # cursor.execute("SELECT * from irrigation_table where Moisture < 40 ")
        # filtered = len(cursor.fetchall())

        data = len(moisture)
        filtered1 = []
        for i in moisture:
            print(i)
            if (i<40): # here, moisture threshold value need to set appropraitely
                filtered1.append(i)
        filtered = len(filtered1)
    print(data, filtered)
    if(filtered/data > 0.5):
        result = "Irrigate"
    else:
        result = "No Irrigation"

    return render_template('data.html' , result= str(result))

# @app.route('/results',methods=['POST'])
# def results():
#
#     data = request.get_json(force=True)
#     prediction = model.predict([np.array(list(data.values()))])
#
#     output = prediction[0]
#     return jsonify(output)

if __name__ == "__main__":
    app.run(debug=True)