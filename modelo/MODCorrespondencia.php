<?php
/**
 *@package pXP
 *@file gen-MODCorrespondencia.php
 *@author  (rac)
 *@date 13-12-2011 16:13:21
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA        AUTOR        DESCRIPCION
#4      		25/07/2019   MCGH         Adición del campo persona_remitente, fecha recepción,
#										  Eliminación del campo id_clasificador,
#										  Adición del campo persona_destino, fecha envio

#5      		21/08/2019   MCGH         Eliminación de Código Basura
#7      		06/09/2019   MCGH         Adición del campo Tiene el Fisico
#8          25/09/2019   Manuel Guerra      nuevas funcionalidades

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
		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');
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
		$this->captura('id_origen','integer');
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

		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');
		$this->setParametro('interface','interface','varchar');

		//$this->setParametro('interface','interface','integer');
		//$parametros  = $this->aParam->getArregloParametros('interface');

		//Definicion de la lista del resultado del query
		$this->captura('id_origen','int4');
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
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
		//$this->captura('desc_clasificador','text'); //#4
		$this->captura('id_clasificador','integer');
		$this->captura('desc_ruta_plantilla_documento','varchar');
		$this->captura('desc_cargo','varchar');
		$this->captura('sw_archivado','varchar');
		$this->captura('iniciales','text');

		$this->captura('desc_insti','varchar');
		$this->captura('nombre_completo1','text');
		$this->captura('id_institucion_destino','integer');
		$this->captura('id_persona_destino','integer');
		$this->captura('otros_adjuntos','varchar');
		$this->captura('direccion_institucion','text');
		$this->captura('desc_funcionario_de_plantilla','text');
		$this->captura('adjunto','bigint');
		$this->captura('fecha_creacion_documento','timestamp');
		$this->captura('acciones','text');
		$this->captura('id_acciones','text');
		$this->captura('fecha_documento_literal','text');
		$this->captura('fecha_ult_derivado','timestamp');
		$this->captura('persona_nombre_plantilla','text');
		$this->captura('observaciones_archivado','text');
		$this->captura('desc_correspondencias_asociadas','text');
		$this->captura('tipo_documento','varchar');
		$this->captura('persona_firma','varchar');
		$this->captura('persona_destino','varchar'); //#4
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

		//Definicion de la lista del resultado del query
		$this->captura('id_origen','int4');
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
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
		$this->captura('desc_cargo','varchar');
		$this->captura('fecha_documento_literal','text');
		$this->captura('desc_funcionario_plantilla','text');
		$this->captura('estado_corre','varchar');
		$this->captura('persona_remitente','varchar');
		$this->captura('fisico','varchar');  //#7

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarCorrespondenciaDetalleAnulado(){
		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CODEAN_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_origen','int4');
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
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
		$this->captura('desc_cargo','varchar');
		$this->captura('fecha_documento_literal','text');
		$this->captura('desc_funcionario_plantilla','text');

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
		$vista = $this->aParam->getParametro('vista');
		$this->aParam->addParametro('vista', $vista);

		$this->setParametro('vista','vista','varchar');
		$this->setParametro('estado','estado','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_documento','fecha_documento','date');
		$this->setParametro('fecha_fin','fecha_fin','date');
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
		$this->setParametro('fecha_creacion_documento','fecha_creacion_documento','date');
		$this->setParametro('cite','cite','varchar');
		$this->setParametro('id_funcionario_saliente','id_funcionario_saliente','varchar');
		$this->setParametro('persona_destino','persona_destino','varchar'); //#4
		$this->setParametro('fecha_envio','fecha_envio','date'); //#4
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
		$this->setParametro('id_institucion_destino','id_institucion_destino','int4');
		$this->setParametro('id_persona_destino','id_persona_destino','int4');
		$this->setParametro('otros_adjuntos','otros_adjuntos','varchar');
		$this->setParametro('id_funcionario_saliente','id_funcionario_saliente','varchar');
		$this->setParametro('persona_destino','persona_destino','varchar'); //#4

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

	function listarCorrespondenciaRecibida(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORREC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//EAQ:envia parametro id_correspondencia padre para funcionalidad acceso directo
		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');
		$this->setParametro('interface','interface','varchar');
		$this->setParametro('tipo','tipo','varchar');

		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		//Definicion de la lista del resultado del query
		$this->captura('id_origen','int4');
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');
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
		$this->captura('origen','varchar');
		$this->captura('desc_funcionario','text');
		$this->captura('desc_funcionario_origen','text');

		$this->captura('acciones','text');
		$this->captura('desc_depto','text');

		$this->captura('desc_uo','text');
		$this->captura('desc_gestion','integer');
		$this->captura('desc_periodo','integer');
		$this->captura('nombre_completo1','text');
		$this->captura('desc_insti','varchar');
		$this->captura('version','int4');
		$this->captura('ruta_archivo','varchar');
		$this->captura('sw_archivado','varchar');
		$this->captura('adjunto','bigint');
		//$this->captura('acciones','text');
		$this->captura('id_acciones','text');
		$this->captura('fecha_creacion_documento','timestamp');
		$this->captura('fecha_ult_derivado','timestamp');
		$this->captura('observaciones_archivado','text');
		$this->captura('cite','varchar');
		$this->captura('otros_adjuntos','varchar');
		$this->captura('nro_paginas','int');
		$this->captura('desc_correspondencias_asociadas','text');
		$this->captura('tipo_documento','varchar');
		$this->captura('persona_firma','varchar');
		$this->captura('estado_fisico','varchar');
		$this->captura('persona_remitente','varchar');
		$this->captura('fisico','varchar');

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
		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4'); //el que envia
		$this->setParametro('id_funcionario','id_funcionario','varchar'); //son a los que enviaremos
		$this->setParametro('id_correspondencia','id_correspondencia_fk','int4');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('id_acciones','id_acciones','varchar');
		$this->setParametro('fisico','fisico','varchar');//#7
		$this->setParametro('id_grupo','id_grupo','varchar'); //#7
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function modificarCorrespondenciaDetalle(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORDET_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('id_correspondencia_fk','id_correspondencia_fk','int4');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('id_acciones','id_acciones','varchar');
		$this->setParametro('id_funcionario','id_funcionario','integer'); //son a los que enviaremos
		$this->setParametro('fisico','fisico','varchar'); //#7

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function subirCorrespondencia()
	{
		$cone = new conexion();
		$this->link = $cone->conectarpdo();
		$copiado = false;
		$sql = "SELECT tamano FROM param.ttipo_archivo WHERE codigo='CORRPRIN'";
		$res = $this->link->prepare($sql);
		$res->execute();
		$result = $res->fetchAll(PDO::FETCH_ASSOC);
		$tamano_archivo=$result[0]['tamano'];

		try{

			$this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->link->beginTransaction();
			//var_dump($tamano_archivo);

			if((($this->arregloFiles['file_correspondencia']['size'] / 1000) / 1024) > $tamano_archivo  ){
				throw new Exception("El tamaño del Archivo supera a la configuración");

			}
			if ($this->arregloFiles['file_correspondencia']['name'] == "") {
				throw new Exception("El archivo no puede estar vacio");
			}

			$this->procedimiento='corres.ft_correspondencia_ime';
			$this->transaccion='CO_ARCHCOR_MOD';
			$this->tipo_procedimiento='IME';

			$version = $this->arreglo['version'] + 1;
			$this->arreglo['version'] = $version;
			$this->arreglo['numero']= str_replace('/','_',$this->arreglo['numero']);
			$this->arreglo['numero']= str_replace(' ','_',$this->arreglo['numero']);
			//validar que no sea un archvo en blanco
			$file_name = $this->getFileName2('file_correspondencia', 'id_correspondencia', '','_v'.$version,$this->arreglo['numero']);


			//manda como parametro la url completa del archivo
			$this->aParam->addParametro('ruta_archivo', $file_name[2]);
			$this->arreglo['ruta_archivo'] = $file_name[2];
			$this->setParametro('ruta_archivo','ruta_archivo','varchar');


			//Define los parametros para la funcion
			$this->setParametro('id_correspondencia','id_correspondencia','integer');
			$this->setParametro('version','version','integer');


			//Ejecuta la instruccion
			$this->armarConsulta();
			$stmt = $this->link->prepare($this->consulta);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);


			if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
				throw new Exception("Error al ejecutar en la bd", 3);
			}



			if($resp_procedimiento['tipo_respuesta'] == 'EXITO'){

				//revisamos si ya existe el archivo la verison anterior sera mayor a cero
				$respuesta = $resp_procedimiento['datos'];
				//cipiamos el nuevo archivo

				$this->setFile('file_correspondencia','id_correspondencia', false,100000 ,array('doc','pdf','docx','jpg','jpeg','bmp','gif','png','PDF','DOC','DOCX','xls','xlsx','XLS','XLSX','rar'), $folder = '','_v'.$version,$this->arreglo['numero']);

			}


			$this->link->commit();
			$this->respuesta=new Mensaje();
			$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
			$this->respuesta->setDatos($respuesta);
		}
		catch (Exception $e) {


			$this->link->rollBack();


			$this->respuesta=new Mensaje();
			if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
				$this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
			} else if ($e->getCode() == 2) {//es un error en bd de una consulta
				$this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
			} else {//es un error lanzado con throw exception
				throw new Exception($e->getMessage(), 2);
			}

		}


		return $this->respuesta;
	}


	function subirCorrespondencia_bk(){

		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_ARCHCOR_MOD';
		$this->tipo_procedimiento='IME';

		//apartir del tipo  del archivo obtiene la extencion
		$ext = pathinfo($this->arregloFiles['file_correspondencia']['name']);
		$this->arreglo['extension'] = strtolower($ext['extension']);

		if($this->arreglo['extension']!='pdf'){
			throw new Exception("Solo se admiten archivos PDF");
		}

		$verion = $this->arreglo['version'] + 1;
		$this->arreglo['version'] = $verion;
		$ruta_dir = '/../control/_archivo/'.$this->arreglo['id_gestion'];
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
		//var_dump(dirname(__FILE__));
		////exit;
		if(!file_exists($ruta_dir))
		{
			///si no existe creamos la carpeta destino
			if(!mkdir($ruta_dir,0744,true)){


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
		$this->setParametro('id_origen','id_origen','int4');
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
		$this->setParametro('observaciones','observaciones','varchar');
		$this->setParametro('interfaz','interfaz','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;

	}
	function habCorregirCorrespondencia()
	{
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_HABCORR_UPD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('observaciones_estado','observaciones_estado','varchar');
		$this->setParametro('estado_corre','estado_corre','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;


	}
	function corregirCorrespondenciaExt()
	{
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORUNDOEXT_UPD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('interfaz','interfaz','varchar');
		$this->setParametro('observaciones','observaciones','varchar');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;


	}

	function finalizarRecepcion(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORFIN_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function verCorrespondencia(){

		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORDOC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$this->setParametro('id_origen','id_origen','integer');

		//Definicion de la lista del resultado del query
		$this->captura('ruta_archivo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;

	}

	function hojaRuta(){

		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORHOJ_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$this->setCount(false);

		$this->setParametro('id_correspondencia','id_correspondencia','integer');
		$this->setParametro('estado_reporte','estado_reporte','varchar');

		//mgarcia para obtener el historico
		$this->setParametro('id_institucion','id_institucion','integer');
		//
		$this->captura('numero','varchar');
		$this->captura('id_correspondencia_fk','int4');
		$this->captura('desc_person_fk','text');
		$this->captura('desc_cargo_fk','text');

		$this->captura('id_correspondencia','int4');
		$this->captura('desc_person','text');
		$this->captura('desc_cargo','text');
		$this->captura('mensaje','text');
		$this->captura('referencia','varchar');
		$this->captura('acciones','text');
		$this->captura('cuenta','varchar');
		$this->captura('desc_id_origen','int4');
		$this->captura('desc_id_funcionario_origen','int4');
		$this->captura('estado','varchar');

		$this->captura('fecha_documento','date');
		$this->captura('fecha_deriv','timestamp');
		$this->captura('fecha_recepcion','timestamp');
		$this->captura('fisico','varchar'); //#7

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;

	}

	function archivarCorrespondencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORARCH_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('sw_archivado','sw_archivado','varchar');
		$this->setParametro('observaciones_archivado','observaciones_archivado','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function habilitarCorrespondencia(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORHAB_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('tipo_corres','tipo_corres','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function listarCorrespondenciaFisicaEmitida(){

		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_CORFIEM_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');

		$this->captura('tiene','text');
		$this->captura('id_correspondencia','int4');
		$this->captura('numero','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('ruta_archivo','varchar');
		$this->captura('estado_fisico','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;

	}

	function cambiarEstadoCorrespondenciaFisica(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_CORESTFIS_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('estado_fisico','estado_fisico','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}


	function listarCorrespondenciaExterna(){

		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_COREXTE_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('estado','estado','varchar');

		//$this->setParametro('interface','interface','integer');
		//$parametros  = $this->aParam->getArregloParametros('interface');


		//Definicion de la lista del resultado del query
		$this->captura('id_origen','int4');
		$this->captura('id_correspondencia','int4');
		$this->captura('estado','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_documento','date');
		$this->captura('fecha_fin','date');

		$this->captura('id_acciones','int4[]');//array
		$this->captura('cite','varchar');
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
		$this->captura('ruta_archivo','varchar');
		$this->captura('version','int4');
		//$this->captura('desc_clasificador','text');
		//$this->captura('id_clasificador','integer');
		$this->captura('desc_ruta_plantilla_documento','varchar');
		$this->captura('sw_archivado','varchar');
		$this->captura('desc_insti','varchar');
		$this->captura('id_institucion_remitente','integer');
		$this->captura('nro_paginas','integer');
		$this->captura('id_persona_remitente','integer');
		$this->captura('nombre_completo1','text');
		$this->captura('otros_adjuntos','varchar');
		$this->captura('adjunto','bigint');
		$this->captura('sw_fisico','varchar');
		$this->captura('fecha_creacion_documento','timestamp');
		$this->captura('observaciones_archivado','text');
		$this->captura('estado_corre','varchar');
		$this->captura('desc_funcionario','text');
		$this->captura('acciones','text');
		$this->captura('desc_correspondencias_asociadas','text');
		$this->captura('tipo_documento','varchar');
		$this->captura('persona_firma','varchar');
		$this->captura('estado_fisico','varchar');
		$this->captura('persona_remitente','varchar');
		$this->captura('fisico','varchar'); ///#7

		//Ejecuta la instruccion
		$this->armarConsulta();
		//echo $this->getConsulta();
		$this->ejecutarConsulta();




		//Devuelve la respuesta
		return $this->respuesta;

	}

	function insertarCorrespondenciaExterna(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COREXT_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion

		$this->setParametro('id_funcionario_usuario','id_funcionario_usuario','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('id_documento','id_documento','int4');
		$this->setParametro('fecha_documento','fecha_documento','date');
		$this->setParametro('id_institucion_remitente','id_institucion_remitente','int4');
		//$this->setParametro('id_persona_remitente','id_persona_remitente','int4');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('id_correspondencias_asociadas','id_correspondencias_asociadas','varchar');
		$this->setParametro('nivel_prioridad','nivel_prioridad','varchar');
		//$this->setParametro('id_clasificador','id_clasificador','int4');
		$this->setParametro('id_depto','id_depto','int4');
		$this->setParametro('nro_paginas','nro_paginas','int4');
		$this->setParametro('otros_adjuntos','otros_adjuntos','varchar');
		$this->setParametro('cite','cite','varchar');
		$this->setParametro('fecha_creacion_documento','fecha_creacion_documento','timestamp');
		$this->setParametro('persona_firma','persona_firma','varchar');
		$this->setParametro('tipo_documento','tipo_documento','varchar');
		$this->setParametro('persona_remitente','persona_remitente','varchar');  //#4 MCGH

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function modificarCorrespondenciaExterna(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COREXT_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		$this->setParametro('tipo','tipo','varchar');
		$this->setParametro('id_documento','id_documento','int4');
		$this->setParametro('fecha_documento','fecha_documento','date');
		$this->setParametro('id_institucion_remitente','id_institucion_remitente','int4');
		//$this->setParametro('id_persona_remitente','id_persona_remitente','int4');
		$this->setParametro('referencia','referencia','varchar');
		$this->setParametro('mensaje','mensaje','text');
		$this->setParametro('id_correspondencias_asociadas','id_correspondencias_asociadas','varchar');
		$this->setParametro('nivel_prioridad','nivel_prioridad','varchar');
		$this->setParametro('id_clasificador','id_clasificador','int4');
		$this->setParametro('id_depto','id_depto','int4');
		$this->setParametro('nro_paginas','nro_paginas','int4');
		$this->setParametro('otros_adjuntos','otros_adjuntos','varchar');
		$this->setParametro('cite','cite','varchar');
		$this->setParametro('persona_firma','persona_firma','varchar');
		$this->setParametro('tipo_documento','tipo_documento','varchar');
		$this->setParametro('persona_remitente','persona_remitente','varchar');//#4 MCGH

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function finalizarRecepcionExterna(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COREXTEST_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('id_correspondencia','id_correspondencia','int4');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	//manu,06/10/2017  agregando qr modelo, segun id_correspondencia
	function recuperarCodigoQR(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='SCO_GETQR_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	//
	function recuperarCodigoQR2(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='SCO_GETQR_L_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
	function anularCorrespondencia()
	{
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_COR_ANU';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;


	}

	function listarHojaPrincipal(){



		//funcionon inserta correpondecia interna  y la esterna emitida
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_HOJORIG_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
		$this->setCount(false);
		//$this->setParametro('id_','id_origen','int4');
		$this->setParametro('id_correspondencia','id_correspondencia','int4');
		//$parametros  = $this->aParam->getArregloParametros('interface');

		$this->setParametro('id_institucion','id_institucion','integer'); //#4

		//Definicion de la lista del resultado del query
		$this->captura('numero','VARCHAR');
		$this->captura('fecha_creacion_documento','TIMESTAMP');
		$this->captura('tipo','VARCHAR(20)');
		$this->captura('desc_insti','VARCHAR(100)');
		$this->captura('nombre_persona','TEXT');
		$this->captura('desc_funcionario','TEXT');
		$this->captura('otros_adjuntos','TEXT');
		$this->captura('referencia','TEXT');
		$this->captura('mensaje','TEXT');

		$this->captura('fecha_documento','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		//echo $this->getConsulta();
		$this->ejecutarConsulta();




		//Devuelve la respuesta
		return $this->respuesta;

	}
	function eliminarAlarmaAnulada()
	{
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='corres.ft_correspondencia_ime';
		$this->transaccion='CO_ALAR_ANU';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		//$this->setParametro('id_correspondencia','id_correspondencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;


	}
	//
	function listarUO(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_UO_SEL';
		$this->tipo_procedimiento='SEL';
		$this->setParametro('id_uo','id_uo','int4');
		$this->setParametro('correspondencia','correspondencia','varchar');
		$this->setParametro('gerencia','gerencia','varchar');
		$this->setParametro('presupuesta','presupuesta','varchar');
		//Definicion de la lista del resultado del query
		$this->captura('id_uo','int4');
		$this->captura('codigo_uo_centro','varchar');
		$this->captura('nombre_uo_centro','varchar');
		$this->captura('id_uo_centro','int4');
		$this->captura('ids','varchar');
		$this->captura('id_uo_padre','int4');
		$this->captura('codigo','varchar');
		$this->captura('nombre_unidad','varchar');
		$this->captura('gerencia','varchar');
		$this->captura('correspondencia','varchar');
		$this->captura('uo_centro_orden','numeric');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}

	//MCGH
	function obtenerUoPorFuncionario(){

		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_UOPFUN_SEL';
		$this->tipo_procedimiento='SEL';
		$this->setParametro('id_fun','id_fun','int4');
		$this->setParametro('fecha_doc','fecha_doc','varchar');

		//Definicion de la lista del resultado del query
		$this->captura('id_uo','int4');
		$this->captura('nombre_unidad','varchar');
		$this->captura('id_gerencia','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}
	//
	function obtenerFuncGerencia(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='corres.ft_correspondencia_sel';
		$this->transaccion='CO_FUNGER_SEL';
		$this->tipo_procedimiento='SEL';
		//$this->setParametro('id_uo','id_uo','int4');
		//Definicion de la lista del resultado del query
		$this->captura('id_uo','int4');
		$this->captura('codigo','varchar');
		$this->captura('nombre_unidad','varchar');
		$this->captura('id_funcionario','int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}
}
?>
