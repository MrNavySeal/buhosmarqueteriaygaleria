<?php
    require 'Libraries/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
    use PhpOffice\PhpSpreadsheet\IOFactory;
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
                $data['panelapp'] = "functions_products_mass.js";
                $this->views->getView($this,"productos",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function plantilla(){
            //Set default config
            $rowCount = 2;
            $lastRowSheet = 100;

            $nextId = $this->model->selectNextId();
            $categories = $this->model->selectCategories();
            
            $fileName = "plantilla";
            //Dropdowns 
            $arrBool = array("Si","No");
            $arrImport = array(0,19);
            $arrStatus = array("activo","inactivo");
            $arrMeasures = $this->model->selectMeasures();
            $arrCategories = $categories['categories'];
            $arrSubcategories = $categories['subcategories'];

            //Headers
            $headProduct = array(
                "id_producto",
                "nombre",
                "sku",
                "descripcion_corta",
                "descripcion",
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
                "estado",
                "categoría"
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

            $colSub = 'S';
            $totalCat = count($arrCategories);
            for ($i=0; $i < $totalCat ; $i++) { 
                $sheetProduct->setCellValue($colSub.'1', "subcategorias_de_".$arrCategories[$i]);
                $subcategories = $arrSubcategories[$i][$arrCategories[$i]];
                for ($j=$rowCount; $j < $lastRowSheet ; $j++) { 
                    $validation = $sheetProduct->getCell($colSub.$j)->getDataValidation();
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
                    ->setFormula1('"'.implode(',', $subcategories).'"');
                }
                $colSub++;
            }

            //Set headers in sheetImg
            $sheetImg->setCellValue('A1', $headImg[0]);
            $sheetImg->setCellValue('B1', $headImg[1]);

            
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
                ->setFormula1('"'.implode(',', $arrMeasures).'"');

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
                ->setFormula1('"'.implode(',', $arrBool).'"');

                $validation = $sheetProduct->getCell("M$i")->getDataValidation();
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
                $validation = $sheetProduct->getCell("Q$i")->getDataValidation();
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
                ->setFormula1('"'.implode(',', $arrBool).'"');
                
                $validation = $sheetProduct->getCell("R$i")->getDataValidation();
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
        
        public function uploadProducts(){
            if($_SESSION['permitsModule']['r']){
                if($_FILES){
                    $template = $_FILES['template'];
                    $extension = explode(".",$template['name'])[1];
                    if($extension != "xlsx"){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos.");
                    }else{
                        $reader = IOFactory::createReader(ucwords($extension));
                        $spreadsheet = $reader->load($template['tmp_name']);
                        $sheetProduct = $spreadsheet->getSheetByName("productos");
                        $sheetImg = $spreadsheet->getSheetByName("imagenes");
                        $arrData = [];
                        $arrProducts = [];
                        $arrImages = [];
                        $index = 2;
                        $cont = 1;
                        $colSub = 'S';
                        $categories = $this->model->selectCategories()['categories'];
                        $totalCat = count($categories);
                        //product upload;
                        while ($data=$sheetProduct->getCell("A$index")->getValue() !="") {
                            $strName = ucwords(strClean($sheetProduct->getCell("B$index")->getValue()));
                            $strReference = strtoupper(strClean($sheetProduct->getCell("C$index")->getValue()));
                            $reference = $strReference != "" ? $strReference."-" : "";
                            $route = clear_cadena($reference.$strName);
                            $route = strtolower(str_replace("¿","",$route));
                            $route = str_replace(" ","-",$route);
                            $route = str_replace("?","",$route);
                            $subcategory = "";
                            for ($i=0; $i < $totalCat; $i++) { 
                                if($sheetProduct->getCell($colSub.$index)->getValue() !=""){
                                    $subcategory = $sheetProduct->getCell($colSub.$index)->getValue();
                                    break;
                                }
                            }
                            $product = array(
                                "id"=>intval($sheetProduct->getCell("A$index")->getValue()),
                                "status"=>strClean($sheetProduct->getCell("Q$index")->getValue()),
                                "subcategory"=>strClean($subcategory),
                                "category"=>strClean($sheetProduct->getCell("R$index")->getValue()),
                                "measure"=>strClean($sheetProduct->getCell("I$index")->getValue()),
                                "import"=>strClean($sheetProduct->getCell("M$index")->getValue()),
                                "is_product"=>strClean($sheetProduct->getCell("F$index")->getValue()),
                                "is_ingredient"=>strClean($sheetProduct->getCell("G$index")->getValue()),
                                "is_combo"=>strClean($sheetProduct->getCell("H$index")->getValue()),
                                "is_stock"=>strClean($sheetProduct->getCell("J$index")->getValue()),
                                "price_purchase"=>intval($sheetProduct->getCell("N$index")->getValue()),
                                "price_sell"=>intval($sheetProduct->getCell("O$index")->getValue()),
                                "price_offer"=>intval($sheetProduct->getCell("P$index")->getValue()),
                                "stock"=>intval($sheetProduct->getCell("K$index")->getValue()),
                                "min_stock"=>intval($sheetProduct->getCell("L$index")->getValue()),
                                "short_description"=>strClean($sheetProduct->getCell("D$index")->getValue()),
                                "description"=>strClean($sheetProduct->getCell("E$index")->getValue()),
                                "name"=>$strName,
                                "reference"=>$strReference,
                                "route"=>$route,
                            );
                            array_push($arrProducts,$product);
                            //$_SESSION['progress'] = (($cont)/count($arrProducts))*100;
                            $index ++;
                            $cont++;
                        }
                        $index = 2;
                        while ($sheetImg->getCell("A$index")->getValue() !=""){
                            $img = array(
                                "id"=>$sheetImg->getCell("A$index")->getValue(),
                                "name"=>$sheetImg->getCell("B$index")->getValue()
                            );
                            array_push($arrImages,$img);
                            $index++;
                        }
                        $arrData = array("products"=>$arrProducts,"images"=>$arrImages);
                        dep($arrData);
                        $request = $this->setProducts($arrData);
                        $arrResponse = array();
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setProducts($data){
            $images = $data['images'];
            $products = $data['products'];
            foreach ($products as $product ) {
                $product['status'] = $product['status'] == "activo" ? 1 : 2;
                $this->model->insertProduct($product);
                foreach ($images as $img) {
                    $img_decode = base64_decode($img['name']);
                    $name = "product_".bin2hex(random_bytes(6)).'.png';
                    $route = "Assets/images/uploads/".$name;
                    file_put_contents($route, $img_decode);
                    $this->model->insertImages($img['id'],$name);
                }
            }
        }
        public function getProgress(){
            if(isset($_SESSION['progress'])){
                echo $_SESSION['progress'];
            }
        }
    }

?>