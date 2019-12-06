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
#HISTORIAL DE MODIFICACIONES:
#ISSUE          FECHA      		  AUTOR       		 DESCRIPCION
#7     		 5/09/2019  		Manuel Guerra      corrrecion en la visualizacion de datos
 */
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
	
	var $referencia;
	
	function datosHeader ($detalle ) {
		
		$this->ancho_hoja = $this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT-5;
		$this->datos_detalle = $detalle;
			
		$this->cod = array('id'  => $detalle['id_correspondencia'],
							'ref' => $detalle['referencia'],
							'fec' => $detalle['fecha_reg'],
							'num' => $detalle['numero'],
							'nom' => $detalle['nombre']);		
		$this->codigo_qr = json_encode($this->cod);			
		$this->SetMargins(-60, 1, 1, true);#7
	}
	
	function Header() {}
	function Footer() {
		//$this->setX(-10);
		$this->setY(195);
		$ormargins = $this->getOriginalMargins();
		$this->SetFont('times', 'B', 14);
		$this->SetTextColor(0, 0, 0);
		
		$this->StartTransform();
		$this->Rotate(90,10,10);
		$c1 = substr(($this->cod['num']),-4);
		$c2 = substr($c1,2);
		$resultado = substr(($this->cod['num']),0,-4);
		$res=strlen(($this->cod['num']));
		
		$this->Cell(20, 0, '', '', 0, 'C');
		$this->Cell(7, 0,'ETR-'.$resultado.$c2, '', 0, 'C');
			
		$this->Ln();
		setlocale(LC_TIME, 'es_ES');
		$dia = date("d",strtotime($this->cod['fec']));
		$month = date("m",strtotime($this->cod['fec']));
		$fecha = DateTime::createFromFormat('!m', $month);
		$mes = strftime("%B", $fecha->getTimestamp());		
		$anio = date("Y",strtotime($this->cod['fec']));
		$hora = date("H:i:s",strtotime($this->cod['fec']));	
		
		$this->Cell(1, 0, '', '', 0, 'L');	
		$this->Cell(180, 0, $dia.'-'.strtoupper (substr($mes,0,3)).'-'.$anio.'  '.$hora, '', 0, 'L');
        $this->StopTransform();  
	}
	function generarReporte() {
	
	}

	function imprimirCodigo(){
	
	}
}

?>
