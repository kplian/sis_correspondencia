<?php
/**
*@package pXP
*@file gen-ACTAccion.php
*@author  (rac)
*@date 13-12-2011 13:49:30
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTAccion extends ACTbase{    
			
	function listarAccion(){
		$this->objParam->defecto('ordenacion','id_accion');
		$this->objParam->defecto('dir_ordenacion','asc');
					
		if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODAccion', 'listarAccion');
        } else {
            $this->objFunc=$this->create('MODAccion');	
		    $this->res=$this->objFunc->listarAccion();
        }
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarAccion(){
		$this->objFunc=$this->create('MODAccion');	
		if($this->objParam->insertar('id_accion')){
			$this->res=$this->objFunc->insertarAccion();			
		} else{			
			$this->res=$this->objFunc->modificarAccion();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarAccion(){
		$this->objFunc=$this->create('MODAccion');	
		$this->res=$this->objFunc->eliminarAccion();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>