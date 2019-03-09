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
								'num' => $detalle['numero'],
								'nom' => $detalle['nombre']);
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
	function Footer() {
		//$this->setY(-20);
		$this->setY(-10);
		$ormargins = $this->getOriginalMargins();
		$this->SetTextColor(0, 0, 0);
	

		//$line_width = 0.99 / $this->getScaleFactor();
		//$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$fontname = TCPDF_FONTS::addTTFfont('/var/www/html/kerp/pxp/lib/tcpdf/fonts/pixelmix.ttf', 'TrueTypeUnicode', '', 96); 
		$this->SetFont($fontname, '', 11, '', false);
		$this->Cell(75, 0, '', '', 0, 'R');
		$pagenumtxt2 = 'ENDE CORANI S.A.';
		$this->Cell(71, 0, $pagenumtxt2, '', 0, 'R');
		
		$c1 = substr(($this->cod['num']),-4);
		$c2 = substr($c1,2);
		$resultado = substr(($this->cod['num']),0,-4);
		$res=strlen(($this->cod['num']));
		
		$this->Cell(39, 0,$resultado.$c2, '', 0, 'R');
			
		$this->Ln();
		$this->Cell(75, 0, $pagenumtxt, '', 0, 'R');
		
		
		setlocale(LC_TIME, 'es_ES');
		$dia = date("d",strtotime($this->cod['fec']));
		$month = date("m",strtotime($this->cod['fec']));
		$fecha = DateTime::createFromFormat('!m', $month);
		$mes = strftime("%B", $fecha->getTimestamp());
		
		$anio = date("Y",strtotime($this->cod['fec']));
		$hora = date("H:i:s",strtotime($this->cod['fec']));
		
		$this->Cell(98, 0, $dia.'-'.strtoupper (substr($mes,0,3)).'-'.$anio.'  '.$hora, '', 0, 'R');
        
		//$this->Ln($line_width);
	}
	function generarReporte() {
		$this->setFontSubsetting(false);
		$style = array(
			'border' => 3,
			'vpadding' => '500',
			'hpadding' => '500',	
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 4, // width of a single module in points
			'module_height' => 4 // height of a single module in points
		);
		$this->imprimirCodigo();
		if($this->tipo == 'unico'){
			$this->imprimirCodigo($style);
		}
		else{
			//imprime varios codigos ....
			foreach ($this->detalle as $val) {
				$this->cod = array('id'  => $val['id_activo_fijo'],
								'ref' => $val['referencia'],
								'fec' => $val['fecha_reg'],
								'num' => $val['numero'],
								'nom' => $detalle['nombre']);
				//formatea el codigo con el conteido requrido
				$this->codigo_qr = json_encode($this->cod);
				$this->imprimirCodigo($style);	
			}
		}
	}

	function imprimirCodigo(){
	
		/*	$this->AddPage();
		//$this->write2DBarcode($this->codigo_qr, 'QRCODE,L', 1, 1,80,0, $style,'T',true);
		$this->SetFont('','B',30);		
		$this->ln(5);
		$this->SetFont('','',22);	
		$this->Text(0, 0, 'RECIBIDO', false, false, true, 0,5,'',false,'',2);
		$this->Text(0, 0, trim($this->cod['nom']), false, false, true, 0,5,'',false,'',2);
		$this->Text(0, 0, trim($this->cod['num']), false, false, true, 0,5,'',false,'',2);				
		$this->Text(0, 0, substr($this->cod['fec'], 0, 19), false, false, true, 0,5,'',false,'',2);*/
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
								'num' => $detalle['numero'],
								'nom' => $detalle['nombre']);
			//formatea el codigo con el conteido requrido
			$this->codigo_qr = json_encode($this->cod);	
		}
		else{
			// para imprimir varios codigos
			$this->detalle = $detalle;
		}
		$this->SetMargins(10, 10, 10, true);
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
								'num' => $val['numero'],
								'nom' => $val['nombre']);
				//formatea el codigo con el conteido requrido
				$this->codigo_qr = json_encode($this->cod);
				$this->imprimirCodigo($style);	
			}
		}
	}

	function imprimirCodigo($style){
		$this->AddPage();
		//$this->write2DBarcode($this->codigo_qr, 'QRCODE,L', 100, 1, 15,0, $style,'T',true);
		//$this->SetFont('','B',15);		
		$this->ln(5);
		$this->SetFont('','',20);	
		$this->Text(0, 3, '', false, false, true, 0,5,'',false,'',1);
		$this->Text(0, 6, trim($this->cod['nom']), false, false, true, 0,5,'',false,'',1);
		$this->Text(0, 9, trim($this->cod['num']), false, false, true, 0,5,'',false,'',1);				
		$this->Text(0, 12, substr($this->cod['fec'], 0, 19), false, false, true, 0,5,'',false,'',1);
	}
}
?>
