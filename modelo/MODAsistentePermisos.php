<?php
/**
*@package pXP
*@file gen-MODAsistentePermisos.php
*@author  (admin)
*@date 04-01-2019 12:01:52
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODAsistentePermisos extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarAsistentePermisos(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_asistente_permisos_sel';
		$this->transaccion='CORRES_ASIPER_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_asistente_permisos','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_asistente','int4');
		$this->captura('id_funcionarios_permitidos','_int4');
		$this->captura('estado','varchar');
		$this->captura('permitir_todo','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarAsistentePermisos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_asistente_permisos_ime';
		$this->transaccion='CORRES_ASIPER_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_asistente','id_asistente','int4');
		$this->setParametro('id_funcionarios_permitidos','id_funcionarios_permitidos','_int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('permitir_todo','permitir_todo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarAsistentePermisos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_asistente_permisos_ime';
		$this->transaccion='CORRES_ASIPER_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_asistente_permisos','id_asistente_permisos','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_asistente','id_asistente','int4');
		$this->setParametro('id_funcionarios_permitidos','id_funcionarios_permitidos','_int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('permitir_todo','permitir_todo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarAsistentePermisos(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_asistente_permisos_ime';
		$this->transaccion='CORRES_ASIPER_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_asistente_permisos','id_asistente_permisos','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>