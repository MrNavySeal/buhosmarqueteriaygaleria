<?php 
    class TercerosModel extends Mysql{
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
        private $strFecha;

        public function __construct(){
            parent::__construct();
        }

        public function insertUsuario(array $data){
			$return = 0;
            $sql= "SELECT * FROM person WHERE (identification = '$data[documento]' 
            AND identification !='222222222') OR (email = '$data[correo]' AND email !='generico@generico.co')";
			$request = $this->select_all($sql);
			if(empty($request)){ 
				$sql  = "INSERT INTO person(image,firstname,lastname,email,phone,address,countryid,
                stateid,cityid,identification,password,status,roleid,date,is_client,is_supplier,is_other,dv,
                person_type,identification_type,regimen_type) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array(
                    $data['imagen'],
                    $data['nombre'],
                    $data['apellido'],
                    $data['correo'],
                    $data['telefono'],
                    $data['direccion'],
                    $data['pais'],
                    $data['departamento'],
                    $data['ciudad'],
                    $data['documento'],
                    $data['contrasena'],
                    $data['estado'],
                    $data['rol'],
                    $data['fecha'],
                    $data['is_cliente'],
                    $data['is_proveedor'],
                    $data['is_otro'],
                    $data['digito_verificacion'],
                    $data['tipo_persona'],
                    $data['tipo_documento'],
                    $data['tipo_regimen'],
        		);
	        	$return = $this->insert($sql,$arrData);
			}else{
				$return = "exist";
			}
	        return $return;
		}

        public function updateUsuario(int $intId,array $data){
            $this->intId = $intId;
            $sql= "SELECT * FROM person WHERE  idperson != $this->intId AND ((identification = '$data[documento]' 
            AND identification !='222222222') OR (email = '$data[correo]' AND email !='generico@generico.co'))";
            $request = $this->select_all($sql);
            
			if(empty($request)){
				if($this->strContrasena  != ""){
					$sql = "UPDATE person SET image=?,firstname=?,lastname=?,email=?,phone=?,address=?,countryid=?,
                    stateid=?,cityid=?,identification=?,password=?,status=?,roleid=?,date=?,is_client=?,is_supplier=?,is_other=?,dv=?,
                    person_type=?,identification_type=?,regimen_type=? WHERE idperson = $this->intId";
					$arrData = array(
                        $data['imagen'],
                        $data['nombre'],
                        $data['apellido'],
                        $data['correo'],
                        $data['telefono'],
                        $data['direccion'],
                        $data['pais'],
                        $data['departamento'],
                        $data['ciudad'],
                        $data['documento'],
                        $data['contrasena'],
                        $data['estado'],
                        $data['rol'],
                        $data['fecha'],
                        $data['is_cliente'],
                        $data['is_proveedor'],
                        $data['is_otro'],
                        $data['digito_verificacion'],
                        $data['tipo_persona'],
                        $data['tipo_documento'],
                        $data['tipo_regimen'],
                    );
				}else{
					$sql = "UPDATE person SET image=?,firstname=?,lastname=?,email=?,phone=?,address=?,countryid=?,
                    stateid=?,cityid=?,identification=?,status=?,roleid=?,date=?,is_client=?,is_supplier=?,is_other=?,dv=?,
                    person_type=?,identification_type=?,regimen_type=? WHERE idperson = $this->intId";
					$arrData = array(
                        $data['imagen'],
                        $data['nombre'],
                        $data['apellido'],
                        $data['correo'],
                        $data['telefono'],
                        $data['direccion'],
                        $data['pais'],
                        $data['departamento'],
                        $data['ciudad'],
                        $data['documento'],
                        $data['estado'],
                        $data['rol'],
                        $data['fecha'],
                        $data['is_cliente'],
                        $data['is_proveedor'],
                        $data['is_otro'],
                        $data['digito_verificacion'],
                        $data['tipo_persona'],
                        $data['tipo_documento'],
                        $data['tipo_regimen'],
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
    }
?>