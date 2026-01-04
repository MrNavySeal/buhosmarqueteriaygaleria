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
        
        $sheetReport->mergeCells("A1:I1");
        $sheetReport->mergeCells("A2:I2");
        $sheetReport->setCellValue('A1',$arrCompany['name']);
        $sheetReport->setCellValue('A2',$fileTitle);
        $sheetReport->setCellValue('A3',"Id");
        $sheetReport->setCellValue('B3',"Referencia");
        $sheetReport->setCellValue('C3',"Nombre");
        $sheetReport->setCellValue('D3',"Categoria");
        $sheetReport->setCellValue('E3',"Subcategoria");
        $sheetReport->setCellValue('F3',"Unidad");
        $sheetReport->setCellValue('G3',"Valor");
        $sheetReport->setCellValue('H3',"Stock");
        $sheetReport->setCellValue('I3',"Total");
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
            $sheetReport->setCellValue("G$row",$data['price_purchase']);
            $sheetReport->setCellValue("H$row",$data['stock']);
            $sheetReport->setCellValue("I$row",$data['total']);
            $sheetReport->getStyle("A$row:I$row")->applyFromArray($arrBordersStyle);
            $sheetReport->getStyle("G$row")->applyFromArray($arrMoneyFormat);
            $sheetReport->getStyle("I$row")->applyFromArray($arrMoneyFormat);
            $sheetReport->getStyle("A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheetReport->getStyle("F$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheetReport->getStyle("H$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheetReport->getStyle("G$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
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
        $sheetReport->getColumnDimension("G")->setAutoSize(true);
        $sheetReport->getColumnDimension("H")->setAutoSize(true);
        $sheetReport->getColumnDimension("I")->setAutoSize(true);
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    
?>