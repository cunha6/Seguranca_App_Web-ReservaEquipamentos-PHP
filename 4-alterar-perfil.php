<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }

$altID = isset($_GET['Num_Utilizador']) ? $_GET['Num_Utilizador'] : null;

if (!$altID) {
    header('Location: index.php');
    exit;
}

include "adicionalmente/config.php";

$sql = "SELECT * FROM utilizadores WHERE Num_Utilizador = '$altID';";
$result = $conn->query($sql);

$row;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header('Location: index.php');
    exit;
}

include "adicionalmente/close.php";

include "adicionalmente/head.php";
?>
<title>Alterar Perfil</title>
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
                <h2><b>Editar Perfil</b></h2>
            </div>
            <form class="w3-container w3-light-grey" method="post" enctype="multipart/form-data" action="4-alterar.php">
                <div class="card-body">
                    <input type="hidden" name="Num_Utilizador" value="<?php echo $altID ?>">

                    <!-- Mostra a imagem do perfil -->
                    
                        <label><b>Imagem</b></label>
                        <p>
                            <?php if (!empty($row['Imagem'])): ?>
                                <img src="foto/<?php echo $row['Imagem']; ?>" alt="Imagem de Perfil" style="max-width: 200px;">
                            <?php endif; ?>

                            <label for="nova_imagem">Nova Imagem:</label>
                            <input type="file" name="nova_imagem" id="nova_imagem">
                        </p>
                    

                    <p>
                        <label><b>Nome do Utilizador</b></label>
                        <input class="w3-input w3-animate-input" type="text" name="Nome_Utilizador" maxlength="45" value="<?php echo $row['Nome_Utilizador'] ?>" required>
                    </p>
                    <p>
                        <label><b>Email</b></label>
                        <input class="w3-input w3-animate-input" type="email" name="Email" value="<?php echo $row['Email'] ?>" required>
                    </p>
                    <p>
                        <label><b>Password</b></label>
                        <a href="forgot_password.php?Num_Utilizador=<?php echo $altID; ?>" class="btn w3-btn botao btn-rounded w3-right" style="margin-right: 1%;">Alterar Password</a>
                    </p>

                    

                    <p>
                        <br>
                        <button type="reset" class="btn w3-btn botao w3-right btn-rounded"> Limpar </button>
                        <button class="btn w3-btn botao btn-rounded w3-right" style="margin-right: 1%;" type="submit">Submeter</button>
                    </p>
                    <br>
                </div>
            </form>
        </div>
    </div>

    <?php include "adicionalmente/fim.php"; ?>

</body>
</html>
