<?php
/**
*@package pXP
*@file gen-ACTGrupoFuncionario.php
*@author  (rac)
*@date 10-01-2012 16:15:05
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTGrupoFuncionario extends ACTbase{    
			
	function listarGrupoFuncionario(){
		$this->objParam->defecto('ordenacion','id_grupo_funcionario');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODGrupoFuncionario','listarGrupoFuncionario');
		} else{
			$this->objFunc=$this->create('MODGrupoFuncionario');	
			$this->res=$this->objFunc->listarGrupoFuncionario();
			$this->res->imprimirRespuesta($this->res->generarJson());
		}
	}
				
	function insertarGrupoFuncionario(){
		$this->objFunc=$this->create('MODGrupoFuncionario');	
		if($this->objParam->insertar('id_grupo_funcionario')){
			$this->res=$this->objFunc->insertarGrupoFuncionario();			
		} else{			
			$this->res=$this->objFunc->modificarGrupoFuncionario();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarGrupoFuncionario(){
		$this->objFunc=$this->create('MODGrupoFuncionario');	
		$this->res=$this->objFunc->eliminarGrupoFuncionario();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>