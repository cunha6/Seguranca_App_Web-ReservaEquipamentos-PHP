<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
	include "adicionalmente/config.php";

	if (isset($_POST)) {
		$Id_Categoria=$_POST["categoriasId"];
		$Nome_Categoria=$_POST["categoriaAlterar"];


	$sql = "UPDATE Categorias SET Nome_Categoria='$Nome_Categoria' WHERE Id_Categoria=$Id_Categoria";


	if (mysqli_query($conn, $sql)) {
	    header("location:index.php?alterar=1");
	} else {
	    echo "Erro ao atualizar: " . mysqli_error($conn);
	}
	}
	include "adicionalmente/close.php";
?>
