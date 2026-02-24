<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function tamanho()
{
    header("Location: ?erro=tamanho");
    exit;
}

function senhaUnica($conn, $senha)
{
    $query = "SELECT COUNT(*) AS total FROM utilizadores WHERE Senha = ?";
    $stmt = mysqli_prepare($conn, $query);
    $stmt->bind_param("s", $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['total'] == 0;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Nome_Utilizador = isset($_POST['Nome_Utilizador']) ? $_POST['Nome_Utilizador'] : null;
    $Email = isset($_POST['Email']) ? $_POST['Email'] : null;
    $Senha = isset($_POST['Senha']) ? $_POST['Senha'] : null;
    $Tipo_Utilizador = isset($_POST['Tipo_Utilizador']) ? $_POST['Tipo_Utilizador'] : null;

    if (!($Nome_Utilizador and $Email and $Senha and $Tipo_Utilizador)) {
        header("Location: ?erro=obrigatorio");
        exit;
    }

    $nome_utilizador_len = strlen($Nome_Utilizador);

    if ($nome_utilizador_len < 3) {
        tamanho();
    }

    include "adicionalmente/config.php";

    $check_query = "SELECT * FROM utilizadores WHERE Email = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    $check_stmt->bind_param("s", $Email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script language='javascript' type='text/javascript' align='center'>
                    alert('Email já existente');
                    window.location='registar-admin.php'; </script>";
        exit;
    }


    $query = "INSERT INTO utilizadores (Nome_Utilizador, Email, Senha, User_Admin, User_Client) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    $Nome_Utilizador = isset($_POST['Nome_Utilizador']) ? $_POST['Nome_Utilizador'] : null;
    $Email = isset($_POST['Email']) ? $_POST['Email'] : null;
    $Senha = isset($_POST['Senha']) ? $_POST['Senha'] : null;
    $User_Admin = ($Tipo_Utilizador == 'admin') ? 'sim' : 'nao';
    $User_Client = ($Tipo_Utilizador == 'client') ? 'sim' : 'nao';

    $hashed_Senha = sha1($Senha);

    $stmt->bind_param("sssss", $Nome_Utilizador, $Email, $hashed_Senha, $User_Admin, $User_Client);
    $stmt->execute();
    $stmt->close();

    header("Location: registar-admin.php");
    exit;
}

include "adicionalmente/head.php";
?>
<title>Perfil</title>
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
                <h2><b>Perfil</b></h2>
            </div>
            <form class="w3-container w3-light-grey" method="post" enctype="multipart/form-data">
                <p>
                    <label><b>Nome de Utilizador</b></label>
                    <input class="w3-input w3-animate-input" type="text" name="Nome_Utilizador" required>
                </p>
                <p>
                    <label><b>Email</b></label>
                    <input class="w3-input w3-animate-input" type="Email" name="Email" required>
                </p>
                <p>
                    <label><b>Password</b></label>
                    <input class="w3-input w3-animate-input" type="password" name="Senha" required>
                </p>
                <p>
                    <label><b>Tipo de Utilizador</b></label>
                    <select class="w3-select" name="Tipo_Utilizador" required>
                        <option value="" disabled selected>Escolha o tipo de utilizador</option>
                        <option value="admin">Administrador</option>
                        <option value="client">Cliente</option>
                    </select>
                </p>
                <p>
                    <br>
                    <button class="btn w3-btn botao w3-right btn-rounded" type="reset"> Limpar </button>
                    <button class="btn w3-btn botao btn-rounded w3-right" style="margin-right: 1%;" type="submit">Registrar</button>
                </p>
                <br>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('btnMenu').onclick = () => this.style.backgroundColor = "red"
    </script>
    <?php include "adicionalmente/fim.php" ?>

</body>
</html>
