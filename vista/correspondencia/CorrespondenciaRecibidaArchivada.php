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
Phx.vista.CorrespondenciaRecibidaArchivada = {
    bsave:false,
    bnew:false,
	bedit:false,
	bdel:false,
    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida Archivada',
	nombreVista: 'CorrespondenciaRecibidaArchivada',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaRecibidaArchivada',
	
	constructor: function(config) {
	    Phx.vista.CorrespondenciaRecibidaArchivada.superclass.constructor.call(this,config);



		this.addButton('DesArchivar', {
			text: 'DesArchivar',
			iconCls: 'bsave',
			disabled: false,
			handler: this.DesArchivar,
			tooltip: '<b>DesArchivar</b><br/>'
		});
		
		this.init();
        this.store.baseParams = {'interface': 'recibida_archivada'};
        this.load({params: {start: 0, limit: 50}})
	  
    
   },
    preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaRecibidaArchivada.superclass.preparaMenu.call(this,n);
		  var data = this.getSelectedData();

		console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar


	 
		 return tb
		
	},finalizarRecepcion:function(){
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


		console.log(resp)
	},
	DesArchivar:function(){
		var rec = this.sm.getSelected();

		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/archivarCorrespondencia',
			params: {
				id_correspondencia: rec.data.id_correspondencia,
				sw_archivado :'no'
			},
			success: this.successFinalizar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});
	}
	
	
	
   
	
	
};
</script>
