<?php

    declare(strict_types=1);

    namespace Config;

    use CodeIgniter\Config\BaseConfig;

    class Config extends BaseConfig
    {

        public string $AppName = "K'Seven";

        public string $Description = 'Gerenciamento de script php, versionamento e licenciamento.';

        public string $Keywords = 'Key, manager';

        public string $Dir = "ltr";

        public string $Theme = 'default'; 

        public string $AdminPath = "demo";
        
        public bool $saveLogs = false;

        public string $DateFormat = "d/m/Y";

        public string $DateTimeFormat = "d/m/Y H:i";

        public string $DateTimeSegFormat = "d/m/Y H:i:s";

        public string $TimeFormat = "H:i";

        public string $TimeSegFormat = "H:i:s";

    }