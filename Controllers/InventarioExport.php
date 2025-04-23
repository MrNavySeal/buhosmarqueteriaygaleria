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
            $this->MultiCell(220, 7, "Reporte - Inventario", "B", 'C', 0, 0, 55, 23, true,0,false,true,7,"M");
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
    class InventarioExport extends Controllers{
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
                        $total = floatval($_POST['total']);
                        $fileName = 'reporte_inventario_'.rand()*10;
                        $arrCompany = getCompanyInfo();
                        $spreadsheet = new Spreadsheet();
                        $spreadsheet->addSheet(new Worksheet($spreadsheet,"reporte"),0);
                        $spreadsheet->setActiveSheetIndexByName("reporte");
                        $sheetReport = $spreadsheet->getSheetByName("reporte");
                        //Delete sheet
                        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
                        $spreadsheet->removeSheetByIndex($sheetIndex);
                        
                        $sheetReport->mergeCells("A1:I1");
                        $sheetReport->mergeCells("A2:I2");
                        $sheetReport->setCellValue('A1',$arrCompany['name']);
                        $sheetReport->setCellValue('A2',"Inventario");
                        $sheetReport->setCellValue('A3',"Id");
                        $sheetReport->setCellValue('B3',"Referencia");
                        $sheetReport->setCellValue('C3',"Nombre");
                        $sheetReport->setCellValue('D3',"Categoria");
                        $sheetReport->setCellValue('E3',"Subcategoria");
                        $sheetReport->setCellValue('F3',"Unidad");
                        $sheetReport->setCellValue('G3',"Stock");
                        $sheetReport->setCellValue('H3',"Costo");
                        $sheetReport->setCellValue('I3',"Costo total");
                        $sheetReport->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A3:I3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0d6efd');
                        $sheetReport->getStyle("A3:I3")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e2e3e5');
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
                        $sheetReport->getStyle("A1:I1")->applyFromArray($arrBordersStyle);
                        $sheetReport->getStyle("A2:I2")->applyFromArray($arrBordersStyle);
                        $sheetReport->getStyle("A3:I3")->applyFromArray($arrBordersStyle);
                        $row =4;
                        foreach ($arrData as $data) {
                            $sheetReport->setCellValue("A$row",$data['id']);
                            $sheetReport->setCellValue("B$row",$data['reference']);
                            $sheetReport->setCellValue("C$row",$data['name']);
                            $sheetReport->setCellValue("D$row",$data['category']);
                            $sheetReport->setCellValue("E$row",$data['subcategory']);
                            $sheetReport->setCellValue("F$row",$data['measure']);
                            $sheetReport->setCellValue("G$row",$data['stock']);
                            $sheetReport->setCellValue("H$row",$data['price_purchase']);
                            $sheetReport->setCellValue("I$row",$data['total']);
                            $sheetReport->getStyle("A$row:I$row")->applyFromArray($arrBordersStyle);
                            $sheetReport->getStyle("H$row")->applyFromArray($arrMoneyFormat);
                            $sheetReport->getStyle("I$row")->applyFromArray($arrMoneyFormat);
                            $sheetReport->getStyle("A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetReport->getStyle("F$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetReport->getStyle("H$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheetReport->getStyle("G$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheetReport->getStyle("I$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheetReport->getStyle("A$row:I$row")->applyFromArray($arrBordersStyle);
                            $row++;
                        }
                        $sheetReport->mergeCells("A$row:H$row");
                        $sheetReport->setCellValue("A$row","Total");
                        $sheetReport->setCellValue("I$row",$total);
                        $sheetReport->getStyle("A$row:I$row")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f8f9fa');
                        $sheetReport->getStyle("A$row:I$row")->applyFromArray($arrBordersStyle);
                        $sheetReport->getStyle("I$row")->applyFromArray($arrMoneyFormat);
                        $sheetReport->getStyle("A$row")->getFont()->setBold(true);
                        $sheetReport->getStyle("A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                        $sheetReport->getStyle("I$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
                        $sheetReport->getColumnDimension('C')->setAutoSize(true);
                        $sheetReport->getColumnDimension('D')->setAutoSize(true);
                        $sheetReport->getColumnDimension("E")->setAutoSize(true);
                        $sheetReport->getColumnDimension("H")->setAutoSize(true);
                        $sheetReport->getColumnDimension("I")->setAutoSize(true);
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
                        $total = floatval($_POST['total']);
                        $fileName = 'reporte_inventario_'.rand()*10;
                        $pdf = new MYPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                        
                        // set document information
                        $pdf->SetCreator(PDF_CREATOR);
                        $pdf->SetAuthor($arrCompany['name']);
                        $pdf->SetTitle('Reporte | Inventario');
                        $pdf->SetSubject('Reporte inventario');
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
                        $pdf->MultiCell(20, 5,"Id", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->MultiCell(30, 5,"Referencia", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->MultiCell(50, 5,"Nombre", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->MultiCell(35, 5,"Categoria", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                        $pdf->MultiCell(30, 5,"Subcategoria", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                        $pdf->MultiCell(20, 5,"Unidad", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                        $pdf->MultiCell(20, 5,"Stock", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                        $pdf->MultiCell(30, 5,"Costo", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");  
                        $pdf->MultiCell(30, 5,"Costo total", "LBRT", 'C', 1, 0, '', "", true,0,0,1,5,"M");
                        $pdf->SetFillColor(255,255,255);
                        $pdf->ln();
                        foreach ($arrData as $data) {
                            if($pdf->GetY()>150){
                                $pdf->addPage();
                                $pdf->ln(10);
                            }
                            $intName = $pdf->getNumLines($data['name'],50);
                            $intCategory = $pdf->getNumLines($data['category'],35);
                            $intSubcategory = $pdf->getNumLines($data['subcategory'],30);
                            $h = max($intName,$intCategory,$intSubcategory)*4;
                            $pdf->MultiCell(20,$h,$data['id'], "LBRT", 'C', 1, 0, '', "", true);
                            $pdf->MultiCell(30,$h,$data['reference'], "LBRT", 'L', 1, 0, '', "", true);
                            $pdf->MultiCell(50,$h,$data['name'], "LBRT", 'L', 1, 0, '', "", true);
                            $pdf->MultiCell(35,$h,$data['category'], "LBRT", 'L', 1, 0, '', "", true);
                            $pdf->MultiCell(30,$h,$data['subcategory'], "LBRT", 'L', 1, 0, '', "", true);
                            $pdf->MultiCell(20,$h,$data['measure'], "LBRT", 'C', 1, 0, '', "", true);
                            $pdf->MultiCell(20,$h,$data['stock'], "LBRT", 'C', 1, 0, '', "", true);
                            $pdf->MultiCell(30,$h,$data['price_purchase_format'], "LBRT", 'R', 1, 0, '', "", true);
                            $pdf->MultiCell(30,$h,$data['total_format'], "LBRT", 'R', 1, 0, '', "", true);
                            
                            $pdf->SetFillColor(255,255,255);
                            $pdf->ln();
                            
                        }
                        $pdf->SetFillColor(248,249,250);
                        $pdf->SetFont('helvetica', 'B', 8);
                        $pdf->MultiCell(235, 5,"Total", "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->SetFont('helvetica', '', 8);
                        $pdf->MultiCell(30, 5,formatNum($total), "LBRT", 'R', 1, 0, '', "", true,0,0,1,5,"M"); 
                        $pdf->ln();
                        ob_end_clean();
                        $pdf->Output($fileName.'.pdf', 'I');
                    }
                }
            }
            die();
        }
    }

?>