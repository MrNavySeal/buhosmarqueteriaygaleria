<?php
    define("DATA",$data);
    define("QUOTE",$data['data']);
    class MYPDF extends TCPDF {

        public function Header() {
            $arrCompany = getCompanyInfo();
            $y = $this->GetY();
            $this->Image(media()."/images/uploads/".$arrCompany['logo'], 25, 8, 20, '', 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->MultiCell(40, 25, '', "LRBT", 'C', 0, 0, '', $y, true); 
            $this->SetFont('helvetica', 'B', 14);
            $this->MultiCell(120, 10, $arrCompany['name'], "T", 'C', 0, 0, 55, $y, true,0,false,true,10,"M");
            $this->SetFont('helvetica', 'B', 10);
            $this->MultiCell(25, 25, 'Cotización', "LRT", 'C', 0, 0, 170, $y, true);
            $this->SetFont('helvetica', 'B', 13);
            $this->MultiCell(25, 10,"No. ".QUOTE['id'], "LR", 'C', 0, 0,170, 15, true);
            $this->SetFont('helvetica', '', 8);
            $this->MultiCell(120, 13.35, "\nNIT: ".$arrCompany['nit']." No responsable de IVA", "", 'C', 0, 0, 55, 10, true);
            $this->SetFont('helvetica', '', 8);
            $this->MultiCell(120, 13.35, "\nDirección: ".$arrCompany['addressfull']."\n Teléfono: ".$arrCompany['phone']." - Email: ".$arrCompany['email']."\nSitio web: ".base_url(), "", 'C', 0, 0, 55, 14, true);
            $this->MultiCell(120, 7, "", "B", 'C', 0, 0, 55, 23, true,0,false,true,7,"M");
            $this->MultiCell(25, 7, "", "BR", 'C', 0, 0, 170, 23, true,0,false,true,7,"M");
            $this->ln();
        }
    
        public function Footer() {
            $arrCompany = getCompanyInfo();
            $this->SetFont('helvetica', 'I', 8);
            $this->SetY(-30);
            $this->MultiCell(180, 10,  "Dirección: ".$arrCompany['addressfull']."\n Teléfono: ".$arrCompany['phone']." - Email: ".$arrCompany['email']." - Sitio web: ".base_url() , "T", 'C', 0, 0, '', "", true,0,0,1,25,"M"); 
            $this->SetY(-8);
            $this->MultiCell(90, 25, 'Fecha: '.date("d/m/Y H:i:s"), "", 'L', 0, 0, '', "", true);
            $this->MultiCell(90, 25, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), "", 'R', 0, 0, '', "", true); 
        }
    }
    $arrCompany = getCompanyInfo();
    $pdf = new MYPDF('P','mm','Letter', true, 'iso-8859-1', false);
    
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($arrCompany['name']);
    $pdf->SetTitle(DATA['page_title']);
    $pdf->SetSubject(DATA['page_title']);
    $pdf->SetKeywords('reporte');

    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetFont('helvetica', '', 9);
    $pdf->AddPage();
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->ln();
    $pdf->setY(40);
    $intHeight = 6;
    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,$intHeight,"Nombre","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(95,$intHeight,QUOTE['name'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(35,$intHeight,"Fecha de emisión","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(30,$intHeight,QUOTE['date'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,$intHeight,"Teléfono","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(40,$intHeight,QUOTE['phone'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,$intHeight,"CC/NIT","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(35,$intHeight,QUOTE['identification'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(35,$intHeight,"Fecha de vencimiento","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(30,$intHeight,QUOTE['date_beat'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,$intHeight,"Correo","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(160,$intHeight,QUOTE['email'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,$intHeight,"Dirección","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(160,$intHeight,QUOTE['address'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,$intHeight,"Estado","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(160,$intHeight,QUOTE['status'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,10,"Notas","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(160,10,QUOTE['note'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(20,10,"Referencia","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(100,10,"Descripción","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(20,10,"Precio","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(20,10,"Cantidad","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(20,10,"Subtotal","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    ob_end_clean();
    $pdf->Output($fileName.'.pdf', 'I');
?>