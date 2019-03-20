<style type="text/css" >
	#titleH1{
		font-size: 24px;
		margin: 10px 10px 0px 10px;		
	}
	.columnaPie{
		width: 49.5%;
		display: inline-block;
		margin-top: 10px
	}
	#comparativo{
		margin-top: 18px;
		display: inline-block;
		width: 100%
	}
</style>
<h1 id="titleH1">Cuadros Estadisticos</h1>
<div>
	<span class="columnaPie" style="float: right">
		<div id="internas" ></div>
	</span>
	<span class="columnaPie" style="float: left">
		<div id="externas" ></div>
	</span>
</div>

<div id="comparativo" ></div>

<script>

	Ext.Ajax.request({
	    url:'../../sis_correspondencia/control/Reporte/reportesEstadisticos',
	    params:{'proceso':1},
	    success:function(res){ 

	    	var internas = [];
			var externas = [];

	    	var res1 = JSON.parse(res.responseText);

	    	res1.datos.forEach(function(data){
	    		if(data.tipo == "interna"){
	    			internas.push(data);
	    		}

	    		if(data.tipo == "externa"){
	    			externas.push(data);
	    		}
	    	});

    		generarEstadisticos(internas, externas);

	    },
	    failure: function(data){

	    },
	    timeout:function(data){

	    },
	    scope:this
	}); 



function generarEstadisticos(internas, externas)
{

	Highcharts.setOptions({
	    lang: {
	        downloadCSV:"Descargar CSV",
			downloadJPEG:"Descargar JPEG imagen",
			downloadPDF:"Descargar PDF documento",
			downloadPNG:"Descargar PNG imagen",
			downloadSVG:"Descargar SVG vector de imagen",
			downloadXLS:"Descargar XLS",
			printChart:"Imprimir"
	    }
	});

	var internasData = generarDataParaInternas(internas); //data manipulada para los la torta de internas

	// Generacion del cuadro estadistico torta de Internas
	Highcharts.chart('internas', {
	    chart: {
	        plotBackgroundColor: null,
	        plotBorderWidth: null,
	        plotShadow: false,
	        type: 'pie'
	    },
	    title: {
	        text: 'Correspondencias Internas'
	    },
	    tooltip: {
	        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	    },
	    plotOptions: {
	        pie: {
	            allowPointSelect: true,
	            cursor: 'pointer',
	            dataLabels: {
	                enabled: true,
	                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
	                style: {
	                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
	                }
	            }
	        }
	    },
	    series: [{
	        name: 'Correspondencia',
	        colorByPoint: true,
	        data: internasData
	    }]
	});





	var externasData = generarDataParaExternas(externas);//data manipulada para los la torta de externas

	// Generacion del cuadro estadistico torta de Externas
	Highcharts.chart('externas', {
	    chart: {
	        plotBackgroundColor: null,
	        plotBorderWidth: null,
	        plotShadow: false,
	        type: 'pie'
	    },
	    title: {
	        text: 'Correspondencias Externas'
	    },
	    tooltip: {
	        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	    },
	    plotOptions: {
	        pie: {
	            allowPointSelect: true,
	            cursor: 'pointer',
	            dataLabels: {
	                enabled: true,
	                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
	                style: {
	                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
	                }
	            }
	        }
	    },
	    series: [{
	        name: 'Correspondencia',
	        colorByPoint: true,
	        data: externasData
	    }]
	});




	var accionesData    = generarDataParaNombresDeAcciones(internas,externas); // lista de todas las acciones de internas y externas unidas
	var comparativoData = generarDataParaComparativo(internas,externas, accionesData); //data manipulada para las barras comparativas

		console.log(comparativoData);
	Highcharts.chart('comparativo', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Cuadro Comparativo'
	    },
	    subtitle: {
	        text: 'Recibidas Vs Emitidas'
	    },
	    xAxis: {
	        crosshair: true,
	        categories: accionesData
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Cantidad'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:15px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0;color:black"><b>{point.y:.1f}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: comparativoData
	});
}

function generarDataParaInternas(internas)
{
	var nuevaData =[];
	var total = 0;
	
	internas.forEach(function(data){
		total = parseInt(data.cantidad)+total;
	});

	internas.forEach(function(data){
		nuevaData.push({name: data.nombre, y: parseInt(data.cantidad)/total});
	});
	return nuevaData;
}

function generarDataParaExternas(externas)
{
	var nuevaData =[];
	var total = 0;
	
	externas.forEach(function(data){
		total = parseInt(data.cantidad)+total;
	});

	externas.forEach(function(data){
		nuevaData.push({name: data.nombre, y: parseInt(data.cantidad)/total});
	});
	return nuevaData;
}

function generarDataParaNombresDeAcciones(internas,externas)
{
	var todosTipos = [];
	internas.forEach(function(data){
		todosTipos.push(data.nombre);
	});
	externas.forEach(function(data){
		todosTipos.push(data.nombre);
	});
	todosTipos.sort();
	todosTipos = todosTipos.filter((valor, indiceActual, arreglo) => arreglo.indexOf(valor) === indiceActual);
	return todosTipos;
}

function generarDataParaComparativo(internas,externas, acciones)
{
	var arregloInternas = [];
	var arregloExternas = [];
	acciones.forEach(function(dataAcciones){

		var cantidadI = 0;
		internas.forEach(function(dataInterna){
			if(dataAcciones==dataInterna.nombre)			
				cantidadI = parseInt(dataInterna.cantidad);
		});
		arregloInternas.push(cantidadI);

		var cantidadE = 0;
		externas.forEach(function(dataExterna){
			if(dataAcciones==dataExterna.nombre)
				cantidadE = parseInt(dataExterna.cantidad);
		});
		arregloExternas.push(cantidadE);

	});

	return [{
	        name: 'Interna',
	        data: arregloInternas,
	        color: "#ef5f5f"

	    }, {
	        name: 'Externa',
	        data: arregloExternas,
	        color: "#6ea6ef"
	    }];
}


</script>