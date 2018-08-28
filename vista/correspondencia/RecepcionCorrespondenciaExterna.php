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
        //this.Atributos[this.getIndAtributo('fecha_creacion_documento')].form=true;
	    Phx.vista.RecepcionCorrespondenciaExterna.superclass.constructor.call(this,config);
	    
	    this.addButton('Finalizar', {
				text: 'Finalizar Recepción',
				iconCls: 'bgood',
				disabled: true,
				handler: this.BFinalizar,
				tooltip: '<b>Finalizar Recepción</b><br/>Finalizar el registro del Documento'
			});

            //this.bloquearOrdenamientoGrid();
            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            //this.getBoton('SubirDocumento').show();
            //this.getBoton('Adjuntos').show();
            this.getBoton('Corregir').hide();
            //this.getBoton('VerDocumento').show();
            //this.getBoton('ImpCodigo').hide();
            //this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('Habilitar').hide();
		  
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
   east : undefined,
   
   
   /*Atributos : [{
   
			config : {
				name : 'id_correspondencias_asociadas',
				fieldLabel : 'Responde a',
				allowBlank : true,
				emptyText : 'Correspondencias...',
				store : new Ext.data.JsonStore({
					url : '../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaSimplificada',
					id : 'id_correspondencia',
					root : 'datos',
					sortInfo : {
						field : 'id_correspondencia',
						direction : 'desc'
					},
					totalProperty : 'total',
					fields : ['id_correspondencia', 'numero', 'referencia', 'desc_funcionario'],
					// turn on remote sorting
					remoteSort : true,
					baseParams : {
						par_filtro : 'cor.numero#cor.referencia#funcionario.desc_funcionario1',
						tipo:'saliente'
					}
				}),
				tpl : '<tpl for="."><div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{numero}</div><p style="padding-left: 20px;">{referencia}</p><p style="padding-left: 20px;">{desc_funcionario}</p> </div></tpl>',
				valueField : 'id_correspondencia',
				displayField : 'numero',
				gdisplayField : 'desc_asociadas', //mapea al store del grid

				hiddenName : 'id_correspondencias_asociadas',
				forceSelection : true,
				typeAhead : true,
				triggerAction : 'all',
				enableMultiSelect : true,
				lazyRender : true,
				mode : 'remote',
				pageSize : 10,
				queryDelay : 1000,
				width : 250,
				gwidth : 200,
				minChars : 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_asociadas']);
				}
			},
			type : 'AwesomeCombo',
			id_grupo : 2,
			
			grid : false,
			form : true
		}],*/
	getParametrosFiltro: function () {
		this.store.baseParams.estado = this.swEstado;
	},
	actualizarSegunTab: function (name, indice) {
		console.log(name);
            //this.bloquearOrdenamientoGrid();
            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            //this.getBoton('SubirDocumento').show();
            //this.getBoton('Adjuntos').show();
            this.getBoton('Corregir').hide();
            //this.getBoton('VerDocumento').show();
            //this.getBoton('ImpCodigo').hide();
            //this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            //this.getBoton('Finalizar').show();
             this.getBoton('Archivar').hide();
           this.getBoton('Habilitar').hide();

		if(name=='borrador_recepcion_externo'){
			
			this.getBoton('Corregir').hide();
			 
		}else{
			
			this.getBoton('Corregir').show();
			
		
		}
		this.swEstado = name;
		this.getParametrosFiltro();
		this.load();
		//Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonAct.call(this);


	},
    preparaMenu:function(n){
      	
      	Phx.vista.RecepcionCorrespondenciaExterna.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar


		if (data['estado'] == 'borrador_recepcion_externo') {

			this.getBoton('SubirDocumento').enable();
			this.getBoton('Adjuntos').enable();
		}


		if (data['version'] > 0) {
			this.getBoton('VerDocumento').enable();
			this.getBoton('Finalizar').enable();
			this.getBoton('ImpCodigo').enable();
		    this.getBoton('ImpCodigoDoc').enable();
		}
		
		if (data['estado'] == 'pendiente_recepcion_externo') {
			this.getBoton('SubirDocumento').disable();
			this.getBoton('Adjuntos').disable();
			this.getBoton('VerDocumento').enable();
			this.getBoton('Corregir').enable();
			this.getBoton('Finalizar').disable();
			this.getBoton('ImpCodigo').disable();
		    this.getBoton('ImpCodigoDoc').disable();
		    this.getBoton('edit').disable();
			this.getBoton('del').disable();
		}
		
	
		 return tb
		
	},
	liberaMenu:function(){
        var tb = Phx.vista.RecepcionCorrespondenciaExterna.superclass.liberaMenu.call(this);
        if(tb){
           
           this.getBoton('SubirDocumento').disable();
			this.getBoton('Adjuntos').disable();
			this.getBoton('VerDocumento').disable();
			this.getBoton('Finalizar').disable();
			this.getBoton('ImpCodigo').disable();
		    this.getBoton('ImpCodigoDoc').disable();
		    this.getBoton('edit').disable();
			this.getBoton('del').disable();
                    
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
		this.ocultarComponente(this.Cmp.fecha_creacion_documento);

		this.adminGrupo({ ocultar: [3]});

		console.log(this.Cmp);
		this.tipo.setValue('externa');
		this.tipo.disable(true);

		cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();


		this.ocultarComponente(this.Cmp.id_funcionario);

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
		

	}

	
};
</script>
