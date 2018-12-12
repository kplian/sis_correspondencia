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
	    
	    this.Atributos[this.getIndAtributo('id_funcionario')].grid=false;
	    this.Atributos[this.getIndAtributo('id_uo')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionario_saliente')].grid=false;
        this.Atributos[this.getIndAtributo('id_institucion_destino')].grid=false;
        this.Atributos[this.getIndAtributo('id_persona_destino')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionarios')].grid=false;
        this.Atributos[this.getIndAtributo('asociar')].grid=false;
        this.Atributos[this.getIndAtributo('id_correspondencias_asociadas')].grid=false;
        this.Atributos[this.getIndAtributo('id_acciones')].grid=false;
        this.Atributos[this.getIndAtributo('observaciones_archivado')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionario_destino')].grid=false;
        this.Atributos[this.getIndAtributo('fecha_ult_derivado')].grid=false;
        
	    Phx.vista.RecepcionCorrespondenciaExterna.superclass.constructor.call(this,config);
	    
	    this.addButton('Finalizar', {
				text: 'Finalizar Recepción',
				iconCls: 'bgood',
				disabled: true,
				handler: this.BFinalizar,
				tooltip: '<b>Finalizar Recepción</b><br/>Finalizar el registro del Documento'
			});

            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('Corregir').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('Habilitar').hide();
		  
		this.init();  
		this.store.baseParams = {'tipo': this.tipo,'estado': this.swEstado};
        this.load({params: {start: 0, limit: 50}})

		//this.iniciarEventos();
    
   },
   iniciarEventos(){
	   	this.Cmp.id_institucion_remitente.on('select',function(combo,record,index){
	    	this.Cmp.id_persona_remitente.store.baseParams.id_institucion=combo.getValue();
	   		this.Cmp.id_persona_remitente.reset();
	   		this.Cmp.id_persona_remitente.modificado=true;
	   		
	   	},this)
   },
   east : undefined,
   
  
	getParametrosFiltro: function () {
		this.store.baseParams.estado = this.swEstado;
	},
	actualizarSegunTab: function (name, indice) {
		console.log(name);
            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('Corregir').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
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
			//this.getBoton('SubirDocumento').disable();
			this.getBoton('Adjuntos').disable();
			this.getBoton('VerDocumento').enable();
			this.getBoton('Corregir').enable();
			this.getBoton('Finalizar').disable();
			this.getBoton('ImpCodigo').disable();
		    this.getBoton('ImpCodigoDoc').disable();
		    this.getBoton('edit').disable();
			this.getBoton('del').disable();
			 this.getBoton('HojaRuta').enable();
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
		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
		
		Phx.vista.RecepcionCorrespondenciaExterna.superclass.onButtonNew.call(this);
    	
    	this.ocultarComponente(this.Cmp.id_funcionario);
		this.ocultarComponente(this.Cmp.id_uo);
		this.ocultarComponente(this.Cmp.id_funcionario_saliente);
		this.ocultarComponente(this.Cmp.id_persona_destino);
		this.ocultarComponente(this.Cmp.id_institucion_destino);
		this.ocultarComponente(this.Cmp.id_funcionarios);
		this.ocultarComponente(this.Cmp.asociar);
		this.ocultarComponente(this.Cmp.id_correspondencias_asociadas);
		this.ocultarComponente(this.Cmp.id_acciones);
		this.ocultarComponente(this.Cmp.fecha_creacion_documento);
		  
		this.adminGrupo({ ocultar: [3], mostrar:[0]});

		this.tipo.setValue('externa');
		this.tipo.disable(true);
	
		cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es externa
		cmbDoc.modificado = true;
		cmbDoc.reset();


		

	},
	
	onButtonEdit: function () {
		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
	
		Phx.vista.RecepcionCorrespondenciaExterna.superclass.onButtonEdit.call(this);
		
        this.ocultarComponente(this.Cmp.id_funcionario);
		this.ocultarComponente(this.Cmp.id_uo);
		this.ocultarComponente(this.Cmp.id_funcionario_saliente);
		this.ocultarComponente(this.Cmp.id_persona_destino);
		this.ocultarComponente(this.Cmp.id_institucion_destino);
		this.ocultarComponente(this.Cmp.id_funcionarios);
		this.ocultarComponente(this.Cmp.asociar);
		this.ocultarComponente(this.Cmp.id_correspondencias_asociadas);
		this.ocultarComponente(this.Cmp.id_acciones);
		  

		this.adminGrupo({ ocultar: [0,3]});

		this.tipo.setValue('externa');
		this.tipo.disable(true);
  	    this.ocultarComponente(this.Cmp.id_funcionario);
	}

	
};
</script>
