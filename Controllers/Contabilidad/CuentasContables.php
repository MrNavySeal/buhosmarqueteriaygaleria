<?php
    
    class CuentasContables extends Controllers{
        public function __construct(){
            session_start();
            if(empty($_SESSION['login'])){
                header("location: ".base_url());
                die();
            }
            parent::__construct();
        }

        public function cuentas(){
            if($_SESSION['permitsModule']['r']){
                $data['botones'] = [
                    "duplicar" => ["mostrar"=>true, "evento"=>"onclick","funcion"=>"mypop=window.open('".BASE_URL.$_SESSION['permitsModule']['route']."','','');mypop.focus();"],
                ];
                $data['page_tag'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_title'] = implode(" | ",[$_SESSION['permitsModule']['option'],$_SESSION['permitsModule']['module']]);
                $data['page_name'] = strtolower($_SESSION['permitsModule']['option']);
                $data['panelapp'] = "/Contabilidad/functions_cuentas_contables.js";
                $data['script_type'] = "module";
                $this->views->getView($this,"cuentas-contables",$data);
            }else{
                header("location: ".base_url());
                die();
            }
        }

        public function getDatosIniciales(){
            if($_SESSION['permitsModule']['r']){
                $request = HelperAccounting::getAccounts();
                echo json_encode($request,JSON_UNESCAPED_UNICODE);
            }
        }

        public function getCuentasPadres(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrAccount = json_decode($_POST['account'],true);
                    $strType = strClean($_POST['type']);
                    $request = HelperAccounting::getParentAccounts($arrAccount['id'],[],$strType == "new" ? true : false);
                    $newAccount = [];
                    if($strType == "new"){
                        if($arrAccount['type']=="clase"){
                            $newAccount = [
                                "level"=>2,
                                "parent_id"=>$arrAccount['id'],
                                "parent_code"=>substr($arrAccount['code'],0,$arrAccount['level']),
                                "name"=>"",
                                "digits"=>1,
                                "status"=>$arrAccount['status'],
                                "type"=>"grupo",
                                "nature"=>$arrAccount['nature'],
                                "parents"=>$request,
                                "code"=>"",
                                "id"=>0,
                            ];
                        }else if($arrAccount['type']=="grupo"){
                            $newAccount = [
                                "level"=>4,
                                "parent_id"=>$arrAccount['id'],
                                "parent_code"=>substr($arrAccount['code'],0,$arrAccount['level']),
                                "name"=>"",
                                "digits"=>2,
                                "status"=>$arrAccount['status'],
                                "type"=>"cuenta",
                                "nature"=>$arrAccount['nature'],
                                "parents"=>$request,
                                "code"=>"",
                                "id"=>0,
                            ];
                        }else if($arrAccount['type']=="cuenta"){
                            $newAccount = [
                                "level"=>6,
                                "parent_id"=>$arrAccount['id'],
                                "parent_code"=>substr($arrAccount['code'],0,$arrAccount['level']),
                                "name"=>"",
                                "digits"=>2,
                                "status"=>$arrAccount['status'],
                                "type"=>"subcuenta",
                                "nature"=>$arrAccount['nature'],
                                "parents"=>$request,
                                "code"=>"",
                                "id"=>0,
                            ];
                        }else if($arrAccount['type']=="subcuenta"){
                            $newAccount = [
                                "level"=>8,
                                "parent_id"=>$arrAccount['id'],
                                "parent_code"=>substr($arrAccount['code'],0,$arrAccount['level']),
                                "name"=>"",
                                "digits"=>2,
                                "status"=>$arrAccount['status'],
                                "type"=>"auxiliar",
                                "nature"=>$arrAccount['nature'],
                                "parents"=>$request,
                                "code"=>"",
                                "id"=>0,
                            ];
                        }
                    }else{
                        $arrAccount['children'] = [];
                        if($arrAccount['parent_id'] != 0){
                            $parent = $request[count($request)-1];
                            $code = substr($arrAccount['code'],strlen($parent['code']),$parent['digits']);
                            $newAccount = [
                                "level"=>$arrAccount['level'],
                                "parent_id"=>$arrAccount['parent_id'],
                                "parent_code"=>$parent['code'],
                                "name"=>$arrAccount['name'],
                                "digits"=>$parent['digits'],
                                "status"=>$arrAccount['status'],
                                "type"=>$arrAccount['type'],
                                "nature"=>$arrAccount['nature'],
                                "parents"=>$request,
                                "code"=>$code,
                                "id"=>$arrAccount['id'],
                            ];
                        }else{
                            $newAccount = $arrAccount;
                        }
                        $newAccount['parents'] = $request;
                    }
                    echo json_encode($newAccount,JSON_UNESCAPED_UNICODE);
                }
            }
        }

        public function setDatos(){
            if($_SESSION['permitsModule']['r']){
                if($_POST){
                    $arrData = json_decode($_POST['account'],true);
                    $intDigits = $arrData['digits'];
                    $errores = validator()->validate([
                        "code"=>"required|min:$intDigits|max:$intDigits;codigo",
                        "name"=>"required;nombre",
                    ],$arrData)->getErrors();

                    if(!empty($errores)){
                        $arrResponse = ["status"=>false,"msg"=>"Por favor, revise los campos.","errors"=>$errores];
                    }else{
                        $option = "";
                        $arrData['code'] = strClean($arrData['code']);
                        $arrData['name'] = strClean(ucfirst(strtolower($arrData['name'])));
                        $arrData['id'] = intval($arrData['id']);

                        if($arrData['id'] == 0){
                            if($_SESSION['permitsModule']['w']){
                                $option = 1;
                                $request = $this->model->insertDatos($arrData);
                            }
                        }else{
                            if($_SESSION['permitsModule']['u']){
                                $option = 2;
                                $request = $this->model->updateDatos($arrData);
                            }
                        }

                        if(is_numeric($request) && $request > 0 ){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados.');
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados.');
                            }
                        }else if($request =="existe"){
                            $arrResponse = array("status" => false, "msg" => 'Esta cuenta ya existe, intente con otra.');
                        }else{
                            $arrResponse = array("status" => false, "msg" => 'No es posible guardar los datos.');
                        }
                    }

                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
        }
    }
?>