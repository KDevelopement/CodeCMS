<?php

    if(!function_exists('_Dump'))
    {
        function _Dump($Array, $Exit = true)
        {
            echo "<pre>";
            var_dump($Array);
            if($Exit)
                exit;
        }
    }

    if (!function_exists('vDump')) 
    {
        /**
         * Função de organização do var_dump().
         * 
         *  @param array|string $Date
         *  @param bool $EXIT
         *  @return void
         */
        function vDump($Date, $EXIT = FALSE) 
        {
            echo "<pre>"; 
            //var_dump($Date);
            dd($Date);
            if($EXIT){
                exit;
            }
        }
    }

    if (!function_exists('gDump')) 
    {
        /**
         * Alternativa vDump()
         * 
         *  @param array|string $Date
         *  @param bool $EXIT
         *  @return void
         */
        function gDump($Date, $EXIT = FALSE) 
        {
            if(isset($_GET["dd"])){ 
                vDump($Date, $EXIT);
            }
        }
    }

    if(!function_exists('delPaste'))
    {
        function delPaste($dir) { 
            $files = array_diff(scandir($dir), array('.','..')); 
            foreach ($files as $file) { 
                (is_dir("$dir/$file")) ? delPaste("$dir/$file") : unlink("$dir/$file"); 
            } 
            return rmdir($dir); 
        }
    }

    if (!function_exists('MoneyFormart')) 
    {
        /**
         * Função de formatação de money.
         * 
         *  @param string $Money
         *  @return string
         */
        function MoneyFormart($Money, $Space = False) 
        {
            return "R$" . ($Space ? " " : NULL) . number_format($Money, 2, ',', '.');
        }
    }

    if (!function_exists('CallFormart')) 
    {
        /**
         * Função de formatação de numero de telefone/celular.
         * 
         *  @param string $Phone
         *  @return string
         */
        function CallFormart($Phone) 
        {
            if (!$Phone OR empty($Phone)) { 
                $Result = NULL;
            } else {
                if (strlen($Phone) == 13) {
                    $Result = substr_replace($Phone, '+', 0, 0);
                    $Result = substr_replace($Result, ' ', 3, 0);
                    $Result = substr_replace($Result, ' ', 6, 0);
                    $Result = substr_replace($Result, ' ', 8, 0);
                    $Result = substr_replace($Result, '-', 13, 0);
                } elseif(strlen($Phone) == 11) {
                    $Result = substr_replace($Phone, '(', 0, 0);
                    $Result = substr_replace($Result, ')', 3, 0);
                    $Result = substr_replace($Result, ' ', 4, 0);
                    $Result = substr_replace($Result, ' ', 6, 0);
                    $Result = substr_replace($Result, '-', 11, 0);
                } else {
                    $Result = substr_replace($Phone, '(', 0, 0);
                    $Result = substr_replace($Result, ')', 3, 0);
                    $Result = substr_replace($Result, ' ', 4, 0);
                    $Result = substr_replace($Result, '9', 5, 0);
                    $Result = substr_replace($Result, ' ', 6, 0);
                    $Result = substr_replace($Result, '-', 11, 0);
                }
            }
            return $Result;
        }
    }

    if (!function_exists('CpfCnpjFormart')) 
    {
        /**
         * Função de formatação de numero de CPF/CNPJ.
         * 
         *  @param string $Doc
         *  @return string
         */
        function CpfCnpjFormart($Doc, $Hidden = FALSE) 
        {
            $CPF_LENGTH = 11;
            $cnpj_cpf = preg_replace("/\D/", '', $Doc);
            if (strlen($cnpj_cpf) === $CPF_LENGTH) {
                $isHiddenCPF = $Hidden ? "\$1.***.***-\$4" : "\$1.\$2.\$3-\$4";
              return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", $isHiddenCPF, $cnpj_cpf);
            } 
            $isHiddenCNPJ = $Hidden ? "\$1.***.***\$4-\$5" : "\$1.\$2.\$3/\$4-\$5";
            return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", $isHiddenCNPJ, $cnpj_cpf);
        }
    }

    if (!function_exists('CepFormart')) 
    {
        /**
         * Função de formatação de numero de CEP.
         * 
         *  @param string $CEP
         *  @return string
         */
        function CepFormart($CEP) 
        {
            $CEP_LENGTH = 8;
            $TratCEP = preg_replace("/\D/", '', $CEP);
            if (strlen($TratCEP) === $CEP_LENGTH) {
                return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $TratCEP);
            }
            return $TratCEP;
        }
    }

	if(!function_exists('vDate'))
    {
        function vDate($date, $format = 'd/m/Y')
        {
            $d = \DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
    }

    if(!function_exists('FormtDate'))
    {
        function FormtDate ($Timestamp) 
        {
            $Date = explode("-", $Timestamp);
            $Day = $Date[2]; // Dia
            $Month = $Date[1]; // Mês
            $Months = [
                "01" => "Janeiro", 
                "02" => "Fevereiro", 
                "03" => "Março", 
                "04" => "Abril", 
                "05" => "Maio", 
                "06" => "Junho", 
                "07" => "Julho", 
                "08" => "Agosto", 
                "09" => "Setembro", 
                "10" => "Outubro", 
                "11" => "Novembro", 
                "12" => "Dezembro", 
            ];
            $year = $Date[0]; // Ano
            return $Day. " " . "de" . " " . $Months["$Month"] . " " . "de" . " " . $year;
        }
    }
    
    if(!function_exists('formError'))
    {
        function formError ($Input) 
        {
            if(session('notification.errors.' . $Input)):
                echo "<div class=\"fv-plugins-message-container invalid-feedback\">";
                echo "<div data-field=\"tagify_input\" data-validator=\"notEmpty\">";
                echo session("notification.errors." . $Input);
                echo "</div>";
                echo "</div>";
            endif;
        }
    }
