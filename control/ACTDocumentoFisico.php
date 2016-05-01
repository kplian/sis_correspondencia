<?php
/**
*@package pXP
*@file gen-ACTDocumentoFisico.php
*@author  (admin)
*@date 27-04-2016 16:45:39
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTDocumentoFisico extends ACTbase{    
			
	function listarDocumentoFisico(){
		$this->objParam->defecto('ordenacion','id_documento_fisico');

		$this->objParam->defecto('dir_ordenacion','asc');

		$this->objParam->addParametro('id_funcionario_usuario',$_SESSION["ss_id_funcionario"]);
		$this->objParam->addParametro('vista_documento_fisico',$this->objParam->getParametro('vista_documento_fisico'));



		if($this->objParam->getParametro('estado') != ''){
			$this->objParam->addFiltro("docfis.estado = ''".$this->objParam->getParametro('estado')."'' ");
		}

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODDocumentoFisico','listarDocumentoFisico');
		} else{
			$this->objFunc=$this->create('MODDocumentoFisico');
			
			$this->res=$this->objFunc->listarDocumentoFisico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarDocumentoFisico(){
		$this->objFunc=$this->create('MODDocumentoFisico');	
		if($this->objParam->insertar('id_documento_fisico')){
			$this->res=$this->objFunc->insertarDocumentoFisico($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarDocumentoFisico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarDocumentoFisico(){
			$this->objFunc=$this->create('MODDocumentoFisico');	
		$this->res=$this->objFunc->eliminarDocumentoFisico($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarDocumentoFisicoDespachar(){
		$this->objFunc=$this->create('MODDocumentoFisico');

		$this->objParam->addFiltro("docfis.estado = ".$this->objParam->getParametro('pendiente'));

		$this->res=$this->objFunc->listarDocumentoFisico($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	function cambiarEstado(){
		$this->objFunc=$this->create('MODDocumentoFisico');
		$this->res=$this->objFunc->cambiarEstado($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>