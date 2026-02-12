<?php 
    class CajasModel extends Mysql{
        private $id;

        public function __construct(){
            parent::__construct();
        }

        /*************************Category methods*******************************/
        public function insertDatos(array $data){
            $sql  = "INSERT INTO sale_branch(name,country_id,state_id,city_id,phone,address,status) VALUES(?,?,?,?,?,?,?)";
            $request = $this->insert($sql,[
                ucfirst($data['nombre']),
                $data['pais'],
                $data['departamento'],
                $data['ciudad'],
                $data['telefono'],
                $data['direccion'],
                $data['estado']
            ]);
	        return $request;
		}

        public function updateDatos(int $id,array $data){
            $sql = "UPDATE sale_branch SET name=?,country_id=?,state_id=?,city_id=?,phone=?,address=?,status=? WHERE id = ?";
            $request = $this->update($sql,[
                ucfirst($data['nombre']),
                $data['pais'],
                $data['departamento'],
                $data['ciudad'],
                $data['telefono'],
                $data['direccion'],
                $data['estado'],
                $id
            ]);
			return $request;
		}

        public function deleteDatos($id){
            $sql = "DELETE FROM sale_branch WHERE id = $id";
            $return = $this->delete($sql);
            return $return;
        }

        public function selectDatos($intPage,$intPerPage,$strSearch){
            $limit ="";
            $intStartPage = ($intPage-1)*$intPerPage;
            if($intPerPage != 0){
                $limit = " LIMIT $intStartPage,$intPerPage";
            }

            $sql = "SELECT cab.*,
            co.name as country, 
            st.name as state, 
            ci.name as city 
            FROM sale_branch cab
            INNER JOIN countries co ON cab.country_id = co.id
            INNER JOIN states st ON cab.state_id = st.id
            INNER JOIN cities ci ON cab.city_id = ci.id
            WHERE cab.name like '$strSearch%' OR co.name like '$strSearch%' 
            OR st.name like '$strSearch%' OR ci.name like '$strSearch%' 
            ORDER BY cab.id DESC $limit";  

            $sqlTotal = "SELECT count(*) as total FROM sale_branch cab
            INNER JOIN countries co ON cab.country_id = co.id
            INNER JOIN states st ON cab.state_id = st.id
            INNER JOIN cities ci ON cab.city_id = ci.id
            WHERE cab.name like '$strSearch%' OR co.name like '$strSearch%' 
            OR st.name like '$strSearch%' OR ci.name like '$strSearch%' 
            ORDER BY cab.id";

            $request = $this->select_all($sql);
            $totalRecords = $this->select($sqlTotal)['total'];

            $arrData = getCalcPages($totalRecords,$intPage,$intPerPage);
            $arrData['data'] = $request;
            return $arrData;
        }

        public function selectDato($id){
            $sql = "SELECT * FROM sale_branch WHERE id = $id";
            $request = $this->select($sql);
            return $request;
        }
    }
?>