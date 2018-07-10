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
Phx.vista.RecepcionCorrespondenciaExterna = {
    bsave:false,
	swEstado: 'borrador_recepcion_externo',
	urlDepto:'../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
	gruposBarraTareas: [{
		name: 'borrador_recepcion_externo',
		title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Recepcionar</h1>',
		grupo: 0,
		height: 0
	},
		{
			name: 'pendiente_recepcion_externo',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Finalizados</h1>',
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
	nombreVista: 'RecepcionCorrespondenciaExterna',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaExterna',
	ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaExterna',

	constructor: function(config) {
	    
	    
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
	    Phx.vista.RecepcionCorrespondenciaExterna.superclass.constructor.call(this,config);

      //  this.bloquearOrdenamientoGrid();


		

//		this.getBoton('verCorrespondencia').hide();
		this.getBoton('mandar').hide();
		//this.getBoton('Adjuntos').hide();
		this.getBoton('corregir').hide();
		this.getBoton('Hoja de Ruta').hide();
        
   
		this.addButton('imprimirCodigoCorrespondencia', {
			text: 'Imprimir Sticker',
			iconCls: 'bprint',
			disabled: true,
			handler: this.imprimirCodigoCorrespondencia,
			tooltip: '<b>Imprimir Codigo</b><br/>imprimir codigo correspondencia'
		});
		
		this.addButton('btnImpCodigo2', {
			text: 'Imprimir Documento',
			iconCls: 'bprintcheck',
			disabled: true,
			handler: this.impCodigo2,
			tooltip: '<b>Imprimir Codigo</b><br/>imprimir codigo correspondencia'
		});

		this.addButton('aSubirCorrespondenciaExterna', {
			text: 'Subir Documento',
			iconCls: 'bupload',
			disabled: true,
			handler: this.SubirCorrespondencia,
			tooltip: '<b>Subir archivo</b><br/>Permite actualizar el documento escaneado'
		});

		this.addButton('finalizarRecepcionExterna', {
			text: 'Finalizar Recp Externa',
			iconCls: 'badelante',
			disabled: true,
			handler: this.finalizarRecepcionExterna,
			tooltip: '<b>Finalizar Recepción</b><br/>Finalizar Recepción de documento entrante (Externa Recibida), pasa a estado de análisis'
		});
		this.getBoton('Historico').hide();
		
		this.init();  
		//this.store.baseParams = {'vista': 'recepcion_correspondencia_externa','estado': this.swEstado};


        this.store.baseParams = {'interface': 'externa','estado': this.swEstado};
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
	getParametrosFiltro: function () {
		this.store.baseParams.estado = this.swEstado;
	},
actualizarSegunTab: function (name, indice) {
		console.log(name);

		this.getBoton('mandar').hide();
		this.getBoton('Hoja de Ruta').hide();
		this.getBoton('Historico').hide();
		if(name=='borrador_recepcion_externo'){
			
			this.getBoton('corregir').hide();
		}else{
			
			this.getBoton('corregir').show();
		}
		
		

		this.swEstado = name;
		this.getParametrosFiltro();
		this.load();
		//Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonAct.call(this);


	},
/*
	actualizarSegunTab: function (name, indice) {
		console.log(name);

		this.getBoton('mandar').hide();
		//this.getBoton('Adjuntos').hide();
		//this.getBoton('corregir').hide();
		this.getBoton('Hoja de Ruta').hide();


		this.swEstado = name;
		this.getParametrosFiltro();
		this.load();
		//Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonAct.call(this);


	},
*/
    preparaMenu:function(n){
      	
      	Phx.vista.RecepcionCorrespondenciaExterna.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar


		if (data['estado'] == 'borrador_recepcion_externo') {

			this.getBoton('aSubirCorrespondenciaExterna').enable();
		}


		if (data['version'] > 0) {
			this.getBoton('verCorrespondencia').enable();
			this.getBoton('finalizarRecepcionExterna').enable();
			this.getBoton('corregir').enable();
		}
		else {
			this.getBoton('verCorrespondencia').disable();
			this.getBoton('finalizarRecepcionExterna').disable();
			this.getBoton('corregir').disable();

		}


		this.getBoton('imprimirCodigoCorrespondencia').enable();
		this.getBoton('btnImpCodigo2').enable();

	 
		 return tb
		
	},
	liberaMenu:function(){
        var tb = Phx.vista.RecepcionCorrespondenciaExterna.superclass.liberaMenu.call(this);
        if(tb){
           
            this.getBoton('verCorrespondencia').disable();
			this.getBoton('finalizarRecepcionExterna').disable();
			this.getBoton('imprimirCodigoCorrespondencia').disable();
			this.getBoton('aSubirCorrespondenciaExterna').disable();
                    
        }
       return tb
    },
	onButtonNew: function () {
		console.log('llega');


		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
	


		Phx.vista.RecepcionCorrespondenciaExterna.superclass.onButtonNew.call(this);


		this.Cmp.id_institucion_destino.hide();
		this.Cmp.id_persona_destino.hide();
		this.Cmp.id_acciones.hide();
		this.Cmp.id_acciones.hide();

		this.ocultarComponente(this.Cmp.id_persona_destino);
		this.ocultarComponente(this.Cmp.id_institucion_destino);
		this.ocultarComponente(this.Cmp.id_acciones);

		

		this.adminGrupo({ ocultar: [3]});

		console.log(this.Cmp);
		this.tipo.setValue('externa');
		this.tipo.disable(true);

		cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();


		this.ocultarComponente(this.Cmp.id_funcionario);

		/*var cmbDoc = this.getComponente('id_documento');
		var cmpFuncionarios = this.getComponente('id_funcionarios');
		var cmpInstitucion = this.getComponente('id_institucion');
		var cmpPersona = this.getComponente('id_persona');

		this.adminGrupo({mostrar: [0, 1, 2, 3]});
		this.ocultarComponente(cmpInstitucion);
		this.ocultarComponente(cmpPersona);
		this.mostrarComponente(cmpFuncionarios);

		this.getComponente('id_uo').enable();
		this.getComponente('id_clasificador').enable();
		this.getComponente('mensaje').enable();
		this.getComponente('nivel_prioridad').enable();
		this.getComponente('referencia').enable();

		cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();*/

	},
	onButtonEdit: function () {
		
		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
	
		Phx.vista.RecepcionCorrespondenciaExterna.superclass.onButtonEdit.call(this);

		this.Cmp.id_institucion_destino.hide();
		this.Cmp.id_persona_destino.hide();
		this.Cmp.id_acciones.hide();
		this.Cmp.id_acciones.hide();

		this.ocultarComponente(this.Cmp.id_persona_destino);
		this.ocultarComponente(this.Cmp.id_institucion_destino);
		this.ocultarComponente(this.Cmp.id_acciones);

		this.adminGrupo({ ocultar: [3]});

		this.tipo.setValue('externa');
		this.tipo.disable(true);
		this.ocultarComponente(this.Cmp.id_funcionario);
		/*this.tipo.setValue('externa');
		this.tipo.disable(true);*/
		/*cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();*/


		//a  this.ocultarComponente(this.Cmp.id_funcionario);

		/*var cmbDoc = this.getComponente('id_documento');
		var cmpFuncionarios = this.getComponente('id_funcionarios');
		var cmpInstitucion = this.getComponente('id_institucion');
		var cmpPersona = this.getComponente('id_persona');

		this.adminGrupo({mostrar: [0, 1, 2, 3]});
		this.ocultarComponente(cmpInstitucion);
		this.ocultarComponente(cmpPersona);
		this.mostrarComponente(cmpFuncionarios);

		this.getComponente('id_uo').enable();
		this.getComponente('id_clasificador').enable();
		this.getComponente('mensaje').enable();
		this.getComponente('nivel_prioridad').enable();
		this.getComponente('referencia').enable();

		cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();*/

	},
	SubirCorrespondencia: function () {
		var rec = this.sm.getSelected();


		Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/subirCorrespondencia.php',
			'Subir Correspondencia',
			{
				modal: true,
				width: 500,
				height: 250
			}, rec.data, this.idContenedor, 'subirCorrespondencia')
	},
	//
	imprimirCodigoCorrespondencia : function () {
		var rec = this.sm.getSelected();
		Phx.CP.loadingShow();		
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/impCodigoCorrespondecia',
			params: { 'id_correspondencia': rec.data.id_correspondencia },
			success : this.successExport,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});
	},
	//
	impCodigo2: function(){
		var rec = this.sm.getSelected();
		Phx.CP.loadingShow();		
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/impCodigoCorrespondecia2',
			params: { 'id_correspondencia': rec.data.id_correspondencia },
			success : this.successExport,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});
	},
	//
	finalizarRecepcionExterna:function () {
		var rec = this.sm.getSelected();
		Phx.CP.loadingShow();
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/finalizarRecepcionExterna',
			params: {
				id_correspondencia: rec.data.id_correspondencia,
				estado: 'pendiente_recepcion_externo'
			},
			success: this.successDerivar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});

	}
	
	
	
};
</script>
