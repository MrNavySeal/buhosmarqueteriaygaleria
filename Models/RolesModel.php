<?php
    class RolesModel extends Mysql{

        private $intIdRole;
        private $strName; 
        private $intIdModule;
        private $boolR;
        private $boolW;
        private $boolU;
        private $boolD;

        public function __construct(){
            parent::__construct();
        }

        public function insertRole($strName){
            $this->strName = $strName;
            $sql = "SELECT * FROM role WHERE name = '$this->strName'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO role(name) VALUES(?)";
                $arrData = array(
                    $this->strName
                );
                $request = $this->insert($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }
        public function selectRoles(){
            $sql = "SELECT * FROM role ORDER BY idrole DESC";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectRole($id){
            $this->intIdRole = $id;
            $sql = "SELECT * FROM role WHERE idrole = $id";
            $request = $this->select($sql);
            return $request;
        }
        public function updateRole(int $id, string $strName){
            $this->strName = $strName;
            $this->intIdRole = $id;
            $sql = "SELECT * FROM role WHERE name = '$this->strName' AND idrole != $this->intIdRole";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE role SET name=? WHERE idrole = $this->intIdRole";
                $arrData = array(
                    $this->strName
                );
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }
        public function deleteRole(int $id){
            $this->intIdRole = $id;
            $sql = "DELETE FROM role WHERE idrole = $this->intIdRole;SET @autoid :=0; 
			UPDATE role SET idrole = @autoid := (@autoid+1);
			ALTER TABLE role Auto_Increment = 1";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectModules(){
            $sql = "SELECT * FROM module";
            $request = $this->select_all($sql);
            return $request;
        }
        public function selectPermits($id){
            $this->intIdRole = $id;
            $sql = "SELECT  
                    p.idpermit,
                    p.roleid,
                    p.moduleid,
                    p.r,
                    p.w,
                    p.u,
                    p.d
                    FROM permit p
                    INNER JOIN module m
                    ON p.moduleid = m.idmodule
                    WHERE p.roleid = $this->intIdRole";
            
            $request = $this->select_all($sql);
            if(count($request)>0){
                for ($i=0; $i < count($request) ; $i++) { 
                    $request[$i]['r'] = boolval($request[$i]['r']);
                    $request[$i]['w'] = boolval($request[$i]['w']);
                    $request[$i]['u'] = boolval($request[$i]['u']);
                    $request[$i]['d'] = boolval($request[$i]['d']);
                }
            }
            return $request;
        }
        public function insertPermits(int $idRole,int $idmodule,int $r,int $w,int $u,int $d){
            $this->intIdRole = $idRole;
            $this->intIdModule = $idmodule;
            $this->boolR = $r;
            $this->boolW = $w;
            $this->boolU = $u;
            $this->boolD = $d;

            $sql ="INSERT INTO permit(roleid,moduleid,r,w,u,d) VALUES(?,?,?,?,?,?)";
                $arrData = array(
                    $this->intIdRole,
                    $this->intIdModule,
                    $this->boolR,
                    $this->boolW,
                    $this->boolU,
                    $this->boolD
                );
                $request = $this->insert($sql,$arrData);
            return $request;
        }
        public function deletePermits($id){
            $this->intIdRole = $id;
            $sql = "DELETE FROM permit WHERE roleid = $this->intIdRole;SET @autoid :=0; 
			UPDATE permit SET idpermit = @autoid := (@autoid+1);
			ALTER TABLE permit Auto_Increment = 1";
            $request = $this->delete($sql);
            return $request;
        }
        public function permitsModule(int $idrol){
            $this->intIdRole = $idrol;
            $sql = "SELECT p.roleid,
                            p.moduleid,
                            m.name as module,
                            p.r,
                            p.w,
                            p.u,
                            p.d
                    FROM permit p
                    INNER JOIN module m
                    ON p.moduleid = m.idmodule
                    WHERE p.roleid = $this->intIdRole";
            $request = $this->select_all($sql);
            $arrPermisos = array();
            for($i = 0; $i< count($request); $i++){
                $arrPermisos[$request[$i]['moduleid']] = $request[$i];
            }
            return $arrPermisos;
        }
        public function search($search){
            $sql = "SELECT * FROM role WHERE name LIKE '%$search%'";
            $request = $this->select_all($sql);
            return $request;
        }
        public function sort($sort){
            $option="DESC";
            if($sort == 2){
                $option = " ASC"; 
            }
            $sql = "SELECT * FROM role ORDER BY idrole $option ";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>