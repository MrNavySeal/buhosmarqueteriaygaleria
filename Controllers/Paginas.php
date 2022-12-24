<?php
    class Paginas extends Controllers{

        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
            sessionCookie();
            getPermits(5);
        }
        public function paginas(){
            if($_SESSION['permitsModule']['r']){
                $data['page_tag'] = "Pagina";
                $data['page_title'] = "Paginas";
                $data['page_name'] = "pagina";
                $data['data'] = $this->getPages();
                $data['app'] = "functions_pages.js";
                $this->views->getView($this,"paginas",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function pagina($pagina){
            if($_SESSION['permitsModule']['u']){
                if(is_numeric($pagina)){
                    $pagina = intval($pagina);
                    $data['page'] = $this->getPage($pagina);
                    if(!empty($data['page'])){
                        $data['page_tag'] = $data['page']['name'];
                        $data['page_title'] = $data['page']['name'];
                        $data['page_name'] = "pagina";
                        $data['app'] = "functions_page.js";
                        $this->views->getView($this,"pagina",$data);
                    }else{
                        header("location: ".base_url()."/paginas");
                        die();
                    }
                }else{
                    header("location: ".base_url()."/paginas");
                    die();
                }
            }else{
                header("location: ".base_url());
                die();
            }
        }
        public function getPages($option=null,$params=null){
            if($_SESSION['permitsModule']['r']){
                $html="";
                $request="";
                if($option == 1){
                    $request = $this->model->search($params);
                }else if($option == 2){
                    $request = $this->model->sort($params);
                }else{
                    $request = $this->model->selectPages();
                }
                if(count($request)>0){
                    for ($i=0; $i < count($request); $i++) { 

                        $btnEdit="";
                        $btnDelete="";
                        $pagina ="";
                        if($_SESSION['permitsModule']['u']){
                            $btnEdit = '<a class="btn btn-success m-1 text-white" href="'.base_url().'/paginas/pagina/'.$request[$i]['id'].'" title="Edit" name="btnEdit"><i class="fas fa-pencil-alt"></i></a>';
                        }
                        if($_SESSION['permitsModule']['d'] && $request[$i]['id']>3){
                            $btnDelete = '<button class="btn btn-danger m-1 text-white" type="button" title="Delete" data-id="'.$request[$i]['id'].'" name="btnDelete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        if($request[$i]['status']==1){
                            $status='<span class="badge me-1 bg-success">Activo</span>';
                        }else{
                            $status='<span class="badge me-1 bg-danger">Inactivo</span>';
                        }
                        if($request[$i]['type'] == 1){
                            $pagina ="Convencional";
                        }else{
                            $pagina ="Servicio";
                        }
                        $html.='
                            <tr class="item">
                                <td data-label="Id: ">'.$request[$i]['id'].'</td>
                                <td class="text-center">'.$request[$i]['name'].'</td>
                                <td data-label="Tipo: ">'.$pagina.'</td>
                                <td data-label="Estado: ">'.$status.'</td>
                                <td data-label="Fecha de creación: ">'.$request[$i]['date'].'</td>
                                <td data-label="Fecha de actualización: ">'.$request[$i]['dateupdated'].'</td>
                                <td class="item-btn">'.$btnEdit.$btnDelete.'</td>
                            </tr>
                        ';
                    }
                    $arrResponse = array("status"=>true,"data"=>$html);
                }else{
                    $arrResponse = array("status"=>false,"data"=>"No hay datos");
                }
            }else{
                header("location: ".base_url());
                die();
            }
            
            return $arrResponse;
        }
        public function getPage($id){
            if($_SESSION['permitsModule']['u']){
                $request = $this->model->selectPage($id);
                if(!empty($request)){
                    if($request['picture']!=""){
                        $request['picture'] = media()."/images/uploads/".$request['picture'];
                    }else{
                        $request['picture'] = media()."/images/uploads/category.jpg";
                    }
                    $type="";
                    $status="";
                    if($request['type'] == 1){
                        $type = '<option value="1" selected>Convencional</option><option value="2">Servicio</option>';
                    }else{
                        $type = '<option value="1">Convencional</option><option value="2" selected>Servicio</option>';
                    }
                    if($request['status'] == 1){
                        $status = '<option value="1" selected>Activo</option><option value="2">Inactivo</option>';
                    }else{
                        $status = '<option value="1">Activo</option><option value="2" selected>Inactivo</option>';
                    }
                    $request['optionT'] = $type;
                    $request['optionS'] = $status;
                }
                return $request;
            }
            
        }
        public function setPage(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    if(empty($_POST['txtName']) || empty($_POST['txtDescription']) || empty($_POST['statusList']) || empty($_POST['typeList'])){
                        $arrResponse = array("status" => false, "msg" => 'Error de datos');
                    }else{ 
                        $id = intval($_POST['id']);
                        $strName = ucwords(strClean($_POST['txtName']));
                        $strDescription = $_POST['txtDescription'];
                        $intStatus = intval($_POST['statusList']);
                        $intType = intval($_POST['typeList']);
                        $route = clear_cadena($strName);
                        $route = str_replace(" ","-",$route);
                        $route = str_replace("?","",$route);
                        $route = strtolower(str_replace("¿","",$route));
                        $photo = "";
                        $photoCategory="";

                        if($id == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;

                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = "category.jpg";
                                }else{
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'page_'.bin2hex(random_bytes(6)).'.png';
                                }

                                $request= $this->model->insertPage($intType,$photoCategory,$strName,$strDescription,$intStatus,$route);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->selectPage($id);
                                if($_FILES['txtImg']['name'] == ""){
                                    $photoCategory = $request['picture'];
                                }else{
                                    if($request['picture'] != "category.jpg"){
                                        deleteFile($request['picture']);
                                    }
                                    $photo = $_FILES['txtImg'];
                                    $photoCategory = 'page_'.bin2hex(random_bytes(6)).'.png';
                                }
                                $request = $this->model->updatePage($id,$intType,$photoCategory,$strName,$strDescription,$intStatus,$route);
                            }
                        }
                        if($request > 0 ){
                            if($photo!=""){
                                uploadImage($photo,$photoCategory);
                            }
                            if($option == 1){
                                $arrResponse = $this->getPages();
                                $arrResponse['msg'] = 'Datos guardados.';
                            }else{
                                $arrResponse = $this->getPages();
                                $arrResponse['msg'] = 'Datos actualizados.';
                            }
                        }else if($request == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => 'La página ya existe, prueba con otro título.');		
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
        public function delPage(){
            if($_SESSION['permitsModule']['d']){
                if($_POST){
                    if(empty($_POST['id'])){
                        $arrResponse=array("status"=>false,"msg"=>"Error de datos");
                    }else{
                        $id = intval($_POST['id']);

                        $request = $this->model->selectPage($id);
                        if($request['picture']!="category.jpg"){
                            deleteFile($request['picture']);
                        }
                        
                        $request = $this->model->deletePage($id);
                        if($request=="ok"){
                            $arrResponse = $this->getPages();
                            $arrResponse['msg'] = "Se ha eliminado";
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
        public function search($params){
            if($_SESSION['permitsModule']['r']){
                $search = strClean($params);
                $arrResponse = $this->getPages(1,$search);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
        public function sort($params){
            if($_SESSION['permitsModule']['r']){
                $sort = intval($params);
                $arrResponse = $this->getPages(2,$sort);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>