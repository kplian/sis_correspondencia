<?php
/**
*@package pXP
*@file gen-Adjunto.php
*@author  (admin)
*@date 22-04-2016 23:13:29
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Adjunto=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Adjunto.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag,id_origen:this.id_origen}})
		this.argumentExtraSubmit={'id_correspondencia_origen':this.id_origen};


		this.addButton('VerArchivoAdjunto', {
			text: 'Ver Archivo Adjunto',
			iconCls: 'bsee',
			disabled: true,
			handler: this.verArchivoAdjunto,
			tooltip: '<b>Ver Archivo Adjunto</b><br/>'
		});

	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_adjunto'
			},
			type:'Field',
			form:true 
		},


		{
			config: {
				fieldLabel: "Documento (archivo)",
				gwidth: 130,
				inputType: 'file',
				name: 'archivo[]',
				allowBlank: false,
				buttonText: '',
				maxLength: 150,
				anchor: '100%',
				listeners: {
					render: function (me, eOpts) {

						var el = Ext.get(me.id);
						console.log(el)
						el.set({
							multiple: 'multiple'
						});

					}
				}

			},
			type: 'Field',
			form: true
		},

		{
			config:{
				name: 'extension',
				fieldLabel: 'extension',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:255
			},
				type:'TextField',
				filters:{pfiltro:'adj.extension',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config: {
				name: 'id_correspondencia_origen',
				fieldLabel: 'id_correspondencia_origen',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_',
				displayField: 'nombre',
				gdisplayField: 'desc_',
				hiddenName: 'id_correspondencia_origen',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: false
		},
		{
			config:{
				name: 'nombre_archivo',
				fieldLabel: 'nombre_archivo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:255
			},
				type:'TextField',
				filters:{pfiltro:'adj.nombre_archivo',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
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
				filters:{pfiltro:'adj.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'ruta_archivo',
				fieldLabel: 'ruta_archivo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:255
			},
				type:'TextField',
				filters:{pfiltro:'adj.ruta_archivo',type:'string'},
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
				filters:{pfiltro:'adj.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'adj.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
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
				filters:{pfiltro:'adj.usuario_ai',type:'string'},
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
				filters:{pfiltro:'adj.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
		fileUpload:true,
	tam_pag:50,	
	title:'Adjunto',
	ActSave:'../../sis_correspondencia/control/Adjunto/insertarAdjunto',
	ActDel:'../../sis_correspondencia/control/Adjunto/eliminarAdjunto',
	ActList:'../../sis_correspondencia/control/Adjunto/listarAdjunto',
	id_store:'id_adjunto',
	fields: [
		{name:'id_adjunto', type: 'numeric'},
		{name:'extension', type: 'string'},
		{name:'id_correspondencia_origen', type: 'numeric'},
		{name:'nombre_archivo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'ruta_archivo', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_adjunto',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
		preparaMenu:function(n){

			Phx.vista.Adjunto.superclass.preparaMenu.call(this,n);
			var data = this.getSelectedData();

			console.log('data',data)
			var tb =this.tbar;
			//si el archivo esta escaneado se permite visualizar
			if(data != undefined){
				this.getBoton('VerArchivoAdjunto').enable();

			}
			else{
				this.getBoton('VerArchivoAdjunto').enable(); //aqui esta disable


			}




			return tb

		},

		verArchivoAdjunto:function(){


			var data = this.getSelectedData();

			var nombre_archivo = data.nombre_archivo+'.'+data.extension;
			window.open(data.ruta_archivo);

		}
	}
)
</script>
		
		