<?php
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    class MYPDF extends TCPDF {

        public function Header() {
            $arrCompany = getCompanyInfo();
            $y = $this->GetY();
            $this->Image(media()."/images/uploads/".$arrCompany['logo'], 25, 8, 20, '', 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->MultiCell(40, 25, '', "LRBT", 'C', 0, 0, '', $y, true); 
            $this->SetFont('helvetica', 'B', 15);
            $this->MultiCell(215, 10, $arrCompany['name'], "T", 'C', 0, 0, 55, $y, true,0,false,true,10,"M");
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(25, 10, 'Fecha', "LRT", 'C', 0, 0, 255, $y, true);
            $this->SetFont('helvetica', 'B', 11);
            $this->MultiCell(25, 13.35, date("d/m/Y"), "LRB", 'C', 0, 0,255, 10, true);
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(215, 13.35, "\nNIT: ".$arrCompany['nit'], "B", 'C', 0, 0, 55, 10, true);
            $this->SetFont('helvetica', 'B', 11);
            $this->MultiCell(220, 7, "Reporte - Kardex", "B", 'C', 0, 0, 55, 23, true,0,false,true,7,"M");
            $this->MultiCell(25, 7, "", "BR", 'C', 0, 0, 255, 23, true,0,false,true,7,"M");
            $this->ln();
        }
    
        public function Footer() {
            $arrCompany = getCompanyInfo();
            $strName = $_SESSION['userData']['firstname']." ".$_SESSION['userData']['lastname'];
            $this->SetFont('helvetica', 'I', 8);
            $this->SetY(-30);
            $this->MultiCell(265, 10,  "Dirección: ".$arrCompany['addressfull']."\n Teléfono: ".$arrCompany['phone']." - Email: ".$arrCompany['email']." - Sitio web: ".base_url() , "T", 'C', 0, 0, '', "", true,0,0,1,25,"M"); 
            $this->SetY(-8);
            $this->MultiCell(69, 25, 'Impreso por: '.$strName , "", 'L', 0, 0, '', "", true); 
            $this->MultiCell(69, 25, 'IP: '.getIp(), "", 'C', 0, 0, '', "", true); 
            $this->MultiCell(69, 25, 'Fecha: '.date("d/m/Y H:i:s"), "", 'C', 0, 0, '', "", true);
            $this->MultiCell(69, 25, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), "", 'R', 0, 0, '', "", true); 
        }
    }
    class InventarioKardexExport extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(4);
            
        }
        public function excel(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrData = json_decode($_POST['data'],true);
                    if(!empty($arrData)){
                        $arrInitialDate = explode("-",strClean($_POST['strInititalDate']));
                        $arrFinalDate = explode("-",strClean($_POST['strFinalDate']));
                        $strInitialDate = $arrInitialDate[2]."/".$arrInitialDate[1]."/".$arrInitialDate[0];
                        $strFinalDate = $arrFinalDate[2]."/".$arrFinalDate[1]."/".$arrFinalDate[0];
                        $fileName = 'reporte_kardex_'.rand()*10;
                        $arrCompany = getCompanyInfo();
                        $spreadsheet = new Spreadsheet();
                        $spreadsheet->addSheet(new Worksheet($spreadsheet,"reporte"),0);
                        $spreadsheet->setActiveSheetIndexByName("reporte");
                        $sheetReport = $spreadsheet->getSheetByName("reporte");
                        //Delete sheet
                        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                        $spreadsheet->removeSheetByIndex($sheetIndex);
                        
                        $sheetReport->mergeCells("A1:M1");
                        $sheetReport->mergeCells("A2:M2");
                        $sheetReport->mergeCells("B3:M3");
                        $sheetReport->setCellValue('A1',$arrCompany['name']);
                        $sheetReport->setCellValue('A2',"Kardex");
                        $sheetReport->setCellValue('A3',"Fecha");
                        $sheetReport->setCellValue('B3',"Desde ".$strInitialDate." hasta ".$strFinalDate);
                        $sheetReport->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0d6efd');
                        $sheetReport->getStyle("A3")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cfe2ff');
                        $sheetReport->getStyle("A1")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                        $sheetReport->getStyle("A1")->getFont()->setBold(true);
                        $sheetReport->getStyle("A2")->getFont()->setBold(true);

                        //Style Array
                        $arrBordersStyle = [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ]
                            ],
                        ];
                        $arrMoneyFormat = [
                            'numberFormat' => [
                                'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD
                            ]
                        ];
                        $sheetReport->getStyle("A1:M1")->applyFromArray($arrBordersStyle);
                        $sheetReport->getStyle("A2:M2")->applyFromArray($arrBordersStyle);
                        $sheetReport->getStyle("A3:M3")->applyFromArray($arrBordersStyle);
                        $row =5;
                        foreach ($arrData as $data) {
                            $detail = $data['detail'];
                            $total = count($detail);
                            $lastStock =0;
                            $lastTotal = 0;
                            
                            $sheetReport->mergeCells("A$row:D$row");
                            $sheetReport->mergeCells("E$row:G$row");
                            $sheetReport->mergeCells("H$row:J$row");
                            $sheetReport->mergeCells("K$row:M$row");
                            $sheetReport->setCellValue("A$row",$data['name']);
                            $sheetReport->setCellValue("E$row","Entradas");
                            $sheetReport->setCellValue("H$row","Salidas");
                            $sheetReport->setCellValue("K$row","Saldo");
                            $sheetReport->getStyle("E$row:K$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetReport->getStyle("E$row:M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e2e3e5');
                            $sheetReport->getStyle("A$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cfe2ff');
                            $sheetReport->getStyle("A$row:M$row")->applyFromArray($arrBordersStyle);
                            $row++;
                            $sheetReport->setCellValue("A$row","Fecha");
                            $sheetReport->setCellValue("B$row","Documento");
                            $sheetReport->setCellValue("C$row","Movimiento");
                            $sheetReport->setCellValue("D$row","Unidad");
                            $sheetReport->setCellValue("E$row","Valor");
                            $sheetReport->setCellValue("F$row","Cantidad");
                            $sheetReport->setCellValue("G$row","Saldo");
                            $sheetReport->setCellValue("H$row","Valor");
                            $sheetReport->setCellValue("I$row","Cantidad");
                            $sheetReport->setCellValue("J$row","Saldo");
                            $sheetReport->setCellValue("K$row","Valor");
                            $sheetReport->setCellValue("L$row","Cantidad");
                            $sheetReport->setCellValue("M$row","Saldo");
                            $sheetReport->getStyle("A$row:M$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetReport->getStyle("A$row:M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f8f9fa');
                            $sheetReport->getStyle("A$row:M$row")->applyFromArray($arrBordersStyle);
                            $row++;
                            for ($i=0; $i < $total ; $i++) { 
                                $det = $detail[$i];
                                $lastStock =$det['balance'];
                                $lastTotal = $det['balance_total'];
                                $sheetReport->setCellValue("A$row",$det['date_format']);
                                $sheetReport->setCellValue("B$row",$det['document']);
                                $sheetReport->setCellValue("C$row",$det['move']);
                                $sheetReport->setCellValue("D$row",$det['measure']);
                                $sheetReport->setCellValue("E$row",$det['price']);
                                $sheetReport->setCellValue("F$row",$det['input']);
                                $sheetReport->setCellValue("G$row",$det['input_total']);
                                $sheetReport->setCellValue("H$row",$det['last_price']);
                                $sheetReport->setCellValue("I$row",$det['output']);
                                $sheetReport->setCellValue("J$row",$det['output_total']);
                                $sheetReport->setCellValue("K$row",$det['last_price']);
                                $sheetReport->setCellValue("L$row",$det['balance']);
                                $sheetReport->setCellValue("M$row",$det['balance_total']);
                                $sheetReport->getStyle("B$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                                $sheetReport->getStyle("D$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $sheetReport->getStyle("F$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $sheetReport->getStyle("I$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $sheetReport->getStyle("L$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                                $sheetReport->getStyle("E$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                                $sheetReport->getStyle("G$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                                $sheetReport->getStyle("H$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                                $sheetReport->getStyle("J$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                                $sheetReport->getStyle("K$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                                $sheetReport->getStyle("M$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                                $sheetReport->getStyle("A$row:M$row")->applyFromArray($arrBordersStyle);
                                $sheetReport->getStyle("E$row")->applyFromArray($arrMoneyFormat);
                                $sheetReport->getStyle("G$row")->applyFromArray($arrMoneyFormat);
                                $sheetReport->getStyle("H$row")->applyFromArray($arrMoneyFormat);
                                $sheetReport->getStyle("J$row")->applyFromArray($arrMoneyFormat);
                                $sheetReport->getStyle("K$row")->applyFromArray($arrMoneyFormat);
                                $sheetReport->getStyle("M$row")->applyFromArray($arrMoneyFormat);
                                $row++;
                            }
                            $sheetReport->mergeCells("A$row:K$row");
                            $sheetReport->setCellValue("A$row","Saldo final");
                            $sheetReport->setCellValue("L$row",$lastStock);
                            $sheetReport->setCellValue("M$row",$lastTotal);
                            $sheetReport->getStyle("M$row")->applyFromArray($arrMoneyFormat);
                            if($lastStock < 0){
                                $sheetReport->getStyle("A$row:M$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffc107');
                                $sheetReport->getStyle("A$row:M$row")->getFont()->setBold(true);
                            }
                            $sheetReport->getStyle("A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheetReport->getStyle("L$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetReport->getStyle("M$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheetReport->getStyle("A$row")->getFont()->setBold(true);
                            $sheetReport->getStyle("A$row:M$row")->applyFromArray($arrBordersStyle);
                            $row++;
                        }
                        $sheetReport->getColumnDimension('A')->setAutoSize(true);
                        $sheetReport->getColumnDimension('C')->setAutoSize(true);
                        $sheetReport->getColumnDimension("E")->setAutoSize(true);
                        $sheetReport->getColumnDimension("G")->setAutoSize(true);
                        $sheetReport->getColumnDimension("H")->setAutoSize(true);
                        $sheetReport->getColumnDimension("J")->setAutoSize(true);
                        $sheetReport->getColumnDimension("K")->setAutoSize(true);
                        $sheetReport->getColumnDimension("M")->setAutoSize(true);
                        $writer = new Xlsx($spreadsheet);
                        ob_end_clean();
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output');
                    }
                }
            }
            die();
        }
        public function pdf(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrData = json_decode($_POST['data'],true);
                    if(!empty($arrData)){
                        $arrCompany = getCompanyInfo();
                        $fileName = 'reporte_kardex_'.rand()*10;
                        $pdf = new MYPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                        $arrInitialDate = explode("-",strClean($_POST['strInititalDate']));
                        $arrFinalDate = explode("-",strClean($_POST['strFinalDate']));
                        $strInitialDate = $arrInitialDate[2]."/".$arrInitialDate[1]."/".$arrInitialDate[0];
                        $strFinalDate = $arrFinalDate[2]."/".$arrFinalDate[1]."/".$arrFinalDate[0];
                        // set document information
                        $pdf->SetCreator(PDF_CREATOR);
                        $pdf->SetAuthor($arrCompany['name']);
                        $pdf->SetTitle('Reporte | Kardex');
                        $pdf->SetSubject('Reporte kardex');
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
                        
                        // ---------------------------------------------------------
            
                        // set default font subsetting mode
                        $pdf->SetFont('helvetica', '', 9);
                        $pdf->AddPage();
                        $pdf->ln(10);
                        $pdf->SetFillColor(207,226,255);
                        $pdf->MultiCell(30, 5,"Fecha", "LBRT", 'L', 1, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->SetFillColor(255,255,255);
                        $pdf->MultiCell(235, 5,"Desde ".$strInitialDate." hasta ".$strFinalDate, "LBRT", 'L', 0, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->ln();
                        $pdf->ln();
                        foreach ($arrData as $data) {
                            if($pdf->GetY()>150){
                                $pdf->addPage();
                                $pdf->ln(10);
                            }
                            
                            $det = $data['detail'];
                            $h = $pdf->getNumLines($data['name'],80)*5;
                            $pdf->SetFillColor(207,226,255);
                            $pdf->MultiCell(80, $h,$data['name'], "LBRT", 'L', 1, 0, '', "", true,0,0,1,$h,"M"); 
                            $pdf->SetFillColor(226,227,229);
                            $pdf->MultiCell(62, $h,"Entradas", "LBRT", 'C', 1, 0, '', "", true,0,0,1,$h,"M"); 
                            $pdf->MultiCell(62, $h,"Salidas", "LBRT", 'C', 1, 0, '', "", true,0,0,1,$h,"M"); 
                            $pdf->MultiCell(61, $h,"Saldo", "LBRT", 'C', 1, 0, '', "", true,0,0,1,$h,"M"); 
                            $pdf->ln();
                            $pdf->SetFont('helvetica', '', 8);
                            $pdf->SetFillColor(248,249,250);
                            $pdf->MultiCell(20, 5,"Fecha", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                            $pdf->MultiCell(20, 5,"Documento", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                            $pdf->MultiCell(30, 5,"Movimiento", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(10, 5,"UN", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(22, 5,"Valor", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(20, 5,"Cantidad", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(20, 5,"Saldo", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(22, 5,"Valor", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(20, 5,"Cantidad", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(20, 5,"Saldo", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(21, 5,"Valor", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(20, 5,"Cantidad", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->MultiCell(20, 5,"Saldo", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                            $pdf->SetFillColor(255,255,255);
                            $pdf->ln();
                            $lastStock = 0;
                            $lastTotal = 0;
                            foreach ($det as $pro) {
                                $pdf->MultiCell(20, 5,$pro['date_format'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                                $pdf->MultiCell(20, 5,$pro['document'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(30, 5,$pro['move'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(10, 5,$pro['measure'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(22, 5,formatNum($pro['price']), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(20, 5,$pro['input'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(20, 5,formatNum($pro['input_total']), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(22, 5,formatNum($pro['last_price']), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(20, 5,$pro['output'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(20, 5,formatNum($pro['output_total']), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(21, 5,formatNum($pro['last_price']), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(20, 5,$pro['balance'], "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                                $pdf->MultiCell(20, 5,formatNum($pro['balance_total']), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M"); 
                                $pdf->ln();
                                $lastStock = $pro['balance'];
                                $lastTotal = $pro['balance_total'];
                            }
                            if($lastStock < 0){
                                $pdf->SetFillColor(255,193,7);
                            }
                            $pdf->SetFont('helvetica', 'B', 8);
                            $pdf->MultiCell(225, 5,"Saldo final", "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M"); 
                            $pdf->SetFont('helvetica', '', 8);
                            $pdf->MultiCell(20, 5,$lastStock, "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                            $pdf->MultiCell(20, 5,formatNum($lastTotal), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M"); 
                            $pdf->ln();
                        }
                        ob_end_clean();
                        $pdf->Output($fileName.'.pdf', 'I');
                    }
                }
            }
            die();
        }
    }

?>