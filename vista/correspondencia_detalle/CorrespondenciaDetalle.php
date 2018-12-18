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
		//* Creación para el combo en la grilla/
		 this.initButtons=[this.cmbEstado];
		
    	//llama al constructor de la clase padre
		Phx.vista.CorrespondenciaDetalle.superclass.constructor.call(this,config);

		this.init();
		this.bloquearMenus();
		
		this.cmbEstado.on('select', function(c,r,i){
	    	if(this.validarFiltros()){
                  this.capturaFiltros();
           }
		},this);
		
		this.cmbEstado.on('clearcmb', function() {this.DisableSelect();this.store.removeAll();}, this);
		
		/*this.getBoton('new').disable();
	    this.getBoton('save').disable();*/
		if(Phx.CP.getPagina(this.idContenedorPadre)){
      	 var dataMaestro=Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();
	 	 if(dataMaestro){ 
	 	 	
	 	 	this.onEnablePanel(this,dataMaestro)
	 	 }
	 	
	  }
	
	  //9
			this.addButton('Derivar', {
				text : 'Derivar',
				iconCls : 'badelante',
				disabled : true,
				handler : this.BDerivar,
				tooltip : '<b>Derivar</b><br/>Despues de scanear y seleccionar los destinatarios puede derivar la correspondencia'
			});
			 console.log(config);
	 	 if ((config.idContenedorPadre=='docs-CORADMG')|| (config.idContenedorPadre=='docs-ADMCORINT')){
	 	 	  this.getBoton('Derivar').show();
	 	 }else{
	 	 	this.getBoton('Derivar').hide();
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
		{name:'acciones', type: 'string'},
		{name:'estado_corre', type: 'string'}
	],
	sortInfo:{
		field: 'id_correspondencia',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	bnew:true,
	
 
	iniciarEventos:function(){ 
	
			  		this.getBoton('new').disable();
			  		this.getBoton('save').disable();
			  		
		this.cmbEstado.store.load({paraºms:{start:0,limit:this.tam_pag},
           callback : function (r) {
   		    console.log('r',r)
           		if (r.length == 1 ) {
           			this.cmbEstado.setValue(r[0].data.ID);
           			this.cmbEstado.fireEvent('select',this.cmbEstado, this.cmbEstado.store.getById(r[0].data.ID));         
                }
            }, scope : this
        });
			  
		
	},
	getParametrosFiltro: function () {
   	 	this.store.baseParams.estado = this.cmbEstado.getValue();
		
	},
	capturaFiltros:function(combo, record, index){
		this.desbloquearOrdenamientoGrid();
        this.getParametrosFiltro();
        this.load({params:{start:0, limit:50}});
	},
	
	cmbEstado: new Ext.form.ComboBox({
				
				fieldLabel : 'Estado',
				typeAhead : true,
				allowBlank : false,
				triggerAction : 'all',
				emptyText : 'Seleccione Opcion...',
				selectOnFocus : true,
				mode : 'local',
				//valorInicial:{ID:'interna',valor:'Interna'},
				store : new Ext.data.ArrayStore({
					fields : ['ID', 'valor'],
					data : [['activo', 'Activos'], ['anulado', 'Anulado']]
				}),
				valueField : 'ID',
				displayField : 'valor',
				width : 150
		}),
		
	validarFiltros:function(){
		
        if(this.cmbEstado.isValid()){// && this.cmbTipoPres.validate()){
            return true;
        }
        else{
            return false;
        }
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
		
         if(this.maestro.estado=='enviado'){
		  	  		this.getBoton('edit').disable();
			  		this.getBoton('del').disable();
			  		this.getBoton('Derivar').enable();
			  		
		  } else {
		 	    if (this.maestro.estado=='pendiente_recibido'){
                  	
                  		this.getBoton('new').disable();
			  			this.getBoton('Derivar').disable();
			  		
		  			}else{
		 	
			  		this.getBoton('edit').enable();
			  		this.getBoton('del').enable();
			  		this.getBoton('new').enable();
			  		this.getBoton('Derivar').disable();
			  		}
			   }
	
		 
    	 this.store.baseParams={id_correspondencia_fk:this.maestro.id_correspondencia};
		 this.load({params:{start:0, limit:50}})
   	},
	preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaDetalle.superclass.preparaMenu.call(this,n);
      	
		  var data = this.getSelectedData();
		  var tb =this.tbar;
		    if(this.maestro.estado=='enviado' ){
		   	if(tb){
		  			if (data.estado_corre=='borrador_corre'){
		  				this.getBoton('edit').enable();
					  	this.getBoton('Derivar').enable();	
		  				if (data.estado=='enviado'){
		  			    	this.getBoton('del').disable();
					  		
						}else{
				 			this.getBoton('del').enable();
					  		
					    }
					}   
		  			else{
					  		this.getBoton('edit').disable();
					  		this.getBoton('del').disable();
					  		this.getBoton('Derivar').enable();
					  		
			  		}
			  			}
		  } else {
		 		if(tb){
		  			
			  		this.getBoton('edit').enable();
			  		this.getBoton('del').enable();
			  		this.getBoton('new').enable();
			  		this.getBoton('save').enable();
			  		this.getBoton('Derivar').disable();
			  		
			  	}
		  	
		  }
		  
		  if (data['estado']=='borrador_detalle_recibido') {
		  	   if(this.maestro.estado=='enviado' ){
		  	     this.getBoton('Derivar').enable();
		  	    }
		  	   else{
		  	     this.getBoton('Derivar').disable();
		  	   }
		  	   
		  	     this.getBoton('edit').enable();
		  	     this.getBoton('del').enable();
		  }else{
		  	 	
		  	 	if (this.maestro.estado_corre=='borrador_corre'){
		  				this.getBoton('edit').enable();
					  	this.getBoton('Derivar').enable();	
		  				if (data.estado=='enviado'){
		  					this.getBoton('del').disable();
					  		
						}else{
				 			this.getBoton('del').enable();
					  		
					    }
					} else{ 
		  	 	
					  	 	this.getBoton('Derivar').disable();
					  	 	this.getBoton('edit').disable();
					  	    this.getBoton('del').disable();
		  	         }
		  }
		  
		   
		
		  return tb
		
	},
	onButtonEdit: function () {
		
		//a this.Cmp.id_funcionario.disable();
		//this.Cmp.id_funcionario.enableMultiSelect(true);	
		Phx.vista.CorrespondenciaDetalle.superclass.onButtonEdit.call(this);
		  	this.ocultarComponente(this.Cmp.id_funcionario);
		      
	}, 
	onButtonNew: function () {
		
		//a this.Cmp.id_funcionario.disable();
		//this.Cmp.id_funcionario.enableMultiSelect(true);	
		Phx.vista.CorrespondenciaDetalle.superclass.onButtonNew.call(this);
		this.mostrarComponente(this.Cmp.id_funcionario);
	},
	//9
	BDerivar : function() {

			var rec = this.sm.getSelected();
			var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
			if (confirm('Esta seguro de DERIVAR el documento  ?')){
         	Phx.CP.loadingShow();
			Ext.Ajax.request({
				url : '../../sis_correspondencia/control/Correspondencia/derivarCorrespondencia',
				params : {
					id_correspondencia : this.maestro.id_correspondencia,
					id_origen          : this.maestro.id_origen
					/*id_correspondencia : id_correspondencia,
					id_origen          : this.maestro.id_origen*/
				},
				success : this.successDerivar,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			});
		   }
		},
			successDerivar : function(resp) {

			Phx.CP.loadingHide();
			var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
			if (!reg.ROOT.error) {
				alert(reg.ROOT.detalle.mensaje)

			}
			this.reload();

		}

}
)
</script>