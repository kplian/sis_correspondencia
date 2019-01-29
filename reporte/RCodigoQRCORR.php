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
		//var_dump($detalle);
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
		$this->setY(-20);
		$ormargins = $this->getOriginalMargins();
		$this->SetTextColor(0, 0, 0);
		//set style for cell border
		$line_width = 0.85 / $this->getScaleFactor();
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		//$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
		//var_dump($ancho);
		//exit;	
		$this->Ln(1);
		$cur_y = $this->GetY();
		//$this->Cell($ancho, 0, 'Generado por XPHS', 'T', 0, 'L');
		$this->SetFont('helvetica','',13);
		
		$p = '____________________________';
		
		$this->Cell(90, 0, '', '', 0, 'R');
		
		$this->Cell(90, 0, $p, '', 0, 'R');
		
		$this->Ln();
		
		$this->Cell(70, 0, '', '', 0, 'R');
		$pagenumtxt = 'CORRESPONDENCIA';
		$this->Cell(70, 0, $pagenumtxt, '', 0, 'R');
		
		$this->Cell(47, 0, trim($this->cod['fec']), '', 0, 'R');
        $this->Ln();
		$this->Cell(70, 0, '', '', 0, 'R');
		$pagenumtxt2 = 'ENDE CORANI S.A.';
		$this->Cell(70, 0, $pagenumtxt2, '', 0, 'R');
		$this->Cell(31, 0, trim($this->cod['num']), '', 0, 'R');
		
		
		$this->Ln($line_width);
	}
	function generarReporte() {
		/*$this->setFontSubsetting(false);*/
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
		/*if($this->tipo == 'unico'){
			$this->imprimirCodigo($style);
		}
		else{
			//imprime varios codigos ....
			/*foreach ($this->detalle as $val) {
				$this->cod = array('id'  => $val['id_activo_fijo'],
								'ref' => $val['referencia'],
								'fec' => $val['fecha_reg'],
								'num' => $val['numero'],
								'nom' => $detalle['nombre']);
				//formatea el codigo con el conteido requrido
				$this->codigo_qr = json_encode($this->cod);
				$this->imprimirCodigo($style);	
			}
		}*/
	}

	function imprimirCodigo(){
	/*
		 $this->AddPage();
        //$this->SetFillColor(192,192,192, true);
        $this->SetFont('helvetica','B',8);
        $this->Cell(100,40,'RECIBIDO' ,0,0,'C',0);
        $this->SetFont('','',7);
        $this->Ln(3);
        $this->Cell(35,5,'NOMBRE CAJERO:' ,0,0,'R');
        $this->Cell(70,5,trim($this->cod['num']),1,0,'L');
        $this->Cell(30,5,'FECHA DE VENTA:' ,0,0,'R');
        $this->Cell(45,5,trim($this->cod['num']),1,1,'L');	*/
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