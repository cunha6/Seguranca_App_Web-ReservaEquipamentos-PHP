<?php 
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
include "adicionalmente/config.php";

include "adicionalmente/head.php"
?>
<title>Alterar Categorias</title>
</head>
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

<body class="w3-light-grey w3-content" style="max-width:2000px">
<?php include "adicionalmente/header.php" ?>
<div class="w3-main" style="margin-left:300px">        
        <header id="portfolio" class="w3-main w3-top w3-light-grey">
            <a href="#"><img src="imagens/logo6.png" style="width:65px;" class=" w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
            <span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
        </header>

        <br><br><br>
	<?php 

        $sql = "SELECT Id_Categoria, Nome_Categoria FROM Categorias";
		$result = mysqli_query($conn, $sql);
		$Id_Categoria=$_GET["Id_Categoria"];

		$sql_categoria = "SELECT * FROM Categorias where Id_Categoria=$Id_Categoria";
		$result_categoria = mysqli_query($conn, $sql_categoria);
		$row_categoria = mysqli_fetch_assoc($result_categoria);

    ?>

    <div class="w3-container w3-medium w3-card-4 w3-light-grey">
        <div class="w3-bottombar">
            <h2><b>Alterar Categoria</b></h2>
        </div>
	<form class="w3-container w3-light-grey" action="alterarcategoria.php" method="post">
        <div class="card-body">
            <label><b>Categorias: &nbsp;</b></label>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <input type="hidden" id="cat<?php echo $row['Id_Categoria'] ?>" name="categorias[]" class="w3-check" value="<?php echo $row['Id_Categoria'] ?>" disabled>
                <label for="cat<?php echo $row['Id_Categoria'] ?>"> <?php echo $row['Nome_Categoria'] ?> | </label>
            <?php
            }
            ?>
            <br><br>
                <p>
		    <input type="hidden" name="categoriasId" value="<?php echo $Id_Categoria ?>" />
                    <label><b>Nome da Categoria</b></label>
                    <input class="w3-input w3-animate-input" type="text" name="categoriaAlterar" value="<?php echo $row_categoria["Nome_Categoria"];?>" required>
                </p>
                <p>
                    <button class="btn w3-btn botao w3-right btn-rounded" type="reset"> Limpar </button>
                    <button class="btn w3-btn botao w3-right btn-rounded" type="submit" style="margin-right: 1%;">Alterar</button>
                </p>
        </div>
	</form>
</div>
</div>
<?php 
include "adicionalmente/close.php";
?>	
</body>
</html>
