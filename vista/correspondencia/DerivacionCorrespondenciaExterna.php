<?php
/**
*@package pXP
*@file gen-SistemaDist.php
*@author  (ffigueroa alias el conejo)
*@date 20-09-2016 10:22:05
*@description Archivo con la interfaz de usuario que permite 
*dar el visto a solicitudes de compra
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.DerivacionCorrespondenciaExterna = {
    bsave:false,	
	bnew:false,
	bedit:true,
	bdel:true,
	swEstado: 'pendiente_recepcion_externo',
	gruposBarraTareas: [{
		name: 'pendiente_recepcion_externo',
		title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Pendientes</h1>',
		grupo: 0,
		height: 0
	},
		{
			name: 'enviado',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Derivados</h1>',
			grupo: 1,
			height: 0
		}

	],

	beditGroups: [0, 1],
	bactGroups: [0, 1],
	btestGroups: [0,1],
	bexcelGroups: [0, 1],
	

    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'DerivacionCorrespondenciaExterna',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaExterna',
	ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaExterna',

	constructor: function(config) {
		
		this.Atributos[this.getIndAtributo('id_funcionario')].grid=false;
		this.Atributos[this.getIndAtributo('id_documento')].grid=false;
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
		   
		
	
        Phx.vista.DerivacionCorrespondenciaExterna.superclass.constructor.call(this,config);


	//	this.bloquearOrdenamientoGrid();
         this.addButton('ImpBorrador', {
				text: 'Vista Previa',
				iconCls: 'bprintcheck',
				disabled: false,
				handler: this.BImpBorrador,
				tooltip: '<b>Vista Previa</b><br/> Vista Previa Correspondencia'
			});
            
		 
	        this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('Habilitar').hide();
		this.init();
        this.store.baseParams = {'vista': 'derivacion_correspondencia_externa','estado': this.swEstado,'tipo': this.tipo};
        
       

        this.load({params: {start: 0, limit: 50}})


    
   },
	getParametrosFiltro: function () {
		this.store.baseParams.estado = this.swEstado;
	},

	actualizarSegunTab: function (name, indice) {
		console.log('derivar',name);
         this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('Habilitar').hide();
                this.getBoton('ImpBorrador').enable();
	
		if(name=='enviado'){
			this.getBoton('HojaRuta').show();
			this.getBoton('Historico').show();
			this.getBoton('Adjuntos').show();
			this.getBoton('SubirDocumento').show();

			this.getBoton('ImpBorrador').hide();
			//this.getBoton('anularCorrespondencia').show();
			//this.getBoton('Corregir').hide();

			this.getBoton('HojaRuta').enable();
			this.getBoton('Historico').enable();
			this.getBoton('Archivar').enable();
			
			 this.getBoton('del').hide();
			 
		}else{
			
			this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('Adjuntos').show();
            this.getBoton('Corregir').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('ImpBorrador').show();
            this.getBoton('ImpBorrador').enable();
		}
		
		

		this.swEstado = name;
		this.getParametrosFiltro();
		this.load();
		

	},

    preparaMenu:function(n){
      	
      	Phx.vista.DerivacionCorrespondenciaExterna.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		  console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
		    this.getBoton('Adjuntos').enable();
			this.getBoton('Corregir').enable();
			this.getBoton('VerDocumento').enable();
			this.getBoton('Derivar').enable();
			this.getBoton('SubirDocumento').enable();
			if (data['estado'] =='enviado'){
			
				this.getBoton('edit').disable();
				this.getBoton('SubirDocumento').enable();
				//this.getBoton('del').disable();
			}
			
   	     return tb
		
	},
	onButtonEdit: function () {
		
		
		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
	
		Phx.vista.DerivacionCorrespondenciaExterna.superclass.onButtonEdit.call(this);
        this.adminGrupo({ ocultar: [0,3,4],mostrar:[2]});
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
		

		this.tipo.setValue('externa');
		this.tipo.disable(true);

		

		 this.ocultarComponente(this.Cmp.id_funcionario);

	
	},
	SubirCorrespondencia: function () {
		var rec = this.sm.getSelected();


		Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/subirCorrespondencia.php',
			'Subir Correspondencia',
			{
				modal: true,
				width: 500,
				height: 250
			}, rec.data, this.idContenedor, 'subirCorrespondencia')
	},

	imprimirCodigoCorrespondencia : function () {
		var rec = this.sm.getSelected();

	},
		BImpBorrador : function() {
			
			var rec = this.sm.getSelected();
			Ext.Ajax.request({
				url : '../../sis_correspondencia/control/Correspondencia/hojaRutaBorrador',
				params : {
					id_correspondencia : rec.data.id_correspondencia,
					id_origen: rec.data.id_origen,
					tipo_corres:rec.data.tipo,
					estado_reporte:'borrador',
					start : 0,
					limit : 1
				},
				success : this.successHojaRuta,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			});

		},  
	finalizarRecepcionExterna:function () {

		var rec = this.sm.getSelected();
		Ext.Ajax.request({
			url: '../../sis_correspondencia/control/Correspondencia/finalizarRecepcionExterna',
			params: {
				id_correspondencia: rec.data.id_correspondencia,
				estado: 'pendiente_recepcion_externo'
			},
			success: this.successDespachar,
			failure: this.conexionFailure,
			timeout: this.timeout,
			scope: this
		});

	}

	
	
};
</script>
