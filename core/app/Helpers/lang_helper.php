<?php 

    if ( ! function_exists('__'))
    {
        /**
         * __
         * 
         * @param string $Line
         * @param array $Args
         * @param null|string $Locale
         * 
         * @return string
         */
        function __(string $Line, array $Args = [], ?string $Locale = null) { 
            return lang("KLicense." . $Line, $Args, $Locale);
        }
    }
