#!/usr/bin/python
# -*- coding: utf-8 -*-
import sys
sys.path.insert(0, '/usr/lib/python2.7/bridge/')
from bridgeclient import BridgeClient as bridgeclient

sys.path.insert(0, '/usr/lib/python2.7/bottle/')
from bottle import run, route, get, post, request

bc = bridgeclient()

@route('/status')
def status():
    try:
        fhi    = bc.get("FHIkey")
        ciclo  = bc.get("CICkey")
        fh     = bc.get("FHkey")
        hs     = bc.get("HSkey")
        ha     = bc.get("HAkey")
        ta     = bc.get("TAkey")
        luz    = bc.get("LUZkey")
        lluvia = bc.get("LLUkey")
        riego  = bc.get("ENCkey")
        ca     = bc.get("CAkey")
        va     = bc.get("VAkey")

	respuesta = "<table>"
        respuesta+= "<tr><td align='right'>Fecha/Hora Inicio:</td><td>"+fhi+"</td></tr>"       
        respuesta+= "<tr><td align='right'>Ciclo:</td><td>"+ciclo+"</td></tr>"
        respuesta+= "<tr><td align='right'>Fecha/Hora:</td><td>"+fh+"</td></tr>"
        respuesta+= "<tr><td align='right'>Humedad Suelo:</td><td>"+hs+"%</td></tr>"
        respuesta+= "<tr><td align='right'>Humedad Ambiente:</td><td>"+ha+"%</td></tr>"
        respuesta+= "<tr><td align='right'>Temp. Ambiente:</td><td>"+ta+"&deg;C</td></tr>"
        respuesta+= "<tr><td align='right'>Iluminaci&oacute;n:</td><td>"+luz+"%</td></tr>"
        respuesta+= "<tr><td align='right'>Lluvia:</td><td>"+lluvia+"</td></tr>"
        respuesta+= "<tr><td align='right'>Riego:</td><td>"+riego+"</td></tr></table>"
        return respuesta 
    except:
        return "Error al consultar al Arduino YUN!\n"

@route('/json')
def json():
    try:
        In= bc.get("FHIkey")
        Ci= bc.get("CICkey")
        Ac= bc.get("FHkey")
        Hs= bc.get("HSkey")
        Ha= bc.get("HAkey")
        Ta= bc.get("TAkey")
        Lu= bc.get("LUZkey")
        Ll= bc.get("LLUkey")
        Ri= bc.get("ENCkey")
        Ca= bc.get("CAkey")
        Va= bc.get("VAkey")

        respuesta = "[{"
        respuesta+= "\"In\":\""+In+"\","       
        respuesta+= "\"Ci\":\""+Ci+"\","
        respuesta+= "\"Ac\":\""+Ac+"\","
        respuesta+= "\"Hs\":\""+Hs+"\","
        respuesta+= "\"Ha\":\""+Ha+"\","
        respuesta+= "\"Ta\":\""+Ta+"\","
        respuesta+= "\"Lu\":\""+Lu+"\","
        respuesta+= "\"Ll\":\""+Ll+"\","
        respuesta+= "\"Ri\":\""+Ri+"\","
        respuesta+= "\"Ca\":\""+Ca+"\","
        respuesta+= "\"Va\":\""+Va+"\""
	respuesta+= "}]"
        return respuesta 
    except:
        respuesta = "[{"
        respuesta+= "\"In\":\"Error\""       
	respuesta+= "}]"
        return respuesta 

@route('/fecha_hora')
def fecha_hora():
    try:
        return bc.get("FHkey") 
    except:
        return "Error al consultar al Arduino YUN!\n"

def main():
    #run(host='localhost', port=8080, debug=True)
    run()

if __name__ == '__main__':
    main()