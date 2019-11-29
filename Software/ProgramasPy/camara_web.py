#!/usr/bin/python
# -*- coding: utf-8 -*-
import os, sys
import subprocess
import sqlite3
from sqlite3 import Error

def dict_factory(cursor, row):
    d = {}
    for idx, col in enumerate(cursor.description):
        d[col[0]] = row[idx]
    return d 

def sql_connection():
    try:
        con = sqlite3.connect('/mnt/sda1/SistRiego/sqlite/sistriego_db.db')
        con.text_factory = str
        #print "Conexión Exitosa!"
        return con
    except Error:
        print Error

def tomar_foto(resolucion):
    comando="fswebcam@-r@"+resolucion+"@/mnt/sda1/Sistriego/web/imagen.png"
    parametros = comando.split('@')
    subprocess.Popen(parametros)

def ejecutar_video(resolucion,fps):
    comando="mjpg_streamer@-i@input_uvc.so -d /dev/video0 -r "+resolucion.strip()+" -f "+str(fps).strip()+"@-o@output_http.so -p 8082 -w /www/webcam@&"
    print comando
    parametros = comando.split('@')
    #print parametros
    subprocess.Popen(parametros)
       
parametro= sys.argv
accion=int(parametro[1])
#Procesos  
con = sql_connection() 
con.row_factory = dict_factory
cursor = con.cursor()
#CONSULTA ACCION MANUAL
sql="SELECT webcam_tamanio_imagen, webcam_tamanio_video, webcam_fps_video FROM configuraciones "
sql+="WHERE configuracion_activar=1 limit 1; "
    #print sql
cursor.execute(sql)
results = cursor.fetchall()
for row in results:
    comando="killall mjpg_streamer"
    valor1=os.system(comando)   
    resolucion_imagen=row["webcam_tamanio_imagen"]
    resolucion_video=row["webcam_tamanio_video"]
    fps=row["webcam_fps_video"]
    if accion==1:
        print "FOTO"
        tomar_foto(resolucion_imagen)
    if accion==2:
        print "VIDEO"
        ejecutar_video(resolucion_video, fps)
       
#CERRAR CONEXION A LA BASE DE DATOS
con.close()