<?php
    class RolesModel extends Mysql{

        private $intId;
        private $strName; 


        public function __construct(){
            parent::__construct();
        }
        //Roles
        public function insertRol($strName){
            $this->strName = $strName;
            $sql = "SELECT * FROM role WHERE name = '$this->strName'";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "INSERT INTO role(name) VALUES(?)";
                $request = intval($this->insert($sql,[$this->strName]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function updateRol($intId,$strName){
            $this->strName = $strName;
            $this->intId = $intId;
            $sql = "SELECT * FROM role WHERE name = '$this->strName' AND idrole != $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "UPDATE role SET name=? WHERE idrole=$this->intId";
                $request = intval($this->update($sql,[$this->strName]));
            }else{
                $request = "existe";
            }
            return $request;
        }
        public function deleteRol($intId){
            $this->intId = $intId;
            $sql = "SELECT * FROM person WHERE roleid = $this->intId";
            $request = $this->select($sql);
            if(empty($request)){
                $sql = "DELETE FROM role WHERE idrole = $this->intId";
                $request = $this->delete($sql);
            }else{
                $request ="existe";
            }
            return $request;
        }
        public function selectRol($intId){
            $this->intId = $intId;
            $sql = "SELECT *,idrole as id FROM role WHERE idrole = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function selectRoles($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT idrole as id, name FROM role WHERE name like  '$strSearch%' ORDER BY idrole DESC $limit";
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM role WHERE name like '$strSearch%'";
            $totalRecords = $this->select($sqlTotal)['total'];
            $totalPages = intval($totalRecords > 0 ? ceil($totalRecords/$intPerPage) : 0);
            $totalPages = $totalPages == 0 ? 1 : $totalPages;
            $startPage = max(1, $intPage - floor(BUTTONS / 2));
            if ($startPage + BUTTONS - 1 > $totalPages) {
                $startPage = max(1, $totalPages - BUTTONS + 1);
            }
            $limitPages = min($startPage + BUTTONS, $totalPages+1);
            $arrButtons = [];
            for ($i=$startPage; $i < $limitPages; $i++) { 
                array_push($arrButtons,$i);
            }
            $arrData = array(
                "data"=>$request,
                "start_page"=>$startPage,
                "limit_page"=>$limitPages,
                "total_pages"=>$totalPages,
                "total_records"=>$totalRecords,
                "buttons"=>$arrButtons
            );
            return $arrData;
        }

        //Permisos
        public function insertPermisos($intId,$arrData){
            $this->intId = $intId;
            $this->delete("DELETE FROM module_permissions WHERE role_id = $this->intId;SET @autoid :=0; 
			UPDATE module_permissions SET id = @autoid := (@autoid+1);
			ALTER TABLE module_permissions Auto_Increment = 1");

            $this->delete("DELETE FROM module_sections_permissions WHERE role_id = $this->intId;SET @autoid :=0; 
			UPDATE module_sections_permissions SET id = @autoid := (@autoid+1);
			ALTER TABLE module_sections_permissions Auto_Increment = 1");

            $this->delete("DELETE FROM module_options_permissions WHERE role_id = $this->intId;SET @autoid :=0; 
			UPDATE module_options_permissions SET id = @autoid := (@autoid+1);
			ALTER TABLE module_options_permissions Auto_Increment = 1");
            foreach ($arrData as $modulo) {
                $sql = "INSERT INTO module_permissions(role_id,module_id,r,w,u,d) VALUES(?,?,?,?,?,?)";
                $request = $this->insert($sql,[$this->intId,$modulo['id'],$modulo['r'],$modulo['w'],$modulo['u'],$modulo['d']]);
                if($request == 0){  $request = ""; break;}
                foreach ($modulo['options'] as $option) {
                    $sql = "INSERT INTO module_options_permissions(role_id,option_id,r,w,u,d) VALUES(?,?,?,?,?,?)";
                    $request = $this->insert($sql,[$this->intId,$option['id'],$option['r'],$option['w'],$option['u'],$option['d']]);
                    if($request == 0){  $request = ""; break;}
                }
                foreach ($modulo['sections'] as $section) {
                    $sql = "INSERT INTO module_sections_permissions(role_id,section_id,r,w,u,d) VALUES(?,?,?,?,?,?)";
                    $request = $this->insert($sql,[$this->intId,$section['id'],$section['r'],$section['w'],$section['u'],$section['d']]);
                    if($request == 0){  $request = ""; break;}

                    foreach ($section['options'] as $option) {
                        $sql = "INSERT INTO module_options_permissions(role_id,option_id,r,w,u,d) VALUES(?,?,?,?,?,?)";
                        $request = $this->insert($sql,[$this->intId,$option['id'],$option['r'],$option['w'],$option['u'],$option['d']]);
                        if($request == 0){  $request = ""; break;}
                    }
                }
            }
            return $request;
        }
        public function selectPermisos($intId){
            $this->intId = $intId;
            $sql = "SELECT idmodule as id,name FROM module";
            $arrModules = $this->select_all($sql);
            foreach ($arrModules as &$module) {
                $sql = "SELECT r,w,u,d FROM module_permissions WHERE module_id = $module[id] AND role_id = $this->intId";
                $request = $this->select($sql);
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
                
                $sql = "SELECT * FROM module_sections WHERE module_id = $module[id]";
                $arrSections = $this->select_all($sql);
                foreach ($arrSections as &$section) {
                    $sql = "SELECT r,w,u,d FROM module_sections_permissions WHERE section_id = $section[id] AND role_id = $this->intId";
                    $request = $this->select($sql);
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
                    $sql = "SELECT * FROM module_options WHERE section_id = $section[id]";
                    $arrOptionsSection = $this->select_all($sql);
                    foreach ($arrOptionsSection as &$optionSection) {
                        $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $optionSection[id] AND role_id = $this->intId";
                        $request = $this->select($sql);
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
                    }
                    $section['options'] = $arrOptionsSection;
                }
                $sql = "SELECT * FROM module_options WHERE module_id = $module[id] AND section_id = 0";
                $arrOptions = $this->select_all($sql);
                foreach ($arrOptions as &$option) {
                    $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $option[id] AND role_id = $this->intId";
                    $request = $this->select($sql);
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
                }
                $module['options'] = $arrOptions;
                $module['sections'] = $arrSections;
            }
            return $arrModules;
        }
        
    }
?>