<?php
/**
*@package pXP
*@file gen-MODDocumentoFisico.php
*@author  (admin)
*@date 27-04-2016 16:45:39
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODDocumentoFisico extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarDocumentoFisico(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_documento_fisico_sel';
		$this->transaccion='CORRES_DOCFIS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion


		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');
		$this->setParametro('vista_documento_fisico','vista_documento_fisico','varchar');


		//Definicion de la lista del resultado del query
		$this->captura('id_documento_fisico','int4');
		$this->captura('id_correspondencia','int4');
		$this->captura('id_correspondencia_padre','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_person','text');
		$this->captura('desc_person_padre','text');
		$this->captura('desc_depto','varchar');
		$this->captura('desc_depto_padre','varchar');
		$this->captura('numero','varchar');



		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarDocumentoFisico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_documento_fisico_ime';
		$this->transaccion='CORRES_DOCFIS_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('id_correspondencia_padre','id_correspondencia_padre','int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarDocumentoFisico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_documento_fisico_ime';
		$this->transaccion='CORRES_DOCFIS_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_documento_fisico','id_documento_fisico','int4');
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('id_correspondencia_padre','id_correspondencia_padre','int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarDocumentoFisico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_documento_fisico_ime';
		$this->transaccion='CORRES_DOCFIS_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_documento_fisico','id_documento_fisico','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function cambiarEstado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_documento_fisico_ime';
		$this->transaccion='CORRES_DOCFISEST_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_documento_fisico','id_documento_fisico','int4');
		$this->setParametro('estado','estado','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>