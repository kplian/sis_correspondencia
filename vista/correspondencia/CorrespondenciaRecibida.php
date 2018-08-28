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
	/*gruposBarraTareas: [{
		name: 'externa',
		title: '<H1 align="center"><i class="fa fa-space-shuttle"></i> Externa</h1>',
		grupo: 0,
		height: 0
	},
		{
			name: 'interna',
			title: '<H1 align="center"><i class="fa fa-connectdevelop"></i> Interna</h1>',
			grupo: 1,
			height: 0
		}

	],*/
	beditGroups: [0, 1],
	bactGroups: [0, 1],
	btestGroups: [0,1],
	bexcelGroups: [0, 1],
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaRecibida',
	
	constructor: function(config) {
		this.Atributos[this.getIndAtributo('fecha_documento')].form=false;
		this.Atributos[this.getIndAtributo('origen')].grid=true;
        this.Atributos[this.getIndAtributo('origen')].form=false;
	    Phx.vista.CorrespondenciaRecibida.superclass.constructor.call(this,config);
	     console.log('AAAAAAAAAAAAAAAAAAAAAAAA');
         console.log(this.tipo);
         //this.bloquearOrdenamientoGrid();
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
             this.getBoton('Habilitar').hide();
		/*this.addButton('finalizarRecibido', {
			grupo : [0,1],
			text: 'Finalizar Recepcion',
			iconCls: 'bgood',
			disabled:true,
			handler: this.finalizarRecepcion,
			tooltip: '<b>finalizarRecibido</b><br/>Permite finalizar la recepcion'
		});	*/	
		
		/*this.addButton('Archivar', {
			grupo : [0,1],
			text: 'Archivar',
			iconCls: 'bsave',
			disabled: false,
			handler: this.archivar,
			tooltip: '<b>Archivar</b><br/>'
		});*/
		
		
		this.init();
        this.store.baseParams = {'interface': 'recibida','tipo': this.tipo};
        this.load({params: {start: 0, limit: 50}})
	  
    
   },
   getParametrosFiltro: function () {
		this.store.baseParams.tipo = this.tipo;
	},
   actualizarSegunTab: function (name, indice) {
		console.log('externa',name);

		     //this.bloquearOrdenamientoGrid();
      
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
             this.getBoton('Habilitar').hide();
            
            this.getBoton('FinalizarExterna').enable();
            this.getBoton('Adjuntos').enable();
            this.getBoton('VerDocumento').enable();
            this.getBoton('Derivar').enable();
            this.getBoton('HojaRuta').enable();
            this.getBoton('Historico').enable();
            this.getBoton('Archivar').enable(); 
         /* if(name=='externa'){
				 this.getBoton('FinalizarExterna').show();
              this.getBoton('Adjuntos').show();
              this.getBoton('VerDocumento').show();
              this.getBoton('Derivar').show();
              this.getBoton('HojaRuta').show();
              this.getBoton('Historico').show();
              this.getBoton('Archivar').show(); 
            
		}else{
				*/	 this.getBoton('FinalizarExterna').show();
              this.getBoton('Adjuntos').show();
              this.getBoton('VerDocumento').show();
              this.getBoton('Derivar').show();
              this.getBoton('HojaRuta').show();
              this.getBoton('Historico').show();
              this.getBoton('Archivar').show(); 
            
		//}
			           
		this.swTipo = this.tipo;
		this.getParametrosFiltro();
		this.load();
	
	},
	
	preparaMenu:function(n){
		     	
		var data = this.getSelectedData();
		console.log('data',data)
		var tb =this.tbar;
		//si el archivo esta escaneado se permite visualizar
		
		
		
		
		if(data['estado']=='recibido'){
			
			this.getBoton('FinalizarExterna').disable();
            this.getBoton('Adjuntos').enable();
            this.getBoton('VerDocumento').enable();
            this.getBoton('Derivar').enable();
            this.getBoton('HojaRuta').enable();
            this.getBoton('Historico').enable();
            this.getBoton('Archivar').enable();
		}
		else{
		    this.getBoton('FinalizarExterna').enable();
            this.getBoton('Adjuntos').disable();
            this.getBoton('VerDocumento').disable();
            this.getBoton('Derivar').disable();
            this.getBoton('HojaRuta').disable();
            this.getBoton('Historico').disable();
            this.getBoton('Archivar').disable();
	
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
