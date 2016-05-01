<?php
/**
*@package pXP
*@file gen-DocumentoFisico.php
*@author  (admin)
*@date 27-04-2016 16:45:39
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.DocumentoFisico=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.DocumentoFisico.superclass.constructor.call(this,config);
		//this.init();
		//this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_documento_fisico'
			},
			type:'Field',
			form:true 
		},


		{
			config: {
				name: 'version',
				fieldLabel: 'Icono',
				gwidth: 60,
				renderer: function (value, p, record) {
					var icono = record.data.estado+'.png';
					console.log('estado',record.data.estado)
					console.log('icono',icono)
					return "<div style='text-align:center'><img src = '../../../sis_correspondencia/imagenes/" + record.data.estado + ".png' align='center' width='40' height='40'/></div>"
				}
			},
			type: 'Field',
			egrid: true,
			filters: {pfiltro: 'cor.version', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: false
		},

		{
			config:{
				name: 'numero',
				fieldLabel: 'numero',
				allowBlank: true,
				anchor: '80%',
				gwidth: 150,
				maxLength:255
			},
			type:'TextField',
			filters:{pfiltro:'cor.numero',type:'string'},
			id_grupo:1,
			grid:true,
			form:true,
			bottom_filter: true

		},


		{
			config: {
				name: 'desc_person_padre',
				fieldLabel: 'De:',
				gwidth: 150,
				renderer: function (value, p, record) {
					var icono = record.data.estado+'.png';
					console.log('estado',record.data.estado)
					console.log('icono',icono)
					return value + "<br /><b>" +record.data.desc_depto_padre+"</b>";
				}
			},
			type: 'Field',
			egrid: true,
			filters: {pfiltro: 'per_padre.nombre_completo2', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: false,
			bottom_filter: true
		},



		{
			config: {
				name: 'desc_person',
				fieldLabel: 'Para:',
				gwidth: 150,
				renderer: function (value, p, record) {
					var icono = record.data.estado+'.png';
					console.log('estado',record.data.estado)
					console.log('icono',icono)
					return value + "<br /><b>" +record.data.desc_depto+"</b>";
				}
			},
			type: 'Field',
			egrid: true,
			filters: {pfiltro: 'per.nombre_completo2', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: false,
			bottom_filter: true
		},


		{
			config:{
				name: 'estado',
				fieldLabel: 'estado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:255
			},
				type:'TextField',
				filters:{pfiltro:'docfis.estado',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'docfis.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'docfis.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'docfis.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'docfis.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'docfis.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Documento Fisico',
	ActSave:'../../sis_correspondencia/control/DocumentoFisico/insertarDocumentoFisico',
	ActDel:'../../sis_correspondencia/control/DocumentoFisico/eliminarDocumentoFisico',
	ActList:'../../sis_correspondencia/control/DocumentoFisico/listarDocumentoFisico',
	id_store:'id_documento_fisico',
	fields: [
		{name:'id_documento_fisico', type: 'numeric'},
		{name:'id_correspondencia', type: 'numeric'},
		{name:'id_correspondencia_padre', type: 'numeric'},
		{name:'estado', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_person', type: 'string'},
		{name:'desc_person_padre', type: 'string'},
		{name:'desc_depto', type: 'string'},
		{name:'desc_depto_padre', type: 'string'},
		{name:'numero', type: 'string'},

	],
	sortInfo:{
		field: 'id_documento_fisico',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		