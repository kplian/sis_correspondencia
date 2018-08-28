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
Phx.vista.CorrespondenciaAdministracion = {
    bsave:false,
    bedit:false,
    bdel:false,
	swEstado: 'anulado',
	urlDepto:'../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
	gruposBarraTareas: [{
		name: 'anulado',
		title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Anulados</h1>',
		grupo: 0,
		height: 0
	},
		{
			name: 'enviado',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Enviados</h1>',
			grupo: 1,
			height:   0
		}

	],
	
		
	beditGroups: [0, 1],
	bactGroups: [0, 1],
	btestGroups: [0,1],
	bexcelGroups: [0, 1],


    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'CorrespondenciaAdministracion',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaExterna',
	ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaExterna',

	constructor: function(config) {
	    
	    //this.tipo.setValue('externa');
        this.Atributos[this.getIndAtributo('id_depto')].form=true;
        this.Atributos[this.getIndAtributo('id_depto')].form=true;
        this.Atributos[this.getIndAtributo('id_funcionario')].grid=false;
        this.Atributos[this.getIndAtributo('id_uo')].grid=false;
        this.Atributos[this.getIndAtributo('id_persona_remitente')].grid=true;
        this.Atributos[this.getIndAtributo('id_institucion_remitente')].grid=true;  
        this.Atributos[this.getIndAtributo('nro_paginas')].grid=true;
        this.Atributos[this.getIndAtributo('nro_paginas')].form=true;
        this.Atributos[this.getIndAtributo('otros_adjuntos')].grid=true;
        this.Atributos[this.getIndAtributo('otros_adjuntos')].form=true;
        this.Atributos[this.getIndAtributo('cite')].grid=true;
        this.Atributos[this.getIndAtributo('cite')].form=true;
        this.Atributos[this.getIndAtributo('estado_reg')].grid=false;
	    Phx.vista.CorrespondenciaAdministracion.superclass.constructor.call(this,config);
	    
	  
        this.addButton('Corregir', {
				grupo : [0,1],
				text : 'Corregir',
				iconCls : 'bundo',
				disabled : true,
				handler : this.BCorregir,
				tooltip : '<b>Corregir</b><br/>Si todos los envios de destinatarios se encuentran pendientes de lectura puede solicitar la corrección'
			});   

            //this.bloquearOrdenamientoGrid();
            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('Adjuntos').hide();
           // this.getBoton('Corregir').hide();
            this.getBoton('VerDocumento').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
		
		this.init();  
		//this.store.baseParams = {'vista': 'recepcion_correspondencia_externa','estado': this.swEstado};
      
        this.store.baseParams = {'interface': this.interfaz,'estado': this.swEstado};
        this.load({params: {start: 0, limit: 50}})

		this.iniciarEventos();
    
   },
   iniciarEventos(){
	   	this.Cmp.id_institucion_remitente.on('select',function(combo,record,index){
	    	this.Cmp.id_persona_remitente.store.baseParams.id_institucion=combo.getValue();
	   		this.Cmp.id_persona_remitente.reset();
	   		this.Cmp.id_persona_remitente.modificado=true;
	   		
	   	},this)
   },
   east : undefined,
   
   	getParametrosFiltro: function () {
		this.store.baseParams.estado = this.swEstado;
	},
	actualizarSegunTab: function (name, indice) {
		console.log(name);
            //this.bloquearOrdenamientoGrid();
            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('Adjuntos').hide();
         //   this.getBoton('Corregir').hide();
            this.getBoton('VerDocumento').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
             this.getBoton('Archivar').hide();
		if(name=='anulado'){
			
			this.getBoton('Corregir').hide();
			
		}else{
			this.getBoton('Corregir').show();
			this.getBoton('Habilitar').hide();
			
		}
		this.swEstado = name;
		this.getParametrosFiltro();
		this.load();
		//Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonAct.call(this);


	},
	
	
    preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaAdministracion.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		//console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
     	this.getBoton('Corregir').enable();

	 
		 return tb
		
	},
	
  BCorregir : function() {
			
			var rec = this.sm.getSelected();
			var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
			   
			   var result = prompt('Especifique las razones por las que se corrige el Documento'+rec.data.numero);
			   if(confirm('Esta seguro de corregir la derivación?'+rec.data.numero)){
			   Phx.CP.loadingShow();
			
			 	   
				Ext.Ajax.request({
				url : '../../sis_correspondencia/control/Correspondencia/corregirCorrespondencia',
				params : {
					id_correspondencia : id_correspondencia,
					interfaz:'administrador',
					tipo:this.interfaz,
					observaciones:result
				},
				success : this.successDerivar,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			    });
			}
		},
	onButtonNew: function () {
		console.log('llega');


		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
		Phx.vista.CorrespondenciaAdministracion.superclass.onButtonNew.call(this);
		if (this.interfaz=='externa'){
	          	this.Cmp.id_institucion_destino.hide();
		        this.Cmp.id_persona_destino.hide();
		        this.Cmp.id_acciones.hide();
		      	this.ocultarComponente(this.Cmp.id_persona_destino);
		        this.ocultarComponente(this.Cmp.id_institucion_destino);
		        this.ocultarComponente(this.Cmp.id_acciones);
         		this.adminGrupo({ ocultar: [3]});
         		this.ocultarComponente(this.Cmp.id_funcionario);
		
		}else{
		       	this.Cmp.id_institucion_destino.hide();
		        this.Cmp.id_persona_destino.hide();
		        this.Cmp.id_institucion_remitente.hide();
		        this.Cmp.id_persona_remitente.hide();
	            this.ocultarComponente(this.Cmp.id_persona_destino);
		        this.ocultarComponente(this.Cmp.id_institucion_destino);
		        this.ocultarComponente(this.Cmp.id_persona_remitente);
		        this.ocultarComponente(this.Cmp.id_institucion_remitente);
		        
		//this.adminGrupo({ ocultar: [3]});
	
		}
  
		console.log(this.interfaz);
		
		this.tipo.setValue(this.interfaz);
		this.tipo.disable(true);
		//var par_tipo;
        if (this.interfaz=='externa'){
        	cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
        }else{
           cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
        	
        }
		//cmbDoc.store.baseParams.tipo = this.interfaz;//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();
	
		

		

	},
	liberaMenu:function(){
        var tb = Phx.vista.CorrespondenciaAdministracion.superclass.liberaMenu.call(this);
        if(tb){
           
            this.getBoton('SubirDocumento').disable();
             this.getBoton('Habilitar').enable();
			this.getBoton('Adjuntos').disable();
			this.getBoton('VerDocumento').disable();
			this.getBoton('Finalizar').disable();
			this.getBoton('ImpCodigo').disable();
		    this.getBoton('ImpCodigoDoc').disable();
		    
	                
        }
       return tb
  }

	
};
</script>
