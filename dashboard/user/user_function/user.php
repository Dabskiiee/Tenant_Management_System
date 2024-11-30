<?php
require_once __DIR__.'/../../../database/dbconnection.php';
include_once __DIR__.'/../../../config/settings-configuration.php';
require_once __DIR__.'/../../admin/authentication/admin-class.php';
require_once __DIR__.'/../../../src/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

CLASS User{

        private $conn;
        private $settings;
        private $smtp_email;
        private $smtp_password;

    public function __construct(){
            $this->settings = new SystemConfig();
            $this->smtp_email = $this->settings->getSmtpEmail();
            $this->smtp_password = $this->settings->getSmtpPassword();

            $database = new Database();
            $this->conn = $database->dbConnection();
    }

    public function upload($to_whom,$type,$text){



    }
    

    
}

if (isset($_POST['btn-submit-sup'])) {
    $to_whom = trim($_POST['person']);
    $type =trim($_POST['type']);
    $text =trim($_POST['message']);

}