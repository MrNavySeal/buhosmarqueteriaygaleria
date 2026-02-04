<?php 
	
	class Mysql extends Conexion
	{
		private $conexion;
		private $query;
		private $values;

		function __construct()
		{
			$this->conexion = new Conexion();
			$this->conexion = $this->conexion->conect();
		}

		public function insert(string $query, array $values){
			$this->query = $query;
			$this->values = $values;
            $insert = 0;
            try {
                $insert = $this->conexion->prepare($this->query);
                $insert->execute($this->values);
				$id = intval($this->conexion->lastInsertId());
				if($id == 0){
					$id = $insert->rowCount();
				}
			    return $id;
            } catch (Exception  $e) {
                $this->getColumnsError($e);
            }

		}
		//Busca un registro
		public function select(string $query,$values=[]){
			$this->query = $query;
			$this->values = $values;
        	$result = $this->conexion->prepare($this->query);
			$result->execute($this->values);
        	$data = $result->fetch(PDO::FETCH_ASSOC);
        	return $data;
		}
		//Devuelve todos los registros
		public function select_all(string $query,$values=[]){
			$this->query = $query;
			$this->values = $values;
        	$result = $this->conexion->prepare($this->query);
			$result->execute($this->values);
        	$data = $result->fetchall(PDO::FETCH_ASSOC);
        	return $data;
		}

		public function update(string $query, array $values){
			$this->query = $query;
			$this->values = $values;
            $update = 0;
            try {
                $update = $this->conexion->prepare($this->query);
			    return intval($update->execute($this->values));
            } catch (Exception  $e) {
                $this->getColumnsError($e);
            }
		}

		public function delete(string $query,$values=[]){
			$this->query = $query;
			$this->values = $values;
			try {
                $result = $this->conexion->prepare($this->query);
			    return $result->execute($this->values);
            } catch (Exception  $e) {
                $this->getColumnsError($e);
            }
		}

		private function getColumnsError(Exception $e){
            $chars = str_split($this->query);
            $arrColumns = array_filter($chars,function($e){return $e=="?";});
            $totalChars = count($chars);
            $totalColumns = count($arrColumns);
            $totalValues = count($this->values);
            $totalDifference = abs($totalColumns-$totalValues);
            $cont = 0;
            for ($i=0; $i < $totalChars ; $i++) {
                if($chars[$i] == "?"){
                    $value = gettype($this->values[$cont]) == "string" ? "'".$this->values[$cont]."'" : $this->values[$cont];
                    $chars[$i] = $value;
                    $cont++;
                }
            }
            $chars = implode($chars);
            throw new Exception($e->getMessage().": columns = $totalColumns, sent values = $totalValues, difference = $totalDifference, failed query = $chars");
        }

		public function beginTransaction(){
			return $this->conexion->beginTransaction();
		}

		public function commit(){
			return $this->conexion->commit();
		}

		public function rollBack(){
			return $this->conexion->rollBack();
		}
	}


 ?>

