<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }

include "adicionalmente/config.php";

$Cod_Equipamento=$_GET['Cod_Equipamento'];

$sql = "DELETE FROM equipamentos WHERE Cod_Equipamento= '$Cod_Equipamento'";


if (mysqli_query($conn, $sql)) {
    header("location:index.php?apagar=1");
	} 
	else 
	{
    echo "Erro ao apagar registo: " . mysqli_error($conn);
	}
include "adicionalmente/close.php"

?>