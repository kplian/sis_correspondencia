<?php
/**
 *@package pXP
 *@file    FormReporteGeneral.php
 *@author  Marcela Garcia
 *@date    26-06-2019
 *@description Archivo con la interfaz y los parametros para generaci�n de reporte
 */

#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA        AUTOR        DESCRIPCION
#5      		21/08/2019   MCGH         Eliminación de Código Basura

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.FormReporteGeneral = Ext.extend(Phx.frmInterfaz, {
		
		Atributos : [
					{
						config:{
							name: 'id_entidad',
							fieldLabel: 'Entidad',
							labelStyle: 'width:150px; margin: 5;',
							qtip: 'entidad a la que pertenese el depto, ',
							allowBlank: false,
							emptyText:'Entidad...',
							store:new Ext.data.JsonStore(
							{
								url: '../../sis_parametros/control/Entidad/listarEntidad',
								id: 'id_entidad',
								root: 'datos',
								sortInfo:{
									field: 'nombre',
									direction: 'ASC'
								},
								totalProperty: 'total',
								fields: ['id_entidad','nit','nombre'],
								// turn on remote sorting
								remoteSort: true,
								baseParams: { par_filtro:'nit#nombre' }
							}),
							valueField: 'id_entidad',
							displayField: 'nombre',
							gdisplayField:'desc_entidad',
							hiddenName: 'id_entidad',
			    			triggerAction: 'all',
			    			lazyRender:true,
							mode:'remote',
							pageSize:50,
							queryDelay:500,
							//anchor:"90%",
							//listWidth:280,
							//gwidth:150,
							width : 250,
							minChars:2,
							renderer:function (value, p, record){return String.format('{0}', record.data['desc_entidad']);}
			
			       		},
			       		type:'ComboBox',
						filters:{pfiltro:'ENT.nombre',type:'string'},
						id_grupo:0,						
						grid:true,
						form:true
					},
					{
						config : {
							name : 'fecha_ini',
                            id:'fecha_ini'+this.idContenedor,
							fieldLabel : 'Fecha Documento Desde',
							labelStyle: 'width:150px; margin: 5;',
							allowBlank : false,
							format : 'd/m/Y',
							renderer : function(value, p, record) {
								return value ? value.dateFormat('d/m/Y h:i:s') : ''
							},
                            vtype: 'daterange',
                            width : 250,
                            endDateField: 'fecha_fin'+this.idContenedor
						},
						type : 'DateField',
						id_grupo : 0,
						grid : true,
						form : true
					},
					{
						config : {
							name : 'fecha_fin',
							id:'fecha_fin'+this.idContenedor,
							fieldLabel: 'Fecha Documento Hasta',
							labelStyle: 'width:150px; margin: 5;',
							allowBlank: false,
							gwidth: 250,
							format: 'd/m/Y',
							renderer: function(value, p, record) {
								return value ? value.dateFormat('d/m/Y h:i:s') : ''
							},
							vtype: 'daterange',
							width : 250,
							startDateField: 'fecha_ini'+this.idContenedor
						},
						type : 'DateField',
						id_grupo : 0,
						grid : true,
						form : true
					},
	    			{
						config : {
							name : 'id_uo',
							baseParams : {
								correspondencia : 'si'
							},
							origen : 'UO',
							//allowBlank: false,
							//LabelWidth:500,
							fieldLabel : 'UO Remitente',
							labelStyle: 'width:150px; margin: 5;',
							
						//	labelStyle: 'white-space: nowrap;',
							
							gdisplayField : 'desc_uo', //mapea al store del grid
							//gwidth : 500,
							renderer : function(value, p, record) {
								return String.format('{0}', record.data['desc_uo']);
							}
						},
						type : 'ComboRec',
						
						id_grupo : 1,
						filters : {
							pfiltro : 'desc_uo',
							type : 'string'
						},
						grid : true,
						form : true
					},
					{
						config : {
							name : 'tipo',
							fieldLabel : 'Tipo Correspond.',
							labelStyle: 'width:150px; margin: 5;',
							LabelWidth:350,
							typeAhead : true,
							//allowBlank : false,
							triggerAction : 'all',
							emptyText : 'Seleccione Opcion...',
							selectOnFocus : true,
							width : 250,
							height: 300,
							//Height : 190,
							mode : 'local',
			
							store : new Ext.data.ArrayStore({
								fields : ['ID', 'valor'],
								data : [['entrante', 'Externa'], ['interna', 'Interna'], ['saliente', 'Saliente']],
			
							}),
							valueField : 'ID',
							displayField : 'valor'
			
						},
						type : 'ComboBox',
						//valorInicial : 'externa',
						filters : {
							pfiltro : 'cor.tipo',
							type : 'string'
						},
						id_grupo : 0,
						grid : true,
						form : true
					},
					
			
					
					{
			         	config : {
						name : 'id_documento',
						fieldLabel : 'Documento',
						labelStyle: 'width:150px; margin: 5;',
						//allowBlank : false,
						emptyText : 'Documento...',
						store : new Ext.data.JsonStore({
						url : '../../sis_parametros/control/Documento/listarDocumento',
					
						id : 'id_documento',
			    		root : 'datos',
						sortInfo : {
							field : 'codigo',
							direction : 'ASC'
						},
					
						totalProperty : 'total',
						fields : ['id_documento','tipo', 'codigo', 'descripcion'],
					// turn on remote sorting
						remoteSort : true,
						baseParams : {
							par_filtro : 'DOCUME.id_documento#DOCUME.tipo#DOCUME.codigo#DOCUME.descripcion',
							correspondencia:'si'
							}
						}),
				
						valueField : 'id_documento',
						displayField : 'descripcion',
						gdisplayField : 'desc_documento', //mapea al store del grid
						tpl : '<tpl for="."><div class="x-combo-list-item"><p>({codigo})({tipo}) {descripcion}</p> </div></tpl>',
						hiddenName : 'id_documento',
						forceSelection : true,
						typeAhead : true,
						triggerAction : 'all',
						lazyRender : true,
						mode : 'remote',
						pageSize : 10,
						queryDelay : 1000,
						width : 250,
						gwidth : 150,
						minChars : 2,

					},
				type : 'ComboBox',
				id_grupo : 0,
				filters : {
					pfiltro : 'doc.id_codumento',
					type : 'string'
					},

					grid : true,
					form : true
			}, 

					
			{
					 		config : {
							name : 'estado',
							fieldLabel : 'Estado',
							labelStyle: 'width:150px; margin: 5;',
							typeAhead : true,
							//allowBlank : false,
							triggerAction : 'all',
							emptyText : 'Seleccione Opcion...',
							selectOnFocus : true,
							mode : 'local',
							store : new Ext.data.ArrayStore({
								fields : ['ID', 'valor'],
								data : [['todos', 'Todos'],['borrador_envio', 'Borrador Recepción Interno'],['borrador_recepcion_externo', 'Borrador Recepción Externo'], ['pendiente_recepcion_externo', 'Pendiente Recepción Externo'], ['enviado', 'Enviado'], ['borrador_detalle_recibido', 'Borrador Detalle Recibido'], ['pendiente_recibido','Pendiente Recibido'], ['recibido','Recibido']]
							}),
							
							valueField : 'ID',
							displayField : 'valor',
							width : 250
			
						},
						type : 'ComboBox',
						
						id_grupo : 2,
						grid : true,
						form : true
					},
               
                {
                    config: {
                        name: 'id_usuario',
                        fieldLabel: 'Usuario Registro',
                        labelStyle: 'width:150px; margin: 5;',
                       
                        allowBlank: true,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.JsonStore({
                            //url: '../../sis_seguridad/control/Usuario/listarUsuario',
                            url: '../../sis_correspondencia/control/ReporteGeneral/listarUsuario',
                            id: 'id_usuario',
                            root: 'datos',
                            sortInfo: {
                                field: 'desc_person',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_usuario', 'desc_person'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'PERSON.nombre_completo2'} //para el filtrado en la consulta
                        }),
                        valueField: 'id_usuario',
                        displayField: 'desc_person',
                        gdisplayField: 'desc_persona',
                      
                        hiddenName: 'id_usuario',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        mode: 'remote',
                        pageSize: 15,
                        queryDelay: 1000,
                        width : 250,
                        minChars: 2,

                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    grid: true,
                    form: true
                }
		],
		
		topBar : true,
		botones : false,
		labelSubmit : 'Generar',
		tooltipSubmit : '<b>Reporte Correspondencia</b>',
		
		constructor : function(config) {
			Phx.vista.FormReporteGeneral.superclass.constructor.call(this, config);
			this.init();
			
			this.iniciarEventos();
			
		},
		

		tipo : 'reporte',
		clsSubmit : 'bprint',
		
		Grupos : [{
			layout : 'column',
			items : [{
				xtype : 'fieldset',
				layout : 'form',
				border : true,
				title : 'Datos para el reporte',
				bodyStyle : 'padding:0 10px 0;',
				columnWidth : '500px',
				items : [],
				id_grupo : 0,
				width : 1050,
				collapsible : true
			}]
		}],
		
	//ActSave:'../../sis_correspondencia/control/ReporteGeneral/reporteGeneral',
	constructor : function(config) {
		Phx.vista.FormReporteGeneral.superclass.constructor.call(this, config);
		this.init();			
		this.iniciarEventos();
        
	},
	
	iniciarEventos:function(){        
		this.Cmp.tipo.on('select',function(c, r, i) {
    		this.Cmp.id_documento.store.baseParams.tipo = r.data.ID;
    		this.Cmp.id_documento.modificado=true;
    	},this);
        	
        this.Cmp.id_usuario.store.load({params:{start:0,limit:30},
            callback : function (r) {
                if (r.length == 1 ) {
                     this.Cmp.id_usuario.setValue(r[0].data.id_usuario);
                     this.Cmp.id_usuario.disable();
                }
            }, scope : this

        });
        
        this.Cmp.id_entidad.store.load({params:{start:0,limit:30},
            callback : function (r) {
                this.Cmp.id_entidad.setValue(r[0].data.id_entidad);
            }, scope : this

        });
        
		this.cmpIdEntidad = this.getComponente('id_entidad');
		this.cmpIdUo = this.getComponente('id_uo');	
		this.cmpTipo = this.getComponente('tipo');			
		this.cmpIdDocumento = this.getComponente('id_documento');
		this.cmpEstado = this.getComponente('estado');
		this.cmpFechaIni = this.getComponente('fecha_ini');
		this.cmpFechaFin = this.getComponente('fecha_fin');
		this.cmpIdUsuario = this.getComponente('id_usuario');
        
	},	
	
	onSubmit:function(o){	
		//console.log(this.getComponente('id_uo'));	
		var idEntidad = this.cmpIdEntidad.getValue(),
			idUo = this.cmpIdUo.getValue(),	
		    tipo = this.cmpTipo.getValue(),
		    idDocumento = this.cmpIdDocumento.getValue(),
		    estado = this.cmpEstado.getValue(),
		    fechaIni = this.cmpFechaIni.getValue().format('d-m-Y'),
		    fechaFin = this.cmpFechaFin.getValue().format('d-m-Y'),
		    idUsuario = this.cmpIdUsuario.getValue();

		Phx.CP.loadingShow();		
		Ext.Ajax.request({
			url:'../../sis_correspondencia/control/ReporteGeneral/reporteGeneral',
			params:
			{	
				'id_entidad' : idEntidad,
				'id_uo' : idUo,  
				'tipo' : tipo,
				'id_documento' : idDocumento,
				'estado' : estado,
				'fecha_ini' : fechaIni,
				'fecha_fin' : fechaFin,
				'id_usuario' : idUsuario,
			},
			success: this.successExport,		
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
		});		
	}

})
</script>