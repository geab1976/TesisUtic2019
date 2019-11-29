#!/usr/bin/python
# -*- coding: utf-8 -*-
import sys
import sqlite3
from sqlite3 import Error
import datetime
import time
"""
Obtener par�metros de hs=humedad de suelo, ta=temperatura ambiente, 
hr=humedad relativa, ls=iluminaci�n, ll=detecci�n de lluvia, f=fecha, h=hora
bi= bandera dispositivo, bf= bandera foto, bv= bandera video
"""
parametros= sys.argv
hs=int(parametros[1])
ta=int(parametros[2])
hr=int(parametros[3])
ls=int(parametros[4])
ll=int(parametros[5])
f= parametros[6]
h= parametros[7]
bi=int(parametros[8])
ca=float(parametros[9])
va=float(parametros[10])

#fecha_hora1 = datetime.datetime.strptime((parametros[6]+" "+parametros[7]), '%d-%m-%Y %H:%M:%S')
#fecha_hora1 = datetime.datetime.strptime((parametros[7]), '%H:%M:%S')
#fecha_hora2 = datetime.datetime.strptime(("14-08-2019 18:55:00"), '%d-%m-%Y %H:%M:%S')
#fecha_hora2 = datetime.datetime.strptime(("18:55:00"), '%H:%M:%S')
#segundos=int((fecha_hora2-fecha_hora1).total_seconds())

"""hola=[7,6,5,4,3,]
letra=['a']
for i,argm,h in zip(hola,parametros,letra):
   print ("{} {} {}".format(i*100,argm,h))
print fecha_hora1
print fecha_hora2
print segundos
"""
def dict_factory(cursor, row):
    d = {}
    for idx, col in enumerate(cursor.description):
        d[col[0]] = row[idx]
    return d
#Conexión a la Base de Datos SLQite3
def sql_connection():
    try:
        con = sqlite3.connect('/mnt/sda1/SistRiego/sqlite/sistriego_db.db')
        con.text_factory = str
        #print("Conexión Exitosa!")
        return con
    except Error:
        #print(Error)
        print "2"
    """finally:
        con.close()"""

#INSERTAR DATOS
def sql_insertar_historico(con, datos):
    cursorObj = con.cursor()
    cursorObj.execute('INSERT INTO tabla(columnas) VALUES(valores)', datos)
    con.commit()
    #Consultar Datos 
#datos = (2, 'Andrew', 800, 'IT', 'Tech', '2018-02-06')

#def comparar_sensores(hs,ta,ls,ll):
def comparar_sensores():
   #print "hs:"+str(hs)
   #print "ta:"+str(ta)
   #print "ls:"+str(ls)
   #print "ll:"+str(ll)

   #print "hs min:"+str(hs_min)+" max:"+str(hs_max)
   #print "ta min:"+str(ta_min)+" max:"+str(ta_max)
   #print "ls min:"+str(ls_min)+" max:"+str(ls_max)
   """hora = datetime.datetime.strptime(h, '%H:%M:%S') #hora actual 
   hh=hora.hour*3600
   mm=hora.minute*60
   ss=hora.second
   tsegundos=hh+mm
   if tsegundos%10800==0:
      alertasSensores()"""
   
   hs_comparado=1
   if hs<=hs_max:
      hs_comparado=0
   #print "hs_comparado: "+str(hs_comparado)
   ta_comparado=1
   if ta_min<=ta and ta<=ta_max:
      ta_comparado=0
   #print "ta_comparado: "+str(ta_comparado)
   ls_comparado=1
   if ls<=ls_max:
      ls_comparado=0
   #print "ls_comparado: "+str(ls_comparado)
   """
   Tabla para encendido
   ll hs ta ls | encender
    0  0  0  0 |    1
    0  0  0  1 |    1
    0  0  1  0 |    1
   """
   encender=0
   if ((hs_comparado==0 and ll==0 and ta_comparado==0) or 
      (hs_comparado==0 and ll==0 and ta_comparado==1 and ls_comparado==0)): #ll hs ta ls> 0000 0001
      encender=1
   return encender

#OBTENER DATOS CONFIGURACION
def sql_vista_configuracion_especie(con):
   #Variables globales
   global id_configuracion
   global id_especie
   global cantidad_maceta
   global riego_mililitros
   global gotero_caudal
   global ta_min
   global ta_max
   global hs_min 
   global hs_max
   global ls_min
   global ls_max
   global riego_minutos_activo
   global riego_minutos_espera
   global resumen_activar
   global resumen_hora_envio
   global alerta_activar
   global alerta_riego_inicio
   global alerta_riego_fin
   global alerta_hs_min
   global alerta_hs_max
   global alerta_ta_min
   global alerta_ta_max
   global alerta_lluvia
   global email_smtp_activar
   global riego_horario
   global dispositivo_activar
   global ban_configuracion
   id_configuracion=0
   id_especie=0
   cantidad_maceta=0
   riego_mililitros=0
   gotero_caudal=0
   ta_min=0
   ta_max=0
   hs_min=0
   hs_max=0
   ls_min=0
   ls_max=0
   riego_minutos_activo=0
   riego_minutos_espera=0
   resumen_activar=0
   resumen_hora_envio=0
   alerta_activar=0
   alerta_riego_inicio=0
   alerta_riego_fin=0
   alerta_hs_min=0
   alerta_hs_max=0
   alerta_ta_min=0
   alerta_ta_max=0
   alerta_lluvia=0
   email_smtp_activar=0
   riego_horario=0
   dispositivo_activar=0
   
   #BANDERAS DE TOMA DE DECISION
   ban_configuracion=0

   con.row_factory = dict_factory
   cursor = con.cursor()
   #CONSULTA ACCION MANUAL
   sql="SELECT id_configuracion, id_especie, maceta_cantidad, riego_mililitros, ta_min, ta_max, "
   sql+="hs_min, hs_max, ls_min, ls_max, riego_minutos_activo, gotero_caudal, "
   sql+="riego_minutos_espera, resumen_activar, resumen_hora_envio, alerta_activar,"
   sql+="alerta_riego_inicio, alerta_riego_fin, alerta_hs_min, alerta_hs_max, "
   sql+="alerta_ta_min, alerta_ta_max, alerta_lluvia, email_smtp_activar, "
   sql+="CASE WHEN (strftime('%s',time('"+h+"'))-strftime('%s',riego_inicio))>=0 AND "
   sql+="(strftime('%s',riego_fin)-strftime('%s',time('"+h+"')))>=0 THEN 1 ELSE 0 END as horario_riego,"
   sql+="dispositivo_activar FROM vista_configuracion_especie; "
   cursor.execute(sql)
   results = cursor.fetchall()
   for row in results:
      ban_configuracion=1
      id_configuracion=row["id_configuracion"]
      id_especie=row["id_especie"]
      cantidad_maceta=row["maceta_cantidad"]
      riego_mililitros=row["riego_mililitros"]
      gotero_caudal=row["gotero_caudal"]
      ta_min=row["ta_min"]
      ta_max=row["ta_max"]
      hs_min=row["hs_min"]
      hs_max=row["hs_max"]
      ls_min=row["ls_min"]
      ls_max=row["ls_max"]
      riego_minutos_activo=row["riego_minutos_activo"]
      riego_minutos_espera=row["riego_minutos_espera"]
      resumen_activar=row["resumen_activar"]
      resumen_hora_envio=row["resumen_hora_envio"]
      alerta_activar=row["alerta_activar"]
      alerta_riego_inicio=row["alerta_riego_inicio"]
      alerta_riego_fin=row["alerta_riego_fin"]
      alerta_hs_min=row["alerta_hs_min"]
      alerta_hs_max=row["alerta_hs_max"]
      alerta_ta_min=row["alerta_ta_min"]
      alerta_ta_max=row["alerta_ta_max"]
      alerta_lluvia=row["alerta_lluvia"]
      email_smtp_activar=row["email_smtp_activar"]
      riego_horario=row["horario_riego"]
      dispositivo_activar=row["dispositivo_activar"]
   if ban_configuracion==1 and dispositivo_activar==1:
      encender=comparar_sensores()
      consultarUltimoRiego(con,encender,id_especie)
   else:
      cerrarUltimoRiego(con,id_especie)
      #print "DESACTIVAR DISPOSITIVO"
      #H
      print "H2"

def cerrarUltimoRiego(con,id_especie):
   con.row_factory = dict_factory
   cursor = con.cursor()
   #CONSULTA ACCION MANUAL
   sql="SELECT id_especie_riego FROM especies_riegos "
   sql+="WHERE id_especie="+str(id_especie)+" AND fecha_hora_fin is NULL "
   sql+="ORDER BY fecha_hora_inicio DESC Limit 1; "
   #print sql
   cursor.execute(sql)
   results = cursor.fetchall()
   for row in results:
      actualizarUltimoregistro(con,f+""+h,row["id_especie_riego"])
      
def actualizarUltimoregistro(con,fecha_hora,id_especie_riego):
   cursor = con.cursor()
   sql_update='UPDATE especies_riegos SET '
   sql_update+='fecha_hora_fin = fecha_hora_actualizacion '
   sql_update+='WHERE id_especie_riego = '+str(id_especie_riego)
   cursor.execute(sql_update)
   con.commit()      

def consultarUltimoRiego(con,encender,id_especie):
   con.row_factory = dict_factory
   cursor = con.cursor()
   #CONSULTA ACCION MANUAL
   sql="SELECT er.id_especie_riego, er.id_especie, time(er.fecha_hora_inicio) as hora_inicio, er.fecha_hora_actualizacion, er.total_duracion, "
   sql+="time(er.fecha_hora_actualizacion) as hora_actualizada, time(er.fecha_hora_fin) as hora_fin, er.total_agua_suministrada as agua_registro,er_d.total_agua_suministrada "
   sql+="FROM especies_riegos er "
   sql+="   LEFT JOIN ("
   sql+="   SELECT"
   sql+="      max(id_especie_riego) id_especie_riego," 
   sql+="      sum(total_duracion) total_duracion, "
   sql+="      sum(total_agua_suministrada) total_agua_suministrada"
   sql+="   FROM"
   sql+="      especies_riegos"
   sql+="   WHERE"
   sql+="      id_especie="+str(id_especie)+" AND date(fecha_hora_inicio)=date('"+f+"') "
   sql+="   GROUP BY"
   sql+="      id_especie,date(fecha_hora_inicio)" 
   sql+=") er_d ON er_d.id_especie_riego=er.id_especie_riego "
   sql+="WHERE er.id_especie="+str(id_especie)+" AND date(er.fecha_hora_inicio)=date('"+f+"') "
   sql+="ORDER BY er.id_especie_riego DESC Limit 1; "
   cursor.execute(sql)
   results = cursor.fetchall()
   ban=0
   id_especie_riego=0
   hora_inicio="00:00:00"
   hora_fin="00:00:00"
   hora_actualizada="00:00:00"
   fecha_hora_actualizacion="28/12/1976 23:15:00"
   total_duracion=0
   agua_registro=0
   total_agua_suminstrada=0
   fecha_hora=f+" "+h
   for row in results:
      ban=1
      id_especie_riego=row["id_especie_riego"]
      hora_inicio=row["hora_inicio"]
      hora_fin=row["hora_fin"]
      hora_actualizada=row["hora_actualizada"]
      fecha_hora_actualizacion=row["fecha_hora_actualizacion"]
      total_duracion=row["total_duracion"]
      agua_registro=row["agua_registro"]
      total_agua_suministrada=row["total_agua_suministrada"]
   if encender==1 and riego_horario==1: # CASO 1
      if ban==1:#Tiene registro en el día
         #print "id_especie_riego:"+str(id_especie_riego)
         #print hora_inicio
         #print "NULL" if hora_fin is None else hora_fin  
         #print str(total_duracion)
         #print str(total_agua_suminstrada)
         ha = datetime.datetime.strptime(h, '%H:%M:%S') #hora actual
         hi = datetime.datetime.strptime(hora_inicio, '%H:%M:%S') #hora inicio
         dhahi = int((ha-hi).total_seconds()) #diferencia en segundos
         #print str(total_agua_suministrada)+" - "+str(agua_registro)
         TA = riego_minutos_activo*60 #Tiempo Activo
         TE = riego_minutos_espera*60 #1*60 Tiempo Espera
         VAC= int(float(gotero_caudal*TA/60/cantidad_maceta)) #Volumen Agua calculado
         #SA = int(gotero_caudal*dhahi/3600) #5000*1/3600= 1
         SA = int(float(va/cantidad_maceta)) #5000*1/3600= 1
         #LA = int(gotero_caudal*TA/60) #5000*60/3600=83
         #TSA= SA+(total_agua_suministrada-agua_registro) #1+(1099-34)
         TSA= SA+total_agua_suministrada #1+(1099-34)
         """print str(VAC)+" -> "
         print str(SA)+" -> "
         print str(total_agua_suministrada)+" -> "
         print str(agua_registro)+" -> "
         print str(TSA)+" -> "
         print str(riego_mililitros)+" -> """
         #print str(dhahi)+" SA="+str(SA)+" tas="+str(total_agua_suministrada)+" ar="+str(agua_registro)+" >>> "
         if hora_fin is None:# Debe tener hora inicio
            if TA>dhahi and riego_mililitros>TSA and VAC>=SA and dhahi>0: #(dhahi>=total_duracion) and
               datos=(fecha_hora, ta, hs, ls, dhahi, SA, id_especie_riego)
               actualizarRegistrosRiego(con,datos)
               print "A1"
            else:
               #if(dhahi>=(TA+1) or dhahi>=(TA+2) or dhahi>=(TA+3) or dhahi>=(TA+4) ):
               #   dhahi=TA
                  #SA=LA
               if riego_mililitros<TSA:
                  SA=riego_mililitros-(total_agua_suministrada-agua_registro)   
                  dhahi=int(3600*SA/gotero_caudal)
                  #print "TSA="+str(TSA)+" | dhahi="+str(dhahi)+" tas="+str(total_agua_suministrada)+" ar="+str(agua_registro)+" $$$ "
               #if dhahi>(TA+120):
               #   dhahi= total_duracion
               #   SA = agua_registro+10000
               #   fecha_hora=fecha_hora_actualizacion  
               if dhahi<0:
                  dhahi= total_duracion
                  SA = agua_registro +20000 
               datos=(fecha_hora, fecha_hora, ta, hs, ls, ll, dhahi, SA, id_especie_riego)      
               cerrarRegistrosRiego(con,datos)
               datos=(fecha_hora,8,"Fin del Riego")
               insertarLog(con,datos)
               print "B0"
         else:
            hf = datetime.datetime.strptime(hora_fin, '%H:%M:%S')
            dhahf = int((ha-hf).total_seconds())
            TSA = total_agua_suministrada
            if TE <= dhahf:
               if riego_mililitros>TSA or dhahf<0:
                  datos=(id_especie, cantidad_maceta,fecha_hora, fecha_hora, hs, hs, ta, ta, ls, ls, ll, 0, 0)
                  insertarRegistrosRiego(con,datos)
                  datos=(fecha_hora,7,"Inicio del Riego")
                  insertarLog(con,datos)
                  print "C1"
               else:
                  print "D0"
            else:
               print "E0"
      else:  
         datos=(id_especie, cantidad_maceta, fecha_hora, fecha_hora, hs, hs, ta, ta, ls, ls, ll, 0, 0)
         insertarRegistrosRiego(con,datos)
         datos=(fecha_hora,7,"Inicio del Riego")
         insertarLog(con,datos)
         print "F1"
   else:# CASO 0
      if hora_fin is None:
         ha = datetime.datetime.strptime(h, '%H:%M:%S') #hora actual
         hi = datetime.datetime.strptime(hora_inicio, '%H:%M:%S') #hora inicio
         dhahi = int((ha-hi).total_seconds()) #diferencia en segundos
         #SA = int(gotero_caudal*dhahi/3600)
         SA = round(float(va/cantidad_maceta)) #5000*1/3600= 1
         datos=(fecha_hora, fecha_hora, ta, hs, ls, ll, dhahi, SA, id_especie_riego)
         cerrarRegistrosRiego(con,datos)
         datos=(fecha_hora,8,"Fin del Riego")
         insertarLog(con,datos)
         print "G0"
      else:
         print "H0"
      
   
def insertarRegistrosRiego(con,datos):
   cursor = con.cursor()
   sql_insert='INSERT INTO especies_riegos('
   sql_insert+='id_especie, cantidad_maceta, fecha_hora_inicio, fecha_hora_actualizacion, '
   sql_insert+='hs_inicio, hs_fin, ta_inicio, ta_fin, ls_inicio, ls_fin, '
   sql_insert+='lluvia_detectada, total_duracion, total_agua_suministrada) ' 
   sql_insert+='VALUES(?,?, datetime(?), datetime(?), ?, ?, ?, ?, ?, ?, ?, ?, ?)'
   cursor.execute(sql_insert, datos)
   con.commit()
   #print sql_insert

def actualizarRegistrosRiego(con,datos):
   cursor = con.cursor()
   sql_update='UPDATE especies_riegos SET '
   sql_update+='fecha_hora_actualizacion = datetime(?),'
   sql_update+='ta_fin = ?,'
   sql_update+='hs_fin = ?,'
   sql_update+='ls_fin = ?,'
   sql_update+='total_duracion = ?,'
   sql_update+='total_agua_suministrada = ? '
   sql_update+='WHERE id_especie_riego = ? '
   cursor.execute(sql_update, datos)
   con.commit()
   #print sql_update

def cerrarRegistrosRiego(con,datos):
   cursor = con.cursor()
   sql_update='UPDATE especies_riegos SET '
   sql_update+='fecha_hora_actualizacion = datetime(?),'
   sql_update+='fecha_hora_fin = datetime(?),'
   sql_update+='ta_fin = ?,'
   sql_update+='hs_fin = ?,'
   sql_update+='ls_fin = ?,'
   sql_update+='lluvia_detectada = ?,'
   sql_update+='total_duracion = ?,'
   sql_update+='total_agua_suministrada = ? '
   sql_update+='WHERE id_especie_riego = ? '
   cursor.execute(sql_update, datos)
   con.commit()
   #print sql_update

def insertarLog(con,datos):
   con.row_factory = dict_factory
   cursor = con.cursor()
   #CONSULTA ACCION MANUAL
   sql=" SELECT id_historico FROM historicos "
   sql+="WHERE strftime('%Y-%m-%d %H:%M', fecha_hora)=strftime('%Y-%m-%d %H:%M', datetime('"+f+" "+h+"')) AND id_historico_motivo IN(11,12,13,14,15,16);"
   #print sql
   cursor.execute(sql)
   results = cursor.fetchall()
   existe=0
   for row in results:
       existe=1
   if existe==0:
      sql_insert='INSERT INTO historicos('
      sql_insert+='fecha_hora, id_historico_motivo, detalle, notificado) ' 
      sql_insert+='VALUES(datetime(?),?,?,0);'
      cursor.execute(sql_insert, datos)
      con.commit()
   
"""def alertasSensores():
   #Sensor Temperatura Ambiente   
   if ta>ta_max:
      datos=(f+" "+h,11,"Temperatura Ambiente Máxima ("+str(ta_max)+"%): "+str(ta)+"% obtenida")
      insertarLog(con,datos)
   if ta<ta_min:
      datos=(f+" "+h,12,"Temperatura Ambiente Mínima ("+str(ta_min)+"%): "+str(ta)+"% obtenida")
      insertarLog(con,datos)

   #Sensor Humedad del Suelo
   if hs>hs_max:
      datos=(f+" "+h,13,"Humedad del Suelo Máxima ("+str(hs_max)+"%): "+str(hs)+"% obtenida")
      insertarLog(con,datos)
   if hs<hs_min:
      datos=(f+" "+h,14,"Humedad del Suelo Mínima ("+str(hs_min)+"%): "+str(hs)+"% obtenida")
      insertarLog(con,datos)

   #Sensor Iluminación   
   if ls>ls_max:
      datos=(f+" "+h,15,"Iluminación Máxima ("+str(ls_max)+"%): "+str(ls)+"% obtenida")
      insertarLog(con,datos)
   if ll==1:
      datos=(f+" "+h,16,"Lluvia detectada")
      insertarLog(con,datos)
      """
#Procesos        
con = sql_connection() 
if bi==0:
   datos=(f+" "+h,1,"SistRiego Activado")
   insertarLog(con,datos)
sql_vista_configuracion_especie(con)
#CERRAR CONEXION A LA BASE DE DATOS
con.close()
time.sleep(2)
#sql_insertar_historico(con, datos)