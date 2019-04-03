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
		$this->objParam->addFiltro("cor.sw_archivado = ''no'' ");
		if ($this->objParam->getParametro('vista')=='CorrespondenciaAdministracion' && $this->objParam->getParametro('estado')=='enviado')
		    {
			}
			else{
			//$this->objParam->addFiltro(" cor.id_correspondencia_fk is null ");
			       
			$this->objParam->addFiltro(" (cor.estado_corre is null or cor.estado_corre not in (''borrador_corre''))");
	     	}
		
		if($this->objParam->getParametro('fecha')==''){
				$date = date('d/m/Y');
			} else {
				$date = $this->objParam->getParametro('fecha');
			}
				
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
		
		if ($this->objParam->getParametro('tipo') != '') {
            $this->objParam->addFiltro("cor.tipo = ''" . $this->objParam->getParametro('tipo')."''");
        }

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
		
		if ($this->objParam->getParametro('estado') == 'anulado') {
            
			if ($this->objParam->getParametro('id_correspondencia_fk') != '') {
            $this->objParam->addFiltro("cor.id_correspondencia_fk = " . $this->objParam->getParametro('id_correspondencia_fk'));
		        }
		
		        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
		            $this->objReporte = new Reporte($this->objParam, $this);
		            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaDetalleAnulado');
		        } else {
		            $this->objFunc = $this->create('MODCorrespondencia');
		            $this->res = $this->objFunc->listarCorrespondenciaDetalleAnulado();
		        }
			
        }else{
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
        }

        

        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarCorrespondenciaDetalleAnulado()
    {

        $this->objParam->defecto('ordenacion', 'id_correspondencia');

        $this->objParam->defecto('dir_ordenacion', 'desc');


        if ($this->objParam->getParametro('id_correspondencia_padre') != '') {
            $this->objParam->addFiltro("cor.id_correspondencia_fk = " . $this->objParam->getParametro('id_correspondencia_padre'));
        }

        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaDetalleAnulado');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaDetalleAnulado();
        }

        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarCorrespondencia()
    { 


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
    { 

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
    	  $this->objFunSeguridad = $this->create('MODCorrespondencia');
		
    	   $this->res = $this->objFunSeguridad->corregirCorrespondencia($this->objParam);
	
        //imprime respuesta en formato JSON
        $this->res->imprimirRespuesta($this->res->generarJson());
  
    }
  function habCorregirCorrespondencia()
    {
    	  $this->objFunSeguridad = $this->create('MODCorrespondencia');
		
    	   $this->res = $this->objFunSeguridad->habCorregirCorrespondencia($this->objParam);
	
        //imprime respuesta en formato JSON
        $this->res->imprimirRespuesta($this->res->generarJson());
  
    }
  
    function consultaAsistente(){
    	 $this->objParam->parametros_consulta['ordenacion'] = 'id_asistente_permisos';
            $this->objParam->parametros_consulta['filtro'] = ' 0 = 0 ';
            $this->objParam->parametros_consulta['cantidad'] = '1000';
            $this->objParam->addFiltro("usua.id_usuario = " . $_SESSION["ss_id_usuario"]);
            $this->objFunc = $this->create('MODAsistentePermisos');
            $this->res = $this->objFunc->listarAsistentePermisos($this->objParam);
		    if ($this->res->getTipo() == 'ERROR') {
                $this->res->imprimirRespuesta($this->res->generarJson());
                exit;
            }
            $asistentePermisos = $this->res->getDatos();
	        return $asistentePermisos[0]['permitir_todo'];
		    
	}
    function listarCorrespondenciaRecibida()
    {
    	
		 if($this->objParam->getParametro('filtro_valor')!=''){
            $this->objParam->addFiltro($this->objParam->getParametro('filtro_campo')." = ".$this->objParam->getParametro('filtro_valor'));	
        }
        
        $this->objParam->defecto('ordenacion', 'id_correspondencia');

        $this->objParam->defecto('dir_ordenacion', 'asc');

        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);
		
        $this->objParam->addFiltro("cor.sw_archivado = ''no'' ");

		if ($this->objParam->getParametro('vista')=='CorrespondenciaAdministracion' && $this->objParam->getParametro('estado')=='enviado')
		    {
			}
			else{
			//$this->objParam->addFiltro(" cor.id_correspondencia_fk is null ");
			   
			$this->objParam->addFiltro(" (cor.estado_corre is null or cor.estado_corre not in (''borrador_corre''))");
	     	}
		
			
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
	/*AVQ
	 * 03/07/2018
	 * Hoja de Ruta en listado.
	 */
 function verHistorico()
    {
    	if($this->objParam->getParametro('id_origen')!=''){
    		$id_correspondencia=$this->objParam->getParametro('id_origen');
		}
			$this->objParam->addParametro('id_correspondencia',$id_correspondencia);
			$this->objParam->addParametro('estado_reporte','finalizado');
    	if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','hojaRuta');
		} else{
			$this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->hojaRuta();
		}
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
            $listAccion = $this->listaAcciones(); //Devuelve la lista de las acciones de la base de datos
            $accionesSeleccionadas = $this->listaAccionesDerivadas($correspondencia[0]['id_correspondencia']);
            
            //desc_funcionario -> es el funcionario que lo envia
            //desc_uo ->
            //numero numero de la correspondencia

            /*generamos una imagen qr para ingresar a la plantilla*/
            $cadena_qr = '|' . $correspondencia[0]['numero'] . '|' . $correspondencia[0]['desc_uo'] . '|' . $correspondencia[0]['desc_funcionario'] . '|' . $correspondencia[0]['fecha_documento'] . '';
            $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');

            //todo cambiar ese nombre por algo randon
            $nombre_archivo = md5($_SESSION["ss_id_usuario_ai"] . $_SESSION["_SEMILLA"]);

           // print_r ($correspondencia);
            //$nombre_archivo= $correspondencia[0]['desc_ruta_plantilla_documento'];
            $png = $barcodeobj->getBarcodePngData($w = 4, $h = 4, $color = array(0, 0, 0));


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
                for ($i = 0; $i <= count($correspondenciaDetalle); $i++) {
                     
                 $xml_destinatario = htmlspecialchars(preg_replace('/\s+/', ' ', $correspondenciaDetalle[$i]['desc_funcionario_plantilla'])) . '</w:t>
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
                                            <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma"/>
                                            <w:b/>
                                            <w:bCs/>
                                            <w:iCs/>
                                            <w:color w:val="000000"/>
                                            <w:lang w:val="es-ES"/>
                                             <w:sz w:val="20"/>
                                        </w:rPr>
                                        <w:t>' . htmlspecialchars($correspondenciaDetalle[$i]["desc_cargo"]) . '</w:t>
                                    </w:r>
                                </w:p>';


                    $numero_key = $i + 1;
                    $key_name = '${destinatario#' . $numero_key . '}</w:t></w:r></w:p>';
                  
								
                    $templateProcessor->setValueDestinatario($key_name, $xml_destinatario);
                    $templateProcessor->setValue($key_name, $correspondenciaDetalle[$i]['desc_funcionario'].'<br /> '.$correspondenciaDetalle[$i]['desc_cargo']);
                    }
               setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");

                $fecha_documento = strftime("%d/%m/%Y", strtotime($correspondencia[0]['fecha_documento']));


                $templateProcessor->setImg('firma_digital', array('src' => $img_qr, 'swh' => '80'));


                $templateProcessor->setImgFooter('qr', array('src' => $img_qr, 'swh' => '50'));
               //$templateProcessor->setImgHeader('qrh',array('src' => $img_qr, 'swh'=>'250'));
                
                //print_r($correspondenciaDetalle);
                $templateProcessor->setValue('remitente', htmlspecialchars(preg_replace('/\s+/', ' ', $correspondencia[0]['desc_funcionario_de_plantilla'])));
                $templateProcessor->setValue('cargo_remitente', htmlspecialchars($correspondencia[0]['desc_cargo']));
                $templateProcessor->setValue('referencia', htmlspecialchars($correspondencia[0]['referencia']));
                $templateProcessor->setValue('fecha', htmlspecialchars($fecha_documento));
                $templateProcessor->setValue('mensaje', htmlspecialchars($correspondencia[0]['mensaje']));
                $templateProcessor->setValue('numero', htmlspecialchars($correspondencia[0]['numero']));
				
				$templateProcessor->setValue('fecha_documento_literal', htmlspecialchars($correspondencia[0]['fecha_documento_literal']));
                $templateProcessor->setValue('iniciales', htmlspecialchars($correspondencia[0]['iniciales']));
                $templateProcessor->setValue('direccion_institucion', htmlspecialchars($correspondencia[0]['direccion_institucion']));
                $templateProcessor->setValue('desc_insti', htmlspecialchars($correspondencia[0]['desc_insti']));
				$templateProcessor->setValue('nombre_completo', htmlspecialchars($correspondencia[0]['persona_nombre_plantilla']));
				$templateProcessor->setValue('tablaAcciones', $this->generarTablaXmlParaWord($accionesSeleccionadas , $listAccion));
               
  
                //$templateProcessor->setValue('uo', htmlspecialchars($correspondencia[0]['desc_uo']));


                $nombre_archivo= str_replace('/','_',$correspondencia[0]['numero']);
				$nombre_archivo= str_replace(' ','_',$nombre_archivo);
				
			   
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
		
		if ($this->objParam->getParametro('estado_reporte')=='borrador'){
			$titulo='HOJA DE RECEPCIÓN DE CORRESPONDENCIA EN BORRADOR';
		}else{
			$titulo='HOJA DE RECEPCIÓN DE CORRESPONDENCIA';
		
		}
		
        $this->res = $this->objFunc->hojaRuta();


        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
		
		
        $hoja_ruta = $this->res->getDatos();

        $id_origen = $hoja_ruta[0]['desc_id_origen'];
        $id_funcionario_origen = $hoja_ruta[0]['desc_id_funcionario_origen'];
		$estado = $hoja_ruta[0]['estado'];
        //obtenemos la correspondencia original el origen
        
              
        $this->objParam->addParametro('id_funcionario_usuario', $id_funcionario_origen);
		$this->objFunc = $this->create('MODCorrespondencia');
			
		$this->res = $this->objFunc->listarHojaPrincipal();
		
        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
        $correspondencia = $this->res->getDatos();
		$fecha_label='';
		if ($correspondencia[0]["tipo"]=='externa'){
			
			$remitente=$correspondencia[0]["desc_insti"].' - '.$correspondencia[0]["nombre_persona"];
			$fecha_label='Fecha de Recep: ';
			/* if (is_null($correspondencia[0]['fecha_creacion_documento'])){
			
			  $fecha_documento = ' ';
		      }else{
		      	*/
			 $fecha_documento = strftime("%d/%m/%Y", strtotime($correspondencia[0]['fecha_creacion_documento']));
			 //}
			
		}else{
			$fecha_label='Fecha de Documento: ';
			
			$remitente=$correspondencia[0]["desc_funcionario"];
			/* if (is_null($correspondencia[0]['fecha_creacion_documento'])){
			
			  $fecha_documento = ' ';
		      }else{
		      	*/
			 $fecha_documento = strftime("%d/%m/%Y", strtotime($correspondencia[0]['fecha_documento']));
			 //}
		}

        
           
        
		// vista o formato del pdf -> del boton hoja de recepcion

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
							.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
							.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:5px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg .tg-e3zv{font-weight:bold}
							.tg .tg-e3zv1{font-weight:bold;
							             border-style:ridge;
										 border-width:1px;
										 border-color: LightGray ;
										 }
							.tg .tg-yw4l{vertical-align:top; border: 0}
							.tg .tg-9hbo{font-weight:bold;vertical-align:top}
							.tg .tg-9hbd{background-color: orange; 
							             margin: 5px;
										 padding: 5px;
										 font-family:Arial, sans-serif;
										 font-size:12px;
										 border-style:ridge;
										 border-width:1px;
										 border-color: LightGray;
										 overflow:hidden;
										 word-break:normal;
										 font-weight:bold;
										 vertical-align:top}     
							.tg .tg-9hbd1{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;
							 border-style:ridge;
										 border-width:1px;
										 border-color: LightGray ;
										overflow:hidden;word-break:normal;font-weight:normal;vertical-align:top}
						
							</style>
							<CENTER><div><B>'.$titulo.'</B></div></CENTER>
							<hr />
							<table class="tg"  border="0" width="100%">
							  <tr>
								<th class="tg-e3zv"> <FONT SIZE=3> Nro: ' . $correspondencia[0]["numero"] . ' </FONT > </th>
								
								<th class="tg-e3zv">'.$fecha_label. $fecha_documento . '</th>
								
							
								<th class="tg-9hbo">Tipo: ' . $correspondencia[0]["tipo"] . '</th>
								
							  </tr>
							  </table>
							  <table class="tg"  border="0" width="100%">
							  <tr>
								<td class="tg-e3zv" width="25%">Remitente: </td>
								<td class="tg-e3zv" width="25%">Referencia:</td>
								<td class="tg-e3zv" width="25%">Adjuntos:</td>
								<td class="tg-9hbo" width="25%">Doc. Físico Entregado A:</td>
								
							  </tr>
							 
							  <tr>
								<td class="tg-yw4l">' . $remitente . '<br /><b style="font-size:6pt">' . $correspondencia[0]["desc_cargo"] . '</b></td>
								<td class="tg-yw4l">' . $correspondencia[0]["referencia"] . '</td>
								<td class="tg-yw4l">' . $correspondencia[0]["otros_adjuntos"] . '</td>
								<td class="tg-yw4l">' . $correspondencia[0]["mensaje"] . '</td>
							  </tr>
							  
							 </table> 
						<table class="tg"  border="0">
							  <th class="tg-e3zv1" colspan="6" >DETALLE DE DERIVACIONES</th>
							  <tr bgcolor="#CCCCCC">
							    <td class="tg-9hbd"> Usuario Reg. </td>
								<td class="tg-9hbd"> Derivado A:</td>
								<td class="tg-9hbd"> Fecha Deriv. </td>
								<td class="tg-9hbd"> Mensaje:  </td>
								<td class="tg-9hbd"> Accion </td>
								<td class="tg-9hbd"> Fecha Recep. </td>
						      </tr>
							  ';
							  
							  
		// forecha del detalle de derivacion
		$vacio=' ';
							  
        foreach ($hoja_ruta as $ruta) {
        		
        	
        			 // Validacion del null para q salga blando o vavio en el pdf 
        		     if (is_null($ruta['fecha_deriv'])){
			
			          $fecha_deriv  = '       ';
		              }else{
		      	
			          $fecha_deriv = '  '.strftime("%d/%m/%Y %H:%m", strtotime($ruta['fecha_deriv']));	
					 // $fecha_deriv = $ruta['fecha_deriv'];	
			         }
        	         
        	         if (is_null($ruta['fecha_recepcion'])){
			
			          $fecha_recepcion2  = '       ';
		              }else{
		      	
			         $fecha_recepcion2 = $vacio.'  '.strftime("%d/%m/%Y %H:%m", strtotime($ruta['fecha_recepcion']));
					    // $fecha_recepcion2 = $ruta['fecha_recepcion'];	
			         }
        	        
				 		
				            	             	
				   
			        
			
							
            $html .= '
							  <tr>
								<td class="tg-9hbd1">(' . $ruta['cuenta'] . ') ' . $ruta['desc_person_fk'] . '<br /><b style="font-size:8pt;">' . $ruta["desc_cargo_fk"] . '</b></td>
								<td class="tg-9hbd1">' . $ruta['desc_person'] . '<br /><b style="font-size:8pt;">' . $ruta["desc_cargo"] . '</b></td>
								<td class="tg-9hbd1">' . $fecha_deriv . '</td>
								<td class="tg-9hbd1">' . $ruta['mensaje'] . '</td>
								<td class="tg-9hbd1">' . $ruta['acciones'] . '</td>
								<td class="tg-9hbd1">' . $fecha_recepcion2 . '</td>
							  </tr> 
							  
							 
						      
						  
						      ';
							  
							
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
	
	
	/*********************hoja borrador ***************/

function hojaRutaBorrador()
    {
      $this->objFunc = $this->create('MODCorrespondencia');
		
		if ($this->objParam->getParametro('estado_reporte')=='borrador'){
			$titulo='HOJA DE RECEPCIÓN DE CORRESPONDENCIA EN BORRADOR';
		}else{
			$titulo='HOJA DE RECEPCIÓN DE CORRESPONDENCIA';
		
		}
		
        $this->res = $this->objFunc->hojaRuta();


        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
		
		
        $hoja_ruta = $this->res->getDatos();

        $id_origen = $hoja_ruta[0]['desc_id_origen'];
        $id_funcionario_origen = $hoja_ruta[0]['desc_id_funcionario_origen'];
		$estado = $hoja_ruta[0]['estado'];
        //obtenemos la correspondencia original el origen
        
              
        $this->objParam->addParametro('id_funcionario_usuario', $id_funcionario_origen);
		$this->objFunc = $this->create('MODCorrespondencia');
			
		$this->res = $this->objFunc->listarHojaPrincipal();
		
        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
        $correspondencia = $this->res->getDatos();
		
		if ($correspondencia[0]["tipo"]=='externa'){
			
			$nombre_completo=$correspondencia[0]["nombre_persona"];
			
			if (is_null($nombre_completo)){
				
				$remitente=$correspondencia[0]["desc_insti"];
				
			}else{
				
				$remitente=$correspondencia[0]["desc_insti"].' ' . '<br /><b style="font-size:8pt"> '.$correspondencia[0]["nombre_persona"]. ' </b>';
			}
			
			
			
		}else{
			$remitente=$correspondencia[0]["desc_funcionario"];
		}
        //print_r($correspondencia); 
        
             // validacion de fecha null para q muestre vacio
        
            if (is_null($correspondencia[0]['fecha_documento'])){
			
			  $fecha_documento = ' ';
		      }else{
		      	
			 $fecha_documento = strftime("%d/%m/%Y", strtotime($correspondencia[0]['fecha_documento']));
			 }
        
        
		// vista o formato del pdf -> del boton hoja de recepcion
        $html = '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<title></title>
			</head>
			<body style="position:absolute">
<!-- agrega marca de agua pero falta centrear en la hoja>
			<div style="position:absolute">
  <p style="font-size:120px">Background</p>
	</div>
-->
	<style type="text/css">
	
	.watermarked {
  position: relative;
}


    .watermark
    {
        position: absolute;
        top: 60px;
        left: 80px;
        opacity: 0.2;
    }
	
							.tg  {border-collapse:collapse;border-spacing:0; border: 0}
							
							.tg td{font-family:Arial, sans-serif;font-size:12px;padding:5px;margin: 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
							.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:5px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
							.tg .tg-e3zv{font-weight:bold}
							.tg .tg-yw4l{vertical-align:top; border: 0;padding:5px;margin: 5px}
							.tg .tg-9hbo{font-weight:bold;vertical-align:top}
							.tg .tg-9hbd{background-color: orange;
							             margin: 5px;
										 padding:5px;
										 font-family:Arial, sans-serif;
										 font-size:12px;
										 border-style:solid;
										 border-width:1px;
										 overflow:hidden;
										 word-break:normal;
										 font-weight:bold;
										 vertical-align:top}     
							.tg .tg-9hbd1{font-family:Arial, sans-serif;font-size:12px;padding:5px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;font-weight:normal;vertical-align:top}
							
							</style>
							
<div style="position: relative; left: 0; top: 0;">        
        <img src="../../../sis_correspondencia/imagenes/fondo_borrador.png" class="watermark"/>
    </div>
							<CENTER>
							  <spam align = "right">  
							      <div align="right" style="color:#F00"><!--<img src="../../../sis_correspondencia/imagenes/fondo_borrador.png">-->BORRADOR </div>
							    </spam > 
                              <div > 
							    <p>  <spam > <B>HOJA DE RECEPCIÓN DE CORRESPONDENCIA </B> </spam > 
						        </p>
							  </div>
							</CENTER>
							<hr />
							<table class="tg"  border="0" width="100%">
							  <tr>
								<th class="tg-e3zv" colspan="4"> <FONT SIZE=3> Nro: ' . $correspondencia[0]["numero"] . ' </FONT > </th>
								
								<th class="tg-e3zv" colspan="3">Fecha Recep: ' . $fecha_documento . '</th>
								
							
								<th class="tg-9hbo" colspan="14">Tipo: ' . $correspondencia[0]["tipo"] . '</th>
								
							  </tr>
							  </table>
							  <table class="tg"  border="0" width="100%">
							  <tr>
								<td class="tg-e3zv" width="25%">Remitente </td>
								<td class="tg-e3zv" width="25%">Referencia</td>
								<td class="tg-e3zv" width="25%">Adjuntos</td>
								<td class="tg-9hbo" width="25%">Doc. Físico Entregado A</td>
								
							  </tr>
							 
							  <tr>
								<td class="tg-yw4l" width="25%">' . $remitente . '<br /><b style="font-size:6pt">' . $correspondencia[0]["desc_cargo"] . '</b></td>
								<td class="tg-yw4l" width="25%">' . $correspondencia[0]["referencia"] . '</td>
								<td class="tg-yw4l" width="25%">' . $correspondencia[0]["otros_adjuntos"] . '</td>
								<td class="tg-yw4l" width="25%">' . $correspondencia[0]["mensaje"] . '</td>
							  </tr>
							  </table>
							  <table class="tg" border="0" >
							  <th class="tg-e3zv" colspan="7" >DETALLE DE DERIVACIONES </th>
							   <tr class="tg-9hbd">
								<td class="tg-9hbd"> Usuario Reg.</td>
								<td class="tg-9hbd"> Derivado A: </td>
								<td class="tg-9hbd"> Fecha Deriv. </td>
							    <td class="tg-9hbd"> Mensaje</td>
								<td class="tg-9hbd"> Accion </td>
								<td class="tg-9hbd"> Estado </td>
								<td class="tg-9hbd"> Fecha Recep. </td>
							  </tr>
							  ';
							  
							  
		// forecha del detalle de derivacion
		$vacio=' ';
							  
        foreach ($hoja_ruta as $ruta) {
        		
        	
        			 // Validacion del null para q salga blando o vavio en el pdf 
        		     if (is_null($ruta['fecha_deriv'])){
			
			          $fecha_deriv  = '       ';
		              }else{
		      	
			          $fecha_deriv = '  '.strftime("%d/%m/%Y %H:%m", strtotime($ruta['fecha_deriv']));	
					 // $fecha_deriv = $ruta['fecha_deriv'];	
			         }
        	         
        	         if (is_null($ruta['fecha_recepcion'])){
			
			          $fecha_recepcion2  = '       ';
		              }else{
		      	
			         $fecha_recepcion2 = $vacio.'  '.strftime("%d/%m/%Y %H:%m", strtotime($ruta['fecha_recepcion']));
					    // $fecha_recepcion2 = $ruta['fecha_recepcion'];	
			         }
        	        
				 		
				            	             	
				   
			        
			
							
            $html .= '
							  <tr>
								<td class="tg-9hbd1"   >(' . $ruta['cuenta'] . ') ' . $ruta['desc_person_fk'] . '<br /><b style="font-size:8pt;">' . $ruta["desc_cargo_fk"] . '</b></td>
								<td class="tg-9hbd1"  >' . $ruta['desc_person'] . '<br /><b style="font-size:8pt;">' . $ruta["desc_cargo"] . '</b></td>
								<td class="tg-9hbd1"  >' . $fecha_deriv . '</td>
								<td class="tg-9hbd1"  >' . $ruta['mensaje'] . '</td>
								<td class="tg-9hbd1"  >' . $ruta['acciones'] . '</td>
								<td class="tg-9hbd1"  >' . $ruta['estado'] . '</td>
								<td class="tg-9hbd1"  >' . $fecha_recepcion2 . '</td>
								</tr> 
							  
							  
							 ';
							  
							  

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
	
	/*****/
	
	

    function archivarCorrespondencia()
    {

        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->archivarCorrespondencia();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function habilitarCorrespondencia()
    {

        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->habilitarCorrespondencia();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarCorrespondenciaRecibidaArchivada()
    {
        $this->objParam->defecto('ordenacion', 'id_correspondencia');
        $this->objParam->defecto('dir_ordenacion', 'asc');
        $this->objParam->addParametro('id_funcionario_usuario', $_SESSION["ss_id_funcionario"]);
        $this->objParam->addFiltro("cor.sw_archivado = ''si'' ");
        
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaRecibida');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaRecibida();
           
        }
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
		if ($this->objParam->insertar('id_correspondencia')) {
			
			if ($this->objParam->getParametro('tipo')=='entrante' or $this->objParam->getParametro('tipo')=='externa'){
				$this->res = $this->objFunc->insertarCorrespondenciaExterna();
			}else{
				
				$this->res = $this->objFunc->insertarCorrespondencia();
			}
            
        } else {
            $this->res = $this->objFunc->modificarCorrespondenciaExterna();
        }
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
		$this->objParam->addFiltro(" cor.tipo = ''".$this->objParam->getParametro('tipo')."''");
		$this->objParam->addFiltro(" cor.estado = ''".$this->objParam->getParametro('estado')."''");
		if ($this->objParam->getParametro('vista')=='CorrespondenciaAdministracion' && $this->objParam->getParametro('estado')=='enviado')
		    {
			}
			else{
			$this->objParam->addFiltro(" cor.id_correspondencia_fk is null ");
			
			$this->objParam->addFiltro(" (cor.estado_corre is null or cor.estado_corre not in (''borrador_corre''))");
	     	}
		
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODCorrespondencia', 'listarCorrespondenciaExterna');
        } else {
            $this->objFunc = $this->create('MODCorrespondencia');
            $this->res = $this->objFunc->listarCorrespondenciaExterna();
        }
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
		$width = 200;  
		$height = 150;
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
function anularCorrespondencia()
    {

        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->anularCorrespondencia();
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    
    public function listaAcciones() 
    {

        $this->objParam->parametros_consulta['ordenacion'] = 'id_accion';
        $this->objParam->parametros_consulta['filtro'] = " 0 = 0 ";
        $this->objParam->parametros_consulta['cantidad'] = '1000';
        $this->objParam->parametros_consulta['puntero'] = '0';
        
        $this->objParam->defecto('ordenacion','id_accion');
        $this->objParam->defecto('dir_ordenacion','asc');
        // $this->objParam->addFiltro(" 0 = 0 ");
        $this->objFunc=$this->create('MODAccion');  
        $this->res=$this->objFunc->listarAccion();
        return $this->res->getDatos();
    }

    public function listaAccionesDerivadas($id_correspondencia) 
    {
        
        $this->objParam->parametros_consulta['ordenacion'] = 'id_correspondencia';
        $this->objParam->parametros_consulta['filtro'] = " 0 = 0 ";
        $this->objParam->parametros_consulta['cantidad'] = '10000';
        $this->objParam->parametros_consulta['puntero'] = '0';
        
        $this->objParam->addFiltro("cor.id_correspondencia_fk = " . $id_correspondencia);
        
        $this->objParam->defecto('ordenacion','id_correspondencia');
        $this->objParam->defecto('dir_ordenacion','asc');
        
        $this->objFunc = $this->create('MODCorrespondencia');
        $this->res = $this->objFunc->listarCorrespondenciaDetalle();
        $datos = $this->res->getDatos();
        $accionesTexto = ''; 

        for ($i=0; $i < count($datos) ; $i++) { 
            if($accionesTexto==''){
                $accionesTexto .= $datos[$i]['id_acciones'];
            }else{
                $accionesTexto .= ','.$datos[$i]['id_acciones'];
            }
        }

        return $accionesTexto;
    }
    
    public function generarTablaXmlParaWord($accionesSeleccionadas="", $listAcciones=array())
    {
        $accionesSeleccionadas = explode(',', $accionesSeleccionadas); // convertir en arreglo todas las acciones seleccionadas

        $filaCompleta = $this->generarNuevoArreglo($accionesSeleccionadas, $listAcciones); // Crea un arreglo de arreglos para la estructura que se desea

        $todasAcciones = "";
        $cantidad = sizeof($listAcciones);
        $resultado = '';
        if($cantidad>0)
        {

        
        
            $resultado = '<w:tbl>
                    <w:tblPr>
                        <w:tblStyle w:val="Tablaconcuadrcula"/>
                        <w:tblpPr w:leftFromText="141" w:rightFromText="141" w:vertAnchor="text" w:horzAnchor="margin" w:tblpXSpec="center" w:tblpY="1"/>
                        <w:tblW w:w="9067" w:type="dxa"/>
                        <w:tblBorders>
                            <w:top w:val="dotted" w:sz="4" w:space="0" w:color="auto"/>
                            <w:left w:val="dotted" w:sz="4" w:space="0" w:color="auto"/>
                            <w:bottom w:val="dotted" w:sz="4" w:space="0" w:color="auto"/>
                            <w:right w:val="dotted" w:sz="4" w:space="0" w:color="auto"/>
                            <w:insideH w:val="dotted" w:sz="4" w:space="0" w:color="auto"/>
                            <w:insideV w:val="dotted" w:sz="4" w:space="0" w:color="auto"/>
                        </w:tblBorders>
                        <w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/>
                    </w:tblPr>
                    <w:tblGrid>';
                for ($i=0; $i <$cantidad ; $i++) { 
                    $resultado .= '<w:gridCol w:w="1277"/>
                            <w:gridCol w:w="298"/>';
                }
                        
            $resultado .='</w:tblGrid>';

            foreach ($filaCompleta as $fila) 
            {
                $resultado .='<w:tr w:rsidR="00EE77A0" w:rsidRPr="00E21E5F" w:rsidTr="00350545">
                            <w:trPr>
                                <w:trHeight w:val="336"/>
                            </w:trPr>';
                foreach ($fila as $data) {
                    
                $resultado .='<w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="1277" w:type="dxa"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00EE77A0" w:rsidRPr="00E34562" w:rsidRDefault="00EE77A0" w:rsidP="00350545">
                                    <w:pPr>
                                        <w:autoSpaceDE w:val="0"/>
                                        <w:autoSpaceDN w:val="0"/>
                                        <w:adjustRightInd w:val="0"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/>
                                            <w:sz w:val="14"/>
                                            <w:szCs w:val="14"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E34562">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/>
                                            <w:sz w:val="14"/>
                                            <w:szCs w:val="14"/>
                                        </w:rPr>
                                        <w:t>'.strtoupper($data['nombre']).'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>
                            <w:tc>
                                <w:tcPr>
                                    <w:tcW w:w="298" w:type="dxa"/>
                                    <w:vAlign w:val="center"/>
                                </w:tcPr>
                                <w:p w:rsidR="00EE77A0" w:rsidRPr="00E34562" w:rsidRDefault="00EE77A0" w:rsidP="00350545">
                                    <w:pPr>
                                        <w:spacing w:line="480" w:lineRule="auto"/>
                                        <w:rPr>
                                            <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/>
                                            <w:sz w:val="14"/>
                                            <w:szCs w:val="14"/>
                                        </w:rPr>
                                    </w:pPr>
                                    <w:r w:rsidRPr="00E34562">
                                        <w:rPr>
                                            <w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/>
                                            <w:sz w:val="14"/>
                                            <w:szCs w:val="14"/>
                                        </w:rPr>
                                        <w:t>'.strtoupper($data['selected']).'</w:t>
                                    </w:r>
                                </w:p>
                            </w:tc>';
                }
                
                $resultado .='</w:tr>';
                
            }


            $resultado .='</w:tbl>';


        }

        return $resultado;
    }

    public function generarNuevoArreglo($accionesSeleccionadas="", $listAcciones=array())
    {
        $cantidadPorFila = 6;
        $filaCompleta = array();
        $fila = array();
        $cant = sizeof($listAcciones);
        $contador = 0;
            
        foreach ($listAcciones as $accion) 
        {
            $contador++;
            array_push($fila, array("nombre"=>$accion['nombre'], 'selected'=>$this->seleccionarAccion($accion['id_accion'], $accionesSeleccionadas))) ;
            if( $contador >= $cantidadPorFila )
            {       
                array_push($filaCompleta,$fila);
                $fila = array();
                $contador=0;
            }
        }

        $resto = (int)($cantidadPorFila-sizeof($fila)); // resto de datos vacio para completar la fila de $cantidadPorFila y no este vacia
        
        if(sizeof($fila)>0)
        {
            for ($i=0; $i < $resto ; $i++) { 
                array_push($fila, array("nombre"=>'', 'selected'=>''));
            }
            array_push($filaCompleta, $fila);
        }

        return $filaCompleta;
    }

    public function seleccionarAccion($idAccion, $accionesSeleccionadas)
    {
        $res = '';
        foreach ($accionesSeleccionadas as $accionSeleccionada) {
            if($accionSeleccionada==$idAccion)
                $res = 'X';
        }
        return $res;
    }
} 
?>
