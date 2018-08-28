<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (ffigueroa alias el conejo)
*@date 20-09-2016 10:22:05
*@description Archivo con la interfaz de usuario que permite 
*dar el visto a solicitudes de compra
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.DerivacionCorrespondenciaExterna = {
    bsave:false,
	bnew:false,
	bedit:true,
	bdel:false,
	swEstado: 'pendiente_recepcion_externo',
	gruposBarraTareas: [{
		name: 'pendiente_recepcion_externo',
		title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Pendientes</h1>',
		grupo: 0,
		height: 0
	},
		{
			name: 'enviado',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Derivados</h1>',
			grupo: 1,
			height: 0
		}

	],

	beditGroups: [0, 1],
	bactGroups: [0, 1],
	btestGroups: [0,1],
	bexcelGroups: [0, 1],
	

    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'DerivacionCorrespondenciaExterna',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaExterna',
	ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaExterna',

	constructor: function(config) {
		
		this.Atributos[this.getIndAtributo('id_depto')].form=true;
		this.Atributos[this.getIndAtributo('id_depto')].grid=false;
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
        Phx.vista.DerivacionCorrespondenciaExterna.superclass.constructor.call(this,config);


	//	this.bloquearOrdenamientoGrid();


		
		this.load();


//		this.getBoton('verCorrespondencia').hide();
		//this.getBoton('mandar').hide();
		this.getBoton('Adjuntos').hide();
		//this.getBoton('corregir').hide();
		this.getBoton('Hoja de Ruta').hide();


		this.addButton('anularCorrespondencia', {
			text: 'Anular',
			iconCls: 'anular',
			disabled: false,
			handler: this.anularCorrespondencia,
			tooltip: '<b>Anular Correspondencia</b><br/>Anula la correspondencia y todas las derivaciones.'
		});

		/*this.addButton('aSubirCorrespondenciaExterna', {
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
			tooltip: '<b>SFinalizar Recp Externa</b><br/>Permite actualizar el documento escaneado'
		});*/
		
		this.init();
        //this.store.baseParams = {'interface': 'derivacion_externa'};
        this.store.baseParams = {'vista': 'derivacion_correspondencia_externa','estado': this.swEstado};
        
       

        this.load({params: {start: 0, limit: 50}})


    
   },
	getParametrosFiltro: function () {
		this.store.baseParams.estado = this.swEstado;
	},

	actualizarSegunTab: function (name, indice) {
		console.log('derivar',name);

		this.getBoton('Adjuntos').hide();
		
		if(name=='enviado'){
			this.getBoton('Hoja de Ruta').show();
			this.getBoton('anularCorrespondencia').show();
			this.getBoton('corregir').hide();
		}else{
			
			this.getBoton('Hoja de Ruta').hide();
			this.getBoton('anularCorrespondencia').hide();
			this.getBoton('corregir').enable();
		}
		
		

		this.swEstado = name;
		this.getParametrosFiltro();
		this.load();
		//Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonAct.call(this);


	},

    preparaMenu:function(n){
      	
      	Phx.vista.DerivacionCorrespondenciaExterna.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar


		this.getBoton('verCorrespondencia').enable();
		this.getBoton('mandar').enable();


		return tb
		
	},
	onButtonNew: function () {
		
		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
		var cmpId_funcionario = this.getComponente('id_funcionario');

		Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonNew.call(this);

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


		this.ocultarComponente(cmpId_funcionario);

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
	
		Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonEdit.call(this);

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

		/*cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();*/


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

	imprimirCodigoCorrespondencia : function () {
		var rec = this.sm.getSelected();

	},
	finalizarRecepcionExterna:function () {

		var rec = this.sm.getSelected();
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/finalizarRecepcionExterna',
			params: {
				id_correspondencia: rec.data.id_correspondencia,
				estado: 'pendiente_recepcion_externo'
			},
			success: this.successDespachar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});

	},
	anularCorrespondencia : function() {

			if(confirm('Esta seguro de anular la correspondencia seleccionada? \nSe eliminaran todas las derivaciones asociadas.')==true){
				var data = this.sm.getSelected().data.id_proceso_contrato;
				var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
				Phx.CP.loadingShow();
				Ext.Ajax.request({
					// form:this.form.getForm().getEl(),
					url : '../../sis_correspondencia/control/Correspondencia/anularCorrespondencia',
					params : {
						id_correspondencia : id_correspondencia
					},
					success : this.successAnular,
					failure : this.conexionFailure,
					timeout : this.timeout,
					scope : this
				});
			}
			
		},
		successAnular : function(resp) {

			Phx.CP.loadingHide();
			var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
			if (!reg.ROOT.error) {
				alert(reg.ROOT.detalle.mensaje)

			}
			this.reload();

		},
	
	
   
	
	
};
</script>
