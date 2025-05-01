<?php
    define("DATA",$data);
    define("QUOTE",$data['data']);
    $arrDet = QUOTE['detail'];
    $discount = QUOTE['coupon'];
    $shipping = QUOTE['shipping'];
    $total = QUOTE['amount'];
    $subtotal = QUOTE['subtotal'];
    $fileName = DATA['file_name'];
    $arrAdvance = QUOTE['detail_advance'];
    $intTotalAdvance = QUOTE['total_advance'];
    $intTotalPendent = QUOTE['total_pendent'];
    $status = "";
    if(QUOTE['status'] =="pendent"){
        $status = 'crédito';
    }else if(QUOTE['status'] =="approved"){
        $status = 'pagado';
    }else if(QUOTE['status'] =="canceled"){
        $status = 'anulado';
    }
    class MYPDF extends TCPDF {

        public function Header() {
            $arrCompany = getCompanyInfo();
            $y = $this->GetY();
            $this->Image(media()."/images/uploads/".$arrCompany['logo'], 25, 8, 20, '', 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->MultiCell(40, 25, '', "LRBT", 'C', 0, 0, '', $y, true); 
            $this->SetFont('helvetica', 'B', 14);
            $this->MultiCell(120, 10, $arrCompany['name'], "T", 'C', 0, 0, 55, $y, true,0,false,true,10,"M");
            $this->SetFont('helvetica', 'B', 8);
            $this->MultiCell(25, 25, 'Factura de venta', "LRT", 'C', 0, 0, 170, $y, true);
            $this->SetFont('helvetica', 'B', 13);
            $this->MultiCell(25, 10,"No. ".QUOTE['idorder'], "LR", 'C', 0, 0,170, 15, true);
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
            $this->setY(-40);
            $this->SetFont('helvetica', 'B', 7.5);
            $this->MultiCell(180, 10,"Esta factura de compra venta se asimila en todos sus efectos legales a la letra de cambio de acuerdo al ART.774 del código de comercio", "", 'C', 0, 0, '', "", true,0,0,1,25,"M"); 
            $this->SetFont('helvetica', 'I', 7);
            $this->SetY(-30);
            $this->MultiCell(180, 10,"Dirección: ".$arrCompany['addressfull']."\n Teléfono: ".$arrCompany['phone']." - Email: ".$arrCompany['email']." - Sitio web: ".base_url() , "T", 'C', 0, 0, '', "", true,0,0,1,25,"M"); 
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
    $pdf->MultiCell(25,$intHeight,"CC/NIT","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(30,$intHeight,QUOTE['identification'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
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
    $pdf->MultiCell(20,$intHeight,"Tipo de pago","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(40,$intHeight,QUOTE['type'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(25,$intHeight,"Estado de pago","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(30,$intHeight,$status,"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(35,$intHeight,"Estado de pedido","LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(30,$intHeight,QUOTE['statusorder'],"LRBT",'L',true,0,'','',true,0,false,true,0,'M',true);
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
    $pdf->MultiCell(20,$intHeight,"Referencia","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(100,$intHeight,"Descripción","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(20,$intHeight,"Precio","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(20,$intHeight,"Cantidad","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->MultiCell(20,$intHeight,"Subtotal","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', '', 9);
    foreach ($arrDet as $pro) {
        $subtotalProduct =$pro['quantity']*$pro['price'];
        $subtotal+= $pro['quantity']*$pro['price'];
        $description="";
        if($pro['topic'] == 1){
            $detail = json_decode($pro['description'],true);
            $img ="";
            if(isset($detail['type'])){
                $intWidth = floatval($detail['width']);
                $intHeight = floatval($detail['height']);
                $intMargin = floatval($detail['margin']);
                $colorFrame =  $detail['colorframe'] ? $detail['colorframe'] : "";
                $material = $detail['material'] ? $detail['material'] : "";
                $marginStyle = $detail['style'] == "Flotante" || $detail['style'] == "Flotante sin marco interno" ? "Fondo" : "Paspartú";
                $borderStyle = $detail['style'] == "Flotante" ? "marco interno" : "bocel";
                $glassStyle = $detail->idType == 4 ? "Bastidor" : "Tipo de vidrio";
                $measureFrame = ($intWidth+($intMargin*2))."cm X ".($intHeight+($intMargin*2))."cm";
                if($detail['photo'] !=""){
                    $img = '<a href="'.media().'/images/uploads/'.$detail['photo'].'" target="_blank">Ver imagen</a><br>';
                }
                $description.='
                        '.$img.'
                        '.$detail['name'].'
                        <ul>
                            <li>Referencia: '.$detail['reference'].'</li>
                            <li>Color del marco: '.$colorFrame.'</li>
                            <li>Material: '.$material.'</li>
                            <li>Orientación: '.$marginStyle.'</li>
                            <li>Estilo de enmarcación: '.$detail['style'].'</li>
                            <li>'.$marginStyle.': '.$detail['margin'].'cm</li>
                            <li>Medida imagen: '.$detail['width'].'cm X '.$detail['height'].'cm</li>
                            <li>Medida marco: '.$measureFrame.'</li>
                            <li>Color del '.$marginStyle.': '.$detail['colormargin'].'</li>
                            <li>Color del '.$borderStyle.': '.$detail['colorborder'].'</li>
                            <li>'.$glassStyle.': '.$detail['glass'].'</li>
                        </ul>
                ';
            }else{
                if($detail['img'] !="" && $detail['img'] !=null){
                    $img = '<a href="'.media().'/images/uploads/'.$detail['img'].'" target="_blank">Ver imagen</a><br>';
                }
                $htmlDetail ="";
                $arrDet = $detail['detail'];
                foreach ($arrDet as $d) {
                    $htmlDetail.='<li>'.$d['name'].' :'.$d['value'].'</li>';
                }
                $description = $img.$detail['name'].'<ul>'.$htmlDetail.'</ul>';
            }
        }else{
            $description=$pro['description'];
            $flag = substr($pro['description'], 0,1) == "{" ? true : false;
            if($flag){
                $arrData = json_decode($pro['description'],true);
                $name = $arrData['name'];
                $varDetail = $arrData['detail'];
                $textDetail ="";
                foreach ($varDetail as $d) {
                    $textDetail .= '<li><span class="fw-bold t-color-3">'.$d['name'].':</span> '.$d['option'].'</li>';
                }
                $description = $name.'<ul>'.$textDetail.'</ul>';
            }
        }
        $lines = $pdf->getNumLines($description);
        $h = $lines*15;
        $pdf->MultiCell(20,$h,$pro['reference'],"LRBT",'C',false,0,'','',true,0,false,true,0,'M',true);
        $pdf->writeHTMLCell(100, $h, '', '', $description, "LRBT", 0, false, true, 'L', true);
        $pdf->MultiCell(20,$h,formatNum($pro['price']),"LRBT",'R',false,0,'','',true,0,false,true,0,'M',true);
        $pdf->MultiCell(20,$h,$pro['quantity'],"LRBT",'C',false,0,'','',true,0,false,true,0,'M',true);
        $pdf->MultiCell(20,$h,formatNum($subtotalProduct),"LRBT",'R',false,0,'','',true,0,false,true,0,'M',true);
        $pdf->ln();
        if($pdf->GetY() > 250){
            $pdf->AddPage();
            $pdf->ln(10);
        }
    }
    if($pdf->GetY() > 230){
        $pdf->AddPage();
        $pdf->ln(10);
    }
    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(160,10,"Subtotal","LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', 'R', 9);
    $pdf->MultiCell(20,10,formatNum($subtotal),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(160,10,"Descuento","LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', 'R', 9);
    $pdf->MultiCell(20,10,formatNum($discount),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();

    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(160,10,"Envio","LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', 'R', 9);
    $pdf->MultiCell(20,10,formatNum($shipping),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->ln();
    
    $pdf->SetFillColor(109,106,107);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->MultiCell(160,10,"Total","LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica', 'R', 9);
    $pdf->MultiCell(20,10,formatNum($total),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
    
    if(count($arrAdvance) > 0){
        $pdf->ln();
        $pdf->ln();
        $pdf->SetFillColor(109,106,107);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->MultiCell(80,$intHeight,"Fecha","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->MultiCell(80,$intHeight,"Método de pago","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->MultiCell(20,$intHeight,"Abono","LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->ln();
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('helvetica', '', 9);
        foreach ($arrAdvance as $det) {
            $pdf->MultiCell(80,$intHeight,$det['date'],"LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
            $pdf->MultiCell(80,$intHeight,$det['type'],"LRBT",'C',true,0,'','',true,0,false,true,0,'M',true);
            $pdf->MultiCell(20,$intHeight,formatNum($det['advance']),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
            $pdf->ln();
            if($pdf->GetY() > 250){
                $pdf->AddPage();
                $pdf->ln(10);
            }
        }
        if($pdf->GetY() > 230){
            $pdf->AddPage();
            $pdf->ln(10);
        }
        $pdf->SetFillColor(109,106,107);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->MultiCell(160,10,"Total abonado","LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('helvetica', 'R', 9);
        $pdf->MultiCell(20,10,formatNum($intTotalAdvance),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->ln();

        $pdf->SetFillColor(109,106,107);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->MultiCell(160,10,"Total pendiente","LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('helvetica', 'R', 9);
        $pdf->MultiCell(20,10,formatNum($intTotalPendent),"LRBT",'R',true,0,'','',true,0,false,true,0,'M',true);
        $pdf->ln();
    }
    ob_end_clean();
    $pdf->Output($fileName.'.pdf', 'I');
?>