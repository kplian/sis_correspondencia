<?php
/**
*@package pXP
*@file HojaRuta.php
*@author  (AVQ)
*@date 03/07/2018 
*@description     Archivo con la interfaz de Hoja de ruta permite ver un listado de todas las personas a las que se ha derivado.
*/

#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA        AUTOR        DESCRIPCION
#5      		21/08/2019   MCGH         Eliminación de Código Basura

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Historico=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Historico.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag,id_origen:this.id_origen,'estado_reporte':'finalizado', id_institucion:this.id_institucion}});
		this.argumentExtraSubmit={'id_correspondencia':this.id_origen,'estado_reporte':'finalizado', 'id_institucion':this.id_institucion};

	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_correspondencia'
			},
			type:'Field',
			form:true 
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_institucion'
			},
			type:'Field',
			form:true 
		},
		{
			config : {
				name : 'desc_person_fk',
				fieldLabel : 'Remitente',
				gwidth : 120
			},
			type : 'TextField',
			filters : {
				pfiltro : 'desc_person_fk',
				type : 'string'
			},
			id_grupo : 0,
			grid : true,
			form : false,
			bottom_filter : true
		},
		
		{
			config : {
				name : 'desc_person',
				fieldLabel : 'Destinatario',
				gwidth : 120,
				renderer:function (value, p, record){
	   			 	
	   			 	if(record.data['estado']=='pendiente_recibido')
	   			 	
	   			 	return String.format('<b><font color="red">{0}</font></b>', record.data['desc_person']);
	   			 	else if(record.data['estado']=='recibido')
	   			 	return String.format('<b><font color="green">{0}</font></b>', record.data['desc_person']);
	   			 	else if(record.data['estado']=='borrador_detalle_recibido')
	   			 	return String.format('<b><font color="blue">{0}</font></b>', record.data['desc_person']);
	   			 	else
	   			 	return String.format('{0}', record.data['desc_person']);
	   			 
	   			 }
			},
			type : 'TextField',
			filters : {
				pfiltro : 'desc_person',
				type : 'string'
			},
			id_grupo : 0,
			grid : true,
			form : false,
			bottom_filter : true
		},
		{
			config : {
				name : 'acciones',
				fieldLabel : 'Acción',
				gwidth : 120
			},
			type : 'TextField',
			filters : {
				pfiltro : 'acciones',
				type : 'string'
			},
			id_grupo : 0,
			grid : true,
			form : false,
			bottom_filter : true
		},
		{
			config : {
				name : 'fecha_documento',
				fieldLabel : 'Fecha Documento.',
				allowBlank : true,

				gwidth : 120,
				renderer : function(value, p, record) {
					return value ? value.dateFormat('d/m/Y') : ''
				}
			},
			type : 'DateField',
			filters : {
				pfiltro : 'cor.fecha_documento',
				type : 'date'
			},
			id_grupo : 0,
			grid : true,
			form : false
		}, {
			config : {
				name : 'fecha_deriv',
				fieldLabel : 'Fecha Derivacion.',
				allowBlank : true,

				gwidth : 100,
				renderer : function(value, p, record) {
					return value ? value.dateFormat('d/m/Y h:i:s') : ''
				}
			},
			type : 'DateField',
			filters : {
				pfiltro : 'cor.fecha_mod',
				type : 'date'
			},
			id_grupo : 0,
			grid : true,
			form : false
		},
		{
			config : {
				name : 'fecha_recepcion',
				fieldLabel : 'Fecha Recepcion.',
				allowBlank : true,

				gwidth : 100,
				renderer : function(value, p, record) {
					return value ? value.dateFormat('d/m/Y h:i:s') : ''
				}
			},
			type : 'DateField',
			filters : {
				pfiltro : 'cor.fecha_mod',
				type : 'date'
			},
			id_grupo : 0,
			grid : true,
			form : false
		},
		{
			config : {
				name : 'estado',
				fieldLabel : 'Estado',
				gwidth : 120
			},
			type : 'TextField',
			filters : {
				pfiltro : 'estado',
				type : 'string'
			},
			id_grupo : 0,
			grid : true,
			form : false,
			bottom_filter : true
		},
		{
			config : {
				name : 'mensaje',
				fieldLabel : 'Mensaje',
				gwidth : 120
			},
			type : 'TextArea',
			filters : {
				pfiltro : 'mensaje',
				type : 'string'
			},
			id_grupo : 0,
			egrid:true,
			grid : true,
			form : false,
			bottom_filter : true
		},
		{
			config : {
				name : 'estado_reporte',
				fieldLabel : 'Estado',
				gwidth : 120
			},
			type : 'TextField',
			filters : {
				pfiltro : 'estado_reporte',
				type : 'string'
			},
			id_grupo : 0,
			default:'finalizado',
			grid : false,
			form : false,
			bottom_filter : false
		},
	],
		fileUpload:false,
	tam_pag:50,	
	title:'Histórico',
	/*ActSave:'../../sis_correspondencia/control/Adjunto/insertarAdjunto',
	ActDel:'../../sis_correspondencia/control/Adjunto/eliminarAdjunto',*/
	ActList:'../../sis_correspondencia/control/Correspondencia/verHistorico',
	id_store:'id_correspondencia',
	fields: [
		{name:'id_correspondencia', type: 'numeric'},
		{name:'id_institucion', type: 'numeric'},
        {name:'desc_person_fk', type: 'string'},
		{name:'desc_cargo_fk', type: 'string'},
		{name:'desc_person', type: 'string'},
		{name:'desc_cargo', type: 'string'},
		{name:'acciones', type: 'string'},
		{name:'mensaje', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'fecha_documento', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_deriv', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'fecha_recepcion', type: 'date',dateFormat:'Y-m-d H:i:s.u'}/*,
		
		{name:'fecha_recepcion', type: 'string'},*/
		
	],
	sortInfo:{
		field: 'id_correspondencia',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bnew: false,
	bedit:false,
		preparaMenu:function(n){

			Phx.vista.Historico.superclass.preparaMenu.call(this,n);
			var data = this.getSelectedData();

			console.log('data',data)
			var tb =this.tbar;

			return tb
		},

	}
)
</script>
		
		