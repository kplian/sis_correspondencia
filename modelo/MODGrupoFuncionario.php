<?php
/**
*@package pXP
*@file gen-MODGrupoFuncionario.php
*@author  (rac)
*@date 10-01-2012 16:15:05
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODGrupoFuncionario extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarGrupoFuncionario(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_grupo_funcionario_sel';
		$this->transaccion='CO_FUNA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_grupo_funcionario','int4');
		$this->captura('id_grupo','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_funcionario','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_funcionario1','text');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarGrupoFuncionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_grupo_funcionario_ime';
		$this->transaccion='CO_FUNA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_grupo','id_grupo','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarGrupoFuncionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_grupo_funcionario_ime';
		$this->transaccion='CO_FUNA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_grupo_funcionario','id_grupo_funcionario','int4');
		$this->setParametro('id_grupo','id_grupo','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarGrupoFuncionario(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_grupo_funcionario_ime';
		$this->transaccion='CO_FUNA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_grupo_funcionario','id_grupo_funcionario','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>