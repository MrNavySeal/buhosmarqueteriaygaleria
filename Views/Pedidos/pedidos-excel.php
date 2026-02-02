<?php
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

    $arrData = $data['data'];
    if(!empty($arrData)){
        $total = $data['total'];
        $fileName = $data['file_name'];
        $fileTitle = $data['file_title'];
        $arrCompany = getCompanyInfo();
        $spreadsheet = new Spreadsheet();
        $spreadsheet->addSheet(new Worksheet($spreadsheet,"reporte"),0);
        $spreadsheet->setActiveSheetIndexByName("reporte");
        $sheetReport = $spreadsheet->getSheetByName("reporte");
        //Delete sheet
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
        $spreadsheet->removeSheetByIndex($sheetIndex);
        
        $sheetReport->mergeCells("A1:K1");
        $sheetReport->mergeCells("A2:K2");
        $sheetReport->setCellValue('A1',$arrCompany['name']);
        $sheetReport->setCellValue('A2',$fileTitle);
        $sheetReport->setCellValue('A3',"ID");
        $sheetReport->setCellValue('B3',"Fecha");
        $sheetReport->setCellValue('C3',"Nombre");
        $sheetReport->setCellValue('D3',"Correo");
        $sheetReport->setCellValue('E3',"Teléfono");
        $sheetReport->setCellValue('F3',"CC/NIT");
        $sheetReport->setCellValue('G3',"Método de pago");
        $sheetReport->setCellValue('H3',"Total");
        $sheetReport->setCellValue('I3',"Total pendiente");
        $sheetReport->setCellValue('J3',"Estado de pago");
        $sheetReport->setCellValue('K3',"Estado de pedido");
        $sheetReport->getStyle("A1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheetReport->getStyle("A2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheetReport->getStyle("A3:K3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheetReport->getStyle("A1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0d6efd');
        $sheetReport->getStyle("A3:K3")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e2e3e5');
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

        $sheetReport->getStyle("A1:K1")->applyFromArray($arrBordersStyle);
        $sheetReport->getStyle("A2:K2")->applyFromArray($arrBordersStyle);
        $sheetReport->getStyle("A3:K3")->applyFromArray($arrBordersStyle);
        $row =4;
        foreach ($arrData as $data) {
            if($data['status'] =="pendent" && $data['type'] != "mercadopago"){
                $status = 'Credito';
            }else if($data['status'] =="approved"){
                $status = 'Pagado';
            }else if($data['status'] =="canceled"){
                $status = 'Anulado';
            }else if($data['status'] =="pendent" && $data['type'] == "mercadopago"){
                $status = 'Pendiente';
            }

            if($data['statusorder'] =="confirmado"){
                $statusOrder = 'Confirmado';
            }else if($data['statusorder'] =="en preparacion"){
                $statusOrder = 'En preparacion';
            }else if($data['statusorder'] =="preparado"){
                $statusOrder = 'Preparado';
            }else if($data['statusorder'] =="entregado"){
                $statusOrder = 'Entregado';
            }else if($data['statusorder'] =="enviado"){
                $statusOrder = 'Enviado';
            }else if($data['statusorder'] =="rechazado" || $data['statusorder'] =="anulado"){
                $statusOrder = 'Anulado';
            }

            $sheetReport->setCellValue("A$row",$data['idorder']);
            $sheetReport->setCellValue("B$row",$data['date']);
            $sheetReport->setCellValue("C$row",$data['name']);
            $sheetReport->setCellValue("D$row",$data['email']);
            $sheetReport->setCellValue("E$row",$data['phone']);
            $sheetReport->setCellValue("F$row",$data['identification']);
            $sheetReport->setCellValue("G$row",$data['type']);
            $sheetReport->setCellValue("H$row",$data['amount']);
            $sheetReport->setCellValue("I$row",$data['total_pendent']);
            $sheetReport->setCellValue("J$row",$status);
            $sheetReport->setCellValue("K$row",$statusOrder);

            $sheetReport->getStyle("A$row:K$row")->applyFromArray($arrBordersStyle);
            $sheetReport->getStyle("H$row")->applyFromArray($arrMoneyFormat);
            $sheetReport->getStyle("I$row")->applyFromArray($arrMoneyFormat);
            $sheetReport->getStyle("H$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheetReport->getStyle("I$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheetReport->getStyle("A$row:K$row")->applyFromArray($arrBordersStyle);
            $row++;
        }

        $sheetReport->getColumnDimension('A')->setAutoSize(true);
        $sheetReport->getColumnDimension('B')->setAutoSize(true);
        $sheetReport->getColumnDimension("C")->setAutoSize(true);
        $sheetReport->getColumnDimension("D")->setAutoSize(true);
        $sheetReport->getColumnDimension("E")->setAutoSize(true);
        $sheetReport->getColumnDimension("F")->setAutoSize(true);
        $sheetReport->getColumnDimension("G")->setAutoSize(true);
        $sheetReport->getColumnDimension("H")->setAutoSize(true);
        $sheetReport->getColumnDimension("I")->setAutoSize(true);
        $sheetReport->getColumnDimension("J")->setAutoSize(true);
        $sheetReport->getColumnDimension("k")->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
?>