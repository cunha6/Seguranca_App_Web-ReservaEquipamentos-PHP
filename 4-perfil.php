<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }

include "adicionalmente/config.php";

// Obtém informações do Utilizador autenticado
$num_Utilizador = $_SESSION['Num_Utilizador'];
$query = "SELECT * FROM utilizadores WHERE Num_Utilizador = ?";
$stmt = mysqli_prepare($conn, $query);
$stmt->bind_param("i", $num_Utilizador);
$stmt->execute();
$result = $stmt->get_result();

// Consulta para obter produtos à venda do utilizador
$productsQuery = "SELECT * FROM equipamentos WHERE Num_Utilizador = ?";
$productsStmt = mysqli_prepare($conn, $productsQuery);
mysqli_stmt_bind_param($productsStmt, "i", $num_Utilizador);
mysqli_stmt_execute($productsStmt);
$productsResult = mysqli_stmt_get_result($productsStmt);
mysqli_stmt_close($productsStmt);

if ($result->num_rows > 0) {
    $utilizador = $result->fetch_assoc();
} else {
    header('location: login.php');
    exit;
}

include "adicionalmente/head.php";
?>
<title>Detalhes do Utilizador</title>
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

        <div class="w3-container w3-medium w3-card-4 w3-light-grey">
            <div class="w3-bottombar">
                <h2><b>Detalhes do Utilizador</b></h2>
            </div>
            <br>
            <?php if (!empty($utilizador['Imagem'])): ?>
                <div class="w3-circle" style="overflow: hidden; width: 100px; height: auto; border-radius: 50%;margin: auto;">
                    <img src="foto/<?php echo $utilizador['Imagem']; ?>" alt="Imagem do utilizador" style="width: 100%; height: auto; border-radius: 50%;">
                </div>
            <?php endif; ?>
            <div class="w3-container w3-light-grey">
                <p><b>Numero Utilizador:</b> <input class="w3-input w3-animate-input" type="text" value="<?php echo $utilizador['Num_Utilizador']; ?>" readonly></p>
                <p><b>Nome:</b> <input class="w3-input w3-animate-input" type="text" value="<?php echo $utilizador['Nome_Utilizador']; ?>" readonly></p>
                <p><b>Email:</b> <input class="w3-input w3-animate-input" type="email" value="<?php echo $utilizador['Email']; ?>" readonly></p>


                <p>
                    <br>
                    <a href="4-alterar-perfil.php?Num_Utilizador=<?php echo $num_Utilizador ?>"> <button class="btn w3-btn botao w3-right btn-rounded" type="reset"> Atualizar </button></a>

                </p>
            </div>
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

    <script>
        document.getElementById('btnMenu').onclick = () => this.style.backgroundColor = "red"
    </script>
    <?php include "adicionalmente/fim.php" ?>

</body>
</html>
