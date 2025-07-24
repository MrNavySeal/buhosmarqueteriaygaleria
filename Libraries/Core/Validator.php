<?php 
    class Validator{
        /**
         * Arreglo donde se almacenan los errores
         * @var array
         */
        private $errors = [];
        /**
         * Función para hacer las respectivas validaciones de los campos recibidos
         * @param array $fields los campos a validar
         * @param array $data la información a validar
         * @return object $this retorna la instancia de la clase para usar sus métodos
         */
        public function validate($fields,$data=[]){
            if(!empty($data)){
                $arrFields = $data;
            }else if(!empty($_POST)){
                $arrFields = $_POST;
            }else if(!empty($_GET)){
                $arrFields = $_GET;
            }
            foreach ($fields as $field => $rule) {
                $content = $arrFields[$field];
                $arrRules = explode("|",$rule);
                foreach ($arrRules as $ruleSet) {
                    $params = null;
                    if(strpos($ruleSet,":") !== false){
                        [$ruleName,$params] = explode(":",$ruleSet);
                    }else{
                        $ruleName = $ruleSet;
                    }
                    $method = "validate".ucFirst(strtolower($ruleName));
                    if(method_exists($this,$method)){
                        $result = $this->$method($content,$params);
                        if(!$result){
                            $this->errors[$field][] = $this->getMessage($ruleName,$params,$content);
                        }
                    }
                }
            }
            return $this;
        }
        /**
         * @return array $errors retorna los errores encontrados
         */
        public function getErrors(){
            return $this->errors;
        }
        private function validateString($content){
            return is_string($content);
        }
        private function validateArray($content){
            return is_array($content);
        }
        private function validateInteger($content){
            return is_int($content);
        }
        private function validateNumeric($content){
            return is_numeric($content);
        }
        private function validateDouble($content){
            return is_double($content);
        }
        private function validateRequired($content){
            return !empty($content);
        }
        private function validateMin($content,$params){
            $type = gettype($content);
            if($type == "string"){
                return strlen($content) >= intval($params);
            }else if($type == "integer" || $type =="double"){
                return $content >= intval($params);
            }else if($type == "array"){
                return count($content) >= intval($params);
            }
        }
        private function validateMax($content,$params){
            $type = gettype($content);
            if($type == "string"){
                return strlen($content) <= intval($params);
            }else if($type == "integer" || $type =="double"){
                return $content <= intval($params);
            }else if($type == "array"){
                return count($content) <= intval($params);
            }
        }
        private function getMessage($rule,$params,$content){
            $messages = [
                "required" => "El campo es obligatorio",
                "string"=>"El campo debe ser texto",
                "numeric"=>"El campo debe ser numérico",
                "array"=>"El campo debe ser una lista",
                "integer"=>"El campo debe ser un número entero",
                "double"=>"El campo debe ser un número con decimales",
                "string_min"=>"El campo debe tener al menos $params carácteres",
                "string_max"=>"El campo debe tener máximo $params carácteres",
                "array_min"=>"El campo debe tener al menos $params elementos",
                "array_max"=>"El campo debe tener máximo $params elementos",
                "numeric_min"=>"El campo debe ser mayor o igual a $params",
                "numeric_max"=>"El campo debe ser menor o igual a $params",
                "integer_min"=>"El campo debe ser mayor o igual a $params",
                "integer_max"=>"El campo debe ser menor o igual a $params",
                "double_min"=>"El campo debe ser mayor o igual a $params",
                "double_max"=>"El campo debe ser menor o igual a $params",
            ];
            $type = gettype($content);
            $rule = in_array($rule,["min","max"]) ? $type."_".$rule : $rule; 
            return $messages[$rule]; 
        }
    }
?>