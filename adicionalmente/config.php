<?php
if (!function_exists('logMessage')) {
    define('LOG_FILE', 'C:/xampp/apache/logs/db_log.txt');

    function logMessage($message, $logType = 'info') {
        $allowedLogTypes = ['info', 'error', 'debug']; // Adicione outros tipos de log conforme necessário

        // Verificar se o tipo de log é permitido
        if (!in_array($logType, $allowedLogTypes)) {
            $logType = 'info'; 
        }

        $logFile = LOG_FILE;
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp][$logType] $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}


$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "SecondLife";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Erro de ligação: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

logMessage('Ligação à base de dados efetuada com sucesso.');

?>