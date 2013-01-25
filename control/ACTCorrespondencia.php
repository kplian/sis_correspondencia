<?php
/**
*@package pXP
*@file gen-ACTCorrespondencia.php
*@author  (rac)
*@date 13-12-2011 16:13:21
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCorrespondencia extends ACTbase{    
			
	function listarCorrespondencia(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');
					
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','listarCorrespondencia');
		} else{
			$this->objFunc=$this->create('MODCorrespondencia');	
			$this->res=$this->objFunc->listarCorrespondencia();
		}
		
		
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarCorrespondenciaSimplificada(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','listarCorrespondenciaSimplificada');
		} else{
			$this->objFunc=$this->create('MODCorrespondencia');	
		   $this->res=$this->objFunc->listarCorrespondenciaSimplificada();
		}
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarCorrespondenciaDetalle(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','listarCorrespondenciaDetalle');
		} else{
			$this->objFunc=$this->create('MODCorrespondencia');
		   $this->res=$this->objFunc->listarCorrespondenciaDetalle();
		}
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
				
	function insertarCorrespondencia(){ //echo "aaaa"; exit;
		$this->objFunc=$this->create('MODCorrespondencia');	
		if($this->objParam->insertar('id_correspondencia')){
			$this->res=$this->objFunc->insertarCorrespondencia();			
		} else{			
			$this->res=$this->objFunc->modificarCorrespondencia();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCorrespondencia(){
		$this->objFunc=$this->create('MODCorrespondencia');	
		$this->res=$this->objFunc->eliminarCorrespondencia();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	function insertarCorrespondenciaDetalle(){ //echo "aaaa"; exit;
		$this->objFunc=$this->create('MODCorrespondencia');	
		if($this->objParam->insertar('id_correspondencia')){
			$this->res=$this->objFunc->insertarCorrespondenciaDetalle();			
		} else{			
			$this->res=$this->objFunc->modificarCorrespondenciaDetalle();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function subirCorrespondencia(){
	
		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		$this->objFunSeguridad=$this->create('MODCorrespondencia');
		$this->res=$this->objFunSeguridad->subirCorrespondencia($this->objParam);
		//imprime respuesta en formato JSON
		$this->res->imprimirRespuesta($this->res->generarJson());

	}
	
	function derivarCorrespondencia()
	{
		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		$this->objFunSeguridad=$this->create('MODCorrespondencia');
		$this->res=$this->objFunSeguridad->derivarCorrespondencia($this->objParam);
		//imprime respuesta en formato JSON
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
	
	function corregirCorrespondencia()
	{
		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		$this->objFunSeguridad=$this->create('MODCorrespondencia');
		$this->res=$this->objFunSeguridad->corregirCorrespondencia($this->objParam);
		//imprime respuesta en formato JSON
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
	
		
	function listarCorrespondenciaRecibida(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','asc');
					
		$this->objFunc=$this->create('MODCorrespondencia');	
		$this->res=$this->objFunc->listarCorrespondenciaRecibida();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}	
}

?>