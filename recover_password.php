<?php
include "adicionalmente/config.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar se o token existe na base de dados
    $query = "SELECT * FROM utilizadores WHERE TokenRecuperacao = ?";
    $stmt = mysqli_prepare($conn, $query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $utilizador = $result->fetch_assoc();


        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // Recuperar a nova senha do formulário
            $novaSenha = isset($_POST['nova_senha']) ? $_POST['nova_senha'] : null;


            // Atualizar a senha e limpar o token
            $queryAtualizarSenha = "UPDATE utilizadores SET Senha = ?, TokenRecuperacao = NULL WHERE TokenRecuperacao = ?";
            $stmtAtualizarSenha = mysqli_prepare($conn, $queryAtualizarSenha);
            $stmtAtualizarSenha->bind_param("ss", sha1($novaSenha), $token);
            $stmtAtualizarSenha->execute();

            header("Location: login.php");
            exit;
        }

        include "adicionalmente/head.php";
        ?>


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

        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center mb-4"><strong>Redefinir Senha</strong></h3>
                    <form method="POST">
                        <div class="md-form mb-4">
                            <label for="nova_senha">Nova Senha:</label>
                            <input type="password" id="nova_senha" class="form-control validate" name="nova_senha" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block btn-rounded z-depth-1a">Redefinir Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include "adicionalmente/fim.php";
    } else {
        header("Location: invalidToken.php");
        exit;
    }
} else {
    header("Location: invalidToken.php");
    exit;
}
?>
