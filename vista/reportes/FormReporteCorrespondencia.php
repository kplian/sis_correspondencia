<<?php
/**
 *@package pXP
 *@file    FormReporteCorrespondencia.php
 *@author  JMH
 *@date    21/11/2018
 *@description Archivo con la interfaz para generación de reporte
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.FormReporteCorrespondencia = Ext.extend(Phx.frmInterfaz, {
		
		constructor: function(config) {
			Ext.apply(this,config);
			this.Atributos = [
				
				{
						config : {
							name : 'id_uo',
							baseParams : {
								correspondencia : 'si'
							},
							origen : 'UO',
							allowBlank: false,
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
							allowBlank : false,
							triggerAction : 'all',
							emptyText : 'Seleccione Opcion...',
							selectOnFocus : true,
							width : 250,
							height: 300,
							//Height : 190,
							mode : 'local',
			
							store : new Ext.data.ArrayStore({
								fields : ['ID', 'valor'],
								data : [['interna', 'Interna'], ['saliente', 'Saliente']],
			
							}),
							valueField : 'ID',
							displayField : 'valor'
			
						},
						type : 'ComboBox',
						valorInicial : 'interna',
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
						allowBlank : false,
						emptyText : 'Documento...',
						store : new Ext.data.JsonStore({
						url : '../../sis_parametros/control/Documento/listarDocumento_mas_Todos',
					
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
					//renderer : function(value, p, record) {
				//	return String.format('{0}', record.data['desc_documento']);
				//}
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
							name : 'estados',
							fieldLabel : 'Estado',
							labelStyle: 'width:150px; margin: 5;',
							typeAhead : true,
							allowBlank : false,
							triggerAction : 'all',
							emptyText : 'Seleccione Opcion...',
							selectOnFocus : true,
							mode : 'local',
							//valorInicial:{ID:'interna',valor:'Interna'},
							store : new Ext.data.ArrayStore({
								fields : ['ID', 'valor'],
								data : [['todos', 'Todos'],['borrador', 'Borrador'], ['enviado', 'Enviado'], ['anulado', 'Anulado']]
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
						config : {
							name : 'fecha_ini',
                            id:'fecha_ini'+this.idContenedor,
							fieldLabel : 'Fecha Desde',
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
							fieldLabel: 'Fecha Hasta',
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
                    config: {
                        name: 'id_usuario',
                        fieldLabel: 'Usuario',
                        labelStyle: 'width:150px; margin: 5;',
                       
                        allowBlank: true,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_seguridad/control/Usuario/listarUsuario',
                            id: 'id_usuario',
                            root: 'datos',
                            sortInfo: {
                                field: 'desc_person',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_usuario', 'desc_person', 'descripcion'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'PERSON.nombre_completo2'}
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
                        
                        //anchor: '100%',
                       // gwidth: 250,
                        width : 250,
                        minChars: 2,
                        renderer : function(value, p, record) {
                            return String.format('{0}', record.data['desc_persona']);
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'vusu.desc_persona',type: 'string'},
                    grid: true,
                    form: true
                }];
					
			Phx.vista.FormReporteCorrespondencia.superclass.constructor.call(this, config);
			this.init();
			
			this.iniciarEventos();
			

		},
		title : 'Reporte Correspondencia',
		topBar : true,
		botones : false,
		remoteServer : '',
		labelSubmit : 'Generar',
		labelWidth: 300,
		tooltipSubmit : '<b>Generar Reporte de Correspondencia</b>',
		tipo : 'reporte',
		
		clsSubmit : 'bprint',
		
		Grupos : [{
			layout : 'column',
			items : [{
				xtype : 'fieldset',
				layout : 'form',
				border : true,
				title : 'Generar Reporte',				
				bodyStyle : 'padding:25px 30px 25px 30px;',
				columnWidth : '400px',
				
				items : [],
				id_grupo : 0,
				width : 1050,
				collapsible : true
			}]
		}],
		
		iniciarEventos : function() {
        	/*this.Cmp.id_sucursal.on('select',function(c, r, i) {
        		this.remoteServer = r.data.servidor_remoto;
        	},this);*/
        	this.Cmp.tipo.on('select',function(c, r, i) {
        		this.Cmp.id_documento.store.baseParams.tipo = r.data.ID;
        		this.Cmp.id_documento.modificado=true;
        		//this.Cmp.id_documento.add(rec);
        		//this.Cmp.add(0,'to','Toods');
        	},this);
        },
		
		onSubmit: function(){
			if (this.form.getForm().isValid()) {
				var data={};
				data.fecha_ini=this.getComponente('fecha_ini').getValue().dateFormat('d/m/Y');
				data.id_uo=this.getComponente('id_uo').getValue();
                data.id_usuario=this.getComponente('id_usuario').getValue();
				data.fecha_fin=this.getComponente('fecha_fin').getValue().dateFormat('d/m/Y');
                data.desc_uo = this.Cmp.id_uo.getRawValue();
				data.tipo = this.getComponente('tipo').getValue();
				data.id_documento = this.getComponente('id_documento').getValue();
				//data.id_documento=this.Cmp.id_documento.getValue();
				data.estado = this.getComponente('estados').getValue();
				Phx.CP.loadWindows('../../../sis_correspondencia/vista/reportes/GridReporteCorrespondencia.php', 'Correspondencia '+ data.tipo+' de '+data.desc_uo, {
						width : '90%',
						height : '80%'
					}, data	, this.idContenedor, 'GridReporteCorrespondencia')
			}
		},
		desc_item:''

	})
</script>
