<?php 
    class Controllers{
		protected $model;
		protected $views;
		private $modelFile;
        public function __construct(){
            $this->views = new Views();
        }
        public function load($controllerFile){
			$arrView = explode("/",$controllerFile);
			$arrView[0] = "Views";
			if(count($arrView) >2){
				unset($arrView[count($arrView)-1]);
			}else{
				$arrView[count($arrView)-1] = explode(".",$arrView[count($arrView)-1])[0];
			}
			$this->modelFile = str_replace("Controllers","Models",$controllerFile);
            $this->modelFile = str_replace(".php","Model.php",$this->modelFile);
			$this->views->setViewFile(implode("/",$arrView));
			$model = get_class($this)."Model";
			if(file_exists($this->modelFile)){
				require_once($this->modelFile);
				$this->model = new $model();
			}
		}
    }

?>