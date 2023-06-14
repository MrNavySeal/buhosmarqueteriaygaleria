<?php
    class Banners extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            getPermits(5);
        }
        
        public function banners(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Banners";
                $data['page_title'] = "Banners";
                $data['page_name'] = "banners";
                $data['data'] = $this->getBanners();
                $data['app'] = "functions_banner.js";
                $this->views->getView($this,"banners",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        /*************************Banners methods*******************************/
        public function getBanners($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->searchc($params);
                }else if($option == 2){
                    $request = $this->model->sortc($params);
                }else{
                    $request = $this->model->selectBanners();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $status="";
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }

                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<button class="btn btn-success m-1" type="button" title="Edit" data-id="'.$request[$i]['id_banner'].'" name="btnEdit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        if($_SESSION['permitsModule']['d']){
                            $btnDelete = '<button class="btn btn-danger m-1" type="button" title="Delete" data-id="'.$request[$i]['id_banner'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        $html.='
                            <tr class="item">
                                <td data-label="Titulo: ">'.$request[$i]['name'].'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $html = '<tr><td colspan="11">No hay datos</td></tr>';
                    $arrResponse = array("status"=>false,"data"=>$html);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getBanner(){
            if($_SESSION['permitsModule']['r']){

                if($_POST){
                    if(empty($_POST)){
                        $arrResponse = array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $idBanner = intval($_POST['idBanner']);
                        $request = $this->model->selectBanner($idBanner);
                        if(!empty($request)){
                            $request['picture'] = media()."/images/uploads/".$request['picture'];
                            $arrResponse = array("status"=>true,"data"=>$request);
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"Error, intenta de nuevo"); 
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
        public function setBanner(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtLink']) || !filter_var($_POST['txtLink'], FILTER_VALIDATE_URL)){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $idBanner = intval($_POST['idBanner']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strLink = strClean($_POST['txtLink']);
                        $status = intval($_POST['statusList']);
                        $button = strClean($_POST['txtBtn']);
                        $description = strClean($_POST['txtDescription']);
                        $photo = "";
                        $photoCategory="";

                        if($idBanner == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'banner_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertBanner(
                                    $photoCategory, 
                                    $strName,
                                    $status,
                                    $strLink,
                                    $button,
                                    $description
                                );
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectBanner($idBanner);
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = $request['picture'];
                                }else{
                                    if($request['picture'] != "category.jpg"){
                                        deleteFile($request['picture']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'banner_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->updateBanner(
                                    $idBanner, 
                                    $photoCategory,
                                    $strName,
                                    $status,
                                    $strLink,
                                    $button,
                                    $description
                                );
                            }
                        }
                        if($request > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoCategory);
                            }
                            if($option == 1){
                                $arrResponse = $this->getBanners();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getBanners();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'El banner ya existe, prueba con otro nombre.');		
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
			die();
		}
        public function delBanner(){
            if($_SESSION['permitsModule']['d']){

                if($_POST){
                    if(empty($_POST['idBanner'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['idBanner']);

                        $request = $this->model->selectBanner($id);
                        if($request['picture']!="category.jpg"){
                            deleteFile($request['picture']);
                        }
                        
                        $request = $this->model->deleteBanner($id);

                        if($request=="ok"){
                            $arrResponse = array("status"=>true,"msg"=>"Se ha eliminado.","data"=>$this->getBanners()['data']);
                        }else if($request =="exist"){
                            $arrResponse = array("status"=>false,"msg"=>"La categoría tiene al menos una subcategoría asignada, no puede ser eliminada.");
                        }else{
                            $arrResponse = array("status"=>false,"msg"=>"No es posible eliminar, intenta de nuevo.");
                        }
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }else{
                header("location: ".base_url());
                die();
            }
            die();
        }
    }
?>