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
            $lastRowSheet = 200;

            $nextId = $this->model->selectNextId();
            $categories = $this->model->selectCategories();
            
            $fileName = "plantilla";
            //Dropdowns 
            $arrBool = array("Si","No");
            $arrImport = array(0,19);
            $arrMeasures = $this->model->selectMeasures();
            $arrSpecs = $this->model->selectSpecs();
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
            $headVariants = array("producto_id","sku","precio_compra","precio_venta","precio_oferta","stock","stock_minimo");
            $headSpecs = array("producto_id","característica","valor");
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
            //Set headers in sheetImg
            $sheetImg->setCellValue('A1', $headImg[0]);
            $sheetImg->setCellValue('B1', $headImg[1]);
            //Set headers in sheetVariant
            $sheetVariant->setCellValue('A1', $headVariants[0]);
            $sheetVariant->setCellValue('B1', $headVariants[1]);
            $sheetVariant->setCellValue('C1', $headVariants[2]);
            $sheetVariant->setCellValue('D1', $headVariants[3]);
            $sheetVariant->setCellValue('E1', $headVariants[4]);
            $sheetVariant->setCellValue('F1', $headVariants[5]);
            $sheetVariant->setCellValue('G1', $headVariants[6]);
            //Set headers in sheetSpecs
            $sheetSpc->setCellValue('A1', $headSpecs[0]);
            $sheetSpc->setCellValue('B1', $headSpecs[1]);
            $sheetSpc->setCellValue('C1', $headSpecs[2]);

            //Set idproduct
            $sheetProduct->setCellValue('A2', $nextId);
            $sheetImg->setCellValue('A2', $nextId);
            $sheetVariant->setCellValue('A2', $nextId);
            $sheetSpc->setCellValue('A2', $nextId);

            //Set categories
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
            //Set general combobox
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
                ->setFormula1('"'.implode(',', $arrBool).'"');
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
                //Categories
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

                $validation = $sheetSpc->getCell("B$i")->getDataValidation();
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
                ->setFormula1('"'.implode(',', $arrSpecs).'"');

            }
            //Set variants
            $variants = $this->model->selectVariants();
            $totalVar = count($variants);
            $colSub = 'H';
            for ($i=0; $i < $totalVar ; $i++) { 
                $sheetVariant->setCellValue($colSub.'1',$variants[$i]['name']);
                $options = $variants[$i]['options'];
                $implode = implode(',', $options);
                for ($j=$rowCount; $j < $lastRowSheet ; $j++) { 
                    $validation = $sheetVariant->getCell($colSub.$j)->getDataValidation();
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
                    ->setFormula1('"'.$implode.'"');
                }
                $colSub++;
            }
            foreach (range('A','Z') as $col) {
                $sheetProduct->getColumnDimension($col)->setAutoSize(true); 
                $sheetVariant->getColumnDimension($col)->setAutoSize(true); 
                $sheetImg->getColumnDimension($col)->setAutoSize(true); 
                $sheetSpc->getColumnDimension($col)->setAutoSize(true); 
            }
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
                        $sheetVariant = $spreadsheet->getSheetByName("variantes");
                        $sheetSpc = $spreadsheet->getSheetByName("caracteristicas");
                        $arrProducts = [];
                        $index = 2;
                        $colSub = 'S';
                        $categories = $this->model->selectCategories()['categories'];
                        $totalCat = count($categories);
                        //product read;
                        while ($sheetProduct->getCell("A$index")->getValue() !="") {
                            $strName = ucwords(strClean($sheetProduct->getCell("B$index")->getValue()));
                            $strReference = strtoupper(strClean($sheetProduct->getCell("C$index")->getValue()));
                            $reference = $strReference != "" ? $strReference."-" : "";
                            $route = clear_cadena($reference.$strName);
                            $route = strtolower(str_replace("¿","",$route));
                            $route = str_replace(" ","-",$route);
                            $route = str_replace("?","",$route);
                            $subcategory = "";
                            $arrImages = [];
                            $arrSpecs = [];
                            for ($i=0; $i < $totalCat; $i++) { 
                                if($sheetProduct->getCell($colSub.$index)->getValue() !=""){
                                    $subcategory = $sheetProduct->getCell($colSub.$index)->getValue();
                                    break;
                                }
                                $colSub++;
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
                                "variants"=>array(),
                                "specs"=>array()
                            );
                            $idCategory = $this->model->selectCategoryId($product['category']);
                            $idSubcategory = $this->model->selectSubcategoryId($idCategory,$subcategory);
                            $product['measure'] = $this->model->selectMeasure($product['measure']);
                            $product['category'] = $idCategory;
                            $product['subcategory'] = $idSubcategory;
                            $product['is_product'] = $product['is_product'] =="Si" ? 1 : 0;
                            $product['is_ingredient'] = $product['is_ingredient'] =="Si" ? 1 : 0;
                            $product['is_combo'] = $product['is_combo'] =="Si" ? 1 : 0;
                            $product['is_stock'] = $product['is_stock'] =="Si" ? 1 : 0;
                            $product['status'] = $product['status'] =="Si" ? 1 : 2;
                            $product['description'] = '<p>'.$product['description'].'</p>';
                            //img read;
                            $indexImg = 2;
                            while ($sheetImg->getCell("A$indexImg")->getValue() !=""){
                                $idProduct = $sheetImg->getCell("A$indexImg")->getValue();
                                if($product['id'] == $idProduct){
                                    array_push($arrImages,$sheetImg->getCell("B$indexImg")->getValue());
                                }
                                $indexImg++;
                            }
                            //Read specs
                            $indexSpec = 2;
                            while ($sheetSpc->getCell("A$indexSpec")->getValue() !=""){
                                $idProduct = $sheetSpc->getCell("A$indexSpec")->getValue();
                                if($product['id'] == $idProduct){
                                    $arrSpec = explode("_",$sheetSpc->getCell("B$indexSpec")->getValue());
                                    array_push($arrSpecs,
                                        array(
                                            "id"=>$arrSpec[0],
                                            "value"=>$sheetSpc->getCell("C$indexSpec")->getValue()
                                        )
                                    );
                                }
                                $indexSpec++;
                            }
                            //Read variants
                            $variants = $this->model->selectVariants();
                            if(!empty($variants)){
                                $totalVar = count($variants);
                                $arrVariants = array();
                                $arrCombinations = array();
                                $arrOptions = array();
                                $indexVar = 2;
                                while ($sheetVariant->getCell("A$indexVar")->getValue() !="") {
                                    $idProduct = intval($sheetVariant->getCell("A$indexVar")->getValue());
                                    if($product['id'] == $idProduct){
                                        $combination =array();
                                        $col = "H";
                                        for ($i=0; $i < $totalVar; $i++) { 
                                            if($sheetVariant->getCell($col.$indexVar)->getValue() !=""){
                                                $value = $sheetVariant->getCell($col.$indexVar)->getValue();
                                                $arrValue = explode("_",$value);
                                                $option = $arrValue[1];
                                                $variant = $arrValue[0];
            
                                                if(!in_array($variant,$arrVariants)){
                                                    array_push($arrVariants,$variant);
                                                }
                                                if(!in_array($option,$arrOptions)){
                                                    array_push($arrOptions,$option);
                                                }
                                                
                                                //array_push($variant,$arrValue[0]);
                                                array_push($combination,$option);
                                            }
                                            $col++;
                                        }
                                        array_push($arrCombinations,
                                            array(
                                                "id"=>$idProduct,
                                                "name"=>implode("-",$combination),
                                                "sku"=>intval($sheetVariant->getCell("B$indexVar")->getValue()),
                                                "price_purchase"=>intval($sheetVariant->getCell("C$indexVar")->getValue()),
                                                "price_sell"=>intval($sheetVariant->getCell("D$indexVar")->getValue()),
                                                "price_offer"=>intval($sheetVariant->getCell("E$indexVar")->getValue()),
                                                "stock"=>intval($sheetVariant->getCell("F$indexVar")->getValue()),
                                                "min_stock"=>intval($sheetVariant->getCell("G$indexVar")->getValue()),
                                            )
                                        );
                                    }
                                    $indexVar++;
                                }
                                if(!empty($arrCombinations)){
                                    $product['variants'] = array(
                                        "combinations"=>$arrCombinations,
                                        "variations"=>$this->model->orderVariants($arrVariants,$arrOptions)
                                    );
                                }
                            }
                            $product['images'] = $arrImages;
                            $product['specs'] = $arrSpecs;
                            $product['product_type'] = !empty($product['variants']) ? 1 : 0;
                            array_push($arrProducts,$product);
                            $index ++;
                        }
                        $this->setProducts($arrProducts);
                        $arrResponse = array("status"=>true,"msg"=>"Productos cargados correctamente.");
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
        public function setProducts($data){
            $total = count($data);
            for ($i=0; $i < $total; $i++) {
                $images = $data[$i]['images'];
                $totalImg = count($images); 
                for ($j=0; $j < $totalImg; $j++) { 
                    $img = file_get_contents($images[$j]);
                    $name = "product_".bin2hex(random_bytes(6)).'.png';
                    $route = "Assets/images/uploads/".$name;
                    $data[$i]['images'][$j] = $name;
                    file_put_contents($route, $img);
                }
                $this->model->insertProduct($data[$i]);
            }
        }
    }

?>