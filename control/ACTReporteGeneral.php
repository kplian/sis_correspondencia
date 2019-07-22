<?php
/**
*@package pXP
*@file ACTReporteGerenal.php
*@author  Marcela Garcia
*@date 26-06-2019 
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../../pxp/pxpReport/DataSource.php');
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDFFormulario.php';
require_once(dirname(__FILE__).'/../reporte/RGeneralCorres.php');

class ACTReporteGeneral extends ACTbase{    

	function recuperarDatosRepGeneral(){    	
	    $this->objFunc = $this->create('MODReporteGeneral');
		$cbteHeader = $this->objFunc->listarReporteGeneral($this->objParam);
 
		if($cbteHeader->getTipo() == 'EXITO'){				
			return $cbteHeader;
		}
        else{
		    $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
			exit;
		}              
		
    }
	
	function recuperarDatosEntidad(){    	
		$this->objFunc = $this->create('sis_parametros/MODEntidad');
		$cbteHeader = $this->objFunc->getEntidad($this->objParam);
		if($cbteHeader->getTipo() == 'EXITO'){				
			return $cbteHeader;
		}
        else{
		    $cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
			exit;
		}              
		
    }

	function reporteGeneral(){
				
		$this->objParam->defecto('ordenacion','id_correspondencia');

        $this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('id_uo')!=''){
            $this->objParam->addFiltro("T.id_uo = ".$this->objParam->getParametro('id_uo'));
        }
		
		if($this->objParam->getParametro('tipo')!=''){
			if($this->objParam->getParametro('tipo')=='entrante'){
				$this->objParam->addFiltro("T.tipo =''externa''");
			} else {
				$this->objParam->addFiltro("T.tipo =''".$this->objParam->getParametro('tipo')."''");
			}
        }
		
		if($this->objParam->getParametro('estado')!=''){
			if($this->objParam->getParametro('estado')=='todos'){
	        	$this->objParam->addFiltro("T.estado in (''borrador_envio'',''borrador_recepcion_externo'',''pendiente_recepcion_externo'',''borrador_detalle_recibido'', ''enviado'',''pendiente_recibido'',''recibido'') ");
	    	}else {$this->objParam->addFiltro("T.estado = ''".$this->objParam->getParametro('estado')."''");}
		}	

		if($this->objParam->getParametro('id_documento')!='' && $this->objParam->getParametro('id_documento')!='0'){
            $this->objParam->addFiltro("T.id_documento = ''".$this->objParam->getParametro('id_documento')."''");
        }else{
        	
			   if($this->objParam->getParametro('id_documento')=='0' && $this->objParam->getParametro('tipo')=='interna'){
				
			   $this->objParam->addFiltro("T.id_documento in (select DOCUME.id_documento from param.tdocumento DOCUME where DOCUME.tipo = ''interna'' ) ");	
			}else{
				
				if($this->objParam->getParametro('id_documento')=='0' && $this->objParam->getParametro('tipo')=='saliente'){
				
			   		$this->objParam->addFiltro("T.id_documento in (select DOCUME.id_documento from param.tdocumento DOCUME where DOCUME.tipo = ''saliente'' ) ");	
			   }else{
				
					if($this->objParam->getParametro('id_documento')=='0' && $this->objParam->getParametro('tipo')=='entrante'){
					
				   		$this->objParam->addFiltro("T.id_documento in (select DOCUME.id_documento from param.tdocumento DOCUME where DOCUME.tipo = ''entrante'' ) ");	
				   }
					
				}
				
			}
			
        }
        
		///
		if($this->objParam->getParametro('fecha_ini')!='' && $this->objParam->getParametro('fecha_fin')!=''){
			$this->objParam->addFiltro("( T.fecha_documento::date  BETWEEN ''%".$this->objParam->getParametro('fecha_ini')."%''::date  and ''%".$this->objParam->getParametro('fecha_fin')."%''::date)");	
		}
		///
		
        if($this->objParam->getParametro('id_usuario')!=''){
            $this->objParam->addFiltro("T.id_usuario_reg = ".$this->objParam->getParametro('id_usuario'));
        }
	
		$nombreArchivo = uniqid(md5(session_id()).'Corres') . '.pdf'; 
		
		$dataSource = $this->recuperarDatosRepGeneral();
		$dataEntidad = $this->recuperarDatosEntidad();									
		
		$tamano = 'LETTER';
		$orientacion = 'L';
		$titulo = 'Correspondencia';
		$this->objParam->addParametro('orientacion',$orientacion);
		$this->objParam->addParametro('tamano',$tamano);		
		$this->objParam->addParametro('titulo_archivo',$titulo);
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);	
		
		// genera el reporte 
		$reporte = new RGeneralCorres($this->objParam);
		//var_dump('Reporteeee ',$dataEntidad->getDatos());
		//exit;	
		$reporte->datosHeader($dataSource->getDatos(),$dataEntidad->getDatos());		
		
		$reporte->generarReporte();
		$reporte->output($reporte->url_archivo,'F');			
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

	}
	
	function listarUsuario(){

		//el objeto objParam contiene todas la variables recibidad desde la interfaz

        //$this->dispararEventoWS();

		// parametros de ordenacion por defecto
		$this->objParam->defecto('ordenacion','desc_person');
		$this->objParam->defecto('dir_ordenacion','asc');
		
		$this->objFunSeguridad=$this->create('MODReporteGeneral');
		$this->res=$this->objFunSeguridad->listarUsuario($this->objParam);

		//imprime respuesta en formato JSON para enviar lo a la interface (vista)
		$this->res->imprimirRespuesta($this->res->generarJson());
		
		
	}		
}

?>