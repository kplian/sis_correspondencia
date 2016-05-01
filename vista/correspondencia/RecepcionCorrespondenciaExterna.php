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

    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'RecepcionCorrespondenciaExterna',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarRecepcionCorrespondenciaExterna',
	
	constructor: function(config) {
	    Phx.vista.RecepcionCorrespondenciaExterna.superclass.constructor.call(this,config);




		this.getBoton('verCorrespondencia').hide();
		this.getBoton('mandar').hide();
		this.getBoton('Adjuntos').hide();
		this.getBoton('corregir').hide();
		this.getBoton('Hoja de Ruta').hide();

    
   },
    preparaMenu:function(n){
      	
      	Phx.vista.RecepcionCorrespondenciaExterna.superclass.preparaMenu.call(this,n);      	
		  var data = this.getSelectedData();

		console.log('data',data)
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
		  if(data['version']>0){
		  	   this.getBoton('verCorrespondencia').enable();
		       this.getBoton('mandar').enable()
		       this.getBoton('finalizarRecibido').enable();
	  		}
	  		else{
	  			this.getBoton('verCorrespondencia').enable(); //aqui esta disable
	  			this.getBoton('mandar').enable(); //aqui tambien
				this.getBoton('finalizarRecibido').enable();
	  			
	  		}



	 
		 return tb
		
	},
	onButtonNew: function () {
		console.log('llega');


		this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');
		var cmpId_funcionario = this.getComponente('id_funcionario');



		Phx.vista.RecepcionCorrespondenciaExterna.superclass.onButtonNew.call(this);


		this.Cmp.id_institucion_destino.hide();
		this.Cmp.id_persona_destino.hide();
		this.Cmp.id_acciones.hide();
		this.Cmp.id_acciones.hide();

		this.ocultarComponente(this.Cmp.id_persona_destino);
		this.ocultarComponente(this.Cmp.id_institucion_destino);
		this.ocultarComponente(this.Cmp.id_acciones);

		

		this.adminGrupo({ ocultar: [3]});

		console.log(this.Cmp);
		this.tipo.setValue('externa');
		this.tipo.disable(true);

		cmbDoc.store.baseParams.tipo = 'entrante';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();


		this.ocultarComponente(cmpId_funcionario);

		/*var cmbDoc = this.getComponente('id_documento');
		var cmpFuncionarios = this.getComponente('id_funcionarios');
		var cmpInstitucion = this.getComponente('id_institucion');
		var cmpPersona = this.getComponente('id_persona');

		this.adminGrupo({mostrar: [0, 1, 2, 3]});
		this.ocultarComponente(cmpInstitucion);
		this.ocultarComponente(cmpPersona);
		this.mostrarComponente(cmpFuncionarios);

		this.getComponente('id_uo').enable();
		this.getComponente('id_clasificador').enable();
		this.getComponente('mensaje').enable();
		this.getComponente('nivel_prioridad').enable();
		this.getComponente('referencia').enable();

		cmbDoc.store.baseParams.tipo = 'interna';//valor por dfecto es interna
		cmbDoc.modificado = true;
		cmbDoc.reset();*/

	},
	
	
	
   
	
	
};
</script>
