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
Phx.vista.CorrespondenciaFisicaRecibido = {
    bsave:false,
    bnew:false,
	bedit:false,
	bdel:false,
    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'CorrespondenciaFisicaRecibido',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaFisicaRecibido',
	
	constructor: function(config) {
	    Phx.vista.CorrespondenciaFisicaRecibido.superclass.constructor.call(this,config);

		this.addButton('finalizarRecibido', {
			text: 'Finalizar Recepcion',
			iconCls: 'bgood',
			disabled: true,
			handler: this.finalizarRecepcion,
			tooltip: '<b>finalizarRecibido</b><br/>Permite finalizar la recepcion'
		});		
		this.init();
        this.store.baseParams = {'interface': 'fisica_recibida'};
        this.load({params: {start: 0, limit: 50}})	  
   },
    preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaFisicaRecibido.superclass.preparaMenu.call(this,n);      	
		var data = this.getSelectedData();
		var tb =this.tbar;
	  	//si el archivo esta escaneado se permite visualizar
	  	if(data['version']>0){
	  	   this.getBoton('verCorrespondencia').enable();
	       this.getBoton('mandar').enable()
	       this.getBoton('finalizarRecibido').enable();
  		}
  		else{
  			this.getBoton('verCorrespondencia').enable(); //aqui esta disable
  			this.getBoton('mandar').enable(); //aqui tambien
			this.getBoton('finalizarRecibido').enable();	  			
  		}
		return tb		
	},
	finalizarRecepcion:function(){
		var rec = this.sm.getSelected();
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/finalizarRecepcion',
			params: {
				id_correspondencia: rec.data.id_correspondencia
			},
			success: this.successFinalizar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});
	},
	
	successFinalizar:function(resp){
		this.load({params: {start: 0, limit: 50}});
	},
	
	archivar:function(){
		var rec = this.sm.getSelected();
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/archivarCorrespondencia',
			params: {
				id_correspondencia: rec.data.id_correspondencia,
				sw_archivado :'si'
			},
			success: this.successFinalizar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});
	}
	
};
</script>
