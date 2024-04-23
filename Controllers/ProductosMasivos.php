<?php
    require 'Libraries/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
    //use PhpOffice\PhpSpreadsheet\IOFactory;
    class ProductosMasivos extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(11);
            
        }
        public function productos(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Productos masivos";
                $data['page_title'] = "Productos | Creación & Edición masiva";
                $data['page_name'] = "masivos";
                //$data['panelapp'] = "functions_products_mass.js";
                $this->views->getView($this,"productos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function plantilla(){
            $nextId = $this->model->selectNextId();
            $categories = $this->model->selectCategories();

            $fileName = "plantilla";
            //Dropdowns 
            $arrBool = array("Si","No");
            $arrImport = array(0,19);
            $arrStatus = array("activo","inactivo");
            $arrCategories = $categories['categories'];
            $arrSubcategories = $categories['subcategories'];
            //dep($arrCategories);exit;

            //Headers
            $headProduct = array(
                "id_producto",
                "nombre",
                "sku",
                "descripcion_corta",
                "descripcion",
                "categoría",
                "subcategoría",
                "es_producto",
                "es_insumo",
                "es_combo",
                "unidad_medida",
                "maneja_inventario",
                "stock",
                "stock_mínimo",
                "impuesto",
                "precio_compra",
                "precio_venta",
                "precio_oferta",
                "estado"
            );
            $headImg = array("producto_id","url");
            $spreadsheet = new Spreadsheet();
            //Add sheets and set sheet names
            $spreadsheet->addSheet(new Worksheet($spreadsheet,"productos"),0);
            $spreadsheet->addSheet(new Worksheet($spreadsheet,"imagenes"),1);
            $spreadsheet->addSheet(new Worksheet($spreadsheet,"variantes"),2);
            $spreadsheet->addSheet(new Worksheet($spreadsheet,"caracteristicas"),3);
            $spreadsheet->setActiveSheetIndexByName("productos");
            
            $sheetProduct = $spreadsheet->getSheetByName("productos");
            $sheetImg = $spreadsheet->getSheetByName("imagenes");
            $sheetVariant = $spreadsheet->getSheetByName("variantes");
            $sheetSpc = $spreadsheet->getSheetByName("caracteristicas");

            //Delete sheet
            $sheetIndex = $spreadsheet->getIndex(
                $spreadsheet->getSheetByName('Worksheet')
            );
            $spreadsheet->removeSheetByIndex($sheetIndex);

            //Set headers in sheetProduct
            $sheetProduct->setCellValue('A1', $headProduct[0]);
            $sheetProduct->setCellValue('B1', $headProduct[1]);
            $sheetProduct->setCellValue('C1', $headProduct[2]);
            $sheetProduct->setCellValue('D1', $headProduct[3]);
            $sheetProduct->setCellValue('E1', $headProduct[4]);
            $sheetProduct->setCellValue('F1', $headProduct[5]);
            $sheetProduct->setCellValue('G1', $headProduct[6]);
            $sheetProduct->setCellValue('H1', $headProduct[7]);
            $sheetProduct->setCellValue('I1', $headProduct[8]);
            $sheetProduct->setCellValue('J1', $headProduct[9]);
            $sheetProduct->setCellValue('K1', $headProduct[10]);
            $sheetProduct->setCellValue('L1', $headProduct[11]);
            $sheetProduct->setCellValue('M1', $headProduct[12]);
            $sheetProduct->setCellValue('N1', $headProduct[13]);
            $sheetProduct->setCellValue('O1', $headProduct[14]);
            $sheetProduct->setCellValue('P1', $headProduct[15]);
            $sheetProduct->setCellValue('Q1', $headProduct[16]);
            $sheetProduct->setCellValue('R1', $headProduct[17]);
            $sheetProduct->setCellValue('S1', $headProduct[18]);

            //Set headers in sheetImg
            $sheetImg->setCellValue('A1', $headImg[0]);
            $sheetImg->setCellValue('B1', $headImg[1]);

            //Set default config
            $rowCount = 2;
            $lastRowSheet = 5;
            for ($i=$rowCount; $i < $lastRowSheet; $i++) { 
                //Boolean fields is_product,is_combo,is_ingredient,is_stock...
                $validation = $sheetProduct->getCell("H$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrBool).'"');
                $validation = $sheetProduct->getCell("I$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrBool).'"');
                $validation = $sheetProduct->getCell("J$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrBool).'"');
                $validation = $sheetProduct->getCell("L$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrBool).'"');
                $validation = $sheetProduct->getCell("O$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrImport).'"');
                //Status
                $validation = $sheetProduct->getCell("S$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrStatus).'"');
                //Category
                $validation = $sheetProduct->getCell("F$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrCategories).'"');
                //Subcategory

                $validation = $sheetProduct->getCell("G$i")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError('Valor no válido')
                ->setPromptTitle('Elegir opción')
                ->setPrompt('Por favor, elige una opción de la lista')
                ->setFormula1('"'.implode(',', $arrSubcategories).'"');
            }
            $sheetProduct->setCellValue('A2', $nextId);
            $sheetImg->setCellValue('A2', $nextId);
            
            foreach (range('A','Z') as $col) {
                $sheetProduct->getColumnDimension($col)->setAutoSize(true); 
                $sheetVariant->getColumnDimension($col)->setAutoSize(true); 
                $sheetImg->getColumnDimension($col)->setAutoSize(true); 
                $sheetSpc->getColumnDimension($col)->setAutoSize(true); 
            }
            $sheetProduct->getColumnDimension("A:P")->setAutoSize(true);
            $sheetVariant->setCellValue('A1', 'Hello World !');
            $writer = new Xlsx($spreadsheet);
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            die();
        }
    }

?>