var especie = "0";
var horario = "00:00:00 | 00:00:00";
var habilitado = "0";
var datoNombre = "@@@";
var riego_litros = "0";
var resolucion_imagen = "0";
var resolucion_video = "0";
var webcam = "0";
var fps = "0";
var hs_min = "0";
var hs_max = "0";
var ta_min = "0";
var ta_max = "0";
var ls_min = "0";
var ls_max = "0";
var hs_ay = "0";
var hr_ay = "0";
var ta_ay = "0";
var ls_ay = "0";
var ll_ay = "0";
var ri_ay = "0";
var tiempo = "0";
var ca_grafico = "";
var hs_grafico = "";
var ls_grafico = "";
var ta_grafico = "";
var hr_grafico = "";
var video_activado = 0;
var suministrado = "0%";
var consulta_exitosa = 0;

function cargarDatos(opcion) {
    $.ajax({
        url: "supervisionDatos.php",
        data: {
            opcion: opcion
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 1) {
                var tabla = "";
                $.each(data, function(id, dato) {
                    especie = dato.especie;
                    horario = dato.horario;
                    riego_litros = dato.riego_mililitros;
                    webcam = dato.webcam;
                    resolucion_imagen = dato.resolucion_imagen;
                    resolucion_video = dato.resolucion_video;
                    fps = dato.fps;
                    hs_min = dato.hs_min;
                    hs_max = dato.hs_max;
                    ta_min = dato.ta_min;
                    ta_max = dato.ta_max;
                    ls_min = dato.ls_min;
                    ls_max = dato.ls_max;
                });
                /*alert(especie + ";" + riego_litros + ";" + webcam + ";" + resolucion_imagen + ";" +
                    resolucion_video + ";" + fps + ";" + hs_min + ";" +
                    hs_max + ";" + ta_min + ";" + ta_max + ";" + ls_min + ";" + ls_max);*/
                var texto_adicional = "";
                if (webcam === 1) {
                    $("#btnImagen").attr("disabled", false);
                    $("#btnVideo").attr("disabled", false);
                    texto_adicional = "";
                } else {
                    $("#btnImagen").attr("disabled", true);
                    $("#btnVideo").attr("disabled", true);
                    texto_adicional = "Deshabilitado por el Administrador.";
                }
                ca_grafico = graficar("divCA", "Caudal Agua", "L/min", 1, 3, "ca", 0, 5);
                hr_grafico = graficar("divHR", "Humedad Relativa", "%", ls_min, ls_max, "hr", 0, 100);
                hs_grafico = graficar("divHS", "Humedad Suelo", "%", hs_min, hs_max, "hs", 0, 100);
                ta_grafico = graficar("divTA", "Temperatura", "°C", ta_min, ta_max, "ta", -10, 50);
                ls_grafico = graficar("divLS", "Iluminación", "%", ls_min, ls_max, "ls", 0, 100);
                var mensaje_imagen = "Capturar Imagen Actual del Huerto [Resolución: " +
                    resolucion_imagen + "] " + texto_adicional;
                var mensaje_video = "Visualizar Huerto en Tiempo Real [Resolución: " +
                    resolucion_video + " | fps: " + fps + "] " + texto_adicional;
                $("#btnImagen").attr("title", mensaje_imagen);
                $("#lblFotoDatos").text(mensaje_imagen);
                $("#btnVideo").attr("title", mensaje_video);
                $("#lblVideoDatos").text(mensaje_video);
                $("#lblCultivo").html(especie);
            }
            if (opcion === 2) {
                var porcentaje = 0;
                var volumen = 0;
                var seriesData = [];
                $.each(data, function(id, dato) {
                    //seriesData.push([dato.nombre, dato.valor]);
                    seriesData.push([dato.valor]);
                    porcentaje = dato.porcentaje;
                    datoNombre = dato.nombre;
                    volumen = dato.volumen;
                    horario = dato.horario;
                    habilitado = dato.habilitado;
                });
                //console.log(seriesData.toString());
                /*$.each(data, function(id, dato) {
                    porcentaje = dato.porcentaje;
                    volumen = dato.porcentaje;
                });*/
                $("#lblCultivo").html(especie + "<br>" + volumen + " ls [" + porcentaje + "%]");
                $("#divHorario").html(horario);
                graficarVolumen(porcentaje, volumen, seriesData);
            }
        },
        complete: function(data) {
            //alert("2");
            if (opcion === 2 && consulta_exitosa === 1) {
                //cargarDatos(2);
                startTime(tiempo);
                graficar_tiempo(tiempo, ll_ay, ri_ay);
                $("#divHorario").html(horario);
                ca_grafico.series[0].points[0].update(ca_ay * 1);
                hs_grafico.series[0].points[0].update(hs_ay * 1);
                hr_grafico.series[0].points[0].update(hr_ay * 1);
                ls_grafico.series[0].points[0].update(ls_ay * 1);
                ta_grafico.series[0].points[0].update(ta_ay * 1);
                $("#divSensores").show();
            }
        },
        error: function(msg) {
            //alert("3");
            console.log(msg);
        }
    });
}

function sensorDatos(opcion) {
    $.ajax({
        url: "dispositivoDatos.php",
        data: {
            opcion: opcion
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            var tabla = "";
            $.each(data, function(id, dato) {
                if ($.trim(dato.In) == "Error") {
                    $("#divEspera" + opcion).show();
                    $("#divEspera" + opcion).html("<center><img src='images/wifi.gif' height='100'><br>PERDIDA DE CONEXION<br>Espere...Reconectando</center>");
                } else {
                    ca_ay = dato.Ca;
                    hs_ay = dato.Hs;
                    hr_ay = dato.Ha;
                    ta_ay = dato.Ta;
                    ls_ay = dato.Lu;
                    ll_ay = dato.Ll;
                    ri_ay = dato.Ri;
                    tiempo = dato.Ac;
                    caudal = dato.Ca;
                    volumen = dato.Va;
                    if (opcion === 1) {
                        tabla += "<tr><th><font color='white'>INICIO (FECHA/HORA)</font></th><th>" + dato.In + "</th></tr>"
                        tabla += "<tr><th><font color='white'>CICLO (BUCLE)</font></th><th>" + dato.Ci + "</th></tr>"
                        tabla += "<tr><th><font color='white'>ACTUAL (FECHA/HORA)</font></th><th>" + dato.Ac + "</th></tr>"
                        tabla += "<tr><th><font color='white'>HUMEDAD RELATIVA (%)</font></th><th>" + dato.Ha + "</th></tr>"
                        tabla += "<tr><th><font color='white'>HUMEDAD DEL SUELO (%)</font></th><th>" + dato.Hs + "</th></tr>"
                        tabla += "<tr><th><font color='white'>TEMPERATURA AMBIENTE (°C) </font></th><th>" + dato.Ta + "</th></tr>"
                        tabla += "<tr><th><font color='white'>ILUMINACION CANTIDAD (%) </font></th><th>" + dato.Lu + "</th></tr>"
                        tabla += "<tr><th><font color='white'>LLUVIA DETECTADA (SI/NO)</font></th><th>" + dato.Ll + "</th></tr>"
                        tabla += "<tr><th><font color='white'>RIEGO ACTIVADO (SI/NO)</font></th><th>" + dato.Ri + "</th></tr>"
                        tabla += "<tr><th><font color='white'>CAUDAL AGUA</font></th><th>" + dato.Ca + "</th></tr>"
                        tabla += "<tr><th><font color='white'>VOLUMEN AGUA</font></th><th>" + dato.Va + "</th></tr>"
                    }
                    consulta_exitosa = 0;
                    if (opcion === 2) {
                        consulta_exitosa = 1;
                    }
                    $("#divEspera" + opcion).hide();
                }
            });
            $("#tblSensores").html(tabla);
        },
        complete: function(data) {
            if (opcion === 2 && consulta_exitosa === 1) {
                cargarDatos(2);
                /*startTime(tiempo);
                graficar_tiempo(tiempo, ll_ay, ri_ay);
                $("#divHorario").html(horario);
                ca_grafico.series[0].points[0].update(ca_ay * 1);
                hs_grafico.series[0].points[0].update(hs_ay * 1);
                hr_grafico.series[0].points[0].update(hr_ay * 1);
                ls_grafico.series[0].points[0].update(ls_ay * 1);
                ta_grafico.series[0].points[0].update(ta_ay * 1);
                $("#divSensores").show();*/
            }
        },
        error: function(msg) {
            //alert("3");
            console.log(msg);
        }
    });
}

function obtenerDatosArduino(accion) {
    clearInterval(intervalo);
    $("#tblSensores").html("");
    $("#divSensores").hide();
    var imagenEspera = "<center>" +
        "<br><br><img src='images/loading.gif' height='128'>" +
        "<br><br></center>";
    $("#divEspera" + accion).html(imagenEspera);
    $("#divEspera" + accion).show();
    intervalo = setInterval(sensorDatos, 10000, accion);
}

var intervalo = 0;

$('#lnUno').click(function(e) {
    obtenerDatosArduino(1);
});
$('#lnDos').click(function(e) {
    obtenerDatosArduino(2);
    cargarDatos(1);
    //luminosidad.series[0].points[0].update(($.trim(data).split("@@")[7]) * 1);
});
$('#lnTres').click(function(e) {
    clearInterval(intervalo);
    cargarDatos(1);
});

function graficar(etiqueta, descripcion, medida, min, max, nombre, inicio, fin) {
    var sensor = Highcharts.chart(etiqueta, {
        chart: {
            type: 'gauge',
            plotBackgroundColor: "white",
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            height: 200
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        title: {
            text: descripcion,
            style: {
                color: 'red',
                fontWeight: 'normal',
                fontSize: '0px',
                lineHeight: '10px'
            },
        },
        pane: {
            startAngle: -120,
            endAngle: 120,
            background: [{
                    backgroundColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0, '# FFF '],
                            [1, '#333']
                        ]
                    },
                    borderWidth: 0,
                    outerRadius: '109%'
                },
                {
                    backgroundColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0, '#333'],
                            [1, '#FFF']
                        ]
                    },
                    borderWidth: 1,
                    outerRadius: '107%'
                }, {
                    // default background
                }, {
                    backgroundColor: '#DDD',
                    borderWidth: 1,
                    outerRadius: '105%',
                    innerRadius: '103%'
                }
            ]
        },
        // the value axis
        yAxis: {
            min: inicio,
            max: fin,
            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',
            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: medida + '<br/>' + descripcion,
                style: {
                    color: 'red',
                    fontWeight: 'normal',
                    fontSize: '9px',
                    lineHeight: '10px'
                },
            },
            plotBands: [{
                from: inicio,
                to: fin,
                color: 'lightcoral' // red
            }, {
                from: min,
                to: max,
                color: 'lightgreen' // green
            }]
        },
        series: [{
            name: nombre,
            data: [0],
            tooltip: {
                valueSuffix: ' ' + medida
            }
        }]
    });
    return sensor;
}

var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octobre', 'Noviembre', 'Deciembre'];
var dias_semana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

function startTime(fecha_hora) {
    //var today = new Date();
    var fecha = fecha_hora.split(" ")[0];
    //alert(fecha_hora);
    //var hoy = new Date(fecha);
    var hr = parseInt(fecha_hora.split(" ")[1].split(":")[0]);
    var min = parseInt(fecha_hora.split(" ")[1].split(":")[1]);
    var sec = parseInt(fecha_hora.split(" ")[1].split(":")[2]);
    ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
    hr = (hr == 0) ? 12 : hr;
    hr = (hr > 12) ? hr - 12 : hr;
    //Add a zero in front of numbers<10
    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);
    $("#clock").html(hr + ":" + min + ":" + sec + " " + ap);
    //var curWeekDay = dias_semana[parseInt(hoy.getUTCDay())];
    var curDay = parseInt(fecha_hora.split(" ")[0].split("-")[2]);
    var curMonth = meses[parseInt(fecha_hora.split(" ")[0].split("-")[1]) - 1];
    var curYear = parseInt(fecha_hora.split(" ")[0].split("-")[0]);
    var date = curDay + " " + curMonth + " " + curYear;
    //document.getElementById("date").innerHTML = date; curWeekDay + ", " + curWeekDay + ", " + 
    $("#date").html(date);
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function graficar_tiempo(fecha_hora, lluvia, riego) {
    var hora = parseInt(fecha_hora.split(" ")[1].split(":")[0]);
    if (lluvia == "NO") { //SIN LLUVIA
        if (hora >= 18 || hora <= 6) { //NOCHE
            if ($("#imgTiempo").attr("src") != "images/noche_luna.png") {
                $("#imgTiempo").attr("src", "images/noche_luna.png");
                $("#imgTiempo").css("cursor", "pointer");
                $("#imgTiempo").attr("title", "NOCHE SIN LUVIA");
            }
        } else { //DIA
            if ($("#imgTiempo").attr("src") != "images/dia_sol.png") {
                $("#imgTiempo").attr("src", "images/dia_sol.png");
                $("#imgTiempo").css("cursor", "pointer");
                $("#imgTiempo").attr("title", "DIA SIN LLUVIA");
            }
        }
    } else { //LLUVIA
        if (hora >= 18 || hora <= 6) { //NOCHE
            if ($("#imgTiempo").attr("src") != "images/noche_lluvia.png") {
                $("#imgTiempo").attr("src", "images/noche_lluvia.png");
                $("#imgTiempo").css("cursor", "pointer");
                $("#imgTiempo").attr("title", "NOCHE CON LLUVIA");
            }
        } else { //DIA
            if ($("#imgTiempo").attr("src") != "images/dia_lluvia.png") {
                $("#imgTiempo").attr("src", "images/dia_lluvia.png");
                $("#imgTiempo").css("cursor", "pointer");
                $("#imgTiempo").attr("title", "DIA CON LLUVIA");
            }
        }
    }
    if (riego == "SI") { //ENCENDIDO
        $("#imgElectrovalvula").attr("src", "images/on.png");
        $("#imgElectrovalvula").css("cursor", "pointer");
        $("#imgElectrovalvula").attr("title", "ELECTROVALVULA ENCENDIDA");
    } else { //APAGADO
        $("#imgElectrovalvula").attr("src", "images/off.png");
        $("#imgElectrovalvula").css("cursor", "pointer");
        $("#imgElectrovalvula").attr("title", "ELECTROVALVULA APAGADA");
    }

    //console.log(datoNombre);
    $("#lblCultivo").show();
    $("#spActivo, #spEnEspera, #spFueraHorario, #spDesactivado").hide();
    if (datoNombre != "@@@") {
        if (datoNombre == "Desactivado") {
            $("#lblCultivo").hide();
            $("#spDesactivado").show();
            console.log("1");
        } else {
            console.log("2");
            if (riego == "NO") {
                console.log("3");
                if (habilitado == "1") {
                    console.log("4");
                    $("#spEnEspera").show();
                } else {
                    console.log("5");
                    $("#spFueraHorario").show();
                }
            } else {
                console.log("6");
                $("#spActivo").show();
            }
        }
    }
}

function verHuertoFoto() {
    $.ajax({
        url: "/arduino/foto",
        dataType: "html",
        method: "POST",
        success: function(result) {
            console.log($.trim(result));
            setTimeout(function() {
                mostrarFoto();
            }, 6500);
            //$("#lnImagen").click();
        },
        error: function(error) {
            setTimeout(function() {
                mostrarFoto();
            }, 6500);
            console.log("Error al obtener la imagen!");
        }
    });
}

function verHuertoVideo() {
    $("#frmVideo").attr("src", "");
    $.ajax({
        url: "/arduino/iniciar_video",
        dataType: "html",
        method: "POST",
        success: function(result) {
            console.log($.trim(result));
            mostrarVideo();
        },
        error: function(error) {
            mostrarVideo();
            console.log("Error enlace Video!");
        }
    });
}

$("#btnImagen").click(function(e) {
    inicializarWebcam();
    video_activado = 0;
    verHuertoFoto();
});

$("#btnVideo").click(function(e) {
    inicializarWebcam()
    video_activado = 1;
    verHuertoVideo();
});

$("#btnVideoApagar").click(function(e) {
    $.ajax({
        url: "/arduino/apagar_video",
        dataType: "html",
        method: "POST"
    });
    $("#divIframe").hide();
    $("#btnVideoApagar").hide();
    $("[name=btnWebcam]").show();
    video_activado = 0;
});

function inicializarWebcam() {
    $("#divIframe").hide();
    var imagenEspera = "<center>" +
        "<br><br><img src='images/cargando_4.gif' height='128'>" +
        "<br><br></center>";
    $("#divEspera3").html(imagenEspera);
    $("#divEspera3").show();
    //$("#lnHuerto").attr("src", "");
    $("#frmVideo").attr("src", "");
    $("[name=btnWebcam]").hide();
    $("#imgFoto").attr("src", "");
    $("#imgFoto").hide();
}

function mostrarFoto() {
    $("#imgFoto").attr("src", "imagen.png?ht=" + Math.random());
    $("#divEspera3").hide();
    $("#imgFoto").show();
    $("[name=btnWebcam]").show();
    cargarDatos(3);
}

function mostrarVideo() {
    $("#divEspera3").hide();
    $("#frmVideo").attr("src", "webcam_server.php");
    $("#divIframe").show();
    $("#btnVideoApagar").show();
    cargarDatos(4);
}

function graficarVolumen(porcentaje, volumen, seriesData) {
    //Highcharts.chart(etiqueta, {
    var chartSpeed = Highcharts.chart('divVolumen', {
        chart: {
            type: 'column',
            plotBackgroundColor: "white",
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            height: 200
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [],
            visible: false
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Riegos Realizados (ml)'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false,
            enabled: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}',
            enabled: false
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false
                }
            }
        },
        series: [{}]
    });
    chartSpeed.series[0].setData(seriesData);
}