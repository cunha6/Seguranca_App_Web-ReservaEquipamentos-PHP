<?php
include "adicionalmente/config.php";
include './auxEnviaEmail.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    $query = "SELECT * FROM utilizadores WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    function gerarToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    if ($result->num_rows > 0) {
        $utilizador = $result->fetch_assoc();

        $Nome = $utilizador['Nome_Utilizador'];


        // Gerar um token único para redefinição de senha
        $token = gerarToken();

        $queryAtualizarToken = "UPDATE utilizadores SET TokenRecuperacao = ? WHERE Email = ?";
        $stmtAtualizarToken = mysqli_prepare($conn, $queryAtualizarToken);
        $stmtAtualizarToken->bind_param("ss", $token, $email);
        $stmtAtualizarToken->execute();

        $assuntoMsg = 'Second Life - Redefenir Senha';

            // Envio de e-mail
            $mensagem = "Olá $Nome,<br><br>

            Foi solicitado a redefinição de senha da sua conta.<br><br>

            Clique no link abaixo para redefinir sua senha:<br>
            http://saw.pt/recover_password.php?token=$token<br><br>

            Se você não solicitou essa redefinição, ignore este e-mail.<br><br>

            Bons Negocios,<br>
            Equipa SecondLife";

            enviaEmail($Nome, $email, $assuntoMsg, $mensagem);


        // Redirecionar para uma página de login
        header("Location: login.php");
        exit;
    } else {
        $error_message = "O e-mail inserido não está associado a uma conta válida.";
    }
}

include "adicionalmente/head.php";
?>

<title>Recuperar Palavra-passe</title>
<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        max-width: 400px;
        margin: 100px auto;
    }

    .card {
        border: 0;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 40px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4"><strong>Recuperar Palavra-passe</strong></h3>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form class="form-elegant" method="POST">
                    <div class="md-form mb-4">
                        <label for="Form-email1">E-mail</label>
                        <input type="email" id="Form-email1" class="form-control validate" name="email" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-block btn-rounded z-depth-1a">Recuperar</button>
                    </div>
                    <div class="text-center mt-4">
                        <a href="login.php" class="btn btn-secondary btn-rounded z-depth-1a">Voltar para o login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "adicionalmente/fim.php" ?>
</body>

</html>
