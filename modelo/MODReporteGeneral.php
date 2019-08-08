<?php
/**
*@package pXP
*@file MODReporteGeneral.php
*@author  Marcela Garcia
*@date 26-06-2019 
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 *  *  *    HISTORIAL DE MODIFICACIONES:
   	
 ISSUE            FECHA:		      AUTOR                 DESCRIPCION
   
 #0        		  26-06-2019          Marcela Garcia        creacion
*/
class MODReporteGeneral extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}			

     function listarReporteGeneral(){
		  //Definicion de variables para ejecucion del procedimientp
		  $this->procedimiento='corres.ft_reporte_sel';
		  $this->transaccion='CO_REPCOR_SEL';
		  $this->tipo_procedimiento='SEL';//tipo de transaccion
		  $this->setCount(false);	
		
		  
		  $this->setParametro('id_uo','id_uo','INTEGER');
		  $this->setParametro('tipo','tipo','VARCHAR');
		  $this->setParametro('id_documento','id_documento','INTEGER');
		  $this->setParametro('estado','estado','VARCHAR');
		  $this->setParametro('fecha_ini','fecha_ini','date');
		  $this->setParametro('fecha_fin','fecha_fin','date');
		  $this->setParametro('id_usuario','id_usuario','INTEGER');

		
		  //Definicion de la lista del resultado del query
			$this->captura('id_correspondencia','int4');
			$this->captura('estado','varchar');
			$this->captura('fecha_documento','date');
			$this->captura('mensaje','text');
			$this->captura('nivel_prioridad','varchar');
			$this->captura('numero','varchar');
			//$this->captura('observaciones_estado','text');
			$this->captura('referencia','varchar');
			$this->captura('usr_reg','varchar');
			$this->captura('desc_funcionario','text');
			$this->captura('version','int4');
			//$this->captura('desc_clasificador','text');
			$this->captura('sw_archivado','varchar');
			//$this->captura('desc_insti','varchar');
			$this->captura('tipo','varchar');
			$this->captura('documento','varchar');
			$this->captura('persona_remi','varchar');
			$this->captura('acciones','text');
			$this->captura('fecha_reg','timestamp');
			$this->captura('id_uo','int4');
			$this->captura('id_documento','int4');
			$this->captura('id_usuario_reg','int4');
		  
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarUsuario(){
		
		
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_reporte_sel';// nombre procedimiento almacenado
		$this->transaccion='CO_REPUSU_SEL';//nombre de la transaccion
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		//$this->setCount(false);
		
		
		//defino varialbes que se captran como retornod e la funcion
		$this->captura('id_usuario','integer');
		$this->captura('desc_person','text');
		
		//Ejecuta la funcion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		return $this->respuesta;

	}

	
}
?>