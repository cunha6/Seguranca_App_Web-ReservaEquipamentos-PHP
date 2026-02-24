<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "adicionalmente/config.php";

    session_start();
    if (!isset($_SESSION)) {
        header('location:login.php');
        exit;
    }

    if (isset($_POST)) {
        $id = $_SESSION['Num_Utilizador'];
        //Acesso aos dados do formulário
        $tipo_reservante_reserva                 =    $_POST["tipo_reservante_reserva"];
        $nome_reserva                            =    $_POST["nome_reserva"];
        $apelido_reserva                         =    $_POST["apelido_reserva"];
        $email_reservante_reserva                =    $_POST["email_reservante_reserva"];
        $Nrcartao_reserva                        =    $_POST["nrcartao_reserva"];
        $Data_prevfim_reserva                    =    $_POST["data_prevfim_reserva"];
        $ano_reserva                             =    $_POST["ano_reserva"];
        $curso_reserva                           =    $_POST["curso_reserva"];
        $turma_reserva                           =    $_POST["turma_reserva"];
        $quantidade_reserva                      =    $_POST["quantidade_reserva"];
        $Cod_Equipamento                         =    $_POST["Cod_Equipamento"];

        // Query para a inserção de dados na BD
            $sql = "INSERT INTO Reservas (Cod_Equipamento, Nome, Apelido, email_reservante, Nrcartao, ano, curso, turma, quantidade_reserva, Num_Utilizador, data_prevfim_reserva, tipo_reservante)
            VALUES ('$Cod_Equipamento', '$nome_reserva', '$apelido_reserva', '$email_reservante_reserva', '$Nrcartao_reserva', '$ano_reserva', '$curso_reserva', '$turma_reserva', '$quantidade_reserva', '$id', '$Data_prevfim_reserva' ,'$tipo_reservante_reserva')";

            if (mysqli_query($conn, $sql)) {
                header("location:lista_reservas.php?insere=1");
            } else {
                // Apresenta o erro
                echo "Erro: " . $sql . "<br>" . mysqli_error($conn);
}        
            
    }

    include "adicionalmente/close.php";
}



?>