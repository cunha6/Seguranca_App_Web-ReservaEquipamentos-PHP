<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) {
    header('location: login.php');
    exit;
}

include "adicionalmente/config.php";

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $userInfoQuery = "SELECT * FROM utilizadores WHERE Num_Utilizador = ?";
    $userInfoStmt = mysqli_prepare($conn, $userInfoQuery);
    mysqli_stmt_bind_param($userInfoStmt, "i", $userId);
    mysqli_stmt_execute($userInfoStmt);
    $userInfoResult = mysqli_stmt_get_result($userInfoStmt);
    $userData = mysqli_fetch_assoc($userInfoResult);
    mysqli_stmt_close($userInfoStmt);

    $productsQuery = "SELECT * FROM equipamentos WHERE Num_Utilizador = ?";
    $productsStmt = mysqli_prepare($conn, $productsQuery);
    mysqli_stmt_bind_param($productsStmt, "i", $userId);
    mysqli_stmt_execute($productsStmt);
    $productsResult = mysqli_stmt_get_result($productsStmt);
    mysqli_stmt_close($productsStmt);
} else {
    header('location: index.php');
    exit;
}

include "adicionalmente/head.php";
?>

<title>Detalhes do Perfil</title>
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
            <div class="w3-container w3-bottombar">
                <h1><b>Detalhes do Perfil</b></h1>
            </div>
        </header>

        <div class="w3-container">
            <br><br><br><br>

            <!-- Exibição de informações do utilizador -->
            
            <table class="w3-table w3-bordered">
            <header class="w3-container w3-teal">
            <h2>Informações do Utilizador</h2>
            </header>
                <tr>
                    <th>ID do Utilizador</th>
                    <td><?php echo $userData['Num_Utilizador']; ?></td>
                </tr>
                <tr>
                    <th>Nome do Utilizador</th>
                    <td><?php echo $userData['Nome_Utilizador']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $userData['Email']; ?></td>
                </tr>
            </table>

<h2>Produtos à Venda</h2>
<div class="w3-card-4" style="width:100%;">
    <header class="w3-container w3-teal">
        <h2>Produtos</h2>
    </header>

    <div class="w3-container">
        <?php
        if (mysqli_num_rows($productsResult) > 0) {
            while ($product = mysqli_fetch_assoc($productsResult)) {
        ?>
                <div class="w3-third w3-container w3-margin-bottom" style="margin-bottom: 0;">
                    <img src="foto/<?php echo $product['Imagem']; ?>" style="width:100%" onclick="onClick(this)" class="w3-hover-opacity img1">
                    <div class="w3-container w3-white">
                        <p style="margin-bottom: 0;"><b><?php echo $product['Nome_Equipamento']; ?></b></p>
                        <p class="hyphenate" style="margin-bottom: 0;"><?php echo $product['Descricao']; ?></p>
                        <p style="font-size: 20px; text-align: right; margin-bottom: 0;"><?php echo number_format($product['Preco'], 2, ',', '.'); ?> €</p>

                        <p style="margin-bottom: 0;"><b>Qtd:</b> <?php echo "<span id='quantidade' value='" . $product['Quantidade'] . "'>" . $product['Quantidade'] . "</span>"; ?> &nbsp; &nbsp; <b>Vendidos:</b> <?php echo "<span id='vendidos' value='" . $product['Emprestimo_Ativo'] . "'>" . $product['Emprestimo_Ativo'] . "</span>"; ?> </p><br>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>Não existem produtos à venda deste utilizador.</p>";
        }
        ?>
    </div>
</div>

        </div>
    </div>
    <?php 
    include "adicionalmente/fim.php"
     ?>
</body>
</html>
