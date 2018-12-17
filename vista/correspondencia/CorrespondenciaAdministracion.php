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
Phx.vista.CorrespondenciaAdministracion = {
    bsave:false,
    bedit:true,
    bdel:true,
    tipo_interfaz:'',
	swEstado: 'anulado',
	urlDepto:'../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
	gruposBarraTareas: [
		{
			name: 'anulado',
			title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Anulados</h1>',
			grupo: 0,
			height: 0
		},
		{
			name: 'enviado',
			title: '<H1 align="center"><i class="fa fa-eye"></i> Enviados</h1>',
			grupo: 1,
			height:   0
		},
		{ 
			name: 'borrador',
			title: '<H1 align="center"><i class="fa fa-eye"></i>Nuevo</h1>',
			grupo: 2,
			height:   0
		}
	],
	
		
	beditGroups: [1, 1],
	bactGroups: [0, 1],
	btestGroups: [0,0],
	bexcelGroups: [0, 1],


    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Administracion',
	nombreVista: 'CorrespondenciaAdministracion',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaExterna',
	ActSave: '../../sis_correspondencia/control/Correspondencia/insertarCorrespondenciaExterna',
   constructor: function(config) {
	        
          
       this.Atributos[this.getIndAtributo('id_documento')].grid=false;
		this.Atributos[this.getIndAtributo('id_uo')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionario_saliente')].grid=false;
        this.Atributos[this.getIndAtributo('id_institucion_destino')].grid=false;
        this.Atributos[this.getIndAtributo('id_persona_destino')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionarios')].grid=false;
        this.Atributos[this.getIndAtributo('asociar')].grid=false;
        this.Atributos[this.getIndAtributo('id_correspondencias_asociadas')].grid=false;
        this.Atributos[this.getIndAtributo('observaciones_archivado')].grid=false;
        this.Atributos[this.getIndAtributo('id_funcionario_destino')].grid=false;
        this.Atributos[this.getIndAtributo('fecha_ult_derivado')].grid=false;
        this.Atributos[this.getIndAtributo('archivado_imagen')].grid=true;
       
         
         if (config.tipo=='interna'){
        	this.Atributos[this.getIndAtributo('cite')].grid=false;
		    this.Atributos[this.getIndAtributo('id_institucion_remitente')].grid=false;
		    this.Atributos[this.getIndAtributo('id_persona_remitente')].grid=false;
		    this.Atributos[this.getIndAtributo('otros_adjuntos')].grid=false;
		    this.Atributos[this.getIndAtributo('nro_paginas')].grid=false;
		    this.Atributos[this.getIndAtributo('id_correspondencias_asociadas')].grid=true;
		    this.Atributos[this.getIndAtributo('id_documento')].grid=true;
		    this.Atributos[this.getIndAtributo('id_uo')].grid=true;
	      }
	    Phx.vista.CorrespondenciaAdministracion.superclass.constructor.call(this,config);
	    
	  	this.addButton('HabCorregir', {
				grupo : [0,1],
				text : 'Habilitar Corrección',
				iconCls : 'bundo',
				disabled : false,
				handler : this.BHabCorregir,
				tooltip : '<b>Corregir</b><br/>La corrección hará que se pueda modificar la correspondencia,siempre y cuando se tenga una solicitud formal del dueño de la correspondencia.'
			});   
		this.addButton('FinCorregir', {
				grupo : [0,1],
				text : 'Finalizar Corrección',
				iconCls : 'bundo',
				disabled : true,
				handler : this.BFinCorregir,
				tooltip : '<b>Corregir</b><br/>Finalización de la corrección.'
			});   

            //this.bloquearOrdenamientoGrid();
            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('Adjuntos').hide();
            this.getBoton('Corregir').hide();
            this.getBoton('VerDocumento').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
		
		this.init();  
		this.argumentExtraSubmit={'vista':'CorrespondenciaAdministracion'};
        this.tipo_interfaz=config.tipo;
        this.store.baseParams = {'tipo': this.tipo,'estado': this.swEstado,'vista':'CorrespondenciaAdministracion'};
        this.load({params: {start: 0, limit: 50}})
     	this.iniciarEventos();
    
   },
   iniciarEventos(){
	   	this.Cmp.id_institucion_remitente.on('select',function(combo,record,index){
	    	this.Cmp.id_persona_remitente.store.baseParams.id_institucion=combo.getValue();
	   		this.Cmp.id_persona_remitente.reset();
	   		this.Cmp.id_persona_remitente.modificado=true;
	   		
	   	},this)
   },
   
  //east : undefined,
   south : {
			
			 url: '../../../sis_correspondencia/vista/correspondencia/CorrespondenciaDetalleAnulado.php',
				
			cls : 'CorrespondenciaDetalleAnulado',
			title : 'Detalle de Derivación Anulados',
			height : '50%'
		},
   	getParametrosFiltro: function () {
   	 	this.store.baseParams.estado = this.swEstado;
		
	},
	actualizarSegunTab: function (name, indice) {
			var data = this.getSelectedData();

            this.getBoton('Plantilla').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('Adjuntos').hide();
            this.getBoton('Corregir').hide();
            this.getBoton('VerDocumento').hide();
            this.getBoton('ImpCodigo').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
        
           
		 if(name=='anulado'){
           this.getBoton('edit').hide();
           this.getBoton('new').hide();
           this.getBoton('del').hide();
           this.getBoton('Adjuntos').hide();
           this.getBoton('HabCorregir').hide();
           this.getBoton('FinCorregir').hide();
        	 
        }else if (name=='enviado'){
            this.getBoton('new').hide();
            this.getBoton('SubirDocumento').show();
            this.getBoton('Adjuntos').enable();
            this.getBoton('VerDocumento').show();
            this.getBoton('HojaRuta').show();
            this.getBoton('Historico').show();
            this.getBoton('Adjuntos').show();
            this.getBoton('del').show(); 
            this.getBoton('HabCorregir').show();
            this.getBoton('FinCorregir').show();
        
            
        	
        }else{
        	 this.getBoton('new').show();
        	 this.getBoton('edit').show();
        	 this.getBoton('del').enable();
             this.getBoton('SubirDocumento').show();
             this.getBoton('Adjuntos').enable();
             this.getBoton('Historico').show();
             this.getBoton('del').show();
             this.getBoton('Adjuntos').show();
             this.getBoton('HabCorregir').hide();
             this.getBoton('FinCorregir').hide();
     
        }
        
		if (name=='borrador'){
			this.swEstado = this.estado;
			
		}else{
		    this.swEstado = name;
			
		}
		this.getParametrosFiltro();
		this.load();
	

	},
	
	
    preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaAdministracion.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
		  if (data.estado_corre=='borrador_corre'){
		  	this.getBoton('HabCorregir').disable();
            this.getBoton('FinCorregir').enable();
            if (data.id_correspondencia_fk == undefined ){
			  this.getBoton('edit').enable();
			}else{
		      this.getBoton('edit').disable();
				
			}
       	 } else {
       	 	this.getBoton('edit').disable();
       	 	this.getBoton('del').disable();
       	    this.getBoton('HabCorregir').enable();
       	    this.getBoton('SubirDocumento').disable();
       	    this.getBoton('Adjuntos').disable();
       	    this.getBoton('VerDocumento').disable();
       	    this.getBoton('HojaRuta').disable();
       	    this.getBoton('Historico').disable();
            this.getBoton('FinCorregir').disable();
            if (data.estado=='borrador_envio' || data.estado=='borrador_recepcion_externo'){
            	this.getBoton('del').enable();
            	this.getBoton('edit').enable();
       	   
            }else{
            	this.getBoton('del').disable();
            	this.getBoton('edit').disable();
       	   
            }
       	 	
       	 }
     
		 return tb
		
	},
	
  BHabCorregir : function() {
			
			var rec = this.sm.getSelected();
			var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
			   
			   var result = prompt('Especifique las razones por las que se corrige el Documento'+rec.data.numero);
			   if(confirm('Esta seguro de corregir la derivación?'+rec.data.numero)){
			   Phx.CP.loadingShow();
			
			 	   
				Ext.Ajax.request({
				url : '../../sis_correspondencia/control/Correspondencia/HabcorregirCorrespondencia',
				params : {
					id_correspondencia : id_correspondencia,
					estado_corre:'borrador_corre',
					tipo:this.getComponente('tipo').getValue(),
					observaciones_estado:result
				},
				success : this.successDerivar,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			    });
			}
		},
	BFinCorregir : function() {
			
			var rec = this.sm.getSelected();
			var id_correspondencia = this.sm.getSelected().data.id_correspondencia;
			   
			 	   
				Ext.Ajax.request({
				url : '../../sis_correspondencia/control/Correspondencia/HabcorregirCorrespondencia',
				params : {
					id_correspondencia : id_correspondencia,
					estado_corre:'corregido',
					tipo:this.getComponente('tipo').getValue(),
					observaciones_estado:rec.data.observaciones_estado+'Fin '
				},
				success : this.successDerivar,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			    });
			
		},
	onButtonNew: function () {
		
         Phx.vista.CorrespondenciaAdministracion.superclass.onButtonNew.call(this);
         
    	var cmbDoc = this.getComponente('id_documento');
		
		if (this.tipo_interfaz=='externa'){
			    this.adminGrupo({ ocultar: [3,4], mostrar:[0,2,1]});
	          	this.Cmp.id_institucion_destino.hide();
		        this.Cmp.id_persona_destino.hide();
		        this.Cmp.id_acciones.hide();
		      	this.ocultarComponente(this.Cmp.id_persona_destino);
		        this.ocultarComponente(this.Cmp.id_institucion_destino);
		        this.ocultarComponente(this.Cmp.id_acciones);
         		this.ocultarComponente(this.Cmp.id_funcionario);
       	    	this.ocultarComponente(this.Cmp.asociar);
         		this.ocultarComponente(this.Cmp.id_correspondencias_asociadas);
         		
		
		}else{  this.adminGrupo({ ocultar: [3,4], mostrar:[0,2,1]});
		       	this.Cmp.id_institucion_destino.hide();
		        this.Cmp.id_persona_destino.hide();
		        this.Cmp.id_institucion_remitente.hide();
		        this.Cmp.id_persona_remitente.hide();
	            this.ocultarComponente(this.Cmp.id_persona_destino);
		        this.ocultarComponente(this.Cmp.id_institucion_destino);
		        this.ocultarComponente(this.Cmp.id_persona_remitente);
		        this.ocultarComponente(this.Cmp.id_institucion_remitente);
		        this.ocultarComponente(this.Cmp.cite);
		        this.ocultarComponente(this.Cmp.otros_adjuntos);
		        this.ocultarComponente(this.Cmp.nro_paginas);
		        this.ocultarComponente(this.Cmp.fecha_creacion_documento);
		
		}
        if (this.tipo_interfaz=='externa'){
        	
        	this.tipo = this.getComponente('tipo');
            this.tipo.setValue('externa');
		    this.tipo.disable(true);
        	cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
        }else{
        	 this.tipo = this.getComponente('tipo');
            this.tipo.setValue('interna');
		    this.tipo.disable(true);
           cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
        	
        }
		cmbDoc.modificado = true;
		cmbDoc.reset();
	
	     
	},   
	onButtonEdit: function () {
		
          Phx.vista.CorrespondenciaAdministracion.superclass.onButtonEdit.call(this);
        this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
		   
		  
		if (this.getComponente('tipo').getValue()=='externa'){  
			 	this.adminGrupo({ ocultar: [0,3,4]});
	          	this.Cmp.id_institucion_destino.hide();
		        this.Cmp.id_persona_destino.hide();
		        this.Cmp.id_acciones.hide();
		      	this.ocultarComponente(this.Cmp.id_persona_destino);
		      	this.ocultarComponente(this.Cmp.id_uo);
		        this.ocultarComponente(this.Cmp.id_funcionario_saliente);
		        this.ocultarComponente(this.Cmp.id_institucion_destino);
		        this.ocultarComponente(this.Cmp.id_acciones);
         		this.adminGrupo({ ocultar: [0,3,4]});
         		this.ocultarComponente(this.Cmp.id_funcionario);
         		this.ocultarComponente(this.Cmp.asociar);
         		this.ocultarComponente(this.Cmp.id_correspondencias_asociadas);
         		this.ocultarComponente(this.Cmp.fecha_creacion_documento);
         		
         		
		   
		}else{    this.adminGrupo({ ocultar: [0,3,4]});
		       	this.Cmp.id_institucion_destino.hide();
		        this.Cmp.id_persona_destino.hide();
		        this.Cmp.id_institucion_remitente.hide();
		        this.Cmp.id_persona_remitente.hide();
	            this.ocultarComponente(this.Cmp.id_persona_destino);
		        this.ocultarComponente(this.Cmp.id_institucion_destino);
		        this.ocultarComponente(this.Cmp.id_funcionario_saliente);
		        
		        this.ocultarComponente(this.Cmp.id_uo);
		        this.ocultarComponente(this.Cmp.id_persona_remitente);
		        this.ocultarComponente(this.Cmp.id_institucion_remitente);
		        this.ocultarComponente(this.Cmp.cite);
		        this.ocultarComponente(this.Cmp.otros_adjuntos);
		        this.ocultarComponente(this.Cmp.nro_paginas);
		        this.ocultarComponente(this.Cmp.fecha_creacion_documento);
		        this.ocultarComponente(this.Cmp.fecha_documento);
		
		}
           
        if (this.getComponente('tipo').getValue()=='externa'){
        	this.tipo = this.getComponente('tipo');
            this.tipo.setValue('externa');
		    this.tipo.disable(true);
        	cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
        }else{
        	 this.tipo = this.getComponente('tipo');
            this.tipo.setValue('interna');
		    this.tipo.disable(true);
           cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
        	
        }
      //cmbDoc.store.baseParams.tipo = this.tipo;//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();
		  
        
		
	
	
	},
	BAdjuntos : function() {
				var rec = this.sm.getSelected();
	
				Phx.CP.loadWindows('../../../sis_correspondencia/vista/adjunto/Adjunto.php?estado=borrador', 'Adjuntos', {
					width : 900,
					height : 400
				}, rec.data, this.idContenedor, 'Adjunto')
			},
		
	liberaMenu:function(){
        var tb = Phx.vista.CorrespondenciaAdministracion.superclass.liberaMenu.call(this);
        if(tb){
   
            this.getBoton('SubirDocumento').enable();
            this.getBoton('Habilitar').enable();
			this.getBoton('Adjuntos').enable();
			this.getBoton('VerDocumento').enable();
			this.getBoton('HojaRuta').enable();
			this.getBoton('Historico').enable();
			
	                
        }
       return tb
  }

	
};
</script>
