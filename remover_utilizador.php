<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) {
    header('location: login.php');
    exit;
}

include "adicionalmente/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover'])) {
    $Num_Utilizador = $_POST['id'];


    $query = "DELETE FROM utilizadores WHERE Num_Utilizador = ?";
    $stmt = mysqli_prepare($conn, $query);
    $stmt->bind_param("i", $Num_Utilizador);

    if ($stmt->execute()) {
        header('location: lista-users.php');
        exit;
    } else {
        header('location: lista-users.php?erro=1');
        exit;
    }
} else {
    header('location: lista-users.php');
    exit;
}

include "adicionalmente/close.php";
?>
