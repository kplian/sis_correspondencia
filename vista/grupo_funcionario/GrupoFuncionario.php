<?php
/*
ISSUE            FECHA:		      AUTOR                 DESCRIPCION
#7   		06/09/2019			manuel guerra		correccion de bug(padre-hijo)
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.GrupoFuncionario=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.GrupoFuncionario.superclass.constructor.call(this,config);
		this.init();
		this.bloquearMenus();
	},

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_grupo_funcionario'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
			    labelSeparator:'',
				name: 'id_grupo',
				inputType:'hidden'
			},
			type:'Field',
			form:true
		},
		{
		config:{
	   		 name:'id_funcionario',
					origen:'FUNCIONARIO',
					fieldLabel:'Funcionario',
					allowBlank:false,
					gwidth: 250, 				
					valueField: 'id_funcionario',
				     gdisplayField: 'desc_funcionario1',
	  			 renderer:function(value, p, record){return String.format('{0}', record.data['desc_funcionario1']);}
	   	    	 },
				type:'ComboRec',
				id_grupo:0,
				filters:{   pfiltro:'funcio.desc_person',
					type:'string'
				},
			    grid:true,
				form:true
	      },
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
			type:'TextField',
			filters:{pfiltro:'funa.estado_reg',type:'string'},
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
			type:'TextField',
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
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'funa.fecha_reg',type:'date'},
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
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'funa.fecha_mod',type:'date'},
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
			type:'TextField',
			filters:{pfiltro:'usu2.cuenta',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
		}
	],
	title:'Funcionarios Agrupados',
	ActSave:'../../sis_correspondencia/control/GrupoFuncionario/insertarGrupoFuncionario',
	ActDel:'../../sis_correspondencia/control/GrupoFuncionario/eliminarGrupoFuncionario',
	ActList:'../../sis_correspondencia/control/GrupoFuncionario/listarGrupoFuncionario',
	id_store:'id_grupo_funcionario',
	fields: [
		{name:'id_grupo_funcionario', type: 'numeric'},
		{name:'id_grupo', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name:'fecha_mod', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		'desc_funcionario1'
		
	],
	sortInfo:{
		field: 'id_grupo_funcionario',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
    //
    onReloadPage:function(m)
    {
        this.maestro=m;
        this.store.baseParams={id_grupo:this.maestro.id_grupo};
        this.load({params:{start:0, limit:this.tam_pag}});
    },
    //#7
    loadValoresIniciales:function()
    {
        Phx.vista.GrupoFuncionario.superclass.loadValoresIniciales.call(this);
        this.Cmp.id_grupo.setValue(this.maestro.id_grupo);
    },
})
</script>
		
		