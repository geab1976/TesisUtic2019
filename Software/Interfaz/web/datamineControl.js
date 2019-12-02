var motivo = "<option value='0'>TODO</option>";
var colors = ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
var datosObtenidos = "";

function cargarDatos(opcion) {
    $("#divEspera1").show();
    $("#tabs2").hide();
    $.ajax({
        url: "datamineDatos.php",
        data: {
            opcion: opcion,
            id_especie: $("#id_especie").val(),
            fecha_desde: $("#fecha_desde").val(),
            fecha_hasta: $("#fecha_hasta").val()
        },
        dataType: "json",
        method: "POST",
        success: function(data) {
            if (opcion === 5) {
                var lista = $("#id_especie").html("");
                $.each(data, function(id, dato) {
                    //alert(activar[(dato.resumen_activar)*1-1]);
                    lista.append("" +
                        "<option value='" + dato.id_especie + "'>" + dato.especie + "</option>"
                    );
                });
                lista.selectmenu('refresh', true);
            }
            if (opcion < 5) {
                datosObtenidos = data;
                $("#divEspera1").hide();
                $("#tabs2").show();
                procesarDatosGraficos(1);
            }
        },
        complete: function(data) {
            //alert("2");
        },
        error: function(msg) {
            //alert("3");
            console.log(msg);
        }
    });
}

cargarDatos(5);

function validarCampos(opcion) {

}

$("#fecha_desde").val(obtenerFechaHoy());
$("#fecha_hasta").val(obtenerFechaHoy());

function obtenerFechaHoy() {
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var hoy = (dia < 10 ? '0' : '') + dia + '/' + (mes < 10 ? '0' : '') + mes + '/' +
        fecha.getFullYear();
    return hoy;
}

$("#lnDos").click(function(e) {
    cargarDatos($("#accion").val());
});


function procesarDatosGraficos(grafico) {
    //console.log($.trim(data));
    var cantidad = datosObtenidos.length;
    var contado = 0;
    var datos_x = "";
    var datos_1y1 = "";
    var datos_1y2 = "";
    var datos_1y3 = "";
    var datos_1y4 = "";

    var datos_2y1 = "";
    var datos_2y2 = "";
    var datos_2y3 = "";
    var datos_2y4 = "";
    var datos_2y5 = "";
    var datos_2y6 = "";

    var datos_3y1 = "";
    var datos_3y2 = "";
    var datos_3y3 = "";
    var datos_3y4 = "";
    var datos_3y5 = "";
    var datos_3y6 = "";

    var datos_4y1 = "";
    var datos_4y2 = "";
    var datos_4y3 = "";
    var datos_4y4 = "";
    var datos_4y5 = "";
    var datos_4y6 = "";

    var seriesData1 = [];
    var seriesData2 = [];
    var seriesData3 = [];
    var seriesData4 = [];

    $.each(datosObtenidos, function(id, dato) {
        contado++;

        seriesData1.push([dato.eje_x, dato.agua]);
        seriesData2.push([dato.eje_x, dato.ll]);
        seriesData3.push([dato.eje_x, dato.veces]);
        seriesData4.push([dato.eje_x, dato.importe]);

        datos_x += dato.eje_x + (contado === cantidad ? "" : ",");

        datos_1y1 += dato.agua + (contado === cantidad ? "" : ",");
        datos_1y2 += dato.ll + (contado === cantidad ? "" : ",");
        datos_1y3 += dato.veces + (contado === cantidad ? "" : ",");
        datos_1y4 += dato.importe + (contado === cantidad ? "" : ",");

        datos_2y1 += dato.hsi + (contado === cantidad ? "" : ",");
        datos_2y2 += dato.hsi_min + (contado === cantidad ? "" : ",");
        datos_2y3 += dato.hsi_max + (contado === cantidad ? "" : ",");
        datos_2y4 += dato.hsf + (contado === cantidad ? "" : ",");
        datos_2y5 += dato.hsf_min + (contado === cantidad ? "" : ",");
        datos_2y6 += dato.hsf_max + (contado === cantidad ? "" : ",");

        datos_3y1 += dato.tai + (contado === cantidad ? "" : ",");
        datos_3y2 += dato.tai_min + (contado === cantidad ? "" : ",");
        datos_3y3 += dato.tai_max + (contado === cantidad ? "" : ",");
        datos_3y4 += dato.taf + (contado === cantidad ? "" : ",");
        datos_3y5 += dato.taf_min + (contado === cantidad ? "" : ",");
        datos_3y6 += dato.taf_max + (contado === cantidad ? "" : ",");

        datos_4y1 += dato.lsi + (contado === cantidad ? "" : ",");
        datos_4y2 += dato.lsi_min + (contado === cantidad ? "" : ",");
        datos_4y3 += dato.lsi_max + (contado === cantidad ? "" : ",");
        datos_4y4 += dato.lsf + (contado === cantidad ? "" : ",");
        datos_4y5 += dato.lsf_min + (contado === cantidad ? "" : ",");
        datos_4y6 += dato.lsf_max + (contado === cantidad ? "" : ",");
    });
    if (grafico === 1) {
        generarGrafico2(
            "divAgua", "Agua - Lluvia - Riego",
            "Datos de Inicio/Fin de Riego del " +
            $("#fecha_desde").val() + " al " + $("#fecha_hasta").val() +
            " [" + $("#accion>option:selected").text() + "]",
            datos_x, "Mililitros (ml)",
            " litros", 1, "Suministro de Agua", "column", datos_1y1,
            " ", 2, "Lluvia Detectada", "column", datos_1y2,
            " ", 3, "Riegos", "column", datos_1y3,
            " Gs.", 4, "Importes", "column", datos_1y4
        );
        graficarTorta("divSuministrado", "Agua Suministrada", seriesData1);
        graficarTorta("divLluvias", "Lluvias Detectadas", seriesData2);
        graficarTorta("divRiegos", "Riegos Realizados", seriesData3);
        graficarTorta("divImportes", "Importe Gs.", seriesData4);
    }

    if (grafico === 2) {
        generarGrafico(
            "divHS", "Humedad del Suelo",
            "Datos de Inicio/Fin de Riego del " +
            $("#fecha_desde").val() + " al " + $("#fecha_hasta").val() +
            " [" + $("#accion>option:selected").text() + "]",
            datos_x, "Porcentual (%)",
            " %", 1, "Inicio---> H. S. Promedio", "spline", datos_2y1,
            " %", 8, "Inicio---> H. S. R. Máximo", "spline", datos_2y2,
            " %", 3, "Inicio---> H. S. R. Mínimo", "spline", datos_2y3,
            " %", 4, "Fin------> H. S. Promedio", "spline", datos_2y4,
            " %", 5, "Fin------> H. S. R. Máximo", "spline", datos_2y5,
            " %", 6, "Fin------> H. S. R. Mínimo", "spline", datos_2y6
        );
    }

    if (grafico === 3) {
        generarGrafico(
            "divTA", "Temperatura Ambiente",
            "Datos de Inicio/Fin de Riego del " +
            $("#fecha_desde").val() + " al " + $("#fecha_hasta").val() +
            " [" + $("#accion>option:selected").text() + "]",
            datos_x, "Grados Celsius (°C)",
            " °C", 1, "Inicio---> T. A. Promedio", "spline", datos_3y1,
            " °C", 2, "Inicio---> T. A. R. Máximo", "spline", datos_3y2,
            " °C", 3, "Inicio---> T. A. R. Mínimo", "spline", datos_3y3,
            " °C", 4, "Fin------> T. A. Promedio", "spline", datos_3y4,
            " °C", 8, "Fin------> T. A. R. Máximo", "spline", datos_3y5,
            " °C", 6, "Fin------> T. A. R. Mínimo", "spline", datos_3y6
        );
    }

    if (grafico === 4) {
        generarGrafico(
            "divLS", "Iluminación Ambiente",
            "Datos de Inicio/Fin de Riego del " +
            $("#fecha_desde").val() + " al " + $("#fecha_hasta").val() +
            " [" + $("#accion>option:selected").text() + "]",
            datos_x, "Porcentual (%)",
            " %", 1, "Inicio---> I. A. Promedio", "spline", datos_4y1,
            " %", 2, "Inicio---> I. A. R. Máximo", "spline", datos_4y2,
            " %", 3, "Inicio---> I. A. R. Mínimo", "spline", datos_4y3,
            " %", 4, "Fin------> I. A. Promedio", "spline", datos_4y4,
            " %", 8, "Fin------> I. A. R. Máximo", "spline", datos_4y5,
            " %", 6, "Fin------> I. A. R. Mínimo", "spline", datos_4y6
        );
    }
}

function generarGrafico(
    contenedor, titulo, subtitulo, valor_eje_x, eje_y_nombre,
    medida1, color1, nombre_eje_y1, tipo_grafico_1, valor_eje_y1, //Promedio Inicio
    medida2, color2, nombre_eje_y2, tipo_grafico_2, valor_eje_y2, //Mínimo Inicio
    medida3, color3, nombre_eje_y3, tipo_grafico_3, valor_eje_y3, //Máximo Inicio
    medida4, color4, nombre_eje_y4, tipo_grafico_4, valor_eje_y4, //Promedio Fin
    medida5, color5, nombre_eje_y5, tipo_grafico_5, valor_eje_y5, //Minimo Fin
    medida6, color6, nombre_eje_y6, tipo_grafico_6, valor_eje_y6 //Máximo Fin
) {
    Highcharts.chart(contenedor, {
        chart: {
            zoomType: 'xy',
            marginTop: 120,
            plotBackgroundColor: "white",
            plotBackgroundImage: null,
            plotBorderWidth: 1,
            plotShadow: false
        },
        credits: {
            enabled: false
        },
        title: {
            text: titulo
        },
        subtitle: {
            text: subtitulo
        },
        xAxis: [{
            categories: valor_eje_x.split(","),
            crosshair: false
        }],
        yAxis: [{ // 1 yAxis
            title: {
                text: eje_y_nombre,
                style: {
                    color: colors[color1]
                }
            },
            labels: {
                format: '{value}' + medida1,
                style: {
                    color: colors[color1]
                }
            },
            visible: true
        }, { // 2 yAxis
            title: {
                text: eje_y_nombre,
                style: {
                    color: colors[color2]
                }
            },
            labels: {
                format: '{value} ' + medida2,
                style: {
                    color: colors[color2]
                }
            },
            visible: false
        }, { // 3 yAxis
            title: {
                text: eje_y_nombre,
                style: {
                    color: colors[color3]
                }
            },
            labels: {
                format: '{value} ' + medida3,
                style: {
                    color: colors[color3]
                }
            },
            visible: false
        }, { // 4 yAxis
            title: {
                text: eje_y_nombre,
                style: {
                    color: colors[color4]
                }
            },
            labels: {
                format: '{value} ' + medida4,
                style: {
                    color: colors[color4]
                }
            },
            opposite: false,
            visible: false
        }, { // 5 yAxis
            title: {
                text: eje_y_nombre,
                style: {
                    color: colors[color5]
                }
            },
            labels: {
                format: '{value} ' + medida5,
                style: {
                    color: colors[color5]
                }
            },
            opposite: false,
            visible: false
        }, { // 6 yAxis
            title: {
                text: eje_y_nombre,
                style: {
                    color: colors[color6]
                }
            },
            labels: {
                format: '{value} ' + medida6,
                style: {
                    color: colors[color6]
                }
            },
            opposite: false,
            visible: false
        }],
        tooltip: {
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            floating: true,
            x: 0,
            y: 55
        },
        series: [{ //1
            name: nombre_eje_y1,
            type: tipo_grafico_1,
            data: valor_eje_y1.split(",").map(Number),
            tooltip: {
                valueSuffix: ' ' + medida1
            }

        }, { //2
            name: nombre_eje_y2,
            type: tipo_grafico_2,
            data: valor_eje_y2.split(",").map(Number),
            tooltip: {
                valueSuffix: medida2
            }
        }, { //3
            name: nombre_eje_y3,
            type: tipo_grafico_3,
            data: valor_eje_y3.split(",").map(Number),
            tooltip: {
                valueSuffix: medida3
            }
        }, { //4
            name: nombre_eje_y4,
            type: tipo_grafico_4,
            data: valor_eje_y4.split(",").map(Number),
            tooltip: {
                valueSuffix: medida4
            }
        }, { //5
            name: nombre_eje_y5,
            type: tipo_grafico_5,
            data: valor_eje_y5.split(",").map(Number),
            tooltip: {
                valueSuffix: medida5
            }
        }, { //6
            name: nombre_eje_y6,
            type: tipo_grafico_6,
            data: valor_eje_y6.split(",").map(Number),
            tooltip: {
                valueSuffix: medida6
            }
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        className: 'small-chart'
                    },
                    chart: {
                        marginTop: 180,
                        plotShadow: true
                    }
                }
            }]
        }
    });
}

function generarGrafico2(
    contenedor, titulo, subtitulo, valor_eje_x, eje_y_nombre,
    medida1, color1, nombre_eje_y1, tipo_grafico_1, valor_eje_y1, //Promedio Inicio
    medida2, color2, nombre_eje_y2, tipo_grafico_2, valor_eje_y2,
    medida3, color3, nombre_eje_y3, tipo_grafico_3, valor_eje_y3,
    medida4, color4, nombre_eje_y4, tipo_grafico_4, valor_eje_y4
) {
    Highcharts.chart(contenedor, {
        chart: {
            zoomType: 'xy',
            marginTop: 100,
            plotBackgroundColor: "white",
            plotBackgroundImage: null,
            plotBorderWidth: 1,
            plotShadow: false
        },
        credits: {
            enabled: false
        },
        title: {
            text: titulo,
            align: 'center'
        },
        subtitle: {
            text: subtitulo,
            align: 'center'
        },
        xAxis: [{
            categories: valor_eje_x.split(","),
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            title: {
                text: 'Valores',
                style: {
                    color: Highcharts.getOptions().colors[color1]
                }
            },
            labels: {
                format: '{value}' + medida1,
                style: {
                    color: Highcharts.getOptions().colors[color1]
                }
            },
            opposite: false,
            visible: true

        }, { // Secondary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[color2]
                }
            },
            labels: {
                format: '{value}' + medida2,
                style: {
                    color: Highcharts.getOptions().colors[color2]
                }
            },
            opposite: true,
            visible: true
        }, { // tercery yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[color3]
                }
            },
            labels: {
                format: '{value}' + medida3,
                style: {
                    color: Highcharts.getOptions().colors[color3]
                }
            },
            opposite: true,
            visible: true
        }, { // cuarto yAxis
            //gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[color4]
                }
            },
            labels: {
                format: '{value}' + medida4,
                style: {
                    color: Highcharts.getOptions().colors[color4]
                }
            },
            opposite: true,
            visible: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            floating: true,
            x: 0,
            y: 55
        },
        series: [{
                name: nombre_eje_y1,
                type: tipo_grafico_1,
                yAxis: 1,
                data: valor_eje_y1.split(",").map(Number),
                tooltip: {
                    valueSuffix: ' ' + medida1
                }

            }, {
                name: nombre_eje_y2,
                type: tipo_grafico_2,
                yAxis: 1,
                data: valor_eje_y2.split(",").map(Number),
                marker: {
                    enabled: false
                },
                dashStyle: 'Solid',
                tooltip: {
                    valueSuffix: ' ' + medida2
                }
            },
            {
                name: nombre_eje_y3,
                type: tipo_grafico_3,
                yAxis: 1,
                data: valor_eje_y3.split(",").map(Number),
                marker: {
                    enabled: false
                },
                dashStyle: 'Solid',
                tooltip: {
                    valueSuffix: ' ' + medida3
                }
            },
            {
                name: nombre_eje_y4,
                type: tipo_grafico_4,
                yAxis: 1,
                data: valor_eje_y4.split(",").map(Number),
                marker: {
                    enabled: false
                },
                dashStyle: 'Solid',
                tooltip: {
                    valueSuffix: ' ' + medida4
                }
            }
        ],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        className: 'small-chart'
                    },
                    chart: {
                        marginTop: 120,
                        plotShadow: true
                    }
                }
            }]
        }
    });
}


function graficarTorta(etiqueta, titulo, seriesData) {
    var chart = Highcharts.chart(etiqueta, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        credits: {
            enabled: false
        },
        title: {
            text: titulo
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}% ({point.y})</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Agua Suministrada',
            colorByPoint: true,
            data: [{}]
        }]
    });
    chart.series[0].setData(seriesData, true);
}

/*
 * We need to turn it into a function.
 * To apply the changes both on document ready and when we resize the browser.
 */
function mediaSize() {
    /* Set the matchMedia */
    if (window.matchMedia('(min-width: 800px)').matches) {
        /* Changes when we reach the min-width  */
        $('#lnAgua').html("AGUA - LLUVIA - RIEGO");
        $('#lnHumedad').html("HUMEDAD SUELO");
        $('#lnTemperatura').html("TEMPERATURA AMB.");
        $('#lnIluminacion').html("ILUMINACIÓN AMB.");
    } else {
        /* Reset for CSS changes – Still need a better way to do this! */
        $('#lnAgua').html("AGUA");
        $('#lnHumedad').html("H. S.");
        $('#lnTemperatura').html("T. A.");
        $('#lnIluminacion').html("I. A.");
    }
};

/* Call the function */
mediaSize();
/* Attach the function to the resize event listener */
window.addEventListener('resize', mediaSize, false);

$("#lnAgua").click(function() {
    procesarDatosGraficos(1);
});

$("#lnHumedad").click(function() {
    procesarDatosGraficos(2);
});

$("#lnTemperatura").click(function() {
    procesarDatosGraficos(3);
});

$("#lnIluminacion").click(function() {
    procesarDatosGraficos(4);
});

$("#lnUno").addClass("ui-btn-active");
$("#lnAgua").addClass("ui-btn-active");

$("#fecha_desde").on("change", function() { //.bind( "change",
    if ($("#fecha_hasta").val().length === 0) {
        $("#fecha_hasta").val($("#fecha_desde").val());
    }
    var inicio = ($("#fecha_desde").val()).split("-");
    var fin = ($("#fecha_hasta").val()).split("-");
    var f1 = new Date(parseInt(inicio[0]), parseInt(inicio[1] - 1), parseInt(inicio[2]));
    var f2 = new Date(parseInt(fin[0]), parseInt(fin[1] - 1), parseInt(fin[2]));
    if (f1 > f2) {
        $("#fecha_hasta").val($("#fecha_desde").val());
    }
});

$("#fecha_hasta").on("change", function() { //.bind( "change",
    if ($("#fecha_desde").val().length === 0) {
        $("#fecha_desde").val($("#fecha_hasta").val());
    }
    var inicio = ($("#fecha_desde").val()).split("-");
    var fin = ($("#fecha_hasta").val()).split("-");
    var f1 = new Date(parseInt(inicio[0]), parseInt(inicio[1] - 1), parseInt(inicio[2]));
    var f2 = new Date(parseInt(fin[0]), parseInt(fin[1] - 1), parseInt(fin[2]));
    if (f2 < f1) {
        $("#fecha_desde").val($("#fecha_hasta").val());
    }
});