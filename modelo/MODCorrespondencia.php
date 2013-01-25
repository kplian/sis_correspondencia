<?php
/**
*@package pXP
*@file gen-MODCorrespondencia.php
*@author  (rac)
*@date 13-12-2011 16:13:21
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCorrespondencia extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
	
							
	function listarCorrespondenciaSimplificada(){
		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORSIM_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion		
		$this->setParametro('interface','interface','integer');
		//$parametros  = $this->aParam->getArregloParametros('interface');		
		//Definicion de la lista del resultado del query
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('nivel','int4');
		$this->captura('nivel_prioridad','varchar');
		$this->captura('numero','varchar');
 		$this->captura('referencia','varchar');
		$this->captura('tipo','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('desc_funcionario','text');		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
			
	function listarCorrespondencia(){
		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_COR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		//$this->setParametro('interface','interface','integer');
		//$parametros  = $this->aParam->getArregloParametros('interface');
		
		//Definicion de la lista del resultado del query
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
		
	//	$this->captura('id_acciones','int4');//array
		
		$this->captura('id_archivo','integer[]');
		$this->captura('id_correspondencia_fk','int4');
		$this->captura('id_correspondencias_asociadas','integer[]');
		$this->captura('id_depto','int4');
		$this->captura('id_documento','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_gestion','int4');
		$this->captura('id_institucion','int4');
		$this->captura('id_periodo','int4');
		$this->captura('id_persona','int4');
		$this->captura('id_uo','int4');
		$this->captura('mensaje','text');
		$this->captura('nivel','int4');
		$this->captura('nivel_prioridad','varchar');
		$this->captura('numero','varchar');
		$this->captura('observaciones_estado','text');
 		$this->captura('referencia','varchar');
		$this->captura('respuestas','varchar');
		$this->captura('sw_responsable','varchar');
		$this->captura('tipo','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_documento','varchar');
		
		
		$this->captura('desc_depto','varchar');
		$this->captura('desc_funcionario','text');
		$this->captura('ruta_archivo','varchar');
		$this->captura('version','int4');
		
		$this->captura('desc_uo','text');
        $this->captura('desc_clasificador','text');
        $this->captura('id_clasificador','integer');
		
		
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}

    function listarCorrespondenciaDetalle(){
		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORDET_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		
		$this->setParametro('id_correspondencia_fk','id_correspondencia_fk','integer');
		
		//Definicion de la lista del resultado del query
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
		
	//	$this->captura('id_acciones','int4');//array
		
		$this->captura('id_archivo','integer[]');
		$this->captura('id_correspondencia_fk','int4');
		$this->captura('id_correspondencias_asociadas','integer[]');
		$this->captura('id_depto','int4');
		$this->captura('id_documento','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_gestion','int4');
		$this->captura('id_institucion','int4');
		$this->captura('id_periodo','int4');
		$this->captura('id_persona','int4');
		$this->captura('id_uo','int4');
		$this->captura('mensaje','text');
		$this->captura('nivel','int4');
		$this->captura('nivel_prioridad','varchar');
		$this->captura('numero','varchar');
		$this->captura('observaciones_estado','text');
 		$this->captura('referencia','varchar');
		$this->captura('respuestas','varchar');
		$this->captura('sw_responsable','varchar');
		$this->captura('tipo','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_documento','varchar');
		
		
		$this->captura('desc_depto','varchar');
		$this->captura('desc_funcionario','text');
		$this->captura('ruta_archivo','varchar');
		$this->captura('version','int4');
		
		$this->captura('desc_persona','text');
		$this->captura('desc_institucion','varchar');
		$this->captura('acciones','text');
		$this->captura('id_acciones','text');
		
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCorrespondencia(){ 
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COR_INS';
		$this->tipo_procedimiento='IME';
			
		//Define los parametros para la funcion
		$this->setParametro('estado','estado','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_documento','fecha_documento','date');
		$this->setParametro('fecha_fin','fecha_fin','date');
		
		//$this->setParametro('id_acciones','id_acciones','varchar');//array
		
		//$this->setParametro('id_archivo','id_archivo','int4');
		$this->setParametro('id_correspondencia_fk','id_correspondencia_fk','int');
		$this->setParametro('id_correspondencias_asociadas','id_correspondencias_asociadas','varchar');
		
		$this->setParametro('id_depto','id_depto','int4');
		$this->setParametro('id_documento','id_documento','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_institucion','id_institucion','int4');
		$this->setParametro('id_persona','id_persona','int4');
		$this->setParametro('id_uo','id_uo','int4');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nivel_prioridad','nivel_prioridad','varchar');
		$this->setParametro('numero','numero','varchar');
		$this->setParametro('observaciones_estado','observaciones_estado','text');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('respuestas','respuestas','varchar');
		$this->setParametro('sw_responsable','sw_responsable','varchar');
		$this->setParametro('tipo','tipo','varchar');
		
		//$this->setParametro('id_funcionarios','id_funcionarios','varchar');
		$this->setParametro('id_clasificador','id_clasificador','int4');
		
		//para correspodencia destino
		$this->setParametro('id_institucion_destino','id_institucion_destino','int4');
		$this->setParametro('id_persona_destino','id_persona_destino','int4');
		$this->setParametro('id_funcionarios','id_funcionarios','varchar');
		$this->setParametro('id_acciones','id_acciones','varchar');
		
		
		$this->setParametro('cite','cite','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCorrespondencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COR_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('estado','estado','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_documento','fecha_documento','date');
		$this->setParametro('fecha_fin','fecha_fin','date');
		$this->setParametro('id_acciones','id_acciones','varchar');
		$this->setParametro('id_archivo','id_archivo','int4');
		$this->setParametro('id_correspondencia_fk','id_correspondencia_fk','int4');
		$this->setParametro('id_correspondencias_asociadas','id_correspondencias_asociadas','varchar');
		$this->setParametro('id_depto','id_depto','int4');
		$this->setParametro('id_documento','id_documento','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_gestion','id_gestion','int4');
		$this->setParametro('id_institucion','id_institucion','int4');
		$this->setParametro('id_periodo','id_periodo','int4');
		$this->setParametro('id_persona','id_persona','int4');
		$this->setParametro('id_uo','id_uo','int4');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nivel_prioridad','nivel_prioridad','varchar');
		$this->setParametro('numero','numero','varchar');
		$this->setParametro('observaciones_estado','observaciones_estado','text');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('respuestas','respuestas','varchar');
		$this->setParametro('sw_responsable','sw_responsable','varchar');
		$this->setParametro('tipo','tipo','varchar');
		//$this->setParametro('id_funcionarios','id_funcionarios','varchar');
		$this->setParametro('id_clasificador','id_clasificador','int4');
	

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCorrespondencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COR_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	/*13/12/11*/
	function listarCorrespondenciaRecibida(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORREC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
		//$this->captura('id_acciones','int4');
		//$this->captura('id_archivo','int4');
		$this->captura('id_correspondencia_fk','int4');
		$this->captura('id_correspondencias_asociadas','integer[]');
		$this->captura('id_depto','int4');
		$this->captura('id_documento','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_gestion','int4');
		$this->captura('id_institucion','int4');
		$this->captura('id_periodo','int4');
		$this->captura('id_persona','int4');
		$this->captura('id_uo','int4');
		$this->captura('mensaje','text');
		$this->captura('nivel','int4');
		$this->captura('nivel_prioridad','varchar');
		$this->captura('numero','varchar');
		$this->captura('observaciones_estado','text');
 		$this->captura('referencia','varchar');
		$this->captura('respuestas','varchar');
		$this->captura('sw_responsable','varchar');
		$this->captura('tipo','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_documento','varchar');
		$this->captura('desc_funcionario','varchar');
		$this->captura('acciones','text');
        $this->captura('desc_depto','text');
    
        $this->captura('desc_uo','text');
		$this->captura('desc_gestion','integer');
        $this->captura('desc_periodo','integer');
        $this->captura('desc_persona','text');
        $this->captura('desc_institucion','varchar');
        $this->captura('version','int4');
        $this->captura('ruta_archivo','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
	function insertarCorrespondenciaDetalle(){ 
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORDET_INS';
		$this->tipo_procedimiento='IME';
			
		
		//Define los parametros para la funcion
			
		$this->setParametro('id_funcionario','id_funcionario','varchar');
		$this->setParametro('id_correspondencia_fk','id_correspondencia_fk','int');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('id_acciones','id_acciones','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}


    function subirCorrespondencia(){
	
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_ARCHCOR_MOD';
		$this->tipo_procedimiento='IME';
		
		//apartir del tipo  del archivo obtiene la extencion
		$ext = pathinfo($this->arregloFiles['file_correspondencia']['name']);
 		$this->arreglo['extension']= strtolower($ext['extension']);
		
		if($this->arreglo['extension']!='pdf'){
			 throw new Exception("Solo se admiten archivos PDF");
		}
		
		$verion = $this->arreglo['version'] +1;
		$this->arreglo['version']=$verion;
		$ruta_dir = './../../sis_correspondencia/control/_archivo/'.$this->arreglo['id_gestion'];
		$this->arreglo['ruta_archivo']=$ruta_dir.'/docCor'.str_replace("/", "_",$this->arreglo['numero']).'v'.$verion.'.'.$this->arreglo['extension'];
		//Define los parametros para la funcion	
		$this->setParametro('id_correspondencia','id_correspondencia','integer');	
		$this->setParametro('version','version','integer');
		$this->setParametro('ruta_archivo','ruta_archivo','varchar');
		
		//verficar si no tiene errores el archivo
		
		 //echo $_SERVER [ 'DOCUMENT_ROOT' ];
		
		if ($this->arregloFiles['file_correspondencia']['error']) {
          switch ($this->arregloFiles['file_correspondencia']['error']){
                   case 1: // UPLOAD_ERR_INI_SIZE
                   throw new Exception("El archivo sobrepasa el limite autorizado por el servidor(archivo php.ini) !");
                   break;
                   case 2: // UPLOAD_ERR_FORM_SIZE
                   throw new Exception("El archivo sobrepasa el limite autorizado en el formulario HTML !");
                   break;
                   case 3: // UPLOAD_ERR_PARTIAL
                   throw new Exception("El envio del archivo ha sido suspendido durante la transferencia!");
                   break;
                   case 4: // UPLOAD_ERR_NO_FILE
                   throw new Exception("El archivo que ha enviado tiene un tamaño nulo !");
                   break;
          }
		}
		else {
		 // $_FILES['nom_del_archivo']['error'] vale 0 es decir UPLOAD_ERR_OK
		 // lo que significa que no ha habido ningún error
		}
				
		
		
		
		//verificar si existe la carpeta destino
		
		if(!file_exists($ruta_dir))
		{
			///si no existe creamos la carpeta destino	
			if(!mkdir($ruta_dir,0777)){
	           throw new Exception("Error al crear el directorio");		
			}
	
		}
		
		//movemos el archivo
		if(!move_uploaded_file($this->arregloFiles['file_correspondencia']["tmp_name"],$this->arreglo['ruta_archivo'])){
			throw new Exception("Error al copiar archivo");	
		}
		
		
		//si la copia de archivo tuvo emodificamos el registro
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		
		return $this->respuesta;
	}
	
	function derivarCorrespondencia()
	{
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORDER_UPD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
		
		
	}
	
	function corregirCorrespondencia()
	{
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORUNDO_UPD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
		
		
	}
	
	
	
			
}
?>