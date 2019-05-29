<?php

/**
 * @package pXP
 * @file gen-MODAdjunto.php
 * @author  (admin)
 * @date 22-04-2016 23:13:29
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */
class MODAdjunto extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listarAdjunto()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'corres.ft_adjunto_sel';
        $this->transaccion = 'CORRES_ADJ_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_adjunto', 'int4');
        $this->captura('extension', 'varchar');
        $this->captura('id_correspondencia_origen', 'int4');
        $this->captura('nombre_archivo', 'varchar');
        $this->captura('estado_reg', 'varchar');
        $this->captura('ruta_archivo', 'varchar');
        $this->captura('id_usuario_ai', 'int4');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('usuario_ai', 'varchar');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function insertarAdjunto()
    {


        $arra = array();
        $id_correspondencia_origen = $this->aParam->getParametro('id_correspondencia_origen');

        $ruta_destino = "./../../../uploaded_files/sis_correspondencia/Adjunto/";

        if (!file_exists($ruta_destino)) {
            //echo $upload_folder;
            //exit;
            if (!mkdir($ruta_destino, 0744, true)) {
                throw new Exception("No se puede crear el directorio uploaded_files/" . $this->objParam->getSistema() . "/" .
                    $this->objParam->getClase() . " para escribir el archivo ");
            }
        } else {
            if (!is_writable($ruta_destino)) {
                throw new Exception("No tiene permisos o no existe el directorio uploaded_files/" . $this->objParam->getSistema() . "/" .
                    $this->objParam->getClase() . " para escribir el archivo ");
            }

        }


        $aux = count($this->arregloFiles['archivo']['name']);

        if ($this->arregloFiles['archivo']['name'][0] == '') {
            throw new Exception("El archivo no puede estar vacio");
        }


        for ($i = 0; $i < $aux; $i++) {
            $img = pathinfo($this->arregloFiles['archivo']['name'][$i]);
            $tmp_name = $this->arregloFiles['archivo']['tmp_name'][$i];
            $tamano = ($this->arregloFiles['archivo']['size'][$i] / 1000) . "Kb"; //Obtenemos el tama?o en KB

            $nombre_archivo = $img['filename']; //nombre de archivo
            $extension = $img['extension']; //extension
            $basename = $img['basename']; //nombre de archivo con extension


            $unico_id = uniqid();

            $file_name = md5($unico_id . $_SESSION["_SEMILLA"]);
            $file_server_name = $file_name . ".$extension";
            move_uploaded_file($tmp_name, $ruta_destino . $file_server_name);

            $ruta_archivo = $ruta_destino . $file_server_name;


            $this->aParam->addParametro('ruta_archivo', $ruta_archivo);
            $this->arreglo['ruta_archivo'] = $ruta_archivo; // esta ruta contiene con el archivo mas


            $arra[] = array(
                "nombre_archivo" => $file_name,
                "extension" => $extension,
                "ruta_archivo" => $ruta_archivo,
                "id_correspondencia_origen" => $id_correspondencia_origen

            );

        }


        $arra_json = json_encode($arra);


        $this->aParam->addParametro('arra_json', $arra_json);
        $this->arreglo['arra_json'] = $arra_json;


        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'corres.ft_adjunto_ime';
        $this->transaccion = 'CORRES_ADJ_INS';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('arra_json', 'arra_json', 'text');
        $this->setParametro('id_correspondencia_origen', 'id_correspondencia_origen', 'int4');


        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;


    }

    function modificarAdjunto()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'corres.ft_adjunto_ime';
        $this->transaccion = 'CORRES_ADJ_MOD';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_adjunto', 'id_adjunto', 'int4');
        $this->setParametro('extension', 'extension', 'varchar');
        $this->setParametro('id_correspondencia_origen', 'id_correspondencia_origen', 'int4');
        $this->setParametro('nombre_archivo', 'nombre_archivo', 'varchar');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('ruta_archivo', 'ruta_archivo', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function eliminarAdjunto()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'corres.ft_adjunto_ime';
        $this->transaccion = 'CORRES_ADJ_ELI';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_adjunto', 'id_adjunto', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

}

?>