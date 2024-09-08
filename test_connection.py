import mysql.connector
import os
from dotenv import load_dotenv

load_dotenv()

conn = mysql.connector.connect(
    host=os.getenv("DB_HOST"),
    port=os.getenv("DB_PORT"),
    user=os.getenv("DB_USER"),
    password=os.getenv("DB_PASSWORD"),
    database=os.getenv("DB_DATABASE")
)

# print( os.getenv("DB_PORT") )

mycursor = conn.cursor()

mycursor.execute("SELECT * FROM ENGCC304")

myresult = mycursor.fetchall()

for x in myresult:
  print(x)