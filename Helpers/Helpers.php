<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require_once('Libraries/PHPMailer/Exception.php');
    require_once('Libraries/PHPMailer/PHPMailer.php');
    require_once('Libraries/PHPMailer/SMTP.php');
    require_once "Helpers/Pagination.php";

    function getCompanyInfo(){
        require_once('Models/Configuracion/EmpresaModel.php');
        $con = new EmpresaModel();
        $data = $con->selectCompany();
        $data['password'] = openssl_encrypt($data['password'],METHOD,KEY);
        return $data;
    }
    function navSubLinks(){
        require_once("Models/EnmarcarTrait.php");
        require_once("Models/PaginaTrait.php");
        require_once("Models/CategoryTrait.php");
        class SubLink{
            use EnmarcarTrait,PaginaTrait,CategoryTrait;
            public function getInfo(){
                $services = $this->selectServices();
                $categories = $this->getCategoriesT();
                $framing = $this->selectTipos();
                
                return array("services"=>$services,"categories"=>$categories,"framing"=>$framing);
            }
        }
        $obj = new SubLink();
        return $arrData = $obj->getInfo();
    }
    function getCredentials(){
        require_once('Models/Configuracion/EmpresaModel.php');
        $con = new EmpresaModel();
        $data = $con->selectCredentials();
        return $data;
    }
    function getSocialMedia(){
        require_once('Models/Configuracion/EmpresaModel.php');
        $con = new EmpresaModel();
        $request = $con->selectSocial();
        $arrSocial = array(
            array("name"=>"facebook",
                "link"=>$request['facebook']
            ),
            array("name"=>"twitter",
                "link"=>$request['twitter']
            ),
            array("name"=>"youtube",
                "link"=>$request['youtube']
            ),
            array("name"=>"instagram",
                "link"=>$request['instagram']
            ),
            array("name"=>"linkedin",
                "link"=>$request['linkedin']
            ),
            array("name"=>"whatsapp",
                "link"=>str_replace("+","",$request['whatsapp'])
            ),
        );
        return $arrSocial;
    }
    function base_url(){
        return BASE_URL;
    }
    function media(){
        return BASE_URL."/Assets";
    }
    function headerPage($data=""){

        $view_header="Views/Template/header_page.php";
        require_once ($view_header);
    }
    function footerPage($data=""){

        $view_footer="Views/Template/footer_page.php";
        require_once ($view_footer);
    }
    function headerAdmin($data=""){

        $view_header="Views/Template/header_admin.php";
        require_once ($view_header);
    }
    function footerAdmin($data=""){

        $view_footer="Views/Template/footer_admin.php";
        require_once ($view_footer);
    }
    function getModal(string $nameModal, $data=null){
    
        $view_modal = "Views/Template/Modal/{$nameModal}.php";
        require_once $view_modal;        
    }
    function dep($data){
    
        $format  = print_r('<pre>');
        $format .= print_r($data);
        $format .= print_r('</pre>');
        return $format;
    }
    function formatNum(int $num,$divisa=true){
        $companyData = getCompanyInfo();
        if($divisa){
            $code = $companyData['currency']['code'];
        }else{
            $code="";
        }
        $num = $companyData['currency']['symbol'].number_format($num,0,DEC,MIL);
        return $num;
    }
    function emailNotification(){
        require_once("Models/Configuracion/AdministracionModel.php");
        $obj = new AdministracionModel();
        $request = $obj->selectMails();
        $total = 0;
        if(!empty($request)){
            foreach ($request as $email) {
                if($email['status']!=1)$total++;
            }
        }
        return $total;
    }
    function commentNotification(){
        require_once("Models/Comentarios/ComentariosModel.php");
        $obj = new ComentariosModel();
        $request = $obj->selectCountReviews();
        return $request;
    }
    function sessionUser(int $idpersona){
        require_once("Models/LoginModel.php");
        $objLogin = new LoginModel();
        $request = $objLogin ->sessionLogin($idpersona);
        return $request;
    }
    function sessionCookie(){
        if((isset($_COOKIE['usercookie'])&&isset($_COOKIE['passwordcookie'])) && !isset($_SESSION['login'])){
            
            require_once("Models/LoginModel.php");
            $objLogin = new LoginModel();
            $strUser = strtolower(strClean($_COOKIE['usercookie']));
            $strPassword = strClean($_COOKIE['passwordcookie']);
            $request = $objLogin->loginUser($strUser, $strPassword);
            if(!empty($request)){
                if($request['status'] == 1){
                    $_SESSION['idUser'] = $request['idperson'];
                    $_SESSION['login'] = true;
                    $objLogin->sessionLogin($_SESSION['idUser']);
					sessionUser($_SESSION['idUser']);
                }else{
                    setcookie("usercookie",$_COOKIE['usercookie'],time()-60); 
                    setcookie("passwordcookie",$_COOKIE['passwordcookie'],time()-60); 
                }
            }else{
                setcookie("usercookie",$_COOKIE['usercookie'],time()-60);
                setcookie("passwordcookie",$_COOKIE['passwordcookie'],time()-60); 
            }
        }
    }
    //Genera un token
    function token(){
        $r1 = bin2hex(random_bytes(10));
        $r2 = bin2hex(random_bytes(10));
        $r3 = bin2hex(random_bytes(10));
        $r4 = bin2hex(random_bytes(10));
        $token = $r1.'-'.$r2.'-'.$r3.'-'.$r4;
        return $token;
    }
    function code(){
        $code = bin2hex(random_bytes(3));;
        return $code;
    }
    function statusCoupon(){
        require_once("Models/CustomerTrait.php");
        class CouponSt{
            use CustomerTrait;
            public function getStatusCoupon(){
                return $this->statusCouponSuscriberT();
            }

        }
        $s = new CouponSt();
        $arrStatus = array();
        if(!empty($s->getStatusCoupon())){
            $arrStatus = array("code"=>$s->getStatusCoupon()['code'],"discount"=>$s->getStatusCoupon()['discount']);
        }
        return $arrStatus;
    }
    function sendEmail($data,$template){
        $companyData = getCompanyInfo();

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        $asunto = $data['asunto'];
        $emailDestino = $data['email_usuario'];
        $nombre="";
        if(!empty($data['nombreUsuario'])){
            $nombre= $data['nombreUsuario'];
        }
        $empresa = $companyData['name'];
        $remitente = $companyData['email'];
        ob_start();
        require_once("Views/Template/Email/".$template.".php");
        $mensaje = ob_get_clean();

        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true; 
        $mail->Username   = $remitente;
        $mail->Password   = openssl_decrypt($companyData['password'],METHOD,KEY);
                                //Enable SMTP authentication
                             //SMTP username
                                       //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
        //Recipients
        $mail->setFrom($remitente,$empresa);
        $mail->addAddress($emailDestino, $nombre);     //Add a recipient
        if(!empty($data['email_copia'])){
            $mail->addBCC($data['email_copia']);
            $mail->addBCC($remitente);
        }
        

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;

        return $mail->send();
    }
    function orderFiles($files,$prefijo){
        $arrFiles = [];
        for ($i=0; $i < count($files['name']) ; $i++) { 
            $data = array("tmp_name"=>$files['tmp_name'][$i]);
            $rename =$prefijo.'_'.bin2hex(random_bytes(6)).'.png';
            $arrFile = array(
                "name"=>$files['name'][$i],
                "rename"=>$rename,
            );
            uploadImage($data, $rename);
            array_push($arrFiles,$arrFile);
        }
        return $arrFiles;
    }
    function curlConnectionGet(string $route,string $content_type){
        $token = getCredentials()['secret'];
        $arrHeader = array('Content-Type:'.$content_type,
                            'Authorization: Bearer '.$token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $route);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if($err){
            $request = "CURL Error #:" . $err;
        }else{
            $request = json_decode($result);
        }
        return $request;
    }
    function getFile(string $url, $data=""){
        ob_start();
        require_once("Views/{$url}.php");
        $file = ob_get_clean();
        return $file;        
    }
    function getPermits($idmodulo){
        /* //dep($idmodulo);exit;
        require_once("Models/RolesModel.php");
        $roleModel = new RolesModel();
        $idrol = intval($_SESSION['userData']['roleid']);
        $arrPermisos = $roleModel->permitsModule($idrol);
        $permisos = '';
        $permisosMod ='';
        if(count($arrPermisos)>0){
            $permisos = $arrPermisos;
            $permisosMod = isset($arrPermisos[$idmodulo]) ? $arrPermisos[$idmodulo] : "";
        }
        $_SESSION['permit'] = $permisos;
        $_SESSION['permitsModule'] = $permisosMod; */
    }
    
    function uploadImage(array $data, string $name){
        $url_temp = $data['tmp_name'];
        $destino = 'Assets/images/uploads/'.$name;
        $move = move_uploaded_file($url_temp, $destino);
        return $move;
    }

    function deleteFile(string $name){
        if(file_exists("Assets/images/uploads/$name")){
            unlink('Assets/images/uploads/'.$name);
        }
    }
    
    function months(){
        $months = array("Enero", 
                      "Febrero", 
                      "Marzo", 
                      "Abril", 
                      "Mayo", 
                      "Junio", 
                      "Julio", 
                      "Agosto", 
                      "Septiembre", 
                      "Octubre", 
                      "Noviembre", 
                      "Diciembre");
        return $months;
    }
    //Elimina exceso de espacios entre palabras
    function strClean($strCadena){
        $string = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $strCadena);
        $string = trim($string); //Elimina espacios en blanco al inicio y al final
        $string = stripslashes($string); // Elimina las \ invertidas
        $string = str_ireplace("<script>","",$string);
        $string = str_ireplace("</script>","",$string);
        $string = str_ireplace("<script src>","",$string);
        $string = str_ireplace("<script type=>","",$string);
        $string = str_ireplace("SELECT * FROM","",$string);
        $string = str_ireplace("DELETE FROM","",$string);
        $string = str_ireplace("INSERT INTO","",$string);
        $string = str_ireplace("SELECT COUNT(*) FROM","",$string);
        $string = str_ireplace("DROP TABLE","",$string);
        $string = str_ireplace("OR '1'='1","",$string);
        $string = str_ireplace('OR "1"="1"',"",$string);
        $string = str_ireplace('OR ´1´=´1´',"",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("LIKE '","",$string);
        $string = str_ireplace('LIKE "',"",$string);
        $string = str_ireplace("LIKE ´","",$string);
        $string = str_ireplace("OR 'a'='a","",$string);
        $string = str_ireplace('OR "a"="a',"",$string);
        $string = str_ireplace("OR ´a´=´a","",$string);
        $string = str_ireplace("OR ´a´=´a","",$string);
        $string = str_ireplace("--","",$string);
        $string = str_ireplace("^","",$string);
        $string = str_ireplace("[","",$string);
        $string = str_ireplace("]","",$string);
        $string = str_ireplace("==","",$string);
        $string = str_ireplace("#","",$string);
        return $string;
    }

    function clear_cadena(string $cadena){
        //Reemplazamos la A y a
        $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
        );
 
        //Reemplazamos la E y e
        $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena );
 
        //Reemplazamos la I y i
        $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena );
 
        //Reemplazamos la O y o
        $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena );
 
        //Reemplazamos la U y u
        $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena );
 
        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç',',','.',';',':'),
        array('N', 'n', 'C', 'c','','','',''),
        $cadena
        );
        return $cadena;
    }
    //Genera una contraseña de 10 caracteres
	function passGenerator($length = 10){
        $pass = "";
        $longitudPass=$length;
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena=strlen($cadena);

        for($i=1; $i<=$longitudPass; $i++)
        {
            $pos = rand(0,$longitudCadena-1);
            $pass .= substr($cadena,$pos,1);
        }
        return $pass;
    }

    function getNavCat(){
        require_once("Models/CategoryTrait.php");
        class getCat {
            use CategoryTrait;
            public function getCategories(){
                return $this->getCategoriesT();
            }
        }
        $categoria = new getCat();
        $request = $categoria->getCategories();
        return $request;
    }
    function getIp(){
        $ip = "";
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
            $ip = $_SERVER['HTTP_CLIENT_IP'];   
        }   
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];   
        }   
        else{   
            $ip = $_SERVER['REMOTE_ADDR'];   
        }  
        return $ip;  
    }
    function getLastPrice($id,$variantName){
        require_once('Models/HelpersModel.php');
        $con = new HelpersModel();
        $arrPurchase = $con->selectPurchaseDet($id,$variantName);
        $arrOrder = $con->selectOrderDet($id,$variantName);
        $arrAdjustment = $con->selectAdjustmentDet($id,$variantName);
        $arrData = [];
        $finalPrice = 0;
        if(!empty($arrPurchase)){
            foreach ($arrPurchase as $arrdata) {
                $arrdata['type_move'] = 1;
                $arrdata['input'] = $arrdata['qty'];
                $arrdata['input_total'] = 0;
                $arrdata['output'] = 0;
                $arrdata['output_total'] = 0;
                $arrdata['balance'] = 0;
                $arrdata['move'] ="Entrada por compra";
                array_push($arrData,$arrdata);
            }
        }
        if(!empty($arrAdjustment)){
            foreach ($arrAdjustment as $arrdata) {
                if($arrdata['type'] == 1){
                    $arrdata['type_move'] = 1;
                    $arrdata['input'] = $arrdata['qty'];
                    $arrdata['input_total'] = 0;
                    $arrdata['output'] = 0;
                    $arrdata['output_total'] = 0;
                    $arrdata['balance'] = 0;
                    $arrdata['move'] ="Entrada por ajuste";
                }else{
                    $arrdata['type_move'] = 2;
                    $arrdata['output'] = $arrdata['qty'];
                    $arrdata['output_total'] = 0;
                    $arrdata['input'] = 0;
                    $arrdata['input_total'] = 0;
                    $arrdata['balance'] = 0;
                    $arrdata['move'] ="Salida por ajuste";
                }
                array_push($arrData,$arrdata);
            }
        }
        if(!empty($arrOrder)){
            foreach ($arrOrder as $arrdata) {
                $arrdata['type_move'] = 2;
                $arrdata['output'] = $arrdata['qty'];
                $arrdata['output_total'] = 0;
                $arrdata['input'] = 0;
                $arrdata['input_total'] = 0;
                $arrdata['balance'] = 0;
                $arrdata['move'] ="Salida por venta";
                array_push($arrData,$arrdata);
            }
        }
        if(!empty($arrData)){
            $total = count($arrData);
            usort($arrData,function($a,$b){
                $date1 = strtotime($a['date']);
                $date2 = strtotime($b['date']);
                return $date1 > $date2;
            });
            for ($i=0; $i < $total; $i++) { 
                $totalCostBalance = 0;
                $price = $arrData[$i]['price'];
                $arrData[$i]['last_price'] = $arrData[$i]['price'];
                if($i == 0){
                    $arrData[$i]['balance'] = $arrData[$i]['input'] - $arrData[$i]['output'];
                    $arrData[$i]['balance_total'] = $arrData[$i]['balance']*$price;
                    $arrData[$i]['output_total'] = $arrData[$i]['output'] * $price;
                }else{
                    $lastRow = $arrData[$i-1];
                    $lastBalance = $lastRow['balance'];
                    $totalBalance = $lastBalance+$arrData[$i]['input']-$arrData[$i]['output'];
                    $totalCostBalance = $lastRow['balance_total'];
                    $arrData[$i]['balance'] = $totalBalance;
                    if($arrData[$i]['type_move'] == 1){
                        $totalCostBalance+=$arrData[$i]['input'] * $arrData[$i]['last_price'];
                        $lastPrice = $totalBalance > 0 ? $totalCostBalance/$totalBalance : 0;
                        $arrData[$i]['last_price'] = $lastPrice;
                        $arrData[$i]['balance_total'] = $arrData[$i]['balance']*$lastPrice;
                    }else{
                        $arrData[$i]['last_price'] =  $lastRow['last_price'];
                        $arrData[$i]['output_total'] = $arrData[$i]['output'] * $lastRow['last_price'];
                        $arrData[$i]['balance_total'] = $arrData[$i]['balance']*$lastRow['last_price'];
                    }
                }
                $finalPrice = $arrData[$i]['last_price'];
            }
        }
        return $finalPrice;
    }
    function getOptionPago(){
        $pago="";
        for ($i=0; $i < count(PAGO) ; $i++) { 
            if(PAGO[$i] != "credito"){
                $pago .='<option value="'.PAGO[$i].'">'.PAGO[$i].'</option>';
            }
        }
        return $pago;
    }
    function getPagination($page,$startPage,$totalPages,$limitPages,$template="admin"){
        $data = [
            "page"=>$page,
            "start_page"=>$startPage,
            "total_pages"=>$totalPages,
            "limit_pages"=>$limitPages
        ];
        ob_start();
        if($template == "admin"){
            getComponent("paginationAdmin",$data);
        }else{
            getComponent("paginationPage",$data);
        }
        $html = ob_get_clean();
        return $html;
    }
    function getCalcPages($totalRecords,$pageNow,$perPage){
        $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$perPage) : 0);
        $totalPages = $totalPages == 0 ? 1 : $totalPages;
        $startPage = max(1, $pageNow - floor(BUTTONS / 2));

        if ($startPage + BUTTONS - 1 > $totalPages) {
            $startPage = max(1, $totalPages - BUTTONS + 1);
        }

        $limitPages = min($startPage + BUTTONS, $totalPages+1);
        $arrButtons = [];
        for ($i=$startPage; $i < $limitPages; $i++) { 
            array_push($arrButtons,$i);
        }

        return [
            "start_page"=>$startPage,
            "limit_page"=>$limitPages,
            "total_pages"=>$totalPages,
            "total_records"=>$totalRecords,
            "buttons"=>$arrButtons
        ];
    }
    function getError($code){
        $company = getCompanyInfo();
        session_start();
        sessionCookie();
        $data['company'] = $company;
        $data['error']['msg'] = ERRORES[$code];
        $data['error']['code'] = $code;
        $data['page_tag'] = $company['name'];
        $data['page_title'] = $company['name'];
        $data['page_name'] = "Error $code";
        require_once "Views/Template/Error/error.php";
    }
    function setView($route){
        $ip = getIp();
        //$ip = "191.107.176.102";
        $con = new Mysql();
        $sql = "SELECT * FROM locations WHERE ip = '$ip' AND route = '$route'";
        $request = $con->select_all($sql);
        if(empty($request)){
            $location = new IpServiceProvider(new IpGeolocationProvider,$ip);
            $location = $location->getLocation();
            if($location['status']=="success"){
                $sql = "INSERT INTO locations(route,country,state,city,zip,lat,lon,timezone,isp,org,aso,ip) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = [
                    $route,
                    $location['country'] ?? "",
                    $location['regionName'] ?? "",
                    $location['city'] ?? "",
                    $location['zip'] ?? "",
                    $location['lat'] ?? "",
                    $location['lon'] ?? "",
                    $location['timezone'] ?? "",
                    $location['isp'] ?? "",
                    $location['org'] ?? "",
                    $location['as'] ?? "",
                    $location['query'] ?? "",
                ];
                $con->insert($sql,$arrData);
            }
        }
    }
    function resetUserData(){
        $id = $_SESSION['idUser'];
        $con = new Mysql();
        $sql = "SELECT  *,r.name as role_name 
        FROM person p 
        INNER JOIN role r
        ON r.idrole = p.roleid
        WHERE idperson = $id";
        $request = $con->select($sql);
        $_SESSION['userData'] = $request;
    }
    function getComponent(string $name, $data=null){
        $file = "Views/Template/Components/{$name}.php";
        require $file;        
    }
    function setEncriptar($data){
        $encrypted = openssl_encrypt($data, METHOD,KEY);
        //$base64 = base64_encode($encrypted); 
        $safe = str_replace(['/', '+'], ['_', '-'], $encrypted);
        return $safe;
    }
    function setDesencriptar($data){
        $data = str_replace(['_', '-'], ['/', '+'], $data);
        $decrypted = openssl_decrypt($data, METHOD, KEY);
        return $decrypted;
    }
    function getRedesSociales(){
        $social = getSocialMedia();
        $links ="";
        for ($i=0; $i < count($social) ; $i++) { 
            if($social[$i]['link']!=""){
                if($social[$i]['name']=="whatsapp"){
                    $links.='<li><a href="https://wa.me/'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
                }else{
                    $links.='<li><a href="'.$social[$i]['link'].'" target="_blank"><i class="fab fa-'.$social[$i]['name'].'"></i></a></li>';
                }
            }
        }
        return $links;
    }
    function getFooterServicios(){
        $con = new Mysql();
        $sql="SELECT * FROM category ORDER BY name";
        $request = $con->select_all($sql);
        $total = count($request);
        for ($i=0; $i < $total ; $i++) { 
            $request[$i]['route'] = base_url()."/servicios/area/".$request[$i]['route'];
        }
        return $request;
    }
    function getPaises(){
        $con = new Mysql();
        $request = $con->select_all("SELECT * FROM countries ORDER BY name");
        return $request;
    }
    function getDepartamentos(int $id){
        $con = new Mysql();
        $request = $con->select_all("SELECT * FROM states WHERE country_id = $id ORDER BY name ");
        return $request;
    }
    function getCiudades(int $id){
        $con = new Mysql();
        $request = $con->select_all("SELECT * FROM cities WHERE state_id = $id ORDER BY name");
        return $request;
    }
    function getCiudad(int $id){
        $con = new Mysql();
        $request = $con->select("SELECT * FROM cities WHERE id = $id");
        return $request;
    }
    function getDepartamento(int $id){
        $con = new Mysql();
        $request = $con->select("SELECT * FROM states WHERE id = $id");
        return $request;
    }
    function getPais(int $id){
        $con = new Mysql();
        $request = $con->select("SELECT * FROM countries WHERE id = $id");
        return $request;
    }
    function getPermisos(){
        if(isset($_SESSION['userData'])){
            $con = new Mysql();
            $intRolId = $_SESSION['userData']['roleid'];
            $intId = $_SESSION['idUser'];
            $sql = "SELECT idmodule as id,name,icon FROM module WHERE status = 1 ORDER BY level";
            $arrModules = $con->select_all($sql);
            $arrOptionsPermits = [];
            foreach ($arrModules as &$module) {
                $sql = "SELECT r,w,u,d FROM module_permissions WHERE module_id = $module[id] AND role_id = $intRolId AND user_id = $intId";
                $request = $con->select($sql);
                if(empty($request)){
                    $sql = "SELECT r,w,u,d FROM module_permissions WHERE module_id = $module[id] AND role_id = $intRolId";
                    $request = $con->select($sql);
                }
                if(!empty($request)){
                    $module["r"] = boolval($request["r"]);
                    $module["w"] = boolval($request["w"]);
                    $module["u"] = boolval($request["u"]);
                    $module["d"] = boolval($request["d"]);
                }else{
                    $module["r"] = false;
                    $module["w"] = false;
                    $module["u"] = false;
                    $module["d"] = false;
                }
                $sql = "SELECT * FROM module_sections WHERE module_id = $module[id] AND status = 1 ORDER BY level";
                $arrSections = $con->select_all($sql);
                foreach ($arrSections as &$section) {
                    $sql = "SELECT r,w,u,d FROM module_sections_permissions WHERE section_id = $section[id] AND role_id = $intRolId AND user_id = $intId";
                    $request = $con->select($sql);
                    if(empty($request)){
                        $sql = "SELECT r,w,u,d FROM module_sections_permissions WHERE section_id = $section[id] AND role_id = $intRolId";
                        $request = $con->select($sql);
                    }
                    if(!empty($request)){
                        $section["r"] = boolval($request["r"]);
                        $section["w"] = boolval($request["w"]);
                        $section["u"] = boolval($request["u"]);
                        $section["d"] = boolval($request["d"]);
                    }else{
                        $section["r"] = false;
                        $section["w"] = false;
                        $section["u"] = false;
                        $section["d"] = false;
                    }
                    $sql = "SELECT * FROM module_options WHERE section_id = $section[id] AND status = 1 ORDER BY level";
                    $arrOptionsSection = $con->select_all($sql);
                    foreach ($arrOptionsSection as &$optionSection) {
                        
                        $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $optionSection[id] AND role_id = $intRolId AND user_id = $intId";
                        $request = $con->select($sql);
                        if(empty($request)){
                            $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $optionSection[id] AND role_id = $intRolId";
                            $request = $con->select($sql);
                        }
                        if(!empty($request)){
                            $optionSection["r"] = boolval($request["r"]);
                            $optionSection["w"] = boolval($request["w"]);
                            $optionSection["u"] = boolval($request["u"]);
                            $optionSection["d"] = boolval($request["d"]);
                        }else{
                            $optionSection["r"] = false;
                            $optionSection["w"] = false;
                            $optionSection["u"] = false;
                            $optionSection["d"] = false;
                        }
                        if($_SESSION['idUser'] == 1){
                            $request["r"] = 1;
                            $request["w"] = 1;
                            $request["u"] = 1;
                            $request["d"] = 1;
                        }
                        $request['roleid'] = $intRolId;
                        $request['module'] = $module['name'];
                        $request['option'] = $optionSection['name'];
                        $request['moduleid'] = $module['id'];
                        $request['route'] ="/".$optionSection['route'];
                        $arrOptionsPermits[$request['route']] = $request;
                    }
                    $section['options'] = $arrOptionsSection;
                }
                $sql = "SELECT * FROM module_options WHERE module_id = $module[id] AND section_id = 0 AND status = 1 ORDER BY level";
                $arrOptions = $con->select_all($sql);
                foreach ($arrOptions as &$option) {
                    $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $option[id] AND role_id = $intRolId AND user_id = $intId";
                    $request = $con->select($sql);
                    if(empty($request)){
                        $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $option[id] AND role_id = $intRolId";
                        $request = $con->select($sql);
                    }
                    if(!empty($request)){
                        $option["r"] = boolval($request["r"]);
                        $option["w"] = boolval($request["w"]);
                        $option["u"] = boolval($request["u"]);
                        $option["d"] = boolval($request["d"]);
                    }else{
                        $option["r"] = false;
                        $option["w"] = false;
                        $option["u"] = false;
                        $option["d"] = false;
                    }
                    if($_SESSION['idUser'] == 1){
                        $request["r"] = 1;
                        $request["w"] = 1;
                        $request["u"] = 1;
                        $request["d"] = 1;
                    }
                    $request['roleid'] = $intRolId;
                    $request['option'] = $option['name'];
                    $request['module'] = $module['name'];
                    $request['moduleid'] = $module['id'];
                    $request['route'] ="/".$option['route'];
                    $arrOptionsPermits[$option['route']] = $request;
                }
                $module['options'] = $arrOptions;
                $module['sections'] = $arrSections;
            }
            $_SESSION['permissions'] = $arrOptionsPermits;
            $_SESSION['navegation'] = $arrModules;
        }
    }
    function getShippingMode(){
        $con = new Mysql();
        $sql = "SELECT * FROM shipping WHERE status = 1";
        $request = $con->select($sql);
        if($request['id'] == 3){
            $sqlCities = "SELECT
            sh.id,
            c.name as country,
            s.name as state,
            cy.name as city,
            sh.value
            FROM shippingcity sh
            INNER JOIN countries c, states s, cities cy
            WHERE c.id = sh.country_id AND s.id = sh.state_id AND cy.id = sh.city_id
            ORDER BY cy.name ASC";
            $cities = $con->select_all($sqlCities);
            $request['cities'] = $cities;
        }
        return $request;
    }
    function validator(){
        return new Validator();
    }
    function updateStock($data,$type = 2){
        $con = new Mysql();
        $sqlStock = "SELECT stock FROM product WHERE idproduct = {$data['id']}";
        //$sqlPurchase = "SELECT AVG(price) as price_purchase FROM orderdetail WHERE product_id = {$this->arrData[$i]['id']}";
        $sqlProduct ="UPDATE product SET stock=? WHERE idproduct = {$data['id']}";

        if($data['product_type']){
            $sqlStock = "SELECT stock FROM product_variations_options 
            WHERE product_id = {$data['id']} AND name = '{$data['variant_name']}'";
            
            $sqlProduct = "UPDATE product_variations_options SET stock=?
            WHERE product_id = {$data['id']} AND name = '{$data['variant_name']}'";
            /*$sqlPurchase = "SELECT AVG(price) as price_purchase
            FROM purchase_det 
            WHERE product_id = {$this->arrData[$i]['id']} 
            AND variant_name = '{$this->arrData[$i]['variant_name']}' ";*/
        } 

        $stock = $con->select($sqlStock)['stock'];

        if($type == 1){
            $stock = $stock +$data['qty'];
        }else{
            $stock = $stock -$data['qty'];
        }

        //$price_purchase = $this->select($sqlPurchase)['price_purchase'];
        $arrData = array($stock);
        $con->update($sqlProduct,$arrData);
    }

    function setAdjustment($type = 2,$concept,$data=[],$ingredient=[],$isRoot=false){
        $total = 0;
        if(empty($data)){
            $data = getIngredientsAdjustment($ingredient['id'],$ingredient['qty'],1,$ingredient['variant_name'],$isRoot);
            if(!empty($data)){
                $data = array_filter($data,function($e){ return $e['key'] !== "";});
            }else{
                $data = [];
            }
        }
        foreach ($data as $det ) { $total += $det['subtotal']; }

        $con = new Mysql();
        $sql = "INSERT INTO adjustment_cab(concept,total,user) VALUES (?,?,?)";
        $request = $con->insert($sql,[$concept,$total,$_SESSION['userData']['idperson']]);
        
        foreach ($data as $det) { 
            updateStock($det,$type);
            $sql = "INSERT INTO adjustment_det(adjustment_id,product_id,current,adjustment,price,type,result,variant_name,subtotal) VALUES(?,?,?,?,?,?,?,?,?)";
            $arrValues = [
                $request,
                $det['id'],
                $det['current_stock'],
                $det['qty'],
                $det['price'],
                $type,
                $det['result_stock'],
                $det['variant_name'],
                $det['subtotal']
            ];
            $con->insert($sql,$arrValues);
        }
    }

    function getIngredientsAdjustment($id,$qty,$type=2,$variantProductName,$isRoot,&$visited = []){
        if (in_array($id, $visited)) {
            throw new Exception("Circular reference detected for item $id");
        }

        $con = new Mysql();
        $sql = "SELECT product as id,qty,variant_name,product_id FROM product_ingredients WHERE product_id = $id";
        $arrIngredients = $con->select_all($sql);

        if(!$isRoot){
            if($variantProductName != ""){
                $sql = "SELECT op.price_purchase,op.stock,p.name
                FROM product_variations_options op
                INNER JOIN product p ON op.product_id = p.idproduct
                WHERE op.name = '$variantProductName' AND op.product_id = $id";
            }else{
                $sql = "SELECT price_purchase,stock,name FROM product WHERE idproduct = $id";
            }
            $arrProduct = $con->select($sql);
            $visited[] = $id;
            $resolved[] = [
                'id' => $id,
                'name' => $arrProduct['name']." ".$variantProductName,
                "key"=>str_replace(" ","",$arrProduct['name']." ".$variantProductName),
                "variant_name"=>$variantProductName,
                'qty' => $qty,
                'current_stock' => $arrProduct['stock'],
                'result_stock' => ($type == 1) ? $arrProduct['stock'] + $qty : $arrProduct['stock'] - $qty,
                'price' => $arrProduct['price_purchase'],
                'subtotal' => $qty * $arrProduct['price_purchase']
            ];
        }

        foreach ($arrIngredients as $ingr) {
            $variantName ="";
            if($ingr['variant_name'] != ""){
                $variantName = $ingr['variant_name'];
                $sql = "SELECT price_purchase,stock FROM product_variations_options 
                WHERE name = '{$ingr['variant_name']}' AND product_id = {$ingr['id']}";
            }else{
                $sql = "SELECT price_purchase,stock FROM product WHERE idproduct = {$ingr['id']}";
            }
            $arrProduct = $con->select($sql);
            $ingr['qty'] = $qty*$ingr['qty'];

            $ingr['name'] = $ingr['name']." ".$variantName;
            $ingr['key'] = str_replace(" ","",$ingr['name']);
            $ingr['current_stock'] = $arrProduct['stock'];
            $ingr['result_stock'] = ($type == 1) ? $ingr['current_stock']+$ingr['qty'] : $ingr['current_stock']-$ingr['qty'];
            
            $ingr['price'] = $arrProduct['price_purchase'];
            $ingr['subtotal'] = $ingr['qty']*$ingr['price'];
            $resolved[] = $ingr;

            $subIngredients = getIngredientsAdjustment($ingr['id'], $ingr['qty'], $type,$variantName, false,$visited);
            $resolved = array_merge($resolved, $subIngredients);
        }
        return $resolved;
    }

    function getStock($id,$variant=null){
        $con = new Mysql();
        $sql = "SELECT stock,product_type FROM product WHERE idproduct = $id";
        $request = $con->select($sql);
        $stock = $request['stock'];
        if($request['product_type'] == 1){
            $name = isset($variant['name']) ? $variant['name'] : $variant ;
            $sqlV = "SELECT stock FROM product_variations_options WHERE product_id = $id AND name = '$name'";
            $stock = $con->select($sqlV)['stock'];
        }
        return $stock;
    }

    function calcWholsale($data,$type=1){
        $arrWholesale = $type==1 ? $data['config']['wholesale'] : $data['wholesale'];
        $productQty = $data['qty'];
        $priceSell = $data['current_price'];
        $priceOffer = 0;
        $discount = 0;
        $percent = 0;
        if(count($arrWholesale) > 0){
            $arrDiscount = array_filter($arrWholesale,function($e)use($productQty){return $productQty >= $e['min'];});
            if(count($arrDiscount)){
                $discount = $arrDiscount[count($arrDiscount)-1]['percent'];
                $discount = $discount/100;
                $discount = $discount*$priceSell;
                $priceOffer = $priceSell-$discount;
                $percent =  round((1-($priceOffer/$priceSell))*100);
            }
        }
        $data['discount'] = $percent;
        $data['price'] = $priceOffer > 0 ? $priceOffer : $priceSell;
        return $data;
    }

?>