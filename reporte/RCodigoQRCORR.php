<?php
// Extend the TCPDF class to create custom MultiRow
/*
 * para imrpesora Zebra TPL 2844
 * Autor manu
 * Fecha: 16/03/2017
 * Descripcion para cambiar la clase que se ejecuta el momento de imprimir modificar la variable global corres_clase_reporte_codigo en PXP 

 Formato QR:

 	id_activo_fijo,
 	referencia,
	fecha_reg,
 	numero
 * 
 * */
class RCodigoQRCORR extends ReportePDF {
	var $datos_titulo;
	var $datos_detalle;
	var $ancho_hoja;
	var $gerencia;
	var $numeracion;
	var $ancho_sin_totales;	
	var $id_activo_fijo;
	var $codigo;
	var $codigo_ant;
	var $denominacion;
	var $nombre_depto;
	var $nombre_entidad;
	var $codigo_qr;
	var $cod;
	var $ref;
	var $tipo;
	
	var $referencia;
	
	function datosHeader ( $tipo, $detalle ) {
		$this->ancho_hoja = $this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT-2;
		$this->datos_detalle = $detalle;
		$this->datos_titulo = $totales;
		$this->datos_entidad = $dataEmpresa;
		$this->datos_gestion = $gestion;
		$this->subtotal = 0;
		$this->tipo = $tipo;
		
		if($tipo == 'unico'){
			//para imprimir un solo codigo
			$this->cod = array('id'  => $detalle['id_correspondencia'],
								'ref' => $detalle['referencia'],
								'fec' => $detalle['fecha_reg'],
								'num' => $detalle['numero']);
			//formatea el codigo con el conteido requrido
			$this->codigo_qr = json_encode($this->cod);	
		}
		else{
			// para imprimir varios codigos
			$this->detalle = $detalle;
		}
		$this->SetMargins(1, 1, 1, true);
	}
	
	function Header() {}
	function Footer() {}
	function generarReporte() {
		$this->setFontSubsetting(false);
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 4, // width of a single module in points
			'module_height' => 4 // height of a single module in points
		);
		
		if($this->tipo == 'unico'){
			$this->imprimirCodigo($style);
		}
		else{
			//imprime varios codigos ....
			foreach ($this->detalle as $val) {
				$this->cod = array('id'  => $val['id_activo_fijo'],
								'ref' => $val['referencia'],
								'fec' => $val['fecha_reg'],
								'num' => $val['numero']);
				//formatea el codigo con el conteido requrido
				$this->codigo_qr = json_encode($this->cod);
				$this->imprimirCodigo($style);	
			}
		}
	}

	function imprimirCodigo($style){
		$this->AddPage();
		$this->write2DBarcode($this->codigo_qr, 'QRCODE,L', 1, 1,80,0, $style,'T',true);
		$this->SetFont('','B',30);		
		$this->ln(5);
		$this->SetFont('','',22);	
		$this->Text(80, 10, 'RECIBIDO', false, false, true, 0,5,'',false,'',2);
		$this->Text(80, 25, trim($this->cod['num']), false, false, true, 0,5,'',false,'',2);				
		$this->Text(80, 35, substr($this->cod['fec'], 0, 19), false, false, true, 0,5,'',false,'',2);
		$this->Text(80, 45, trim($this->cod['ref']), false, false, true, 0,5,'',false,'',2);	
	}
}
// 
class RCodigoQRCORR_v1 extends  ReportePDF {
	var $datos_titulo;
	var $datos_detalle;
	var $ancho_hoja;
	var $gerencia;
	var $numeracion;
	var $ancho_sin_totales;	
	var $id_activo_fijo;
	var $codigo;
	var $codigo_ant;
	var $denominacion;
	var $nombre_depto;
	var $nombre_entidad;
	var $codigo_qr;
	var $cod;
	var $ref;
	var $tipo;	
	var $referencia;
	
	function datosHeader ( $tipo, $detalle ) {
		$this->ancho_hoja = $this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT-2;
		$this->datos_detalle = $detalle;
		$this->datos_titulo = $totales;
		$this->datos_entidad = $dataEmpresa;
		$this->datos_gestion = $gestion;
		$this->subtotal = 0;
		$this->tipo = $tipo;
		
		if($tipo == 'unico'){
			//para imprimir un solo codigo
			$this->cod = array('id'  => $detalle['id_correspondencia'],
								'ref' => $detalle['referencia'],
								'fec' => $detalle['fecha_reg'],
								'num' => $detalle['numero']);
			//formatea el codigo con el conteido requrido
			$this->codigo_qr = json_encode($this->cod);	
		}
		else{
			// para imprimir varios codigos
			$this->detalle = $detalle;
		}
		$this->SetMargins(1, 1, 1, true);
	}
	
	function Header() {}
	function Footer() {}
	function generarReporte() {
		$this->setFontSubsetting(false);
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 4, // width of a single module in points
			'module_height' => 4 // height of a single module in points
		);
		
		if($this->tipo == 'unico'){
			$this->imprimirCodigo($style);
		}
		else{
			//imprime varios codigos ....
			foreach ($this->detalle as $val) {
				$this->cod = array('id'  => $val['id_activo_fijo'],
								'ref' => $val['referencia'],
								'fec' => $val['fecha_reg'],
								'num' => $val['numero']);
				//formatea el codigo con el conteido requrido
				$this->codigo_qr = json_encode($this->cod);
				$this->imprimirCodigo($style);	
			}
		}
	}

	function imprimirCodigo($style){
		$this->AddPage();
		$this->write2DBarcode($this->codigo_qr, 'QRCODE,L', 1, 1,80,0, $style,'T',true);
		$this->SetFont('','B',30);		
		$this->ln(5);
		$this->SetFont('','',22);	
		$this->Text(80, 10, 'manuuuuuuu', false, false, true, 0,5,'',false,'',2);
		$this->Text(80, 25, trim($this->cod['num']), false, false, true, 0,5,'',false,'',2);				
		$this->Text(80, 35, substr($this->cod['fec'], 0, 19), false, false, true, 0,5,'',false,'',2);
		$this->Text(80, 45, trim($this->cod['ref']), false, false, true, 0,5,'',false,'',2);	
	}
}
?>