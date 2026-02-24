<!-- Barra lateral / Menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
    <div class="w3-container">
        <a href="#" onclick="w3_close()" class="w3-hide-large w3-right w3-jumbo w3-padding w3-hover-grey" title="close menu">
            <i class="fa fa-remove"></i>
        </a>
        <img src="../imagens/logo6.png" style="width:70%;" class="w3-round"><br><br><br>
        <h4><b>SecondLife</b></h4><br>
    </div>
    <div class="w3-bar-block">
        <?php
        include "adicionalmente/config.php";

        if (isset($_SESSION['Num_Utilizador'])) {

            $Num_Utilizador = $_SESSION['Num_Utilizador'];

            $query = "SELECT * FROM utilizadores WHERE Num_Utilizador = ?";
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param("s", $Num_Utilizador);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();


                $_SESSION['Num_Utilizador'] = $row['Num_Utilizador'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['User_Admin'] = $row['User_Admin'];
                $_SESSION['User_Client'] = $row['User_Client'];

                // Verificar se o utilizador tem permissões de administrador
                if ($_SESSION['User_Admin'] == 'sim') {
                    // Se sim, mostrar todos os itens do menu
                    echo '
                    <a href="3-form_inserir.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == '3-form_inserir.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-eject fa-fw w3-margin-right"></i>INSERIR EQUIPAMENTOS
                    </a>
                    <a href="index.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'index.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-th-large fa-fw w3-margin-right"></i>EQUIPAMENTOS
                    </a>
                    <a href="lista_emprestimo.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'lista_emprestimo.php' ? ' w3-text-teal' : '') . '">
                        <i class="fas fa-share-square fa-fw w3-margin-right"></i>VENDAS
                    </a>
                    <a href="categorias.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'categorias.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-list fa-fw w3-margin-right" aria-hidden="true"></i>ADICIONAR CATEGORIAS
                    </a>
                    <a href="4-perfil.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == '4-perfil.php' ? ' w3-text-teal' : '') . '">
                        <i class="far fa-user fa-fw w3-margin-right" aria-hidden="true"></i>PERFIL
                    </a>
                    <a href="registar-admin.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'registar-admin.php' ? ' w3-text-teal' : '') . '">
                        <i class="fas fa-plus w3-margin-right" aria-hidden="true"></i>INSERIR UTILIZADORES
                    </a>
                    <a href="lista-users.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'lista-users.php' ? ' w3-text-teal' : '') . '">
                        <i class="fas fa-users w3-margin-right" aria-hidden="true"></i>LISTA UTILIZADORES
                    </a>
                    <a href="logs.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'logs.php' ? ' w3-text-teal' : '') . '">
                        <i class="fas fa-file-alt fa-fw w3-margin-right"></i>VER LOGS
                    </a>
                ';
                } elseif ($_SESSION['User_Client'] == 'sim') {
                    // Se o utilizador não for administrador, mas for um cliente, mostrar itens específicos
                    echo '
                    <a href="3-form_inserir.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == '3-form_inserir.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-eject fa-fw w3-margin-right"></i>INSERIR EQUIPAMENTOS
                    </a>
                    <a href="index.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'index.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-th-large fa-fw w3-margin-right"></i>EQUIPAMENTOS
                    </a>
                    <a href="4-perfil.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == '4-perfil.php' ? ' w3-text-teal' : '') . '">
                        <i class="far fa-user fa-fw w3-margin-right" aria-hidden="true"></i>PERFIL
                    </a>
                ';
                }
                echo '<br><br><br><br>';
                echo '
                <a href="adicionalmente/logout.php" class="w3-bar-item w3-button w3-padding">
                    <i class="fas fa-sign-out-alt fa-fw"></i>TERMINAR SESSÃO
                </a>
            ';
            } else {
                echo '
                <a href="login.php" class="w3-bar-item w3-button w3-padding">
                    <i class="fas fa-sign-in-alt fa-fw"></i> INICIAR SESSÃO
                </a>
            ';
            }
        } else if (
            !isset($_SESSION['Num_Utilizador']) &&
            !in_array(basename($_SERVER['PHP_SELF']), array('registar-admin.php', 'index.php'))
        ) {
            header('Location: login.php');
            exit;
        } else {
            
            echo '
                    <a href="3-form_inserir.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == '3-form_inserir.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-eject fa-fw w3-margin-right"></i>INSERIR EQUIPAMENTOS
                    </a>
                    <a href="index.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == 'index.php' ? ' w3-text-teal' : '') . '">
                        <i class="fa fa-th-large fa-fw w3-margin-right"></i>EQUIPAMENTOS
                    </a>
                    <a href="4-perfil.php" class="w3-bar-item w3-button w3-padding' . (basename($_SERVER['PHP_SELF']) == '4-perfil.php' ? ' w3-text-teal' : '') . '">
                        <i class="far fa-user fa-fw w3-margin-right" aria-hidden="true"></i>PERFIL
                    </a>

                    <br><br><br><br>
                    
                    <a href="login.php" class="w3-bar-item w3-button w3-padding">
                    <i class="fas fa-sign-in-alt fa-fw"></i> INICIAR SESSÃO
                </a>
                ';
        }
        ?>
    </div>
</nav>