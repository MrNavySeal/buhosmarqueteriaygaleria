<?php
    require 'Libraries/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
    use PhpOffice\PhpSpreadsheet\IOFactory;
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
                        $sheetReport->setCellValue('A1',$arrCompany['name']);
                        $sheetReport->setCellValue('A2',"Kardex");
                        $sheetReport->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheetReport->getStyle("A1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0d6efd');
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
                        $row =3;
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
                            $sheetReport->setCellValue("B$row","Factura");
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
                                $sheetReport->setCellValue("H$row",$det['price']);
                                $sheetReport->setCellValue("I$row",$det['output']);
                                $sheetReport->setCellValue("J$row",$det['output_total']);
                                $sheetReport->setCellValue("K$row",$det['price']);
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
                            $sheetReport->setCellValue("A$row","Total");
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
    }

?>