<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (fprudencio)
*@date 20-09-2011 10:22:05
*@description Archivo con la interfaz de usuario que permite 
*dar el visto a solicitudes de compra
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CorrespondenciaFisicaEmitida = {
    bsave:false,
    bnew:false,
	bedit:false,
	bdel:false,
    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'CorrespondenciaFisicaEmitida',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaFisicaEmitida',
	
	constructor: function(config) {
	    Phx.vista.CorrespondenciaFisicaEmitida.superclass.constructor.call(this,config);

		this.addButton('enviarFisico', {
			text: 'Enviar Fisico',
			iconCls: 'bgood',
			disabled: true,
			handler: this.enviarFisico,
			tooltip: '<b>enviarFisico</b><br/>Permite confirmar que si estas enviando esta correspondencia'
		});


		this.getBoton('VerDocumento').hide();
		this.getBoton('Derivar').hide();
		this.getBoton('Adjuntos').hide();
		this.getBoton('Corregir').hide();
		this.getBoton('HojaRuta').hide();
		
		this.init();
        this.store.baseParams = {'interface': 'fisica_emitida'};
        this.load({params: {start: 0, limit: 50}})


   },
	preparaMenu:function(n){

		Phx.vista.CorrespondenciaFisicaEmitida.superclass.preparaMenu.call(this,n);
		var data = this.getSelectedData();

		console.log('data',data)
		var tb =this.tbar;
		//si el archivo esta escaneado se permite visualizar
		if(data.id_correspondencia){
			this.getBoton('enviarFisico').enable();

		}
		else{
			this.getBoton('enviarFisico').disabled(); //aqui esta disable


		}


		return tb

	},

    enviarFisico:function() {
		var rec = this.sm.getSelected();

		console.log(rec);
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/cambiarEstadoCorrespondenciaFisica',
			params: {
				id_correspondencia: rec.data.id_correspondencia,
				estado_fisico: 'despachado', //el estado que queremos que cambie

			},
			success: this.successFinalizar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});


	}
	
	
	
   
	
	
};
</script>
