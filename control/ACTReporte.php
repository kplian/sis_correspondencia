<?php
/**
 *@package pXP
 *@file ACTReporte.php
 *@author  (José Mita)
 *@date 22-11-2018 10:11:23
 *@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTReporte extends ACTbase{

    function listarReporteCorrespondencia(){
        $this->objParam->defecto('ordenacion','id_correspondencia');

        $this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('fecha_ini')!=''){
            $this->objParam->addFiltro("cor.fecha_documento::date >= ''" . $this->objParam->getParametro('fecha_ini') . "''::date");
        }

        if($this->objParam->getParametro('fecha_fin')!=''){
            $this->objParam->addFiltro("cor.fecha_documento::date <= ''" . $this->objParam->getParametro('fecha_fin') . "''::date");
        }

        if($this->objParam->getParametro('id_uo')!=''){
            $this->objParam->addFiltro("cor.id_uo = ".$this->objParam->getParametro('id_uo'));
        }
		
		if($this->objParam->getParametro('id_usuario')!=''){
            $this->objParam->addFiltro("cor.id_usuario_reg = ".$this->objParam->getParametro('id_usuario'));
        }
		
		if($this->objParam->getParametro('tipo')!=''){
            $this->objParam->addFiltro("cor.tipo =''".$this->objParam->getParametro('tipo')."'' ");
        }
		
		if($this->objParam->getParametro('tipo')=='interna'){
            $this->objParam->addFiltro("cor.tipo =''".$this->objParam->getParametro('tipo')."'' ");
			if($this->objParam->getParametro('estado')=='todos'){
            $this->objParam->addFiltro("cor.estado in (''borrador_envio'', ''enviado'',''anulado'') ");
        	}else {$this->objParam->addFiltro("cor.estado = ''".$this->objParam->getParametro('estado')."'' ");}
			
        }
		
		if($this->objParam->getParametro('tipo')=='saliente'){
            $this->objParam->addFiltro("cor.tipo =''".$this->objParam->getParametro('tipo')."'' ");
			if($this->objParam->getParametro('estado')=='todos'){
            $this->objParam->addFiltro("cor.estado in (''borrador_envio'', ''enviado'',''anulado'') ");
        	}else {$this->objParam->addFiltro("cor.estado = ''".$this->objParam->getParametro('estado')."'' ");}
			
        }
	
		/*
			if($this->objParam->getParametro('estado')=='todos'){
            $this->objParam->addFiltro("cor.estado in ('borrador_envio', 'enviado','anulado') ");
        }
		
		if($this->objParam->getParametro('estado')!='borrador' && $this->objParam->getParametro('estado')!='todos'){
			
            $this->objParam->addFiltro("cor.estado = ''".$this->objParam->getParametro('estado')."'' ");
        }else{
        	if($this->objParam->getParametro('tipo')!='externa'){
            $this->objParam->addFiltro("cor.estado = ''borrador_envio'' ");
        	}else{$this->objParam->addFiltro("cor.estado = ''borrador_recepcion_externa'' ");}
        }*/

        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODReporte','listarReporteCorrespondencia');
        } else{
            $this->objFunc=$this->create('MODReporte');

            $this->res=$this->objFunc->listarReporteCorrespondencia($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }


  


}

?>
