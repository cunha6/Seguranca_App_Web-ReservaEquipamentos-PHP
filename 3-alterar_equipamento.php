<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }


$altID = isset($_GET['id']) ? $_GET['id'] : null;

if (!$altID) {
    header('Location: index.php');
    exit;
}

include "adicionalmente/config.php";

$sql = "SELECT * FROM Equipamentos WHERE Cod_Equipamento = '$altID';";
$result = $conn->query($sql);

$row;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header('Location: index.php');
    exit;
}

$sql = "SELECT Id_Categoria FROM Categorias_Equipamentos WHERE Cod_Equipamento = '$altID'";

$result = mysqli_query($conn, $sql);
$row2 = mysqli_fetch_array($result);

include "adicionalmente/close.php";

include "adicionalmente/head.php"; ?>
<title>Alterar Equipamentos</title>
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

    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:300px">
        <!-- Header -->
        <header id="portfolio" class="w3-main w3-top w3-light-grey">
            <a href="#"><img src="imagens/logo6.png" style="width:65px;" class="w3-circle w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
            <span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
        </header>

        <br><br><br>

        <div class="w3-container w3-medium w3-card-4 w3-light-grey">
            <div class="w3-bottombar">
                <h2><b>Editar Equipamento</b></h2>
            </div>
            <form class="w3-container w3-light-grey" method="post" enctype="multipart/form-data" action="3-alterar.php">
                <div class="card-body">
                    <input type="hidden" name="id" value="<?php echo $altID ?>">

                    <label><b>Categorias</b></label>
                    <?php
                    
                    include "adicionalmente/config.php";


                    $sql    = "SELECT * FROM categorias";
                    $result = mysqli_query($conn, $sql);

                    $query = "SELECT Id_Categoria FROM Categorias_Equipamentos WHERE Cod_Equipamento=$altID";
                    $r1 = mysqli_query($conn, $query);
                    $query = mysqli_fetch_all($r1);
                    $r1= iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($query)), false);

                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <input type="checkbox" id="categorias_<?php echo $row['Nome_Categoria'] ?>" name="categorias[]" class="w3-check" value="<?php echo $row['Id_Categoria'] ?>"<?php echo in_array($row['Id_Categoria'], $r1) ? 'checked' : '' ?>>
                        <label for ="categorias_<?php echo $row['Nome_Categoria'] ?>"> <?php echo $row['Nome_Categoria'] ?> </label>
                    <?php
                    }
                    ?>
                    <?php
                        $id=$_GET["id"];

                        $sql_equipamentos = "SELECT * FROM equipamentos where Cod_Equipamento=$id";
                        $result_equipamentos = mysqli_query($conn, $sql_equipamentos);
                        $row_equipamentos = mysqli_fetch_assoc($result_equipamentos);?>
        
                    <p>
                        <label><b>Nome do Equipamento</b></label>
                        <input class="w3-input w3-animate-input" type="text" name="nomeequip"  maxlength="45" value="<?php echo $row_equipamentos['Nome_Equipamento'] ?>" required>
                    </p>
                    <p>
                        <label><b>Quantidade</b></label>
                        <input class="w3-input w3-animate-input" type="number" name="quantidade" value="<?php echo $row_equipamentos['Total'] ?>" required>
                    </p>
                    <p>
                        <label><b>Descrição</b></label>
                        <input class="w3-input w3-animate-input" type="text" name="descricao" minlength="50" maxlength="90" value="<?php echo $row_equipamentos['Descricao'] ?>" required>
                    </p>
                    <p>
                        <label><b>Preco</b></label>
                        <input class="w3-input w3-animate-input" type="number" name="preco" step="0.01" value="<?php echo $row_equipamentos['Preco'] ?>" required>
                    </p>
                    <p>
                    <label>FOTO</label>
                    <?php echo "<img src='foto/".$row_equipamentos["Imagem"]."'width='50' height='50'>";?>
                    <label for="ficheiro"> <?php echo $row_equipamentos["Imagem"];?></label><br>
                    <input name="ficheiro" type="file"  accept="image/*"  value=<?php echo $row_equipamentos["Imagem"];?>> <!-- accept permite apenas imagens!!-->
                    <input type="hidden" id="url" name="url" value=<?php echo $row_equipamentos["Imagem"];?>>
                    </p>
                    <p>
                        <br>
                        <button type="reset" class="btn w3-btn botao w3-right btn-rounded" > Limpar </button>
                        <button class="btn w3-btn botao btn-rounded w3-right" style="margin-right: 1%;" type="submit">Submeter</button>
                    </p>
                    <br>
            </form>

        </div>

    </div>

    <script src="js/escolha.js"></script>
    <?php   include "adicionalmente/fim.php";?>

</body>
 
</html>