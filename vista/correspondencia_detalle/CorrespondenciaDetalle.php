<?php
/**
 *@package pXP
 *@file gen-CorrespondenciaDetalle.php
 *@author  (rac)
 *@date 13-12-2011 16:13:21
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CorrespondenciaDetalle=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.CorrespondenciaDetalle.superclass.constructor.call(this,config);

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
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_correspondencia_fk'
			},
			type:'Field',
			form:true 
		},
	

		
	
		
    	
   	    
   	{
			config:{
				name: 'acciones',
				fieldLabel: 'Acciones',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'TextField',
			filters:{pfiltro:'acciones',type:'numeric'},
			id_grupo:0,
			grid:true,
			form:false
		},
   	    {
   			config:{
   				name:'id_funcionario',
   				fieldLabel:'Funcionario(s). Destino',
   				allowBlank:true,
   				emptyText:'Funcionarios...',
   				store: new Ext.data.JsonStore({  
					url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo:{
						field: 'desc_funcionario1', 
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','id_uo','codigo','nombre_cargo','desc_funcionario1','email_empresa'],
					// turn on remote sorting
					remoteSort: true,
					baseParams: {par_filtro:'desc_funcionario1#email_empresa#codigo#nombre_cargo',estado_reg_asi:'activo'}
					
				}),
				tpl:'<tpl for="."><div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}-{desc_funcionario1}</div><p style="padding-left: 20px;">{nombre_cargo}</p><p style="padding-left: 20px;">{email_empresa}</p> </div></tpl>',
			    valueField: 'id_funcionario',
   				displayField: 'desc_funcionario1',
   				/*renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_funcionario']);
				}*/
   				hiddenName: 'id_funcionarios',
   				typeAhead: true,
       			triggerAction: 'all',
       			lazyRender:true,
   				mode:'remote',
   				pageSize:10,
   				queryDelay:1000,
   				width:250,
   				minChars:2,
       			enableMultiSelect:false
       			  
   			},
   			type:'AwesomeCombo',
   			id_grupo:3,
   			grid:false,
   			form:true
   	    },
   	    {
   			config:{
       		    name:'id_funcionario',
   				origen:'FUNCIONARIOCAR',
      			 tinit:true,
   				fieldLabel:'Funcionario Destino',
   				gdisplayField:'desc_funcionario',//mapea al store del grid
   			    gwidth:200,
	   			 renderer:function (value, p, record){
	   			 	
	   			 	if(record.data['estado']=='pendiente_recibido')
	   			 	
	   			 	return String.format('<b><font color="blue">{0}</font></b>', record.data['desc_funcionario']);
	   			 	else
	   			 	return String.format('{0}', record.data['desc_funcionario']);
	   			 
	   			 }
       	     },
   			type:'ComboRec',
   			id_grupo:3,
   			filters:{	
		        pfiltro:'desc_funcionario1',
				type:'string'
			},
   		   
   			grid:true,
   			form:false
   	      },{
   			config:{
       		    name:'id_persona',
   				origen:'PERSONA',
      			 tinit:true,
   				fieldLabel:'Persona',
   				gdisplayField:'desc_persona',//mapea al store del grid
   			    gwidth:200,
	   			 renderer:function (value, p, record){return String.format('{0}', record.data['desc_persona']);}
       	     },
   			type:'ComboRec',
   			id_grupo:3,
   			filters:{	
		        pfiltro:'PERSON.nombre_completo1',
				type:'string'
			},
   		   
   			grid:false,
   			form:true
   	      },{
    	   		config:{
				name:'id_institucion',
				fieldLabel: 'Institucion',
				anchor: '90%',
				tinit:true,
				allowBlank:false,
				origen:'INSTITUCION',
				gdisplayField:'desc_institucion',
			    gwidth:200,	
			   	renderer:function (value, p, record){return String.format('{0}', record.data['desc_institucion']);}
			
			  },
			type:'ComboRec',
			id_grupo:3,
			filters:{pfiltro:'nombre',type:'string'},
			grid:false,
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
		},
		{
   			config:{
   				name:'id_acciones',
   				fieldLabel:'Acciones',
   				allowBlank:false,
   				emptyText:'Acciones...',
   				store: new Ext.data.JsonStore({
                    url: '../../sis_correspondencia/control/Accion/listarAccion',
					id: 'id_accion',
					root: 'datos',
					sortInfo:{
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_accion','nombre'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'acco.nombre'}
				}),
   				valueField: 'id_accion',
   				displayField: 'nombre',
   				gdisplayField:'desc_acciones',//mapea al store del grid
   			
   				hiddenName: 'id_acciones',
   				forceSelection:true,
   				typeAhead: true,
       			triggerAction: 'all',
       			lazyRender:true,
   				mode:'remote',
   				pageSize:10,
   				queryDelay:1000,
   				width:250,
   				gwidth:200,
   				minChars:2,
   				enableMultiSelect:true,
   				renderer:function (value, p, record){return String.format('{0}', record.data['desc_acciones']);}
   			},
   			type:'AwesomeCombo',
   			id_grupo:3,
   			/*filters:{	
   				        pfiltro:'acco.desc_acciones',
   						type:'string'
   					},*/
   		   
   			grid:false,
   			form:true
   	},
   		{
			config:{
				name: 'estado',
				fieldLabel: 'Estado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'TextField',
			filters:{pfiltro:'cor.estado',type:'numeric'},
			id_grupo:0,
			grid:true,
			form:false
		}
	],
	title:'CorrespondenciaDetalle',
	ActSave:'../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaDetalle',
	ActDel:'../../sis_correspondencia/control/Correspondencia/eliminarCorrespondencia',
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaDetalle',
	id_store:'id_correspondencia',
	fields: [
		{name:'id_correspondencia', type: 'numeric'},
		{name:'estado', type: 'numeric'},
		{name:'id_acciones', type: 'string'},
		
		{name:'id_correspondencia_fk', type: 'numeric'},
		{name:'id_correspondencias_asociadas', type: 'string'},
		
		
		{name:'id_funcionario', type: 'numeric'},
		{name:'id_gestion', type: 'numeric'},
		{name:'id_institucion', type: 'numeric'},
		{name:'id_periodo', type: 'numeric'},
		{name:'id_persona', type: 'numeric'},
		
		{name:'mensaje', type: 'string'},
		
		
		{name:'referencia', type: 'string'},
		
		{name:'sw_responsable', type: 'string'},
		{name:'tipo', type: 'string'},
		
		{name:'desc_documento', type: 'string'},
		{name:'desc_funcionario', type: 'string'},
		{name:'desc_persona', type: 'string'},
		{name:'desc_institucion', type: 'string'},
		{name:'acciones', type: 'string'}
	],
	sortInfo:{
		field: 'id_correspondencia',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	
	/*Grupos: [
	            {
	                layout: 'column',
	                border: false,
	                defaults: {
	                   border: false
	                },            
	                items: [
	                        {
	                        bodyStyle: 'padding-left:5px;padding-left:5px;',
	                        border: false,
	                        defaults: {
		     	                   border: false
		     	                },   
					        width: 500,
					        items: [
					           
	    	               			 {
	                	          	bodyStyle: 'padding-left:5px;padding-left:5px;',
						          	 items: [{
								            xtype: 'fieldset',
								            title: 'Parametros',
								           
								            //autoHeight: true,
								            items: [],
									        id_grupo:0
								        }]
								    },{
								    	 bodyStyle: 'padding-left:5px;padding-left:5px;',
								        items: [{
								            xtype: 'fieldset',
								            title: 'Datos Remitente',
								          
								           // autoHeight: true,
								            items: [],
									        id_grupo:1
								        }]
								    },
								    {
								        bodyStyle: 'padding-left:5px;padding-left:5px;',
								        items: [{
								            xtype: 'fieldset',
								          
								            title: 'Datos Destinatario',
								           // autoHeight: true,
								            items: [],
									        id_grupo:3
								        }]
								    } 
					        ]
					        },

						    {
						        bodyStyle: 'padding-left:5px;padding-left:5px;',
						        border: false,
						        width: 500,
						        items: [{
						            xtype: 'fieldset',
						         
						            title: 'Mensaje',
						            //autoHeight: true,
						            items: [],
							        id_grupo:2
						        }]
						    } ]
	            }
	           
	        ],*/

	    /* loadValoresIniciales:function(){

		Phx.vista.CorrespondenciaDetalle.superclass.loadValoresIniciales.call(this);

		 var cmbDoc = this.getComponente('id_documento');
		 var cmpFuncionarios = this.getComponente('id_funcionarios');
	     var cmpInstitucion = this.getComponente('id_institucion');
		 var cmpPersona = this.getComponente('id_persona');
			
			 this.ocultarComponente(cmpInstitucion);
	         this.ocultarComponente(cmpPersona);
	         this.mostrarComponente(cmpFuncionarios)
	         cmbDoc.store.baseParams.tipo='interna';//valor por dfecto es interna	
	         cmbDoc.modificado = true;
	         cmbDoc.reset();



        },   */    
	iniciarEventos:function(){ 
	

	/*	var cmpFuncionarios = this.getComponente('id_funcionarios');
		var cmpInstitucion = this.getComponente('id_institucion');
		var cmpPersona = this.getComponente('id_persona');
		var cmbDoc = this.getComponente('id_documento');
	*/	
		
	    //para habilitar el tipo de correspondecia para el sistema corres

	/*	this.getComponente('tipo').on('select',function(combo,record,index){
                //actualiza combos de documento segun el tipo
        		cmbDoc.store.baseParams.tipo=record.data.ID;	
                 cmbDoc.modificado = true;
                 cmbDoc.reset();

                 if(record.data.ID=='interna'){
                	 this.ocultarComponente(cmpInstitucion);
                	 this.ocultarComponente(cmpPersona);
                	 this.mostrarComponente(cmpFuncionarios);
                	 cmpPersona.reset();
                	 cmpInstitucion.reset();
                	 
                     }
                 else{
                	 this.mostrarComponente(cmpInstitucion);
                	 this.mostrarComponente(cmpPersona);
                	 this.ocultarComponente(cmpFuncionarios);
                	 cmpFuncionarios.reset();

                     }
        		
    			
		},this);*/

	},
	
	
	onReloadPage:function(m){

       
		this.maestro=m;
		this.Atributos[1].valorInicial=this.maestro.id_correspondencia;

		if(this.maestro.tipo=='interna' || this.maestro.tipo=='externa'){
			
			this.ocultarComponente(this.getComponente('id_persona'));			
			this.ocultarComponente(this.getComponente('id_institucion'));
		}
		else{
			this.ocultarComponente(this.getComponente('id_funcionario'));	
		}
		

		//actualiza combos del departamento
		// var cmbUo = this.getComponente('id_uo');
		 /*if(this.maestro.codigo=='COR'){
			 cmbUo.store.baseParams.correspondencia='si'; 
		
		 }else{
			delete  cmbUo.store.baseParams.correspondencia;
			 }
		 cmbUo.modificado = true;*/


		// this.Atributos.config['id_subsistema'].setValue(this.maestro.id_subsistema);

      /* if(m.id != 'id'){*/
    	 this.store.baseParams={id_correspondencia_fk:this.maestro.id_correspondencia};
		 this.load({params:{start:0, limit:50}})
       /*}
       else{
    	 this.grid.getTopToolbar().disable();
   		 this.grid.getBottomToolbar().disable(); 
   		 this.store.removeAll(); 
    	   
       }*/
       
       
			
	},
	preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaDetalle.superclass.preparaMenu.call(this,n);
      	
		  var data = this.getSelectedData();
		  var tb =this.tbar;
		     
		  console.log(this.maestro);
		  //cuando el conrtato esta registrado el abogado no puede hacerle mas cambios
		  if(this.maestro.estado=='enviado'){
		  		if(tb){
		  			
			  		this.getBoton('edit').disable();
			  		this.getBoton('del').disable();
			  		this.getBoton('new').disable();
			  		this.getBoton('save').disable();
			  	}
		  } 
		  return tb
		
	},
	onButtonEdit: function () {
		
		//a this.Cmp.id_funcionario.disable();
		//this.Cmp.id_funcionario.enableMultiSelect(true);	
		Phx.vista.CorrespondenciaDetalle.superclass.onButtonEdit.call(this);
	}, 

}
)
</script>

