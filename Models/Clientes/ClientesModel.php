<?php 
    class ClientesModel extends Mysql{
        private $intId;
        private $strNombre; 
        private $strApellido;
        private $intTelefono;
        private $strCorreo; 
        private $strDireccion; 
        private $intPais;
        private $intDepartamento;
        private $intCiudad;
        private $strContrasena;
        private $intEstado;
        private $strDocumento;
        private $intRolId;
        private $strImagenNombre;
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
            WHERE p.roleid = 2 AND p.idperson != 1 AND (CONCAT(p.firstname,p.lastname) like '$strSearch%' OR p.phone like '$strSearch%' 
            OR p.address like '$strSearch%' OR co.name like '$strSearch%' OR st.name like '$strSearch%' 
            OR ci.name like '$strSearch%') 
            ORDER BY p.idperson DESC $limit";  
            $request = $this->select_all($sql);

            $sqlTotal = "SELECT count(*) as total FROM person p
            LEFT JOIN countries co ON p.countryid = co.id
            LEFT JOIN states st ON p.stateid = st.id
            LEFT JOIN cities ci ON p.cityid = ci.id
            LEFT JOIN role r ON r.idrole = p.roleid 
            WHERE p.roleid = 2 AND p.idperson != 1 AND (CONCAT(p.firstname,p.lastname) like '$strSearch%' OR p.phone like '$strSearch%' 
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
    }
?>