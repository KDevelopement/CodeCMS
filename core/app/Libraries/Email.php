<?php

    namespace App\Libraries;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    /**
     * Controle de plugin
     * @author K'Seven - https://kseven.dev.br/
     */

     class Email {

        /**
         * @var string
         */
        protected $Config;

        /**
         * @var string
         */
        protected $newLine;

        /**
         * @var string
         */
        protected $Template;

        /**
         * @var string
         */
        protected $TypeMail;

        /**
         * @var string
         */
        protected $Email;
        
        public function __construct()
        {
            $this->newLine = is_cli() ? "\r\n" : "<br>&nbsp;";
            $this->Config =  [
                "Name" => setting('Email.fromName'),
                "Email" => setting('Email.fromEmail'),
                "Method" => setting('Email.protocol'),
                "Host" => setting('Email.SMTPHost'),
                "User" => setting('Email.SMTPUser'),
                "Password" => setting('Email.SMTPPass'),
                "Port" => setting('Email.SMTPPort'),
                "Security" => setting('Email.SMTPCrypto'),
            ];
            $this->Template = setting('Notification.Template');
            $this->TypeMail = setting('Notification.Information');
            $this->Email = null;
        }

        public function setTemplate($Template)
        {
            $this->Template = $Template;
            return $this;
        }

        public function Email($Email)
        {
            $this->Email = $Email;
            return $this;
        }

        public function TypeMail($TypeMail)
        {
            if ($TypeMail == "LicenseExpired") {
                $Result = setting('Notification.LicenseExpiredCode');
            } else if ($TypeMail == "SupportExpired") {
                $Result = setting('Notification.SupportExpiredCode');
            } else if ($TypeMail == "UpdateExpired") {
                $Result = setting('Notification.UpdateExpiredCode');
            } else if ($TypeMail == "NewVersion") {
                $Result = setting('Notification.NewVersionCode');
            } else {
                $Result = setting('Notification.Information');
            }
            $this->TypeMail = $Result;
            return $this;
        }

        public function Sent($Date)
        {
            if (empty($this->Email)) {
                return false;
            }
            //enviar
            return true;
        }

     }