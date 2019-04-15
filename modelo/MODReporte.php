<?php
/**
 *@package pXP
 *@file MODReporte.php
 *@author  (José Mita)
 *@date 22-11-2018 10:11:23
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODReporte extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);
    }

  function listarReporteCorrespondencia(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='corres.ft_reporte_sel';
        $this->transaccion='CO_REPCOR_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        
        //Definicion de la lista del resultado del query
        $this->captura('id_correspondencia','int4');
        $this->captura('estado','varchar');
        $this->captura('fecha_documento','date');
        $this->captura('mensaje','text');
        $this->captura('nivel_prioridad','varchar');
        $this->captura('numero','varchar');
        $this->captura('observaciones_estado','text');
        $this->captura('referencia','varchar');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('desc_funcionario','text');
        $this->captura('version','int4');
        $this->captura('desc_clasificador','text');
        $this->captura('desc_cargo','varchar');
		$this->captura('sw_archivado','varchar');
		$this->captura('desc_insti','varchar');
		$this->captura('tipo','varchar');
		$this->captura('documento','varchar');
		$this->captura('id_documento','int4');
		$this->captura('persona_remi','text');
		$this->captura('acciones','text');


        //Ejecuta la instruccion
        $this->armarConsulta();
		//echo $this->getConsulta();
		//die;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listarReporteCorrespondenciaEstadistico(){

        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='corres.ft_reporte_sel';
        $this->transaccion='CO_REPCOR_ESTA';
        $this->tipo_procedimiento='SEL'; //tipo de transaccion
        $this->setCount(false);

        $this->captura('cantidad','numeric');
        $this->captura('nombre','varchar');
        $this->captura('id_accion','int4');
        $this->captura('tipo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        // echo $this->getConsulta();exit;
        $this->ejecutarConsulta();
        
        //Devuelve la respuesta
        return $this->respuesta;
    }

   
}
?>