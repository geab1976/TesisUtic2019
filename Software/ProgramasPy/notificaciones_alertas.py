#!/usr/bin/python
# -*- coding: utf-8 -*-
import os
import sys
sys.path.insert(0, '/usr/lib/python2.7/bridge/')
from bridgeclient import BridgeClient as bridgeclient
import time
import datetime
import sqlite3
from sqlite3 import Error
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.MIMEImage import MIMEImage
import mimetypes
import httplib
import urlparse
import urllib

bc= bridgeclient()
fh= "2019-01-01 00:00:00"
hs= 0
ha= 0
ta= 0
ls= 0
llu= "NO"
ri= 0
valor_entrada_sensor=0
fecha=""
hora=""
id_especie=0

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

def insertarLog(con,datos):
   cursor = con.cursor()
   sql_insert='INSERT INTO historicos('
   sql_insert+='fecha_hora, id_historico_motivo, detalle) ' 
   sql_insert+='VALUES(datetime(?),?,?)'
   cursor.execute(sql_insert, datos)
   con.commit()  

def tomar_foto(fecha_hora, resolucion, usuario):
    datos=(fecha_hora,9,"Usuario: "+usuario+" Resolución: "+resolucion)
    insertarLog(con,datos)

def ejecutar_video(fecha_hora, resolucion, fps, usuario):
    datos=(fecha_hora,10,"Usuario: "+usuario+" Resolución: "+resolucion+" fps: "+str(fps))
    insertarLog(con,datos)
      
def get_server_status_code(url):
    # descarga sólo el encabezado de una URL y devolver el código de estado del servidor.
    host, path = urlparse.urlparse(url)[1:3]
    try:
        conexion = httplib.HTTPConnection(host)
        conexion.request('HEAD', path)
        return conexion.getresponse().status
    except StandardError:
        return None

# función que se encarga de checkear que exista la url a guardar
def check_url(url):
    # Comprobar si existe un URL sin necesidad de descargar todo el archivo. Sólo comprobar el encabezado URL.
    # variable que se encarga de traer las respuestas
    codigo = [httplib.OK, httplib.FOUND, httplib.MOVED_PERMANENTLY]
    return get_server_status_code(url) in codigo     
      
def enviarEmail(valor_entrada, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego):
    #enviarEmail(1, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
    #sensores_datos=(0 fh, 1 hs, 2 ha, 3 ta, 4 ls, 5 ll, 6 ri)
    #sensores_rango=(0 hs_max, 1 hs_min, 2 ta_max, 3 ta_min, 4 ls_max, 5 webcam)
    #smtp_valores=(0 smtp_activar, 1 smtp_servidor, 2 smtp_puerto, 3 smtp_ssl, 4 smtp_usuario, 5 smtp_clave)
    #historico=(0 fh, 1 id_historico_motivo, 2 asunto)
    #datos_riego=(0 idh, 1 fhi, 2 fha, 3 tai, 4 hsi, 5 lsi, 6 taf, 7 hsf, 8 lsf, 9 lld, 10 agua, 11 maceta)
    #fecha hora / resumen diario
    #INSERTAMOS LOG DE EVENTOS
    con.row_factory = dict_factory
    cursor = con.cursor()
    #CONSULTA ACCION MANUAL
    existe=0
    if valor_entrada>2:
        sql=" SELECT id_historico "
        sql+="   id_historico "
        sql+="FROM "
        sql+="   historicos "
        sql+="WHERE "
        sql+="  strftime('%Y-%m-%d %H', fecha_hora)=strftime('%Y-%m-%d %H', datetime('"+sensores_datos[0]+"')) AND "
        sql+="  id_historico_motivo IN("+str(historico[1])+");"
        #print sql
        cursor.execute(sql)
        results = cursor.fetchall()
        for row in results:
            existe=1        
        
    if existe==0:
        # Configuracion del mail 
        emisor  = "Notificador SistRiego <"+smtp_valores[4]+">"
        mensaje = MIMEMultipart('alternative') 
        mensaje['From']=emisor 
        mensaje['To']=emails
        receptor2=mensaje['To'].split(",")
        mensaje['Subject']=historico[2]
        if valor_entrada>2 and valor_entrada<9:
            html = "<table>"        
            html += "<tr><td>Fecha/Hora: </td></tr>"
            html += "<tr><td>"+sensores_datos[0]+"</td></tr>"
            
            html += "<tr><td><br>Humedad Relativa (%): </td></tr>"
            html += "<tr><td>"+str(sensores_datos[2])+"%<br></td></tr>"
            
            html += "<tr><td><br>Humedad Suelo: </td></tr>"
            html += "<tr><td>["+str(sensores_rango[1])+"-"+str(sensores_rango[0])+"%] -> "+str(sensores_datos[1])+"%</td></tr>"
            
            html += "<tr><td><br>Temperatura Ambiente: </td></tr>"
            html += "<tr><td>["+str(sensores_rango[3])+"-"+str(sensores_rango[2])+"°C] -> "+str(sensores_datos[3])+"°C</td></tr>"
            
            html += "<tr><td><br>Iluminación:</td></tr>"
            html += "<tr><td>["+str(sensores_rango[5])+"-"+str(sensores_rango[4])+"%] -> "+str(sensores_datos[4])+"%</td></tr>"
            
            html += "<tr><td><br>Lluvia Detectada: </td></tr>"
            html += "<tr><td>"+sensores_datos[5]+"</td></tr>"
            
            html += "<tr><td><br>Riego: </td></tr>"
            html += "<tr><td>"+sensores_datos[6]+"</td></tr></table>"
        if valor_entrada<3:
            fecha_fin=datos_riego[2]
            ta_fin=str(datos_riego[6])
            hs_fin=str(datos_riego[7])
            ls_fin=str(datos_riego[8])
            agua=str(datos_riego[10])
            if valor_entrada==1:
                fecha_fin="---"
                ta_fin="---"
                hs_fin="---"
                ls_fin="---"
                agua="---"
            html = "<table>"#datos_riego=(0 idh, 1 fhi, 2 fha, 3 tai, 4 hsi, 5 lsi, 6 taf, 7 hsf, 8 lsf, 9 lld)
            html += "<tr><td>Fecha/Hora Inicio: </td></tr>"
            html += "<tr><td>"+datos_riego[1]+"</td></tr>"
            
            html += "<tr><td><br>Fecha/Hora Fin: </td></tr>"
            html += "<tr><td>"+fecha_fin+"</td></tr>"
            
            html += "<tr><td><br>Cantidad Macetas: </td></tr>"
            html += "<tr><td>"+str(datos_riego[11])+" unidad/es</td></tr>"
            
            html += "<tr><td><br>Agua Suministrada por Maceta (ml): </td></tr>"
            html += "<tr><td>"+agua+" ml</td></tr>"
            
            html += "<tr><td><br>Temperatura Ambiente Inicio (°C): </td></tr>"
            html += "<tr><td>"+str(datos_riego[3])+"°C</td></tr>"
            html += "<tr><td><br>Temperatura Ambiente Fin (°C): </td></tr>"
            html += "<tr><td>"+ta_fin+"°C</td></tr>"
            
            html += "<tr><td><br>Humedad Suelo Inicio (%): </td></tr>"
            html += "<tr><td>"+str(datos_riego[4])+"%</td></tr>"
            html += "<tr><td><br>Humedad Suelo Fin (%): </td></tr>"
            html += "<tr><td>"+hs_fin+"%</td></tr>"
            
            html += "<tr><td><br>Iluminación Inicio(%): </td></tr>"
            html += "<tr><td>"+str(datos_riego[5])+"%</td></tr>"
            html += "<tr><td><br>Iluminación Fin (%): </td></tr>"
            html += "<tr><td>"+ls_fin+"%</td></tr>"
            
            html += "<tr><td><br>Lluvia Detectada: </td></tr>"
            html += "<tr><td>"+str(datos_riego[9])+"</td></tr></table>"
            
        if valor_entrada==9:
            html=resumen_diario(str(id_especie)+":4:1:"+fecha+":"+fecha)
            
        log=""
        part2 = MIMEText(html, 'html')
        mensaje.attach(part2)
        #print check_url('http://www.google.com') 
        # There is no connection
        if sensores_rango[5]==1 and smtp_valores[0]==1 and check_url('http://www.google.com'):
            os.system("killall mjpg_streamer")
            os.system("fswebcam -r 320x240 /mnt/sda1/Sistriego/web/imagen_web.png")
            # Adjuntamos Imagen
            file = open("/mnt/sda1/Sistriego/web/imagen_web.png", "rb")
            attach_image = MIMEImage(file.read())
            attach_image.add_header('Content-Disposition', 'attachment; filename = "huerto_'+sensores_datos[0].replace(" ","_")+'.png"')
            mensaje.attach(attach_image)
        enviado=0;
        if smtp_valores[0]==1 and check_url('http://www.google.com'):
            serverSMTP = smtplib.SMTP_SSL(smtp_valores[1],smtp_valores[2]) 
            serverSMTP.ehlo() 
            #serverSMTP.starttls() 
            serverSMTP.login(smtp_valores[4],smtp_valores[5]) 
            # Enviamos el mensaje 
            serverSMTP.set_debuglevel(0)
            serverSMTP.sendmail(emisor,receptor2,mensaje.as_string()) 
            # Cerramos la conexion 
            serverSMTP.close()
            enviado=1
            if valor_entrada>2 and valor_entrada<9:
                log += "Fecha/Hora: "+sensores_datos[0]+", "
                log += "Hum. Rel. (%): "+str(sensores_datos[2])+"%, "
                log += "Hum. Suelo ("+str(sensores_rango[0])+" a "+str(sensores_rango[1])+"%): "+str(sensores_datos[1])+"%, "
                log += "Temp. Amb. ("+str(sensores_rango[2])+" a "+str(sensores_rango[3])+"°C): "+str(sensores_datos[3])+"°C, "
                log += "Ilum. ("+str(sensores_rango[4])+" a "+str(sensores_rango[5])+"%): "+str(sensores_datos[4])+"%, "
                log += "Lluv. Detect.: "+sensores_datos[5]+", "        
                log += "Riego: "+sensores_datos[6]+", "
                log += "Notificado a: "+emails
                
            sql="INSERT INTO historicos("
            sql+="fecha_hora, id_historico_motivo, detalle, notificado) " 
            sql+="VALUES(datetime('"+historico[0]+"'),"+str(historico[1])+",'"+historico[2]+log+"',"+str(enviado)+");"    
            
            if valor_entrada<3:
                log += "Fecha/Hora Inicio: "+datos_riego[1]+", "
                log += "Fecha/Hora Fin: "+fecha_fin+", "
                log += "Cantidad Macetas: "+str(datos_riego[11])+", "
                log += "Agua Suministrada por Maceta (ml): "+agua+"ml, "
                log += "Temp. Amb. Inicio (°C): "+str(datos_riego[3])+"°C, "
                log += "Temp. Amb. Fin (°C): "+ta_fin+"°C, "
                log += "Hum. Suelo Inicio (%): "+str(datos_riego[4])+"%, "
                log += "Hum. Suelo Fin (%): "+hs_fin+"%, "
                log += "Ilum. Inicio (%): "+str(datos_riego[5])+"%, "
                log += "Ilum. Fin (%): "+ls_fin+"%, "
                log += "Lluv. Detect.: "+str(datos_riego[9])+", "        
                log += "Fecha Notificacion: "+historico[0]+", "
                log += "Notificado a: "+emails
                
                sql = "UPDATE historicos SET "
                sql += "   detalle = detalle||' -> '||'"+log+"', "
                sql += "   notificado = '1' "
                sql += "WHERE "
                sql += "   id_historico = '"+str(datos_riego[0])+"';"
        #print sql
        cursor.execute(sql)
        con.commit()
        #print 'Notificacion realizada!'
        time.sleep(60)
        
def resumen_diario(valor):
    url = "http://localhost/web/riegosImprimir.php" 
    campo="id_especie_enviar:agrupar_enviar:consulta_enviar:fecha_desde_enviar:fecha_hasta_enviar"
    variables=[]
    valores=[]
    datos = {}
    for campo_variables,valor_variables in zip(campo.split(":"),valor.split(":")):
        variables.append(campo_variables)
        valores.append(valor_variables)
    for variable,valor in zip(variables,valores):
        datos['%s'%variable] = valor
    try:
        return urllib.urlopen(url,urllib.urlencode(datos)).read()
    except StandardError:
        return "No se puede conectar a %s"%(url)

def obtener_datos_telemetria():
    #print "Obteniendo Telemetría..."
    global fh
    global hs
    global ha
    global ta
    global ls
    global llu
    global ri
    try:
        fh = bc.get("FHkey")
        hs = int(bc.get("HSkey"))
        ha = int(bc.get("HAkey"))
        ta = int(bc.get("TAkey"))
        ls = int(bc.get("LUZkey"))
        ll = bc.get("LLUkey")
        ri = bc.get("ENCkey")
        #print "Telemetría exitosa!!!"
        return True
    except StandardError:
        return None

def alerta_sensor(intervalo):
    global valor_entrada_sensor
    #Obtenemos datos de sensores  
    #valor = "1:4:1:10/09/2019:10/09/2019"  
    #conectar(valor)
    validar=obtener_datos_telemetria()
    if validar:
        sensores_datos=(fh, hs, ha, ta, ls, llu, ri)

        con = sql_connection() 
        con.row_factory = dict_factory
        cursor = con.cursor()
        #CONSULTA ACCION MANUAL
        sql="SELECT "
        sql+="   e.id_especie, "
        sql+="   e.hs_max, "
        sql+="   e.hs_min, "
        sql+="   e.ta_max, "
        sql+="   e.ta_min, "
        sql+="   e.ls_max, "
        sql+="   c.resumen_activar, "
        sql+="   strftime('%H:%M', c.resumen_hora_envio) AS resumen_hora_envio, "
        sql+="   c.alerta_riego_inicio, "
        sql+="   c.alerta_riego_fin, "
        sql+="   c.alerta_hs_min, "
        sql+="   c.alerta_hs_max, "
        sql+="   c.alerta_ta_min, "
        sql+="   c.alerta_ta_max, "
        sql+="   c.alerta_ls_max, "
        sql+="   c.alerta_lluvia, "
        sql+="   c.webcam_activar, "
        sql+="   c.email_smtp_servidor, "
        sql+="   c.email_smtp_puerto, "
        sql+="   c.email_smtp_ssl, "
        sql+="   c.email_smtp_usuario, "
        sql+="   c.email_smtp_clave, "
        sql+="   c.email_smtp_activar, "
        sql+="   uce.emails, "
        sql+="   strftime('%d/%m/%Y', datetime('"+fh+"')) as fecha, "
        sql+="   strftime('%H:%M', time('"+fh+"')) as hora "
        sql+="FROM "
        sql+="   configuraciones c "
        sql+="   LEFT JOIN especies e ON e.id_especie=c.id_especie "
        sql+="   LEFT JOIN ( "
        sql+="      SELECT group_concat(email) as emails FROM usuarios WHERE activo=1 "
        sql+="   ) uce ON 1>0 "
        sql+="WHERE "
        sql+="   c.configuracion_activar=1 AND "
        sql+="   c.alerta_activar=1;"
        #print sql
        cursor.execute(sql)
        results = cursor.fetchall()
        id_historico=0;
        #datos alertas
        alerta_ri=0
        alerta_rf=0
        alerta_tamin=0
        alerta_tamax=0
        alerta_hsmin=0
        alerta_hsmax=0
        alerta_lsmax=0
        alerta_lld=0
        smtp_activar=0
        smtp_servidor=""
        smtp_puerto=0
        smtp_ssl=""
        smtp_usuario=""
        smtp_clave=""
        emails=""
        hs_max=0
        hs_min=0
        ta_max=0
        ta_min=0
        ls_max=0
        ll=0
        rd=""
        rdh=""
        webcam=0
        global fecha
        global hora
        global id_especie
        fecha=""
        hora=""
        id_especie=0
        for row in results:
            alerta_ri=row['alerta_riego_inicio']
            alerta_rf=row['alerta_riego_fin']
            alerta_tamin=row['alerta_ta_min']
            alerta_tamax=row['alerta_ta_max']
            alerta_hsmin=row['alerta_hs_min']
            alerta_hsmax=row['alerta_hs_max']
            alerta_lsmax=row['alerta_ls_max']
            alerta_lld=row['alerta_lluvia']
            
            smtp_activar=row['email_smtp_activar']
            smtp_servidor=row['email_smtp_servidor']
            smtp_puerto=row['email_smtp_puerto']
            smtp_ssl=row['email_smtp_ssl']
            smtp_usuario=row['email_smtp_usuario']
            smtp_clave=row['email_smtp_clave']
            
            emails=row['emails']
            smtp_valores=(smtp_activar, smtp_servidor, smtp_puerto, smtp_ssl, smtp_usuario, smtp_clave)
            #print emails
            hs_max=row['hs_max']
            hs_min=row['hs_min']
            ta_max=row['ta_max']
            ta_min=row['ta_min']
            ls_max=row['ls_max']
            
            id_especie=row['id_especie']
            rd=row['resumen_activar']
            rdh=row['resumen_hora_envio']
            fecha=row['fecha']
            hora=row['hora']
                    
            webcam=row['webcam_activar']
            sensores_rango=(hs_max, hs_min, ta_max, ta_min, ls_max, webcam)
            #Datos Riegos realizados
            idh=0
            fhi=""
            fha=""
            tai=0
            hsi=0
            lsi=0
            taf=0
            hsf=0
            lsf=0
            lld=0
            agua=0
            maceta=0
            datos_riego=(idh, fhi, fha, tai, hsi, lsi, taf, hsf, lsf, lld, agua, maceta)
            valor_entrada_sensor+=1
            #print valor_entrada_sensor
            
            tipo_riego=7
            tipo_consulta_riego="fecha_hora_inicio"
            if valor_entrada_sensor==2:
                tipo_riego=8
                tipo_consulta_riego="fecha_hora_fin"
            #Datos Riegos e Histórico
            sql="SELECT "
            sql+="  h.id_historico, "
            sql+="  er.id_especie,"
            sql+="  er.fecha_hora_inicio,"
            sql+="  er.fecha_hora_actualizacion,"
            sql+="  er.ta_inicio,"
            sql+="  er.hs_inicio,"
            sql+="  er.ls_inicio,"
            sql+="  er.ta_fin,"
            sql+="  er.hs_fin,"
            sql+="  er.ls_fin,"
            sql+="  er.total_agua_suministrada,"
            sql+="  er.cantidad_maceta,"
            sql+="  er.lluvia_detectada "
            sql+="FROM "
            sql+="  especies_riegos er "
            sql+="  INNER JOIN historicos h ON h.fecha_hora=er."+tipo_consulta_riego+" "
            sql+="WHERE "
            sql+="  h.notificado=0 AND"
            sql+="  h.id_historico_motivo="+str(tipo_riego)+" AND"
            sql+="  strftime('%Y-%m-%d %H', "+tipo_consulta_riego+")=strftime('%Y-%m-%d %H', datetime('"+fh+"')) "
            sql+="LIMIT 1;"
            
            """print fecha
            print hora
            print rdh
            print rdh==hora"""
            if rd==1 and rdh==hora:
                asunto="Resumen Diario ["+fecha+" "+hora+"]"
                historico=(fh,17,asunto)
                enviarEmail(9, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
            
            if valor_entrada_sensor==1 and alerta_ri==1:# 7 Inicio Riego
                #print sql
                cursor.execute(sql)
                results = cursor.fetchall()
                for row in results:
                    idh=row['id_historico']
                    fhi=row['fecha_hora_inicio'] 
                    fha=row['fecha_hora_actualizacion'] 
                    tai=row['ta_inicio']
                    hsi=row['hs_inicio']
                    lsi=row['ls_inicio']
                    taf=row['ta_fin']
                    hsf=row['hs_fin']
                    lsf=row['ls_fin']
                    lld=row['lluvia_detectada']
                    agua=row['total_agua_suministrada']
                    maceta=row['cantidad_maceta']
                    datos_riego=(idh, fhi, fha, tai, hsi, lsi, taf, hsf, lsf, lld, agua, maceta)
                    asunto="Riego iniciado"
                    historico=(fh, 7, asunto)
                    enviarEmail(1, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if valor_entrada_sensor==2 and alerta_rf==1:# 8 Fin Riego
                #print sql
                cursor.execute(sql)
                results = cursor.fetchall()
                for row in results:
                    idh=row['id_historico']
                    fhi=row['fecha_hora_inicio'] 
                    fha=row['fecha_hora_actualizacion'] 
                    tai=row['ta_inicio']
                    hsi=row['hs_inicio']
                    lsi=row['ls_inicio']
                    taf=row['ta_fin']
                    hsf=row['hs_fin']
                    lsf=row['ls_fin']
                    lld=row['lluvia_detectada']
                    agua=row['total_agua_suministrada']
                    maceta=row['cantidad_maceta']
                    datos_riego=(idh, fhi, fha, tai, hsi, lsi, taf, hsf, lsf, lld, agua, maceta)
                    asunto="Riego finalizado"
                    historico=(fh, 8, asunto)
                    enviarEmail(2, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                    
            if ta>ta_max and alerta_tamax==1 and valor_entrada_sensor==3: #11 TA max
                asunto="Temperatura Ambiente Máxima ("+str(ta_max)+"%): "+str(hs)+"% obtenida"
                historico=(fh,11,asunto)
                enviarEmail(3, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if ta<ta_min and alerta_tamin==1 and valor_entrada_sensor==4: #12 TA min
                asunto="Temperatura Ambiente Mínima ("+str(ta_min)+"°C): "+str(hs)+"°C obtenida"
                historico=(fh,12,asunto)
                enviarEmail(4, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if hs>hs_max and alerta_hsmax==1 and valor_entrada_sensor==5: #13 HS max
                asunto="Humedad Suelo Máxima ("+str(hs_max)+"%): "+str(hs)+"% obtenida"
                historico=(fh,13,asunto)
                enviarEmail(5, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if hs<hs_min and alerta_hsmin==1 and valor_entrada_sensor==6: #14 HS min
                asunto="Humedad Suelo Mínima ("+str(hs_min)+"%): "+str(hs)+"% obtenida"
                historico=(fh,14,asunto)
                enviarEmail(6, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if ls>ls_max and alerta_lsmax==1 and valor_entrada_sensor==7: #15 LS max
                asunto="Iluminación Máxima ("+str(ls_max)+"%): "+str(hs)+"% obtenida"
                historico=(fh,15,asunto)
                enviarEmail(7, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if ll=="SI" and alerta_lld==1 and valor_entrada_sensor==8: #16 LL detectada
                asunto="Lluvia Detectada"
                historico=(fh,16,asunto)
                enviarEmail(8, sensores_datos, sensores_rango, emails, smtp_valores, con, historico, datos_riego)
                
            if valor_entrada_sensor==8:
                valor_entrada_sensor=0   
        con.close()
    
if __name__ == '__main__':
    i=0 
    while 1>0:
        i+=1
        #print i
        if i==10:
            alerta_sensor(i)
            i=0
        time.sleep(6)