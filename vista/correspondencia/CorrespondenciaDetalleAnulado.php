<?php
/**
 *@package pXP
 *@file CorrespondenciaDetalleAnulado.php
 *@author  (jos)
 *@date 11-12-2018 16:13:21
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CorrespondenciaDetalleAnulado=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.CorrespondenciaDetalleAnulado.superclass.constructor.call(this,config);

		this.init();
		this.bloquearMenus();
		if(Phx.CP.getPagina(this.idContenedorPadre)){
      	 var dataMaestro=Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();
	 	 if(dataMaestro){ 
	 	 	this.onEnablePanel(this,dataMaestro)
	 	 }
	  }
		
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
   			config:{
   				name:'desc_funcionario',
   				fieldLabel:'Funcionario(s). Destino',
   			
			
			    valueField: 'id_funcionario',
   				displayField: 'desc_funcionario',
   				
   				width:350,
   			
   			},
   			type:'Field',
   			id_grupo:3,
   			grid:true,
   			form:true
   	    },
		{
   			config:{
   				name:'acciones',
   				fieldLabel:'Acciones',
   				valueField: 'id_accion',
   				displayField: 'acciones',
   				gdisplayField:'acciones',//mapea al store del grid
   			},
   			type:'Field',
   			id_grupo:3,
   			
   		   
   			grid:true,
   			form:true
   		},
   		{
			config:{
				name: 'mensaje',
				fieldLabel: 'Mensaje',
				allowBlank: true,
				width: 300,
				growMin:100,
				grow : true,
				gwidth: 100
			},
			type:'TextArea',
			filters:{pfiltro:'cor.mensaje',type:'string'},
			id_grupo:2,
			grid:true,
			form:true
		}
	],
	title:'CorrespondenciaDetalleAnulado',
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaDetalleAnulado',
	id_store:'id_correspondencia',
	fields: [
		{name:'id_correspondencia', type: 'numeric'},
		{name:'estado', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'fecha_documento', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name:'fecha_fin', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name:'id_acciones', type: 'string'},
		{name:'id_archivo', type: 'numeric'},
		{name:'id_correspondencia_fk', type: 'numeric'},
		{name:'id_correspondencias_asociadas', type: 'string'},
		{name:'id_depto', type: 'numeric'},
		{name:'id_documento', type: 'numeric'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'id_gestion', type: 'numeric'},
		{name:'id_institucion', type: 'numeric'},
		{name:'id_periodo', type: 'numeric'},
		{name:'id_persona', type: 'numeric'},
		{name:'id_uo', type: 'numeric'},
		{name:'mensaje', type: 'string'},
		{name:'nivel', type: 'numeric'},
		{name:'nivel_prioridad', type: 'string'},
		{name:'numero', type: 'string'},
		{name:'observaciones_estado', type: 'string'},
		{name:'referencia', type: 'string'},
		{name:'respuestas', type: 'string'},
		{name:'sw_responsable', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'fecha_reg', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},'cite',
		{name:'desc_depto', type: 'string'},
		{name:'desc_documento', type: 'string'},
		{name:'desc_funcionario', type: 'string'},
		{name:'acciones', type: 'string'}
	],
	sortInfo:{
		field: 'id_correspondencia',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bedit:false,
	bnew:false,

	/*iniciarEventos:function(){ 
	
	},*/
	onReloadPage:function(m){

       
		this.maestro=m;
		this.Atributos[1].valorInicial=this.maestro.id_correspondencia;

	         this.store.baseParams={id_correspondencia_padre:this.maestro.id_correspondencia};
		 this.load({params:{start:0, limit:50}})

	},

}
)
</script>

