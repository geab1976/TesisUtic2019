/*
  DOCUMENTO    : SistRiegoUtic2019.ino
  CREADO POR   : LIC. GUSTAVO ELOY ALCARAZ BOGADO
  CREACION     : 23/08/2018  ‏‎04:34:56 PM
  MODIFICACION : 14/06/2019  20:10:00 PM
  MODIFICACION : 30/10/2019  22:18:00 PM
  DESCRIPCION  : SISTEMA DE CONTROL AUTOMÁTICO PROGRAMABLE
                 PARA EL RIEGO POR GOTEO DE UN HUERTO URBANO EN
                 MACETAS
  =============================================================
  La presente investigación tecnológica se enfocará en crear un
  dispositivo  electrónico que podrá  automatizar, configurar y
  monitorear el proceso de riego por goteo de una huerta urbana
  en macetas empleando un actuador  (electroválvula) controlada
  por una unidad de control (plataforma de hardware abierto con
  microcontrolador) mediante sensores de: temperatura ambiental,
  iluminación,  humedad  del  suelo  y  lluvia,  y  por  último
  interactuar en forma inalámbrica (WIFI) mediante una interfaz
  gráfica   multiplataforma  (WEB),   teniendo  como  finalidad
  primordial  la racionalización  en el uso  del suministro  de
  agua  según las  necesidades de las plantas  en el proceso de
  riego.
  =============================================================
*/

//LIBRERIAS NECESARIAS PARA EL FUNCIONAMIENTO
#include <Bridge.h>
#include <BridgeServer.h>
#include <BridgeClient.h>
#include <DHT.h>
#include <avr/wdt.h>
#include <Process.h>
#include <Wire.h>
#include <SPI.h>
#include "RTClib.h"
#include <Watchdog.h>
/*
  ESPECIFICACIONES DE ENTRADAS LOGICAS Y ANALOGICAS UTILIZADAS
  ============================================================
  SCL: RTC DS1307                  [SEÑAL DE RELOJ]
  SDA: RTC DS1307                  [SEÑAL DE DATOS]
  D12: RELE DE UN CANAL            [CONTROLA ELECTROVALVULA]
  D11: LED RGB AZUL                [ACCESO REMOTO]
  D10: LED RGB VERDE               [RIEGO ACTIVADO]
  D09: LED RGB ROJO                [DISPOSITIVO ACTIVO]
  D08: SENSOR HUMEDAD/TEMPERATURA  [DHT22]
  D07: SENSOR DE CAUDAL            [YF-S201]
  D06: DETECTOR LLUVIA             [YL-38/MH-RD]
   A0: SENSOR ILUMINACION          [LDR GL55]
   A1: SENSOR HUMEDAD DEL SUELO    [YL-38/YL69]
  =============================================================
*/
//SALIDAS DIGITALES
#define ActuadorRele 12
#define LedAzul 11
#define LedVerde 10
#define LedRojo 9

//ENTRADAS DIGITALES
#define SensorLluvia 6
#define SensorCaudal 7
#define SensorHumedadTemperaturaAmbiente 8

//ENTRADAS ANALOGICAS
#define SensorHumedadSueloAnalogico A0
#define SensorLuzAnalogico A1

RTC_DS1307 RTC;
DHT dht(SensorHumedadTemperaturaAmbiente, DHT22);

// Listen on default port 5555, the webserver on the Yún
// will forward there all the HTTP requests for us.
BridgeServer server;
//String inicioFechaHora;
long consultas = 0;
long vueltas = 0;
//long tiempoEncendido = 0;
long tiempoInicio = 0;
DateTime now;
int bandera = 0;
int bi = 0;
int servidor = 1;
int servidor_video = 0;
String accion = "";
String hr;
String ta;
String ll;
String hs;
String ls;
String fh;
int o1 = 0;
int o2 = 0;
int o3 = 0;
int o4 = 0;
// VARIABLES CAUDALIMETRO
volatile int NumPulsos; //variable para la cantidad de pulsos recibidos
float factor_conversion = 4.6; //para convertir de frecuencia a caudal
float va = 0; //Volumen de Agua suministrada
float ca = 0; //Caudal del Flujo de Agua
long dt = 0; //variación de tiempo por cada bucle
long t0 = 0; //millis() del bucle anterior

void setup() {
  wdt_disable();
  pinMode(SensorCaudal, INPUT);
  //LED ROJO : Indicador de Funcionamiento ARDUINO
  pinMode(LedRojo, OUTPUT);
  digitalWrite(LedRojo, HIGH);
  //LED VERDE: Indicador de Funcionamiento ELECTROVALVULA
  pinMode(LedVerde, OUTPUT);
  //LED AZUL : Ejecución de Script Python
  pinMode(LedAzul, OUTPUT);
  //SENSOR: Lluvia
  pinMode(SensorLluvia, INPUT);
  //SENSOR: Humedad Suelo
  //pinMode(SensorHumedadSuelo, INPUT);
  //ACTUADOR: Relé
  pinMode(ActuadorRele, OUTPUT);
  digitalWrite(ActuadorRele, LOW);

  //INICILIZACION DE SERVICIOS

  //DHT22 [TEMPERATURA Y HUMEDAD AMBIENTE]
  dht.begin();

  //RTC DS1307 [FECHA-HORA SISTEMA]
  RTC.begin();
  while (!RTC.isrunning()) {
    //RTC.adjust(DateTime(__DATE__, __TIME__));
  }

  // Iniciando el Puente entre Arduino y Linino
  Bridge.begin();
  // Escucha conexiones locales solamente
  server.listenOnLocalhost();
  server.begin();
  digitalWrite(LedRojo, LOW);
  digitalWrite(LedAzul, HIGH);
  digitalWrite(LedVerde, HIGH);
  // Obtiene el tiempo de inicio:
  //inicioFechaHora = obtenerFechaHoraLinux();
  SerialUSB.begin(9600);
  //CAUDALIMETRO
  attachInterrupt(
    digitalPinToInterrupt(SensorCaudal),
    ContarPulsos,
    RISING); //(Interrupción 0(Pin3),función,Flanco de subida)
  delay(35000);
  //while (!Serial);
  Bridge.put("FHIkey", obtenerFechaHoraFormateado());
  digitalWrite(LedVerde, LOW);
  //Activar perro guardian
  wdt_enable(WDTO_8S);
  t0 = millis();
}

void loop() {
  consultarSensores();
  actualizarDatosSensoresWeb();
  if (vueltas == 0) {
    iniciarServicioDatosSensores();
  }
  if (vueltas == 5) {
    iniciarServicioNotificacion();
  }
  if (vueltas == 10) {
    ejecutarEstrategiaControl();
    esperarPeticionExterna();
  } else {
    vueltas++;
  }
}

//FUNCION CONSULTAR DATOS DE SENSORES
void consultarSensores() {
  //OBTENCION DE DATOS POR CONSULTA A SENSORES
  //HUMEDAD AMBIENTE [DHT22]
  hr = String(int(dht.readHumidity()));
  //TEMPERATURA AMBIENTE [DHT22]
  ta = String(int(dht.readTemperature()));
  //DETECTOR DE LLUVIA [MH SENSOR SERIES MH-RD]
  ll = (digitalRead(SensorLluvia) ? "NO" : "SI");
  //HUMEDAD SUELO [MH SENSOR SERIES]
  //String hs = String(100 - ((float)analogRead(A2) / 1023) * 100);
  hs = String(map(analogRead(SensorHumedadSueloAnalogico), 0, 1023, 100, 0));
  //ILUMINACION AMBIENTE [LDR]
  //String luz = String(((float)analogRead(A0) / 1023) * 100);
  ls = String(map(analogRead(SensorLuzAnalogico), 0, 1023, 0, 100));
  //FECHA-HORA [RTC DS1307]
  fh = obtenerFechaHoraFormateado();
  wdt_reset();
}

//FUNCION DE CONVERSION DEL TIPO DATETIME A STRING PARA ENVIAR AL MICROSD
String obtenerFechaHoraFormateado() {
  // Fecha y Hora del RTC
  now = RTC.now();
  char datetimeBuffer[20] = "";
  sprintf(datetimeBuffer, "%04d-%02d-%02d %02d:%02d:%02d",
          now.year(), now.month(), now.day(),
          now.hour(), now.minute(), now.second());
  wdt_reset();
  return datetimeBuffer;
}

//FUNCION PARA ACTUALIZAR LOS VALORES DE DATOS EN EL SERVICIO WEB OBTENIDOS POR LOS SENSORES
void actualizarDatosSensoresWeb() {
  //PONER LOS VALORES DE SENSORES EN ALMACENAMIENTO DEL PROCESADOR DEL LINUX
  Bridge.put("FHkey", fh);
  Bridge.put("HSkey", hs);
  Bridge.put("HAkey", hr);
  Bridge.put("TAkey", ta);
  Bridge.put("LUZkey", ls);
  Bridge.put("LLUkey", ll);
  Bridge.put("ENCkey", (digitalRead(ActuadorRele) ? "SI" : "NO"));
  Bridge.put("CICkey", String(vueltas));
  Bridge.put("CAkey", String(ca));
  Bridge.put("VAkey", String(va));
  wdt_reset();
}

//FUNCION PARA EJECUTAR LA ESTRATEGIA DE CONTROL DE RIEGO MEDIANTE CONSULTA SCRIPT PYTHON
void ejecutarEstrategiaControl() {
  Process control;
  String linea_comando = "python /mnt/sda1/SistRiego/python/estrategia_control.py ";
  String argumentos = hs + " " +
                      ta + " " +
                      hr + " " +
                      ls + " " +
                      (ll == "NO" ? 0 : 1) + " " +
                      fh + " " +
                      String(bi) + " " +
                      String(ca) + " " +
                      String(va);
  control.runShellCommand(linea_comando + argumentos);
  String accion = "";
  while (control.available()) {
    char c = control.read();
    accion += c;
  }
  accion.replace("\n", "");
  SerialUSB.println(accion + " " + argumentos);
  bi = 1;
  //return accion;
  if ((accion == "A1" || accion == "C1" || accion == "F1")) {//Opcion 1
    ejecutarAccion(o1, HIGH, LedRojo, LedAzul, LedVerde, LedVerde);
    consultarVolumenAgua();
    o1 = 1;
  } else {
    ca = 0;
    va = 0;
    o1 = 0;
  }
  if ((accion == "B0" || accion == "D0" || accion == "E0")) { //Opcion 2
    ejecutarAccion(o2, LOW, LedVerde, LedAzul, LedRojo, LedRojo);
    o2 = 1;
  } else {
    o2 = 0;
  }
  if ((accion == "G0" || accion == "H0")) { //Opcion 3
    ejecutarAccion(o3, LOW, LedRojo, LedRojo, LedAzul, LedVerde);
    o3 = 1;
  } else {
    o3 = 0;
  }
  if ((accion == "H2")) { //Opcion 4
    ejecutarAccion(o4, LOW, LedVerde, LedVerde, LedRojo, LedAzul);
    o4 = 1;
  } else {
    o4 = 0;
  }
  wdt_reset();
}

//FUNCION PARA EJECUTAR ACCION (INDICADORES VISUALES Y ACTIVAR/DESACTIVAR RIEGO)
void ejecutarAccion(int opcion, boolean rele, int led1, int led2, int led3, int led4) {
  if (opcion == 0) {
    digitalWrite(ActuadorRele, rele);
    digitalWrite(led1, LOW);
    digitalWrite(led2, LOW);
    t0 = millis();
  }
  digitalRead(led3) == HIGH ? digitalWrite(led3, LOW) : digitalWrite(led3, HIGH);
  digitalWrite(led4,  digitalRead(led3));
  delay(50);
}

//FUNCION PARA CONSULTAR EL VOLUMEN DE AGUA SUMINISTRADO AL RIEGO
void consultarVolumenAgua() {
  float frecuencia = ObtenerFrecuencia(); //obtenemos la frecuencia de los pulsos en Hz
  //va = va + ((ca / 60) * (dt/1000)); // volumen(L)=caudal(L/s)*tiempo(s)
  ca = frecuencia / factor_conversion; //calculamos el caudal en L/min
  //SerialUSB.println("DIFERENCIA SEGUNDOS= " + String(dt) + " | TO= " + String(t0));
  dt = millis() - t0; //calculamos la variación de tiempo
  if (dt < 0) {
    dt = 1000;
  }
  t0 = millis();
  va = va + (ca / 60.0 * (dt / 1000.0) * 1000.0); // volumen(L)=caudal(mL/min)*tiempo(min)
}

//FUNCION DE INTERRUPCION PARA CONSULTAR CAUDALIMETRO
void ContarPulsos () {
  NumPulsos++;  //incrementamos la variable de pulsos
}

//FUNCION PARA OBTENER PULSOS DEL CAUDALIMETRO
int ObtenerFrecuencia()
{
  int frecuencia;
  NumPulsos = 0;   //Ponemos a 0 el número de pulsos
  interrupts();    //Habilitamos las interrupciones
  delay(1000);   //muestra de 1 segundo
  //noInterrupts(); //Deshabilitamos  las interrupciones
  frecuencia = NumPulsos; //Hz(pulsos por segundo)
  return frecuencia;
}

//FUNCION INICIAR EL SERVICIO DE OBTENCION DE DATOS DE SENSORES POR WEB (TELEMETRIA)
void iniciarServicioDatosSensores() {
  SerialUSB.println("INICIANDO... " + String(vueltas));
  SerialUSB.println("CARGANDO DATOS SENSORES AL BRIDGE... " + String(vueltas));
  Process serviciosSensores;
  serviciosSensores.runShellCommandAsynchronously("python /mnt/sda1/SistRiego/python/datos_sensores.py &");
  delay(2000);
  wdt_reset();
}

//FUNCION INICIAR SERVICIO DE NOTIFICACIONES
void iniciarServicioNotificacion() {
  SerialUSB.println("CARGANDO SERVICIO DE NOTIFICACIONES... " + String(vueltas));
  Process serviciosNotificaciones;
  serviciosNotificaciones.runShellCommandAsynchronously(
    "python /mnt/sda1/SistRiego/python/notificaciones_alertas.py &");
  delay(2000);
  digitalWrite(LedAzul, LOW);
  wdt_reset();
}

//FUNCION DE EJECUCION DE SERVICIOS SOLICITADOS POR LA INTERFAZ GRAFICA (EXTERNA)
void esperarPeticionExterna() {
  // Obtener clientes para el servidor
  BridgeClient client = server.accept();
  //SerialUSB.println("Hola");
  //Hay alguna consulta de un cliente
  if (client) {
    digitalWrite(LedRojo, LOW);
    digitalWrite(LedVerde, LOW);
    digitalWrite(LedAzul, HIGH);
    // Leer el comando
    String command = client.readString();
    command.trim(); //Eliminar espacio en blanco
    //SerialUSB.println(command);
    String comando = command;
    for (int i = 0; i < comando.length(); i++) {
      if (comando.substring(i, i + 1) == ";") {
        bandera = 1;
        RTC.adjust(DateTime(
                     comando.substring(0, 4).toInt(),
                     comando.substring(5, 7).toInt(),
                     comando.substring(8, 10).toInt(),
                     comando.substring(11, 13).toInt(),
                     comando.substring(14, 16).toInt(),
                     comando.substring(17, 19).toInt()));
        break;
      }
    }
    if (command == "foto") {
      Process foto;
      foto.runShellCommandAsynchronously(
        "python /mnt/sda1/SistRiego/python/camara_web.py 1 " + fh);
      client.print("FOTO TOMADA CON EXITO!");
    }
    if (command == "iniciar_video") {
      Process video;
      video.runShellCommandAsynchronously(
        "python /mnt/sda1/SistRiego/python/camara_web.py 2 " + fh);
      client.print("VIDEO EN VIVO ACTIVADO!");
    }
    if (command == "apagar_video") {
      Process video;
      video.runShellCommandAsynchronously(
        "killall mjpg_streamer");
      client.print("VIDEO EN VIVO APAGADO!");
    }
    bandera = 0;
    // Cierra conexión y libera recursos.
    digitalWrite(LedAzul, LOW);
    client.stop();
  }
  wdt_reset();
}
/*
  //FUNCION CONVERTIR HORAS MINUTOS A SEGUNDOS
  long obtenerSegundos() {
  now = RTC.now();
  long segundos = now.hour() * 3600 + now.minute() * 60 + now.second();
  return segundos;
  }
*/
