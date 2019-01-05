<?php
/**
*@package pXP
*@file gen-ACTAsistentePermisos.php
*@author  (admin)
*@date 04-01-2019 12:01:52
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTAsistentePermisos extends ACTbase{    
			
	function listarAsistentePermisos(){
		$this->objParam->defecto('ordenacion','id_asistente_permisos');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODAsistentePermisos','listarAsistentePermisos');
		} else{
			$this->objFunc=$this->create('MODAsistentePermisos');
			
			$this->res=$this->objFunc->listarAsistentePermisos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarAsistentePermisos(){
		$this->objFunc=$this->create('MODAsistentePermisos');	
		if($this->objParam->insertar('id_asistente_permisos')){
			$this->res=$this->objFunc->insertarAsistentePermisos($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarAsistentePermisos($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarAsistentePermisos(){
			$this->objFunc=$this->create('MODAsistentePermisos');	
		$this->res=$this->objFunc->eliminarAsistentePermisos($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>