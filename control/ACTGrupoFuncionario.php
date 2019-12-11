<?php
/*
ISSUE            FECHA:		      AUTOR                 DESCRIPCION
#7 			06/09/2019			manuel guerra		correccion de bug(padre-hijo)
*/

class ACTGrupoFuncionario extends ACTbase{    
			
	function listarGrupoFuncionario(){
		$this->objParam->defecto('ordenacion','id_grupo_funcionario');
		$this->objParam->defecto('dir_ordenacion','asc');
        //#7
        if ($this->objParam->getParametro('id_grupo') != '') {
            $this->objParam->addFiltro("id_grupo= ". $this->objParam->getParametro('id_grupo'));
        }
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