<?php
    class CurrencyConversionServiceProvider{
        private $obj;
        private $base;
        private $target;
        private $valueBase;
        public function __construct(CurrencyConversionInterface $obj,$base,$target,$valueBase){
            $this->obj = $obj;
            $this->base = $base;
            $this->target = $target;
            $this->valueBase = $valueBase;
        }
        public function getConversion(){
            return $this->obj->getConversion($this->base,$this->target,$this->valueBase);
        }
    }
?>