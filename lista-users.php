<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) {
    header('location: login.php');
    exit;
}

include "adicionalmente/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bloquear'])) {
        $userIdToToggle = $_POST['userIdToToggle'];

        $sql = "SELECT User_Client FROM utilizadores WHERE Num_Utilizador = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userIdToToggle);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userClient);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        //Altera quando clicar no Bloquear sim passa para nao
        if ($userClient === 'sim') {
            $newUserClient = 'nao'; 
        } else {
            $newUserClient = 'sim'; 
        }

        // Atualizar a base de dados
        $updateSql = "UPDATE utilizadores SET User_Client = ? WHERE Num_Utilizador = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "si", $newUserClient, $userIdToToggle);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);
    }
}

include "adicionalmente/head.php";
?>

<title>Lista de Utilizadores</title>
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
                <h1><b>Lista de Utilizadores</b></h1>
            </div>
        </header>

        <div class="w3-container">
            <br><br><br>
            <table class="w3-table w3-bordered">
                <tr class="w3-teal">
                    <th>ID do Utilizador</th>
                    <th>Nome do Utilizador</th>
                    <th>Email</th>
                    <th class="w3-center" colspan="2">Operações</th>
                </tr>

                <?php
                $sql = "SELECT * FROM utilizadores WHERE User_Admin = 'nao'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr onclick="location.href='detalhes-perfil-admin.php?id=<?php echo $row['Num_Utilizador']; ?>'">
                            <td><?php echo $row['Num_Utilizador']; ?></td>
                            <td><?php echo $row['Nome_Utilizador']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td style="width:5%; border-right: solid 0px #ccc;">
                                <form method="post">
                                    <input type="hidden" name="userIdToToggle" value="<?php echo $row['Num_Utilizador']; ?>">
                                    <input class="w3-button w3-border w3-text-black" name="bloquear" type="submit" value="<?php echo ($row['User_Client'] === 'sim') ? 'Bloquear' : 'Desbloquear'; ?>">
                                </form>
                            </td>
                            <td style="width:5%; border-left:  solid 0px #ccc;">
                                <form method="post" action="remover_utilizador.php" onsubmit="return confirm('Tem certeza que deseja remover este utilizador?');">
                                    <input type="hidden" name="id" value="<?php echo $row['Num_Utilizador']; ?>">
                                    <button class="w3-button w3-black w3-text-white" type="submit" name="remover">Remover</button>
                                </form>
                            </td>                                
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='3'>Não existem utilizadores na BD.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <?php include "adicionalmente/fim.php" ?>
</body>
</html>
