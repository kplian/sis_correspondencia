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
	swTipo: 'externa',
	gruposBarraTareas: [{
		name: 'externa',
		title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Externa</h1>',
		grupo: 0,
		height: 0
	},
		{
			name: 'interna',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Interna</h1>',
			grupo: 1,
			height: 0
		}
		,
		{
			name: 'saliente',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Emitida Externa</h1>',
			grupo: 1,
			height: 0
		}

	],    
	beditGroups: [0, 1],
	bactGroups: [0, 1],
	btestGroups: [0,1],
	bexcelGroups: [0, 1],
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaRecibidaArchivada',
	
	constructor: function(config) {
	    Phx.vista.CorrespondenciaRecibidaArchivada.superclass.constructor.call(this,config);
        this.getBoton('Plantilla').hide();
           // this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            //this.getBoton('Adjuntos').show();
            this.getBoton('Corregir').hide();
            //this.getBoton('VerDocumento').show();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            //this.getBoton('Derivar').hide();
            //this.getBoton('HojaRuta').hide();
            //this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide(); 
            this.getBoton('Archivar').hide(); 
            this.getBoton('Habilitar').hide(); 

		this.addButton('DesArchivar', {
			text: 'DesArchivar',
			iconCls: 'bsave',
			disabled: false,
			handler: this.DesArchivar,
			tooltip: '<b>DesArchivar</b><br/>'
		});
		
		this.init();
        this.store.baseParams = {'interface': 'recibida_archivada','tipo': this.swTipo};
        this.load({params: {start: 0, limit: 50}})
	  
    
   },
  getParametrosFiltro: function () {
		this.store.baseParams.tipo = this.swTipo;
	},
   actualizarSegunTab: function (name, indice) {
		console.log('externa',this.swTipo);
                this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            //this.getBoton('Adjuntos').show();
            this.getBoton('Corregir').hide();
            //this.getBoton('VerDocumento').show();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            //this.getBoton('HojaRuta').hide();
            //this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide(); 
            this.getBoton('Archivar').hide(); 
            this.getBoton('Habilitar').hide(); 
        
		//this.getBoton('FinalizarExterna').enable();
           this.getBoton('Adjuntos').enable();
            this.getBoton('VerDocumento').enable();
            this.getBoton('Derivar').enable();
            this.getBoton('HojaRuta').enable();
            this.getBoton('Historico').enable();
            this.getBoton('Archivar').enable(); 
		
		 if(name=='externa'){
				// this.getBoton('FinalizarExterna').show();
              this.getBoton('Adjuntos').show();
              this.getBoton('VerDocumento').show();
            //  this.getBoton('Derivar').show();
              this.getBoton('HojaRuta').show();
              this.getBoton('Historico').show();
              this.getBoton('DesArchivar').show(); 
            
		}else{
			 // tis.getBoton('FinalizarExterna').show();
              this.getBoton('Adjuntos').show();
              this.getBoton('VerDocumento').show();
              //this.getBoton('Derivar').show();
              this.getBoton('HojaRuta').show();
              this.getBoton('Historico').show();
              this.getBoton('DesArchivar').show();
            
		}
		
		this.swTipo = name;
		this.getParametrosFiltro();
		this.load();
	
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
