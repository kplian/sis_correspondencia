<?php
/**
 * @package pXP
 * @file gen-ACTCorrespondencia.php
 * @author  (rac)
 * @date 13-12-2011 16:13:21
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
require_once(dirname(__FILE__) . '/../reporte/RReportes.php');

require_once(dirname(__FILE__) . '/../../lib/PHPWord-master/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();


require_once(dirname(__FILE__).'/../reporte/RCodigoQRCORR.php');

class ACTCorrespondencia extends ACTbase
{

    function listarCorrespondencia()
    {
        $this->objParam->defecto('ordenacion', 'id_correspondencia');

        $this->objParam->defecto('dir_ordenacion', 'desc');


        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);

        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondencia');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondencia();
        }


        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarCorrespondenciaSimplificada()
    {
        $this->objParam->defecto('ordenacion', 'id_correspondencia');

        $this->objParam->defecto('dir_ordenacion', 'desc');

        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaSimplificada');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaSimplificada();
        }

        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarCorrespondenciaDetalle()
    {

        $this->objParam->defecto('ordenacion', 'id_correspondencia');

        $this->objParam->defecto('dir_ordenacion', 'desc');


        if ($this->objParam->getParametro('id_correspondencia_fk') != '') {
            $this->objParam->addFiltro("cor.id_correspondencia_fk = " . $this->objParam->getParametro('id_correspondencia_fk'));
        }

        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaDetalle');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaDetalle();
        }

        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    function insertarCorrespondencia()
    { //echo "aaaa"; exit;


        $this->objFunc = $this->create('MODCorrespondencia');
        if ($this->objParam->insertar('id_correspondencia')) {
            $this->res = $this->objFunc->insertarCorrespondencia();
        } else {
            $this->res = $this->objFunc->modificarCorrespondencia();
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarCorrespondencia()
    {
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->eliminarCorrespondencia();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    function insertarCorrespondenciaDetalle()
    { //echo "aaaa"; exit;

        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);


        $this->objFunc = $this->create('MODCorrespondencia');

        if ($this->objParam->insertar('id_correspondencia')) {
            $this->res = $this->objFunc->insertarCorrespondenciaDetalle($this->objParam);
        } else {
            $this->res = $this->objFunc->modificarCorrespondenciaDetalle($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function subirCorrespondencia()
    {

        //crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
        $this->objFunSeguridad = $this->create('MODCorrespondencia');
        $this->res = $this->objFunSeguridad->subirCorrespondencia($this->objParam);
        //imprime respuesta en formato JSON
        $this->res->imprimirRespuesta($this->res->generarJson());

    }

    function derivarCorrespondencia()
    {
        //crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
        $this->objFunSeguridad = $this->create('MODCorrespondencia');
        $this->res = $this->objFunSeguridad->derivarCorrespondencia($this->objParam);
        //imprime respuesta en formato JSON
        $this->res->imprimirRespuesta($this->res->generarJson());

    }

    function corregirCorrespondencia()
    {
        //crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
        $this->objFunSeguridad = $this->create('MODCorrespondencia');
        $this->res = $this->objFunSeguridad->corregirCorrespondencia($this->objParam);
        //imprime respuesta en formato JSON
        $this->res->imprimirRespuesta($this->res->generarJson());

    }


    function listarCorrespondenciaRecibida()
    {
        $this->objParam->defecto('ordenacion', 'id_correspondencia');

        $this->objParam->defecto('dir_ordenacion', 'asc');

        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);

        $this->objParam->addFiltro("cor.sw_archivado = ''no'' ");

		
		if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaRecibida');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaRecibida();
        }
		$this->res->imprimirRespuesta($this->res->generarJson());
    }

    function finalizarRecepcion()
    {


        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->finalizarRecepcion();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function verCorrespondencia()
    {
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->verCorrespondencia();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function PlantillaCorrespondencia()
    {


        try {



            $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);
            $this->objParam->defecto('ordenacion', 'id_correspondencia');
            $this->objParam->defecto('dir_ordenacion', 'desc');
            $this->objParam->addFiltro("cor.id_correspondencia = " . $this->objParam->getParametro('id_correspondencia'));
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondencia();


            if ($this->res->getTipo() == 'ERROR') {
                $this->res->imprimirRespuesta($this->res->generarJson());
                exit;
            }
            $correspondencia = $this->res->getDatos();


            //obtener detalle de envios
            $this->objParam->parametros_consulta['ordenacion'] = 'id_correspondencia';
            $this->objParam->parametros_consulta['filtro'] = ' 0 = 0 ';
            $this->objParam->parametros_consulta['cantidad'] = '1000';
            $this->objParam->addFiltro("cor.id_correspondencia_fk = " . $this->objParam->getParametro('id_correspondencia'));
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaDetalle($this->objParam);

            if ($this->res->getTipo() == 'ERROR') {
                $this->res->imprimirRespuesta($this->res->generarJson());
                exit;
            }
            $correspondenciaDetalle = $this->res->getDatos();


            //desc_funcionario -> es el funcionario que lo envia
            //desc_uo ->
            //numero numero de la correspondencia

            /*generamos una imagen qr para ingresar a la plantilla*/
            $cadena_qr = '|' . $correspondencia[0]['numero'] . '|' . $correspondencia[0]['desc_uo'] . '|' . $correspondencia[0]['desc_funcionario'] . '';
            $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');

            //todo cambiar ese nombre por algo randon
            $nombre_archivo = md5($_SESSION["ss_id_usuario_ai"] . $_SESSION["_SEMILLA"]);

            $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));


            $im = imagecreatefromstring($png);
            if ($im !== false) {


                header('Content-Type: image/png');
                imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nombre_archivo . ".png");
                imagedestroy($im);

                $img_qr = dirname(__FILE__) . "/../../reportes_generados/" . $nombre_archivo . ".png";



                if($correspondencia[0]['desc_ruta_plantilla_documento'] == NULL){

                    throw new Exception('no tiene plantilla o no esta en el formato correspondiente');

                }


                /*agrego a la plantilla word los datos */
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($correspondencia[0]['desc_ruta_plantilla_documento']);



                $templateProcessor->cloneRow('destinatario', count($correspondenciaDetalle));

                for ($i = 0; $i < count($correspondenciaDetalle); $i++) {

                    $xml_destinatario = htmlspecialchars($correspondenciaDetalle[$i]['desc_funcionario']) . '</w:t>
                                    </w:r>
                                </w:p>
                                <w:p w:rsidR="003D7875" w:rsidRDefault="006C602F" w:rsidP="006C602F">
                                    <w:pPr>
                                        <w:pStyle w:val="Encabezado"/>
                                        <w:tabs>
                                            <w:tab w:val="clear" w:pos="4818"/>
                                            <w:tab w:val="left" w:pos="1276"/>
                                            <w:tab w:val="left" w:pos="2268"/>
                                            <w:tab w:val="left" w:pos="2552"/>
                                        </w:tabs>
                                        <w:jc w:val="both"/>
                                        <w:rPr>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:iCs/>
                                            <w:color w:val="000000"/>
                                            <w:lang w:val="es-ES"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r>
                                        <w:rPr>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:iCs/>
                                            <w:color w:val="000000"/>
                                            <w:lang w:val="es-ES"/>
                                        </w:rPr>
                                        <w:t>' . htmlspecialchars($correspondenciaDetalle[$i]["desc_cargo"]) . '</w:t>
                                    </w:r>
                                </w:p>';


                    $numero_key = $i + 1;
                    $key_name = '${destinatario#' . $numero_key . '}</w:t></w:r></w:p>';

                    $key_2 = '${destinatario#1}</w:t></w:r></w:p>';

                    $templateProcessor->setValueDestinatario($key_name, $xml_destinatario);
                    //$templateProcessor->setValue($key_name, $correspondenciaDetalle[$i]['desc_funcionario'].'<br /> '.$correspondenciaDetalle[$i]['desc_cargo']);


                }

                setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");

                $fecha_documento = strftime("%d/%m/%Y", strtotime($correspondencia[0]['fecha_documento']));


                $templateProcessor->setImg('firma_digital', array('src' => $img_qr, 'swh' => '150'));

                $templateProcessor->setImgFooter('qr', array('src' => $img_qr, 'swh' => '250'));
                //$templateProcessor->setImgHeader('qrh',array('src' => $img_qr, 'swh'=>'250'));

                $templateProcessor->setValue('remitente', htmlspecialchars($correspondencia[0]['desc_funcionario']));
                $templateProcessor->setValue('cargo_remitente', htmlspecialchars($correspondencia[0]['desc_cargo']));
                $templateProcessor->setValue('referencia', htmlspecialchars($correspondencia[0]['referencia']));
                $templateProcessor->setValue('fecha', htmlspecialchars($fecha_documento));
                $templateProcessor->setValue('mensaje', htmlspecialchars($correspondencia[0]['mensaje']));
                $templateProcessor->setValue('numero', htmlspecialchars($correspondencia[0]['numero']));
                //$templateProcessor->setValue('uo', htmlspecialchars($correspondencia[0]['desc_uo']));


                $templateProcessor->saveAs(dirname(__FILE__) . '/../../reportes_generados/' . $nombre_archivo . '.docx');


                $temp['docx'] = $nombre_archivo . '.docx';


                $this->res->setDatos($temp);

                $this->res->imprimirRespuesta($this->res->generarJson());

            } else {
                echo 'ocurrio un error al guardar la imagen.';
            }
        } catch (Exception $e) {


            throw new Exception($e->getMessage(), 2);


        } //fin catch


    }

    function hojaRuta()
    {
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->hojaRuta();


        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
        $hoja_ruta = $this->res->getDatos();

        $id_origen = $hoja_ruta[0]['desc_id_origen'];
        $id_funcionario_origen = $hoja_ruta[0]['desc_id_funcionario_origen'];

        //obtenemos la correspondencia original el origen
        $this->objParam->addParametro('id_funcionario_usuario', $id_funcionario_origen);
        $this->objParam->defecto('ordenacion', 'id_correspondencia');
        $this->objParam->defecto('dir_ordenacion', 'desc');
        $this->objParam->addFiltro("cor.id_correspondencia = " . $id_origen);
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->listarCorrespondencia();


        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
        $correspondencia = $this->res->getDatos();


        $html = '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<title></title>
			</head>
			<body>

			<style type="text/css">
							.tg  {border-collapse:collapse;border-spacing:0; border: 0;}
							.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
							.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg .tg-e3zv{font-weight:bold}
							.tg .tg-yw4l{vertical-align:top}
							.tg .tg-9hbo{font-weight:bold;vertical-align:top}
							</style>
							<CENTER><div><B>REGISTRO DE CORRESPONDENCIA DERIVADA</B></div></CENTER>
							<hr />
							<table class="tg" border="0">
							  <tr>
								<th class="tg-e3zv">Nro:</th>
								<th class="tg-031e">' . $correspondencia[0]["numero"] . '</th>
								<th class="tg-e3zv">Fecha Recept:</th>
								<th class="tg-031e">' . $correspondencia[0]["fecha_documento"] . '</th>
								<th class="tg-e3zv">Fecha Doc:</th>
								<th class="tg-yw4l">' . $correspondencia[0]["fecha_documento"] . '</th>
								<th class="tg-9hbo">Tipo</th>
								<th class="tg-yw4l"></th>
							  </tr>
							  <tr>
								<td class="tg-e3zv" colspan="2">Remitente:</td>
								<td class="tg-e3zv" colspan="2">Referencia:</td>
								<td class="tg-e3zv" colspan="2">Estado:</td>
								<td class="tg-9hbo" colspan="2">observaciones</td>
							  </tr>
							  <tr>
								<td class="tg-yw4l" colspan="2">' . $correspondencia[0]["desc_funcionario"] . '<br /><b style="font-size:8pt">' . $correspondencia[0]["desc_cargo"] . '</b></td>
								<td class="tg-yw4l" colspan="2">' . $correspondencia[0]["referencia"] . '</td>
								<td class="tg-yw4l" colspan="2">' . $correspondencia[0]["estado"] . '</td>
								<td class="tg-yw4l" colspan="2">' . $correspondencia[0]["observaciones_estado"] . '</td>
							  </tr>
							  <tr>
								<td class="tg-e3zv" colspan="2">Usuario Reg</td>
								<td class="tg-e3zv" colspan="3">Derivado a:</td>
								<td class="tg-9hbo">Accion</td>
								<td class="tg-9hbo">Mensaje</td>
								<td class="tg-9hbo">Firma</td>
							  </tr>
							  ';
        foreach ($hoja_ruta as $ruta) {
            $html .= '
							  <tr>
								<td class="tg-yw4l" colspan="2">(' . $ruta['cuenta'] . ') ' . $ruta['desc_person_fk'] . '<br /><b style="font-size:8pt;">' . $ruta["desc_cargo_fk"] . '</b></td>
								<td class="tg-yw4l" colspan="3">' . $ruta['desc_person'] . '<br /><b style="font-size:8pt;">' . $ruta["desc_cargo"] . '</b></td>
								<td class="tg-yw4l">' . $ruta['acciones'] . '</td>
								<td class="tg-yw4l">' . $ruta['mensaje'] . '</td>
								<td class="tg-yw4l"></td>
							  </tr>';

        }

        $html .= '</table>

	<script type="text/javascript">
window.onload=function(){self.print();}
</script>

			</body>
			</html>
';

        $temp['html'] = $html;


        $this->res->setDatos($temp);


        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    function archivarCorrespondencia()
    {

        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->archivarCorrespondencia();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    function listarCorrespondenciaRecibidaArchivada()
    {
        $this->objParam->defecto('ordenacion', 'id_correspondencia');
        $this->objParam->defecto('dir_ordenacion', 'asc');
        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);
        $this->objParam->addFiltro("cor.sw_archivado = ''si'' ");
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->listarCorrespondenciaRecibida();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarCorrespondenciaFisicaEmitida()
    {

        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);


        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->listarCorrespondenciaFisicaEmitida();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function cambiarEstadoCorrespondenciaFisica()
    {

        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);


        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->cambiarEstadoCorrespondenciaFisica();
        $this->res->imprimirRespuesta($this->res->generarJson());

    }


    /*funcion para correspondencia externa */


    function insertarCorrespondenciaExterna()
    {


        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->insertarCorrespondenciaExterna();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function finalizarRecepcionExterna()
    {
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->finalizarRecepcionExterna();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarCorrespondenciaExterna()
    {
        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);


        /*
                if($this->objParam->getParametro('estado') != ''){
                    $this->objParam->addFiltro("cor.estado in ('' ".$this->objParam->getParametro('estado')." '' ) ");
                }*/


        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->listarCorrespondenciaExterna();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }	
	//manu,06/10/2017 agregando a control
	function recuperarCodigoQR(){
		$this->objFunc = $this->create('MODCorrespondencia');
		$cbteHeader = $this->objFunc->recuperarCodigoQR($this->objParam);
		if($cbteHeader->getTipo() == 'EXITO'){				
			return $cbteHeader;
		}
		else{
			$cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
			exit;
		}	
	}
	//
	function recuperarCodigoQR2(){
		$this->objFunc = $this->create('MODCorrespondencia');
		$cbteHeader = $this->objFunc->recuperarCodigoQR2($this->objParam);
		if($cbteHeader->getTipo() == 'EXITO'){				
			return $cbteHeader;
		}
		else{
			$cbteHeader->imprimirRespuesta($cbteHeader->generarJson());
			exit;
		}	
	}
	//
	function impCodigoCorrespondecia(){
		
		$nombreArchivo = 'CodigoCO'.uniqid(md5(session_id())).'.pdf'; 				
		$dataSource = $this->recuperarCodigoQR();		
		$orientacion = 'L';
		$titulo = 'Códigos Correspondencia';				
		$width = 160;  
		$height = 80;
		$this->objParam->addParametro('orientacion',$orientacion);
		$this->objParam->addParametro('tamano',array($width, $height));		
		$this->objParam->addParametro('titulo_archivo',$titulo);        
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		
		$clsRep = $dataSource->getDatos();
		//var_dump($clsRep);
		eval('$reporte = new '.$clsRep['v_clase_reporte'].'($this->objParam);');
		$reporte->datosHeader('unico', $dataSource->getDatos());
		$reporte->generarReporte();
		$reporte->output($reporte->url_archivo,'F');  		
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
	}
	//
	function impCodigoCorrespondecia2(){
		
		$nombreArchivo = 'CodigoCO'.uniqid(md5(session_id())).'.pdf'; 				
		$dataSource = $this->recuperarCodigoQR2();		
		$orientacion = 'L';
		$titulo = 'Código de correspondencia';	
		$tamano = 'LETTER';			

		$this->objParam->addParametro('orientacion',$orientacion);
		$this->objParam->addParametro('tamano',array($width, $height));		
		$this->objParam->addParametro('titulo_archivo',$titulo);        
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		
		$clsRep = $dataSource->getDatos();

		eval('$reporte = new '.$clsRep['v_clase_reporte'].'($this->objParam);');
		$reporte->datosHeader('unico', $dataSource->getDatos());
		$reporte->generarReporte();
		$reporte->output($reporte->url_archivo,'F');  		
		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
	}
}
?>