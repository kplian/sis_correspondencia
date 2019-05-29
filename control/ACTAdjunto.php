<?php
/**
*@package pXP
*@file gen-ACTAdjunto.php
*@author  (admin)
*@date 22-04-2016 23:13:29
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTAdjunto extends ACTbase{    
			
	function listarAdjunto(){
		$this->objParam->defecto('ordenacion','id_adjunto');

		$this->objParam->defecto('dir_ordenacion','asc');

		/*if($this->objParam->getParametro('id_correspondencia')!=''){
		    $id_correspondencia=$this->objParam->getParametro('id_correspondencia');
			$this->objParam->addFiltro('id_correspondencia',$id_correspondencia);
		}*/
		if($this->objParam->getParametro('id_origen')!=''){
			$this->objParam->addFiltro("cor.id_correspondencia = ".$this->objParam->getParametro('id_origen')."");
		}
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODAdjunto','listarAdjunto');
		} else{
			$this->objFunc=$this->create('MODAdjunto');
			
			$this->res=$this->objFunc->listarAdjunto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarAdjunto(){
		$this->objFunc=$this->create('MODAdjunto');	
		if($this->objParam->insertar('id_adjunto')){
			$this->res=$this->objFunc->insertarAdjunto($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarAdjunto($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarAdjunto(){
		    
		$this->objFunc=$this->create('MODAdjunto');	
		if($this->objParam->getParametro('id_adjunto')!=''){
			$this->objParam->addFiltro("adj.id_adjunto = ''".$this->objParam->getParametro('id_adjunto')."''");
			$adjunto=$this->objFunc->listarAdjunto($this->objParam);
		}
        
		$this->res=$this->objFunc->eliminarAdjunto($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}


			
}

?>