<?php 
    class UsuariosModel extends Mysql{
        private $intId;
        private $strNombre; 
        private $strApellido;
        private $intTelefono;
        private $intPaisTelefono;
        private $strCorreo; 
        private $strDireccion; 
        private $intPais;
        private $intDepartamento;
        private $intCiudad;
        private $strContrasena;
        private $intEstado;
        private $intTipoDocumento;
        private $strDocumento;
        private $intRolId;
        private $strImagenNombre;
        private $intPorPagina;
        private $intPaginaActual;
        private $intPaginaInicio;
        private $strBuscar;

        public function __construct(){
            parent::__construct();
        }
        public function insertUsuario(string $strNombre, string $strApellido,string $intTelefono, string $strCorreo, string $strDireccion, 
        int $intPais, int $intDepartamento, int $intCiudad,string $strContrasena,int $intEstado,string $strDocumento,int $intRolId,string $strImagenNombre){
            $this->strImagenNombre = $strImagenNombre;
			$this->strNombre = $strNombre;
			$this->strApellido = $strApellido;
            $this->strDocumento = $strDocumento;
            $this->strCorreo = $strCorreo;
			$this->intTelefono = $intTelefono;
            $this->strDireccion = $strDireccion;
            $this->intPais = $intPais;
            $this->intDepartamento = $intDepartamento;
            $this->intCiudad = $intCiudad;
            $this->strContrasena = $strContrasena;
            $this->intEstado = $intEstado;
            $this->intRolId = $intRolId;
            
			$return = 0;
            $strDocumento = "";
            $strCorreo ="";
            if($this->strDocumento != "222222222"){
                $strDocumento = " OR identification = '{$this->strDocumento}'";
            }
            if($this->strCorreo != "generico@generico.co"){
                $strCorreo = " OR email = '{$this->strCorreo}'";
            }
            $sql= "SELECT * FROM person WHERE phone = '{$this->intTelefono}' $strDocumento $strCorreo";
			$request = $this->select_all($sql);
			if(empty($request)){ 
				$sql  = "INSERT INTO person(image,firstname,lastname,email,phone,address,countryid,stateid,cityid,identification,password,status,roleid) 
								  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array(
                    $this->strImagenNombre,
                    $this->strNombre,
                    $this->strApellido,
                    $this->strCorreo,
                    $this->intTelefono,
                    $this->strDireccion,
                    $this->intPais,
                    $this->intDepartamento,
                    $this->intCiudad,
                    $this->strDocumento,
                    $this->strContrasena,
                    $this->intEstado,
                    $this->intRolId,
        		);
	        	$return = $this->insert($sql,$arrData);
			}else{
				$return = "exist";
			}
	        return $return;
		}
        public function updateUsuario(int $intId,string $strNombre, string $strApellido,string $intTelefono, string $strCorreo, string $strDireccion, 
        int $intPais, int $intDepartamento, int $intCiudad,string $strContrasena,int $intEstado,string $strDocumento,int $intRolId, $strImagenNombre){
            $this->intId = $intId;
            $this->strImagenNombre = $strImagenNombre;
			$this->strNombre = $strNombre;
			$this->strApellido = $strApellido;
            $this->strDocumento = $strDocumento;
            $this->strCorreo = $strCorreo;
			$this->intTelefono = $intTelefono;
            $this->strDireccion = $strDireccion;
            $this->intPais = $intPais;
            $this->intDepartamento = $intDepartamento;
            $this->intCiudad = $intCiudad;
            $this->strContrasena = $strContrasena;
            $this->intEstado = $intEstado;
            $this->intRolId = $intRolId;
            $strDocumento = "";
            $strCorreo ="";
            if($this->strDocumento != "222222222"){
                $strDocumento = " OR identification = '{$this->strDocumento}'";
            }
            if($this->strCorreo != "generico@generico.co"){
                $strCorreo = " OR email = '{$this->strCorreo}'";
            }
            $sql= "SELECT * FROM person WHERE  (phone = '$this->intTelefono' $strDocumento $strCorreo) AND  idperson != $this->intId";
            $request = $this->select_all($sql);
            
			if(empty($request)){
				if($this->strContrasena  != ""){
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?,address=?,countryid=?,stateid=?,cityid=?,identification=?, 
                    password=?, status=?,roleid=?
                    WHERE idperson = $this->intId";
					$arrData = array(
                        $this->strImagenNombre,
                        $this->strNombre,
                        $this->strApellido,
                        $this->strCorreo,
                        $this->intTelefono,
                        $this->strDireccion,
                        $this->intPais,
                        $this->intDepartamento,
                        $this->intCiudad,
                        $this->strDocumento,
                        $this->strContrasena,
                        $this->intEstado,
                        $this->intRolId,
                    );
				}else{
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?,address=?,countryid=?,stateid=?,cityid=?,identification=?,status=?,roleid=?
                            WHERE idperson = $this->intId";
					$arrData = array(
                        $this->strImagenNombre,
                        $this->strNombre,
                        $this->strApellido,
                        $this->strCorreo,
                        $this->intTelefono,
                        $this->strDireccion,
                        $this->intPais,
                        $this->intDepartamento,
                        $this->intCiudad,
                        $this->strDocumento,
                        $this->intEstado,
                        $this->intRolId,
                    );
				}
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		
		}
        public function selectUsuarios($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }
            $sql = "SELECT p.idperson as id,
            DATE_FORMAT(p.date, '%d/%m/%Y') as fecha,
            p.status,
            p.image,
            p.identification as documento,
            co.name as pais,
            st.name as departamento,
            ci.name as ciudad,
            r.name as role,
            p.roleid,
            p.phone as telefono,
            CONCAT(p.firstname,' ',p.lastname) as nombre,
            p.address as direccion
            FROM person p
            LEFT JOIN countries co ON p.countryid = co.id
            LEFT JOIN states st ON p.stateid = st.id
            LEFT JOIN cities ci ON p.cityid = ci.id
            LEFT JOIN role r ON r.idrole = p.roleid 
            WHERE p.roleid != 2 AND p.idperson != 1 AND (CONCAT(p.firstname,p.lastname) like '$strSearch%' OR p.phone like '$strSearch%' 
            OR p.address like '$strSearch%' OR co.name like '$strSearch%' OR st.name like '$strSearch%' 
            OR ci.name like '$strSearch%') 
            ORDER BY p.idperson DESC $limit";  
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM person p
            LEFT JOIN countries co ON p.countryid = co.id
            LEFT JOIN states st ON p.stateid = st.id
            LEFT JOIN cities ci ON p.cityid = ci.id
            LEFT JOIN role r ON r.idrole = p.roleid 
            WHERE p.roleid != 2 AND p.idperson != 1 AND (CONCAT(p.firstname,p.lastname) like '$strSearch%' OR p.phone like '$strSearch%' 
            OR p.address like '$strSearch%' OR co.name like '$strSearch%' OR st.name like '$strSearch%' 
            OR ci.name like '$strSearch%') 
            ORDER BY p.idperson";

            foreach ($request as &$data) { 
                $data['url'] = media()."/images/uploads/".$data['image'];
            }

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
        public function selectUsuario(int $intId){
            $this->intId = $intId;
            $sql = "SELECT *, idperson as id FROM person WHERE idperson = $this->intId";
            $request = $this->select($sql);
            return $request;
        }
        public function deleteUsuario($id){
            $this->intId = $id;
            $sql = "DELETE FROM person WHERE idperson = $this->intId";
            $request = $this->delete($sql);
            return $request;
        }
        public function selectRoles(){
            $sql = "SELECT * FROM role ORDER BY name";
            $request = $this->select_all($sql);
            return $request;
        }
        //Permisos
        public function insertPermisos($intRolId,$intId,$arrData){
            $this->intRolId = $intRolId;
            $this->intId = $intId;

            $this->delete("DELETE FROM module_permissions WHERE role_id = $this->intRolId AND user_id = $this->intId;SET @autoid :=0; 
            UPDATE module_permissions SET id = @autoid := (@autoid+1);
            ALTER TABLE module_permissions Auto_Increment = 1");

            $this->delete("DELETE FROM module_sections_permissions WHERE role_id = $this->intRolId AND user_id = $this->intId;SET @autoid :=0; 
            UPDATE module_sections_permissions SET id = @autoid := (@autoid+1);
            ALTER TABLE module_sections_permissions Auto_Increment = 1");

            $this->delete("DELETE FROM module_options_permissions WHERE role_id = $this->intRolId AND user_id = $this->intId;SET @autoid :=0; 
            UPDATE module_options_permissions SET id = @autoid := (@autoid+1);
            ALTER TABLE module_options_permissions Auto_Increment = 1");
            foreach ($arrData as $modulo) {
                $sql = "INSERT INTO module_permissions(role_id,user_id,module_id,r,w,u,d) VALUES(?,?,?,?,?,?,?)";
                $request = $this->insert($sql,[$this->intRolId,$this->intId,$modulo['id'],$modulo['r'],$modulo['w'],$modulo['u'],$modulo['d']]);
                if($request == 0){  $request = ""; break;}
                foreach ($modulo['options'] as $option) {
                    $sql = "INSERT INTO module_options_permissions(role_id,user_id,option_id,r,w,u,d) VALUES(?,?,?,?,?,?,?)";
                    $request = $this->insert($sql,[$this->intRolId,$this->intId,$option['id'],$option['r'],$option['w'],$option['u'],$option['d']]);
                    if($request == 0){  $request = ""; break;}
                }
                foreach ($modulo['sections'] as $section) {
                    $sql = "INSERT INTO module_sections_permissions(role_id,user_id,section_id,r,w,u,d) VALUES(?,?,?,?,?,?,?)";
                    $request = $this->insert($sql,[$this->intRolId,$this->intId,$section['id'],$section['r'],$section['w'],$section['u'],$section['d']]);
                    if($request == 0){  $request = ""; break;}

                    foreach ($section['options'] as $option) {
                        $sql = "INSERT INTO module_options_permissions(role_id,user_id,option_id,r,w,u,d) VALUES(?,?,?,?,?,?,?)";
                        $request = $this->insert($sql,[$this->intRolId,$this->intId,$option['id'],$option['r'],$option['w'],$option['u'],$option['d']]);
                        if($request == 0){  $request = ""; break;}
                    }
                }
            }
            return $request;
        }
        public function selectPermisos($intRolId,$intId){
            $this->intRolId = $intRolId;
            $this->intId = $intId;
            $sql = "SELECT idmodule as id,name FROM module";
            $arrModules = $this->select_all($sql);
            foreach ($arrModules as &$module) {
                $sql = "SELECT r,w,u,d FROM module_permissions WHERE module_id = $module[id] AND role_id = $this->intRolId AND user_id = $this->intId";
                $request = $this->select($sql);
                if(empty($request)){
                    $sql = "SELECT r,w,u,d FROM module_permissions WHERE module_id = $module[id] AND role_id = $this->intRolId";
                    $request = $this->select($sql);
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
                $sql = "SELECT * FROM module_sections WHERE module_id = $module[id]";
                $arrSections = $this->select_all($sql);
                foreach ($arrSections as &$section) {
                    $sql = "SELECT r,w,u,d FROM module_sections_permissions WHERE section_id = $section[id] AND role_id = $this->intRolId AND user_id = $this->intId";
                    $request = $this->select($sql);
                    if(empty($request)){
                        $sql = "SELECT r,w,u,d FROM module_sections_permissions WHERE section_id = $section[id] AND role_id = $this->intRolId";
                        $request = $this->select($sql);
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
                    $sql = "SELECT * FROM module_options WHERE section_id = $section[id]";
                    $arrOptionsSection = $this->select_all($sql);
                    foreach ($arrOptionsSection as &$optionSection) {
                        
                        $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $optionSection[id] AND role_id = $this->intRolId AND user_id = $this->intId";
                        $request = $this->select($sql);
                        if(empty($request)){
                            $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $optionSection[id] AND role_id = $this->intRolId";
                            $request = $this->select($sql);
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
                    }
                    $section['options'] = $arrOptionsSection;
                }
                $sql = "SELECT * FROM module_options WHERE module_id = $module[id] AND section_id = 0";
                $arrOptions = $this->select_all($sql);
                foreach ($arrOptions as &$option) {
                    $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $option[id] AND role_id = $this->intRolId AND user_id = $this->intId";
                    $request = $this->select($sql);
                    if(empty($request)){
                        $sql = "SELECT r,w,u,d FROM module_options_permissions WHERE option_id = $option[id] AND role_id = $this->intRolId";
                        $request = $this->select($sql);
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
                }
                $module['options'] = $arrOptions;
                $module['sections'] = $arrSections;
            }
            return $arrModules;
        }
        /*************************Profile methods*******************************/
        public function updatePerfil(int $idUser, string $strName,string $strLastName, string $strPicture, string $intPhone,string $strAddress, 
            int $intCountry, int $intState,int $intCity,string $strIdentification, string $strEmail, string $strPassword){
            
            $this->intId = $idUser;
			$this->strNombre = $strName;
			$this->strApellido = $strLastName;
			$this->intTelefono = $intPhone;
			$this->strCorreo = $strEmail;
			$this->strContrasena = $strPassword;
            $this->strImagenNombre = $strPicture;
            $this->strDireccion = $strAddress;
            $this->intPais = $intCountry;
            $this->intEstado = $intState;
            $this->intCiudad = $intCity;
            $this->strDocumento = $strIdentification;

			$sql = "SELECT * FROM person WHERE (email = '{$this->strCorreo}' OR identification = '{$this->strDocumento}' OR phone = '{$this->intTelefono}') AND idperson != $this->intId";
			$request = $this->select_all($sql);

			if(empty($request)){
				if($this->strContrasena  != ""){
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?, address=?, countryid=?, stateid=?, cityid=?,identification=?, password=? 
							WHERE idperson = $this->intId";
					$arrData = array(
                        $this->strImagenNombre,
                        $this->strNombre,
                        $this->strApellido,
                        $this->strCorreo,
                        $this->intTelefono,
                        $this->strDireccion,
                        $this->intPais,
                        $this->intEstado,
                        $this->intCiudad,
                        $this->strDocumento,
                        $this->strContrasena
                    );
				}else{
					$sql = "UPDATE person SET image=?, firstname=?, lastname=?,email=?, phone=?, address=?, countryid=?, stateid=?, cityid=?,identification=?
							WHERE idperson = $this->intId";
					$arrData = array(
                        $this->strImagenNombre,
                        $this->strNombre,
                        $this->strApellido,
                        $this->strCorreo,
                        $this->intTelefono,
                        $this->strDireccion,
                        $this->intPais,
                        $this->intEstado,
                        $this->intCiudad,
                        $this->strDocumento
                    );
				}
				$request = $this->update($sql,$arrData);
                $_SESSION['userData'] = sessionUser($this->intId);

			}else{
				$request = "exist";
			}
			return $request;
		
		}
    }
?>