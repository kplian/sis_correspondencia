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
Phx.vista.CorrespondenciaRecibida = {
	
    bsave:false,
    bnew:false,
	bedit:false,
	bdel:false,
    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Recibida',
	nombreVista: 'CorrespondenciaRecibida',
	swTipo: 'externa',
	
	beditGroups: [0, 1],
	bactGroups: [0, 1],
	btestGroups: [0,1],
	bexcelGroups: [0, 1],
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaRecibida',
	
	constructor: function(config) {
		//this.Atributos[this.getIndAtributo('id_funcionario')].grid=false;
		
		
		this.Atributos[this.getIndAtributo('id_documento')].grid=false;
		this.Atributos[this.getIndAtributo('id_uo')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionario_saliente')].grid=false;
        this.Atributos[this.getIndAtributo('id_institucion_destino')].grid=false;
        this.Atributos[this.getIndAtributo('id_persona_destino')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionarios')].grid=false;
        this.Atributos[this.getIndAtributo('asociar')].grid=false;
        this.Atributos[this.getIndAtributo('id_correspondencias_asociadas')].grid=false;
        this.Atributos[this.getIndAtributo('observaciones_archivado')].grid=false;
         
         if (config.tipo=='interna'|| config.aux=='interna'){
        	this.Atributos[this.getIndAtributo('cite')].grid=false;
		    this.Atributos[this.getIndAtributo('id_institucion_remitente')].grid=false;
		    this.Atributos[this.getIndAtributo('id_persona_remitente')].grid=false;
		    this.Atributos[this.getIndAtributo('otros_adjuntos')].grid=false;
		    this.Atributos[this.getIndAtributo('nro_paginas')].grid=false;
		    this.Atributos[this.getIndAtributo('id_correspondencias_asociadas')].grid=true;
		    this.Atributos[this.getIndAtributo('id_documento')].grid=true;
		    this.Atributos[this.getIndAtributo('id_uo')].grid=true;
		   // this.Atributos[this.getIndAtributo('persona_firma')].grid=false;
			//this.Atributos[this.getIndAtributo('tipo_documento')].grid=false;
	      }
	    Phx.vista.CorrespondenciaRecibida.superclass.constructor.call(this,config);
	     //this.bloquearOrdenamientoGrid();
           this.getBoton('Plantilla').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
             this.getBoton('Finalizar').hide(); 
             this.getBoton('Habilitar').hide();
		
		
		this.init();
	    this.store.baseParams = {'interface': 'recibida','tipo': this.tipo}; 
	   
      //EAQ:filtro_directo funcionalidad acceso directo
        if(config.filtro_directo){
        
           this.store.baseParams.filtro_valor = config.filtro_directo.valor;
           this.store.baseParams.filtro_campo = config.filtro_directo.campo;
       }
        this.load({params: {start: 0, limit: 50}})
	  
    
   },
   getParametrosFiltro: function () {
		this.store.baseParams.tipo = this.tipo;
	}
	,
	preparaMenu:function(n){
		     	
		var data = this.getSelectedData();
		console.log('data',data)
		var tb =this.tbar;
		//si el archivo esta escaneado se permite visualizar
		
		if(data['estado']=='pendiente_recibido'){
			 this.getBoton('FinalizarExterna').enable();
            this.getBoton('Adjuntos').disable();
            this.getBoton('VerDocumento').disable();
            this.getBoton('Derivar').disable();
            this.getBoton('HojaRuta').disable();
            this.getBoton('Historico').disable();
            this.getBoton('Archivar').disable();
            this.getBoton('Corregir').disable();
			
		
		}
		else{
			if(data['estado']=='enviado'){
			this.getBoton('FinalizarExterna').disable();
            this.getBoton('Adjuntos').enable();
            this.getBoton('VerDocumento').enable();
            this.getBoton('Derivar').disable();
            this.getBoton('HojaRuta').enable();
            this.getBoton('Historico').enable();
            this.getBoton('Archivar').enable();
            this.getBoton('Corregir').enable();
            
			
		}else {
			this.getBoton('FinalizarExterna').disable();
            this.getBoton('Adjuntos').enable();
            this.getBoton('VerDocumento').enable();
            this.getBoton('Derivar').enable();
            this.getBoton('HojaRuta').enable();
            this.getBoton('Historico').enable();
            this.getBoton('Archivar').enable();
            this.getBoton('Corregir').disable();
          }
      }
      
		
		
		Phx.vista.CorrespondenciaRecibida.superclass.preparaMenu.call(this,n); 
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


};
</script>
