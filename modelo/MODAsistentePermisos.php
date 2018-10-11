<?php
/**
*@package pXP
*@file gen-MODAsistentePermisos.php
*@author  AVQ
*@date 30/09/2018
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODAsistentePermisos extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarAsistentePermisos(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_asistente_permisos_sel';
		$this->transaccion='CO_ASPER_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		 
		$this->captura('id_asistente_permisos','int4');
		$this->captura('permitir_todo','varchar');
		$this->captura('estado','varchar');
		$this->captura('id_asistente','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_uo','int4');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	/*function insertarAccion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_accion_ime';
		$this->transaccion='CO_ACCO_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarAccion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_accion_ime';
		$this->transaccion='CO_ACCO_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_accion','id_accion','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nombre','nombre','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarAccion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_accion_ime';
		$this->transaccion='CO_ACCO_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_accion','id_accion','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}*/
			
}
?>