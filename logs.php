<?php
session_start();

include "adicionalmente/header.php";


// Função para ler e exibir os logs
function displayLogs() {
    $logFile = 'C:/xampp/apache/logs/db_log.txt';

    if (file_exists($logFile)) {
        // Lê o conteúdo do arquivo de log
        $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Organiza logs pelo mais recente
        $logs = array_reverse($logs);

        // Exibe os logs em um elemento HTML
        echo '<div class="w3-container w3-card w3-white">';
        echo '<h2 class="w3-text-grey"><i class="fas fa-file-alt fa-fw w3-margin-right"></i>Logs do Sistema</h2>';
        echo '<div class="w3-container">';
        foreach ($logs as $log) {
            echo '<p class="w3-text-dark-grey">' . htmlspecialchars($log) . '</p>';
        }
        echo '</div></div>';
    } else {
        echo '<p class="w3-text-red">O arquivo de log não existe ou está vazio.</p>';
    }
}

include "adicionalmente/head.php"
?>


    <title>Visualização de Logs</title>
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Raleway", sans-serif
        }
    </style>
</head>
<body>

<div class="w3-main" style="margin-left:300px">
    <div class="">
        <?php
        displayLogs();
        ?>
    </div>
</div>

</body>
</html>