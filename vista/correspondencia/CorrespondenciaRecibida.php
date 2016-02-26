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
Phx.vista.CorrespondenciaRecibida = {
    bsave:false,
    bnew:false,
	bedit:false,
	bdel:false,
    require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
	requireclase: 'Phx.vista.Correspondencia',
	title: 'Correspondencia Recibida',
	nombreVista: 'CorrespondenciaRecibida',
	
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaRecibida',
	
	constructor: function(config) {
	    Phx.vista.CorrespondenciaRecibida.superclass.constructor.call(this,config);
	    
	  
    
   },
    preparaMenu:function(n){
      	
      	Phx.vista.CorrespondenciaRecibida.superclass.preparaMenu.call(this,n);      	
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
	 
		 return tb
		
	}
	
	
	
   
	
	
};
</script>
