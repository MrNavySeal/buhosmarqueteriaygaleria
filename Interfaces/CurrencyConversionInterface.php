<?php
    interface CurrencyConversionInterface{
        function getConversion(string $base,string $target,$valueBase);
    }
?>