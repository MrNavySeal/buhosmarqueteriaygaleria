<?php
    require_once("Libraries/Core/Mysql.php");
    trait ProductTrait{
        private $con;
        private $intIdProduct;

        public function getProductsByCat($id,$cant=""){
            $this->con=new Mysql();
            if($cant !=""){
                $cant = " LIMIT $cant";
            }
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.is_stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.categoryid = $id AND p.status = 1
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            ORDER BY RAND() $cant";
            
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $request[$i]['price'] = round((($request[$i]['price']*COMISION)+TASA)/1000)*1000;
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;

                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->con->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT price_sell,price_offer, name, stock
                        FROM product_variations_options WHERE product_id =$idProduct AND status = 1 
                        AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$idProduct AND status = 1)";
                        $requestPrices = $this->con->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct";
                        $request[$i]['price'] = $requestPrices['price_sell'];
                        $request[$i]['discount'] = $requestPrices['price_offer'];
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['total'];
                    }
                }
            }
            return $request;
        }
        public function getProductsT($cant=""){
            $this->con=new Mysql();
            if($cant !=""){
                $cant = " LIMIT $cant";
            }
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1 
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            ORDER BY p.idproduct DESC $cant";
            
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;
                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->con->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT price_sell,price_offer, name, stock
                        FROM product_variations_options WHERE product_id =$idProduct AND status = 1 
                        AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$idProduct AND status = 1)";
                        $requestPrices = $this->con->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct AND status = 1";
                        $request[$i]['price'] = $requestPrices['price_sell'];
                        $request[$i]['discount'] = $requestPrices['price_offer'];
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['total'];
                    }
                }
            }
            return $request;
        }
        public function selectProductVariant($id,$variant){
            $this->con=new Mysql();
            $this->intIdProduct = $id;
            $sqlV = "SELECT * FROM product_variations_options WHERE name = '$variant' AND product_id = $this->intIdProduct";
            $request = $this->con->select($sqlV);
            $request['is_stock'] = $this->con->select("SELECT is_stock FROM product WHERE idproduct = $this->intIdProduct")['is_stock'];
            return $request;
        }
        public function getProductsPageT(int $pageNow, int $sort){
            $this->con=new Mysql();
            $perPage = PERPAGE;
            $option ="ORDER BY p.idproduct DESC";
            if($sort == 2){
                $option = "ORDER BY p.price DESC";
            }else if($sort == 3){
                $option = "ORDER BY p.price ASC";
            }

            $totalProducts =$this->con->select("SELECT COUNT(*) AS total FROM product WHERE status = 1")['total'];
            $totalPages = ceil($totalProducts/$perPage);
            $start = ($pageNow - 1) * $perPage;
            $start = $start < 0 ? 0 : $start;
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            $option LIMIT $start,$perPage
            ";
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;
                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->con->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT price_sell,price_offer, name, stock
                        FROM product_variations_options WHERE product_id =$idProduct AND status = 1 
                        AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$idProduct AND status = 1)";
                        $requestPrices = $this->con->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct AND status = 1";
                        $request[$i]['price'] = $requestPrices['price_sell'];
                        $request[$i]['discount'] = $requestPrices['price_offer'];
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['total'];
                    }
                }
            }
            $array = array("productos"=>$request,"paginas"=>$totalPages);
            //dep($request);exit;
            return $array;

        }
        public function getProductsSearchT(int $pageNow, int $sort, string $search){
            $this->con=new Mysql();
            $perPage = PERPAGE;
            $option ="ORDER BY p.idproduct DESC";
            if($sort == 2){
                $option = "ORDER BY p.price DESC";
            }else if($sort == 3){
                $option = "ORDER BY p.price ASC";
            }
            $sqlTotal = "SELECT COUNT(*) AS total
                        FROM product p 
                        INNER JOIN category c, subcategory s
                        WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid 
                        AND p.subcategoryid = s.idsubcategory AND p.status = 1 
                        AND (p.name LIKE '%$search%' || c.name LIKE '%$search%' || s.name LIKE '%$search%' || p.reference LIKE '%$search%')";
                        
            $totalProducts =$this->con->select($sqlTotal)['total'];
            $totalPages = ceil($totalProducts/$perPage);
            $start = ($pageNow - 1) * $perPage;
            $start = $start < 0 ? 0 : $start;
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid 
            AND p.subcategoryid = s.idsubcategory AND p.status = 1 
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            AND (p.reference LIKE '%$search%' || p.name LIKE '%$search%' || c.name LIKE '%$search%' || s.name LIKE '%$search%')
            $option LIMIT $start,$perPage
            ";
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;
                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->con->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT price_sell,price_offer, name, stock
                        FROM product_variations_options WHERE product_id =$idProduct AND status = 1 
                        AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$idProduct AND status = 1)";
                        $requestPrices = $this->con->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct AND status = 1";
                        $request[$i]['price'] = $requestPrices['price_sell'];
                        $request[$i]['discount'] = $requestPrices['price_offer'];
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['total'];
                    }
                }
            }
            if(empty($request)){
                $totalPages = 1;
            }
            $array = array("productos"=>$request,"paginas"=>$totalPages,"total"=>$totalProducts,"buscar"=>$search);
            //dep($request);exit;
            return $array;

        }
        public function getProductsRelT($idProduct,$idCategory,$idSubcategory,$cant){
            $idProduct = $idProduct;
            $idCategory = $idCategory;
            $idSubcategory = $idSubcategory;
            $this->con=new Mysql();
            if($cant !=""){
                $cant = " LIMIT $cant";
            }
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid 
            AND p.subcategoryid = s.idsubcategory 
            AND p.status = 1 AND p.categoryid = $idCategory AND p.idproduct != $idProduct
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            ORDER BY RAND() $cant
            ";
            $sqlS = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid 
            AND p.subcategoryid = s.idsubcategory 
            AND p.status = 1 AND p.subcategoryid = $idSubcategory AND p.idproduct != $idProduct
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            ORDER BY RAND() $cant
            ";
            $request = $this->con->select_all($sqlS);
            if(count($request) < 4){
                $request = $this->con->select_all($sql);
            }
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $request[$i]['price'] = round((($request[$i]['price']*COMISION)+TASA)/1000)*1000;
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;
                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->con->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT price_sell,price_offer, name, stock
                        FROM product_variations_options WHERE product_id =$idProduct AND status = 1 
                        AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$idProduct AND status = 1)";
                        $requestPrices = $this->con->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct AND status = 1";
                        $request[$i]['price'] = $requestPrices['price_sell'];
                        $request[$i]['discount'] = $requestPrices['price_offer'];
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['total'];
                    }
                }
            }
            //dep($request);exit;
            return $request;
        }
        public function getProductsCategoryT(array $arrParams,int $pageNow,int $sort){
            $category = $arrParams[0];
            $subcategory = count($arrParams)>1 ? $arrParams[1] : "";
            $routes ="AND c.route = '$category'";
            if($subcategory !="")$routes="AND (c.route = '$category' AND s.route = '$subcategory')";
            $this->con=new Mysql();
            $perPage = PERPAGE;
            $option ="ORDER BY p.idproduct DESC";
            if($sort == 2){
                $option = "ORDER BY p.price DESC";
            }else if($sort == 3){
                $option = "ORDER BY p.price ASC";
            }
            $sqlTotal = "SELECT COUNT(p.idproduct) AS total 
            FROM product p 
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory 
            AND p.status = 1 $routes";
             
            $totalProducts =$this->con->select($sqlTotal)['total'];
            $totalPages = ceil($totalProducts/$perPage);
            $start = ($pageNow - 1) * $perPage;
            $start = $start < 0 ? 0 : $start;

            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.description,
                p.price,
                p.discount,
                p.description,
                p.stock,
                p.status,
                p.product_type,
                p.route,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory,
                c.route as routec,
                s.route as routes,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1
            AND (p.is_product = 1 OR p.is_combo = 1)  AND c.status = 1 AND s.status = 1
            $routes $option 
            LIMIT $start,$perPage";
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $request[$i]['price'] = round((($request[$i]['price']*COMISION)+TASA)/1000)*1000;
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;
                    if(isset($_SESSION['login'])){
                        $idUser = $_SESSION['idUser'];
                        $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
                        $requestFavorite = $this->con->select($sqlFavorite);
                        if(!empty($requestFavorite)){
                            $request[$i]['favorite'] = $requestFavorite['status'];
                        }
                    }
                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }
                    if($request[$i]['product_type'] == 1){
                        $sqlV = "SELECT price_sell,price_offer, name, stock
                        FROM product_variations_options WHERE product_id =$idProduct AND status = 1 
                        AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$idProduct AND status = 1)";
                        $requestPrices = $this->con->select($sqlV);
                        $sqlTotal = "SELECT SUM(stock) AS total FROM product_variations_options WHERE product_id =$idProduct AND status = 1";
                        $request[$i]['price'] = $requestPrices['price_sell'];
                        $request[$i]['discount'] = $requestPrices['price_offer'];
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['total'];
                    }
                }
            }
            $array = array("productos"=>$request,"paginas"=>$totalPages);
            return $array;
        }
        public function getProductsFavorites($id){
            $this->con=new Mysql();
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.price,
                p.discount,
                p.stock,
                p.product_type,
                p.status,
                p.route,
                c.idcategory,
                c.name as category,
                c.route as routec,
                s.idsubcategory,
                s.categoryid,
                s.name as subcategory
            FROM product p
            INNER JOIN category c, subcategory s, wishlist w
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1
            AND c.status = 1 AND s.status = 1 AND
            AND p.idproduct = w.productid AND w.personid = $id AND w.status = 1";
            $request = $this->con->select_all($sql);
            if(count($request)> 0){
                for ($i=0; $i < count($request); $i++) { 
                    $idProduct = $request[$i]['idproduct'];
                    $request[$i]['price'] = round((($request[$i]['price']*COMISION)+TASA)/1000)*1000;
                    $sqlImg = "SELECT * FROM productimage WHERE productid = $idProduct";
                    $requestImg = $this->con->select_all($sqlImg);
                    $request[$i]['favorite'] = 0;

                    if(count($requestImg)>0){
                        $request[$i]['url'] = media()."/images/uploads/".$requestImg[0]['name'];
                        $request[$i]['image'] = $requestImg[0]['name'];
                    }else{
                        $request[$i]['image'] = media()."/images/uploads/image.png";
                    }

                    if($request[$i]['product_type'] == 2){
                        $sqlV = "SELECT MIN(price) AS minimo FROM product_variant WHERE productid =$idProduct AND stock > 0";
                        $sqlTotal = "SELECT SUM(stock) AS stock FROM product_variant WHERE productid =$idProduct AND stock > 0";
                        $request[$i]['price'] = round((($this->con->select($sqlV)['minimo']*COMISION)+TASA)/1000)*1000;
                        $request[$i]['stock'] = $this->con->select($sqlTotal)['stock'];
                    }
                }
            }
            return $request;
        }
        public function getProductT(int $idProduct,$variant){
            $this->con=new Mysql();
            $this->intIdProduct = $idProduct;
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.shortdescription,
                p.description,
                p.price,
                p.discount,
                p.stock,
                p.status,
                p.route,
                p.product_type,
                p.framing_mode,
                p.specifications,
                p.framing_img,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                c.route as routec,
                s.route as routes,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory 
            AND p.idproduct = $this->intIdProduct AND p.status = 1
            AND c.status = 1 AND s.status = 1";
            $request = $this->con->select($sql);
            if(!empty($request)){
                $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
                $requestImg = $this->con->select_all($sqlImg);
                $sqlRate = "SELECT AVG(rate) as rate, COUNT(rate) as total FROM productrate 
                WHERE productid = $this->intIdProduct AND status = 1 HAVING rate IS NOT NULL";
                $requestRate =  $this->con->select($sqlRate);
                $request['price'] = $request['discount'] > 0 ? $request['discount'] : $request['price'];
                $request['is_stock'] = boolval($request['is_stock']);
                if(!empty($requestRate)){
                    $request['rate'] = number_format($requestRate['rate'],1);
                    $request['reviews'] = $requestRate['total'];
                }else{
                    $request['rate'] = number_format(0,1);
                    $request['reviews'] = 0;
                }
                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = array("url"=>media()."/images/uploads/".$requestImg[$i]['name'],"name"=>$requestImg[$i]['name']);
                    }
                }
                if($request['product_type'] == 1){
                    $sqlV = "SELECT name,price_sell,price_offer,stock,sku 
                    FROM product_variations_options WHERE name = '$variant' AND product_id = $this->intIdProduct";
                    $sqlVar = "SELECT variation FROM product_variations WHERE product_id = $this->intIdProduct";
                    $request['combination'] = $this->con->select($sqlV);
                    $request['variants'] = json_decode($this->con->select($sqlVar)['variation'],true);
                    $request['price'] = $request['combination']['price_offer'] > 0 ? $request['combination']['price_offer'] : $request['combination']['price_sell'];
                    $request['stock'] = $request['combination']['stock'];
                }
            }
            return $request;
        }
        public function getProductPageT(string $route){
            $this->con=new Mysql();
            $sql = "SELECT 
                p.idproduct,
                p.categoryid,
                p.subcategoryid,
                p.reference,
                p.name,
                p.shortdescription,
                p.description,
                p.price,
                p.discount,
                p.stock,
                p.status,
                p.route,
                p.product_type,
                p.framing_mode,
                p.specifications,
                p.framing_img,
                c.idcategory,
                c.name as category,
                s.idsubcategory,
                c.route as routec,
                s.route as routes,
                s.categoryid,
                s.name as subcategory,
                DATE_FORMAT(p.date, '%d/%m/%Y') as date,
                p.is_stock
            FROM product p
            INNER JOIN category c, subcategory s
            WHERE c.idcategory = p.categoryid AND c.idcategory = s.categoryid AND p.subcategoryid = s.idsubcategory AND p.status = 1
            AND c.status = 1 AND s.status = 1
            AND p.route = '$route'";
            $request = $this->con->select($sql);
            $this->intIdProduct = $request['idproduct'];
            if(!empty($request)){
                $sqlImg = "SELECT * FROM productimage WHERE productid = $this->intIdProduct";
                $requestImg = $this->con->select_all($sqlImg);
                $sqlRate = "SELECT AVG(rate) as rate, COUNT(rate) as total FROM productrate WHERE productid = $this->intIdProduct AND status = 1 HAVING rate IS NOT NULL";
                $requestRate =  $this->con->select($sqlRate);
                $request['price'] = round((($request['price']*COMISION)+TASA)/1000)*1000;
                $request['favorite'] = 0;
                if(isset($_SESSION['login'])){
                    $idUser = $_SESSION['idUser'];
                    $sqlFavorite = "SELECT * FROM wishlist WHERE productid = $this->intIdProduct AND personid = $idUser";
                    $requestFavorite = $this->con->select($sqlFavorite);
                    if(!empty($requestFavorite)){
                        $request['favorite'] = $requestFavorite['status'];
                    }
                }
                if(!empty($requestRate)){
                    $request['rate'] = number_format($requestRate['rate'],1);
                    $request['reviews'] = $requestRate['total'];
                }else{
                    $request['rate'] = number_format(0,1);
                    $request['reviews'] = 0;
                }
                if(count($requestImg)){
                    for ($i=0; $i < count($requestImg); $i++) { 
                        $request['image'][$i] = array("url"=>media()."/images/uploads/".$requestImg[$i]['name'],"name"=>$requestImg[$i]['name']);
                    }
                }
                if($request['product_type'] == 1){
                    $sqlVariants = "SELECT variation FROM product_variations WHERE product_id = $this->intIdProduct";
                    $sqlV = "SELECT price_sell,price_offer, name, stock
                    FROM product_variations_options WHERE product_id =$this->intIdProduct AND status = 1 
                    AND price_sell = (select min(price_sell) from product_variations_options WHERE product_id =$this->intIdProduct AND status = 1)";
                    $requestPrices = $this->con->select($sqlV);
                    $request['price'] = $requestPrices['price_sell'];
                    $request['discount'] = $requestPrices['price_offer'];
                    $request['stock'] = $requestPrices['stock'];
                    $request['variation'] = json_decode($this->con->select($sqlVariants)['variation'],true);;
                    $request['default'] = explode("-",$requestPrices['name']);
                }
            }
            return $request;
        }
        public function setReviewT($idProduct,$idUser,$strReview,$intRate){
            $this->con = new Mysql();
            $this->intIdProduct = $idProduct;
            $this->intIdPerson = $idUser;
            $this->strReview = $strReview;
            $this->intRate = $intRate;
            $return="";
            $sql = "SELECT * FROM productrate WHERE productid = $this->intIdProduct AND personid = $this->intIdPerson";
            $request = $this->con->select_all($sql);
            if(empty($request)){
                $sql = "INSERT INTO productrate(productid,personid,description,rate) VALUES(?,?,?,?)";
                $arrData = array($this->intIdProduct,$this->intIdPerson,$this->strReview,$this->intRate);
                $request = $this->con->insert($sql,$arrData);
                $return = $request;
            }else{
                $return = "exists";
            }
            return $return;
        }
        public function getRate($id){
            $this->con = new Mysql();
            $sql = "SELECT 
            AVG(rate) as rate, 
            COUNT(rate) as total,
            sum(case when rate = 5 then 1 else 0 end) AS five,
            sum(case when rate = 4 then 1 else 0 end) AS four, 
            sum(case when rate = 3 then 1 else 0 end) AS three, 
            sum(case when rate = 2 then 1 else 0 end) AS two, 
            sum(case when rate = 1 then 1 else 0 end) AS one
            FROM productrate WHERE productid=$id";
            $request = $this->con->select($sql);

            if($request['rate']==null){
                $request['five'] = 0;
                $request['four'] = 0;
                $request['three'] = 0;
                $request['two'] = 0;
                $request['one'] = 0;
                $request['rate']=0;
            }
            return $request;
        }
        public function getReviewsT($id){
            $this->con = new Mysql();
            $this->intIdProduct = $id;
            $sql = "SELECT 
                    r.id,
                    r.personid,
                    r.description,
                    r.rate,
                    p.idperson,
                    p.image,
                    p.firstname,
                    p.lastname,
                    DATE_FORMAT(r.date, '%d/%m/%Y') as date,
                    DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated
                    FROM productrate r
                    INNER JOIN person p, product pr
                    WHERE p.idperson = r.personid AND pr.idproduct = r.productid AND r.status = 1 AND pr.idproduct = $this->intIdProduct";
            $request = $this->con->select_all($sql);
            //dep($request);exit;
            return $request;
        }
        public function getReviewsSortT(int $idProduct,int $sort){
            $this->con = new Mysql();
            $this->intIdProduct = $idProduct;
            $option=" ORDER BY r.rate DESC";
            if($sort == 1){
                $option = " ORDER BY r.id DESC"; 
            }else if($sort == 3){
                $option=" ORDER BY r.rate ASC";
            }
            $sql = "SELECT 
                r.id,
                r.personid,
                r.description,
                r.rate,
                p.idperson,
                p.image,
                p.firstname,
                p.lastname,
                pr.name,
                r.status,
                DATE_FORMAT(r.date, '%d/%m/%Y') as date,
                DATE_FORMAT(r.date_updated, '%d/%m/%Y') as dateupdated
                FROM productrate r
                INNER JOIN person p, product pr
                WHERE p.idperson = r.personid AND pr.idproduct = r.productid AND r.productid = $this->intIdProduct $option";
            $request = $this->con->select_all($sql);
            //dep($sql);
            return $request;
        }
        public function addWishListT($idProduct,$idUser){
            $this->con = new Mysql();
            $sql = "SELECT * FROM wishlist WHERE productid = $idProduct AND personid = $idUser";
            $request = $this->con->select_all($sql);
            $return ="";
            if(empty($request)){
                $sql = "INSERT INTO wishlist (productid,personid,status) VALUE(?,?,?)";
                $array = array($idProduct,$idUser,1);
                $request = $this->con->insert($sql,$array);
                $return =$request;
            }else{
                $return ="exists";
            }
            return $return;
        }
        public function delWishListT($idProduct,$idUser){
            $this->con = new Mysql();
            $sql = "DELETE FROM wishlist WHERE productid=$idProduct AND personid = $idUser";
            $request = $this->con->delete($sql);
            return $request;
        }
        
    }
    
?>