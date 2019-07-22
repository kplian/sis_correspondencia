<?php
// Extend the TCPDF class to create custom MultiRow
class RGeneralCorres extends  ReportePDF {
	var $datos_detalle;
	var $datos_entidad;
	
	
	
	function datosHeader($detalle, $entidad) {
        $this->SetHeaderMargin(8);
        $this->SetAutoPageBreak(TRUE, 10);
		$this->ancho_hoja = $this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT-10;
		$this->datos_detalle = $detalle;
		$this->datos_entidad = $entidad;
		$this->SetMargins(7, 46, 5);
	}
	
	function Header() {
		
		$white = array('LTRB' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 255, 255)));
        $black = array('T' =>array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
		
		$this->Ln(3);
		//formato de fecha
		
		//cabecera del reporte
		$this->Image(dirname(__FILE__).'/../../lib/imagenes/logos/logo.jpg', 10,5,40,20);
		$this->ln(5);
		
		
	    $this->SetFont('','B',12);		
		$this->Cell(0,5,"REPORTE DE CORRESPONDENCIAS",0,1,'C');		
		$this->Ln();
		
		$this->SetFont('','',10);
		
		$height = 5;
        $width1 = 5;
		$esp_width = 10;
        $width_c1= 55;
		$width_c2= 112;
        $width3 = 40;
        $width4 = 75;
		
		
			
		$fecha_ini =$this->objParam->getParametro('fecha_ini');
	    $fecha_fin = $this->objParam->getParametro('fecha_fin');
	
	
		$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell($width_c1, $height, 'DEL:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell($width_c2, $height, $fecha_ini, 0, 0, 'L', true, '', 0, false, 'T', 'C');
        
        $this->Cell($esp_width, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell(20, $height,'HASTA:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell(50, $height, $fecha_fin, 0, 0, 'L', true, '', 0, false, 'T', 'C');
		
		
		$this->Ln();
		
		$this->Cell($width1, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell($width_c1, $height, 'Nombre o Razón Social:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell($width_c2, $height, $this->datos_entidad['nombre'].' ('.$this->datos_entidad['direccion_matriz'].')', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        
        $this->Cell($esp_width, $height, '', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell(20, $height,'NIT:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('', '');
        $this->SetFillColor(192,192,192, true);
        $this->Cell(50, $height, $this->datos_entidad['nit'], 0, 0, 'L', false, '', 0, false, 'T', 'C');
        
		
		$this->Ln(8);
		
		$this->SetFont('','B',6);
		$this->generarCabecera();
		
		
	}
	
	function Footer() {
		
		$this->setY(-15);
		$ormargins = $this->getOriginalMargins();
		$this->SetTextColor(0, 0, 0);
		//set style for cell border
		$line_width = 0.85 / $this->getScaleFactor();
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
		$this->Ln(2);
		$cur_y = $this->GetY();
		
		$this->Cell($ancho, 0, '', '', 0, 'L');
		$pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
		$this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
		$this->Cell($ancho, 0, '', '', 0, 'R');
		$this->Ln();
		$fecha_rep = date("d-m-Y H:i:s");
		$this->Cell($ancho, 0, '', '', 0, 'L');
		$this->Ln($line_width);
		
				
	
	}
	
	 function generarCabecera(){
    	
		
		
		//arma cabecera de la tabla  17  - 13   20   -    ;  (15,  14  21,   2,,4,6)
		$conf_par_tablewidths=array(5,25,23,18,25,15,25,15,18,20,20,17,15,15);
        $conf_par_tablealigns=array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
        //$conf_par_tablenumbers=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$conf_tableborders=array();
        $conf_tabletextcolor=array();
		
		$this->tablewidths=$conf_par_tablewidths;
        $this->tablealigns=$conf_par_tablealigns;
        //$this->tablenumbers=$conf_par_tablenumbers;
        $this->tableborders=$conf_tableborders;
        $this->tabletextcolor=$conf_tabletextcolor;
		
		

		$RowArray = array(
				's0' => 'Nº',
				's1' => 'NÚMERO',   
				's2' => "TIPO DE \nCORRESPONDENCIA",
				's3' => 'DOCUMENTO',
				
				's4' => 'REMITENTE',
				's5' => "FECHA DOCUMENTO",        
				//uo
				
				's6' => "REFERENCIA",   
				//'s7' => "OBSERVACIÓN",    
				's7' => "NIVEL\nPRIORIDAD",
				's8' => "CLASIFICADOR",
				
				's9' => "FUNCIONARIO\nDESTINATARIO",     
				's10' => 'MENSAJE',           
				's11' => 'ACCIONES',
				//'s13' => "INSTITUCION \nDESTINATARIO",
				
				's12' => "USUARIO REGISTRO",       
				's13' => "ARCHIVADO",      
				
				);
		

		$this->MultiRow($RowArray, false, 1);
    }
	
	function generarCuerpo($detalle){
		
		$count = 1;
		$sw = 0;
		$ult_region = '';
		$fill = 0;
		
		$this->total = count($detalle);
		/*$this->s1 = 0;
		$this->s2 = 0;
		$this->s3 = 0;
		$this->s4 = 0;
		$this->s5 = 0;
		$this->s6 = 0;*/
        //$this->Cell(20, $height,'NIT:', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		foreach ($detalle as $val) {			
			$this->imprimirLinea($val,$count,$fill);
			$fill = !$fill;
			$count = $count + 1;
			$this->total = $this->total -1;
			$this->revisarfinPagina();
		}
	}	
	
	function imprimirLinea($val,$count,$fill){
		
		$this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('','',5.5);
			
		
		$conf_par_tablewidths=array(5,25,23,18,25,15,25,15,18,20,20,17,15,15);
        $conf_par_tablealigns=array('C','C','R','L','R','R','R','R','R','R','R','R','C','C');
        //$conf_par_tablenumbers=array(0,0,0,0,0,0,0,2,2,2,2,2,2,0,0);
        $conf_tableborders=array('LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB','LRB');
		
		$this->tablewidths=$conf_par_tablewidths;
        $this->tablealigns=$conf_par_tablealigns;
        //$this->tablenumbers=$conf_par_tablenumbers;
        $this->tableborders=$conf_tableborders;
        $this->tabletextcolor=$conf_tabletextcolor;
		
		
		$RowArray = array(
            			's0'  => $count,
            			's1' => trim($val['numero']),
            			's2' => trim($val['tipo']),
            			's3' => trim($val['documento']),
						
						's4' => trim($val['persona_remi']),
						's5' => trim($val['fecha_documento']),
						
						's6' => trim($val['referencia']),
						//'s7' => $this->cortar_cadena(trim($val['observaciones_estado']),58),
						's7' => trim($val['nivel_prioridad']),
						's8' => trim($val['desc_clasificador']),
						
						's9' => trim($val['desc_funcionario']),
                        's10' => $this->cortar_cadena(trim($val['mensaje']),58),
                        's11' => trim($val['acciones']),
						//'s13' => trim($val['desc_insti']),
                        
                        's12' => trim($val['usr_reg']),
						's13' => trim($val['sw_archivado']),
					);

		$this-> MultiRow($RowArray,$fill,0);
			
	}

	function revisarfinPagina(){
		$dimensions = $this->getPageDimensions();
		$hasBorder = false; //flag for fringe case
		
		$startY = $this->GetY();
		$this->getNumLines($row['cell1data'], 80);
		
        if ($startY > 190) {
            //$this->cerrarCuadro();
            //$this->cerrarCuadroTotal();
            if($this->total!= 0){
                $this->AddPage();
            }
        }
    }

	function cortar_cadena($cadena, $longitud) {
	    // Inicializamos las variables
	    $contador = 0;
	    $texto = '';
	    $arrayTexto = explode(' ', $cadena);
		while($longitud >= strlen($texto) + strlen($arrayTexto[$contador])) {
			$texto .= ' '.$arrayTexto[$contador];
			$contador++;
		}
	    if(strlen($cadena)>$longitud){
	        $texto .= '';
	    }
	    return trim($texto);
	}
	
	
	function generarReporte() {
		$this->setFontSubsetting(false);
		$this->AddPage();
		
		$this->generarCuerpo($this->datos_detalle);
		
		$this->Ln(4);
	} 
	

 
}
?>