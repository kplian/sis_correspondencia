<?php
/**
*@package pXP
*@file gen-ACTCorrespondencia.php
*@author  (rac)
*@date 13-12-2011 16:13:21
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

require_once(dirname(__FILE__).'/../../lib/tcpdf/tcpdf_barcodes_2d.php');
require_once(dirname(__FILE__) . '/../reporte/RReportes.php');

require_once (dirname(__FILE__).'/../../lib/PHPWord-master/src/PhpWord/Autoloader.php');
\PhpOffice\PhpWord\Autoloader::register();





class ACTCorrespondencia extends ACTbase{    
			
	function listarCorrespondencia(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');



		$this->objParam->addParametro('id_funcionario_usuario',$_SESSION["ss_id_funcionario"]);
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','listarCorrespondencia');
		} else{
			$this->objFunc=$this->create('MODCorrespondencia');	
			$this->res=$this->objFunc->listarCorrespondencia();
		}
		
		
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarCorrespondenciaSimplificada(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','listarCorrespondenciaSimplificada');
		} else{
			$this->objFunc=$this->create('MODCorrespondencia');	
		   $this->res=$this->objFunc->listarCorrespondenciaSimplificada();
		}
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function listarCorrespondenciaDetalle(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam, $this);
			$this->res = $this->objReporte->generarReporteListado('MODCorrespondencia','listarCorrespondenciaDetalle');
		} else{
			$this->objFunc=$this->create('MODCorrespondencia');
		   $this->res=$this->objFunc->listarCorrespondenciaDetalle();
		}
		
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
				
	function insertarCorrespondencia(){ //echo "aaaa"; exit;


		$this->objFunc=$this->create('MODCorrespondencia');	
		if($this->objParam->insertar('id_correspondencia')){
			$this->res=$this->objFunc->insertarCorrespondencia();			
		} else{			
			$this->res=$this->objFunc->modificarCorrespondencia();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCorrespondencia(){
		$this->objFunc=$this->create('MODCorrespondencia');	
		$this->res=$this->objFunc->eliminarCorrespondencia();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	function insertarCorrespondenciaDetalle(){ //echo "aaaa"; exit;
		$this->objFunc=$this->create('MODCorrespondencia');	
		if($this->objParam->insertar('id_correspondencia')){
			$this->res=$this->objFunc->insertarCorrespondenciaDetalle();			
		} else{			
			$this->res=$this->objFunc->modificarCorrespondenciaDetalle();
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	function subirCorrespondencia(){
	
		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		$this->objFunSeguridad=$this->create('MODCorrespondencia');
		$this->res=$this->objFunSeguridad->subirCorrespondencia($this->objParam);
		//imprime respuesta en formato JSON
		$this->res->imprimirRespuesta($this->res->generarJson());

	}
	
	function derivarCorrespondencia()
	{
		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		$this->objFunSeguridad=$this->create('MODCorrespondencia');
		$this->res=$this->objFunSeguridad->derivarCorrespondencia($this->objParam);
		//imprime respuesta en formato JSON
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
	
	function corregirCorrespondencia()
	{
		//crea el objetoFunSeguridad que contiene todos los metodos del sistema de seguridad
		$this->objFunSeguridad=$this->create('MODCorrespondencia');
		$this->res=$this->objFunSeguridad->corregirCorrespondencia($this->objParam);
		//imprime respuesta en formato JSON
		$this->res->imprimirRespuesta($this->res->generarJson());
		
	}
	
		
	function listarCorrespondenciaRecibida(){
		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','asc');

		$this->objParam->addParametro('id_funcionario_usuario',$_SESSION["ss_id_funcionario"]);
					
		$this->objFunc=$this->create('MODCorrespondencia');	
		$this->res=$this->objFunc->listarCorrespondenciaRecibida();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function finalizarRecepcion(){


		$this->objFunc=$this->create('MODCorrespondencia');
		$this->res=$this->objFunc->finalizarRecepcion();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function verCorrespondencia(){
		$this->objFunc=$this->create('MODCorrespondencia');
		$this->res=$this->objFunc->verCorrespondencia();
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function PlantillaCorrespondencia(){


		$this->objParam->addParametro('id_funcionario_usuario',$_SESSION["ss_id_funcionario"]);

		$this->objParam->defecto('ordenacion','id_correspondencia');

		$this->objParam->defecto('dir_ordenacion','desc');
        $this->objParam->addFiltro("cor.id_correspondencia = " . $this->objParam->getParametro('id_correspondencia'));

		$this->objFunc=$this->create('MODCorrespondencia');
		$this->res=$this->objFunc->listarCorrespondencia();



		if($this->res->getTipo()=='ERROR'){
			$this->res->imprimirRespuesta($this->res->generarJson());
			exit;
		}
		$correspondencia = $this->res->getDatos();

		//desc_funcionario -> es el funcionario que lo envia
		//desc_uo ->
		//numero numero de la correspondencia

		/*generamos una imagen qr para ingresar a la plantilla*/
		$cadena_qr = '|'.$correspondencia[0]['numero'].'|'.$correspondencia[0]['desc_uo'].'|'.$correspondencia[0]['desc_funcionario'].'';
		$barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');

		//todo cambiar ese nombre por algo randon
		$nombre_archivo = md5($_SESSION["ss_id_usuario_ai"] . $_SESSION["_SEMILLA"]);

		$png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));


		$im = imagecreatefromstring($png);
		if ($im !== false) {
			header('Content-Type: image/png');
			imagepng($im, $_SERVER['DOCUMENT_ROOT'] . "kerp/reportes_generados/" . $nombre_archivo . ".png");
			imagedestroy($im);

			$img_qr = $_SERVER['DOCUMENT_ROOT'] . "kerp/reportes_generados/" . $nombre_archivo . ".png";

			/*agrego a la plantilla word los datos */
			$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($correspondencia[0]['desc_ruta_plantilla_documento']);
			$templateProcessor->setImgFooter('qr',array('src' => $img_qr,'swh'=>'250'));
			$templateProcessor->setValue('remitente', htmlspecialchars($correspondencia[0]['desc_funcionario']));
			//$templateProcessor->setValue('numero', htmlspecialchars($correspondencia[0]['numero']));
			//$templateProcessor->setValue('uo', htmlspecialchars($correspondencia[0]['desc_uo']));
			$templateProcessor->saveAs(dirname(__FILE__).'/../../reportes_generados/'.$nombre_archivo.'.docx');



			$temp['docx'] = $nombre_archivo.'.docx';



			$this->res->setDatos($temp);

			$this->res->imprimirRespuesta($this->res->generarJson());

		} else {
			echo 'An error occurred.';
		}








	}
}

?>