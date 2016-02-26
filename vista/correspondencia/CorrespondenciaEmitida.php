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
Phx.vista.CorrespondenciaEmitida = {
    bsave:false,
   
	require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Emitida',
	nombreVista: 'CorrespondenciaEmitida',
	
	constructor: function(config) {
	    Phx.vista.CorrespondenciaEmitida.superclass.constructor.call(this,config);
	    
	    this.addButton('aSubirCorrespondencia',{text:'Subir Documento',iconCls:'bupload',disabled:true,handler:this.SubirCorrespondencia,tooltip: '<b>Subir archivo</b><br/>Permite actualizar el documento escaneado'});
		
	    
	  
    
    },     
	iniciarEventos:function(){ 
	

		var cmpFuncionarios = this.getComponente('id_funcionarios');
		var cmpInstitucion = this.getComponente('id_institucion');
		var cmpPersona = this.getComponente('id_persona');
		var cmbDoc = this.getComponente('id_documento');
		
		
	    //para habilitar el tipo de correspondecia para el sistema corres

		this.getComponente('tipo').on('select',function(combo,record,index){
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
        		
    			
		},this);

	},
	
	onButtonNew:function(){
    	 var cmbDoc = this.getComponente('id_documento');
		 var cmpFuncionarios = this.getComponente('id_funcionarios');
	     var cmpInstitucion = this.getComponente('id_institucion');
		 var cmpPersona = this.getComponente('id_persona');
		 
    	 Phx.vista.Correspondencia.superclass.onButtonNew.call(this);
      	 this.adminGrupo({mostrar:[0,1,2,3]});
      	 this.ocultarComponente(cmpInstitucion);
	     this.ocultarComponente(cmpPersona);
	     this.mostrarComponente(cmpFuncionarios);
	     
	     this.getComponente('id_uo').enable();
	     this.getComponente('id_clasificador').enable();
	     this.getComponente('mensaje').enable();
	     this.getComponente('nivel_prioridad').enable();
	     this.getComponente('referencia').enable();
	    
	     cmbDoc.store.baseParams.tipo='interna';//valor por dfecto es interna	
	     cmbDoc.modificado = true;
	     cmbDoc.reset();
      	
     },
    
    onButtonEdit:function(){
    	Phx.vista.Correspondencia.superclass.onButtonEdit.call(this);
      	this.adminGrupo({mostrar:[2],ocultar:[0,1,3]});
      	this.getComponente('id_uo').disable();
      	 var data=this.sm.getSelected().data;
      	 console.log(data,data.estado)
      	if(data.estado=='borrador_envio')
      	{
	      	this.getComponente('id_clasificador').enable();
	      	this.getComponente('mensaje').enable();
		    this.getComponente('nivel_prioridad').enable();
		    this.getComponente('referencia').enable(); 
	     }
	     else if(data.estado=='enviado')
	     {
	     	this.getComponente('id_clasificador').disable();
	      	this.getComponente('mensaje').disable();
		    this.getComponente('nivel_prioridad').disable();
		    this.getComponente('referencia').disable();
	     	
	     }
    },
    SubirCorrespondencia:function()
		{					
			var rec=this.sm.getSelected();
			
						
			Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/subirCorrespondencia.php',
			'Subir Correspondencia',
			{
				modal:true,
				width:500,
				height:250
		    },rec.data,this.idContenedor,'subirCorrespondencia')
	 },
	 
	  preparaMenu:function(n){
      	
      	Phx.vista.Correspondencia.superclass.preparaMenu.call(this,n);
      	
		  var data = this.getSelectedData();
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
		  if(data['version']>0){
		  	   this.getBoton('verCorrespondencia').enable();
		       this.getBoton('mandar').enable()
	  		}
	  		else
	  		{
	  			this.getBoton('verCorrespondencia').disable();
	  			this.getBoton('mandar').disable()
	  			
	  		}
	 
		  
		  //cuando el conrtato esta registrado el abogado no puede hacerle mas cambios
		  if(data['estado']=='borrador_envio'){
		  		
			  	this.getBoton('aSubirCorrespondencia').enable();
			  		this.getBoton('corregir').disable();
		  } 
		  if(data['estado']=='enviado'){
		  		if(tb){
			  		
			  		this.getBoton('aSubirCorrespondencia').disable();
			  		this.getBoton('corregir').enable();
			  		
			  		
			  		
			  	}
			  	
		  } 
		  
		  
		  return tb
		
	}
	
   
	
	
};
</script>
