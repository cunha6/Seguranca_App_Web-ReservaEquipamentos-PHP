<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
$Num_Utilizador = $_SESSION['Num_Utilizador'];
function tamanho()
{
    header("Location: ?erro=tamanho");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $categorias = isset($_POST['categorias']) ? $_POST['categorias'] : null;
    $nomeequip  = isset($_POST['nomeequip'])  ? $_POST['nomeequip']  : null;
    $descricao  = isset($_POST['descricao'])  ? $_POST['descricao']  : null;
    $quantidade = isset($_POST['quantidade']) ? $_POST['quantidade'] : null;
    $preco      = isset($_POST['preco']) ? $_POST['preco'] : null;

    if (!($categorias and $nomeequip and $descricao and $quantidade and $preco)) {
        header("Location: ?erro=obrigatorio");
        exit;
    }

    $nomeequipLen  = strlen($nomeequip);
    $descricaoLen  = strlen($descricao);

    if (is_int($categorias) and ($categorias < 1 or $categorias > 4)) tamanho();
    if ($nomeequipLen < 3) tamanho();
    if ($descricaoLen < 2) tamanho();

    $target_dir       = "foto/";
    $target_file      = $target_dir . basename($_FILES["ficheiro"]["name"]);
    $ficheiro         = $_FILES['ficheiro']['name'];
    $_UP['pasta']     = 'foto/';
    $_UP['tamanho']   = 1024 * 1024 * 4;
    $_UP['extensoes'] = array('png', 'jpg', 'jpeg', 'gif');
    $nome_final       = $_FILES['ficheiro']['name'];

    //Faz a verificação da extensao do ficheiro

    $img_separador = explode('.', $ficheiro);
    $extensao = strtolower(end($img_separador));
    $response["message"] = "";

    if (array_search($extensao, $_UP['extensoes']) === false) {
        //Array com os tipos de erros de upload do PHP
        $response = array(
            "message" => "O ficheiro contêm uma extensão inválida!!"
        );
    } else if ($_UP['tamanho'] < $_FILES['ficheiro']['size']) {
        //Array com os tipos de erros de upload do PHP
        $response = array("message" => "O ficheiro ultrapassa o limite de tamanho especificado no HTML");
    } else if (file_exists($target_file)) {
        $date = new DateTime();
        //acrescenta ao nome do ficheiro tempo de forma a serem diferentes
        $nome_final = $date->format('His') . $_FILES['ficheiro']['name'];
        $response = array("message" => "");
    } else {
        //mantem o nome original do ficheiro
        $nome_final = $_FILES['ficheiro']['name'];
    }

    if ($response["message"] != "") {
        echo "	<script type=\"text/javascript\">window.history.back(); </script>";
    } else {
        if (move_uploaded_file($_FILES['ficheiro']['tmp_name'], $_UP['pasta'] . $nome_final)) {

            $files = $nome_final;

            include "adicionalmente/config.php";

            $query = "INSERT INTO equipamentos (nome_equipamento, Num_Utilizador, descricao, preco, total, quantidade, imagem) VALUES (?, ?, ?, ?, ?, ?, ?);";
            $stmt = mysqli_prepare($conn, $query);
            $stmt->bind_param("ssssiis", $nomeequip, $Num_Utilizador, $descricao, $preco, $quantidade, $quantidade, $nome_final);
            $stmt->execute();
            $stmt->close();

            

            $id = $conn->insert_id;

            foreach ($categorias as $posCategoria => $categoria) {
                $query = "INSERT INTO Categorias_Equipamentos VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                $stmt->bind_param("ss", $categoria, $id);
                $stmt->execute();
                $stmt->close();
            } 

            include "adicionalmente/close.php";

            header("Location: index.php");
            exit;
        } else {
            $error_message = error_get_last()['message'];
            echo "Error uploading file: $error_message";
        }
    }
}

include "adicionalmente/head.php"; ?>
<title>Inserir Equipamentos</title>
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
            <a href="#"><img src="imagens/logo6.png" style="width:65px;" class=" w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
            <span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
        </header>

        <br><br><br>

        <div class="w3-container w3-medium w3-card-4 w3-light-grey">
            <div class="w3-bottombar">
                <h2><b>Inserir Equipamento</b></h2>
            </div>
            <form class="w3-container w3-light-grey" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    
                        <label><b>Categorias</b></label>
                        <?php
                    
                        include "adicionalmente/config.php";

                        $sql    = "SELECT * FROM categorias";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <input type="checkbox" id="categorias_<?php echo $row['Nome_Categoria'] ?>" name="categorias[]" class="w3-check" value="<?php echo $row['Id_Categoria'] ?>">
                            <label for ="categorias_<?php echo $row['Nome_Categoria'] ?>"> <?php echo $row['Nome_Categoria'] ?> </label>
                        <?php
                        }
                        ?>
                    <p>
                        <label><b>Nome do Equipamento</b></label> 
                        <input class="w3-input w3-animate-input" type="text" name="nomeequip"  maxlength="45" required>
                    </p>
                    <p>
                        <label><b>Quantidade</b></label>
                        <input class="w3-input w3-animate-input" type="number" name="quantidade" required>
                    </p>
                    <p>
                        <label><b>Descrição</b></label>
                        <input class="w3-input w3-animate-input" type="text" name="descricao" minlength="50" maxlength="90" required>
                    </p>
                    <p>
                        <label><b>Preco</b></label>
                        <input class="w3-input w3-animate-input" type="number" name="preco" step="0.01" required>
                    </p>
                    <label><b>Inserir imagem</b></label> <br>
                    <input name="ficheiro" class=" btn-rounded" type="file" accept="image/*" required>

                    <p>
                        <br>
                        <button class="btn w3-btn botao w3-right btn-rounded" type="reset"> Limpar </button>
                        <button class="btn w3-btn botao btn-rounded w3-right" style="margin-right: 1%;" type="submit">Submeter</button>
                    </p>
                    <br>
            </form>

        </div>

    </div>
    <script>
        document.getElementById('btnMenu').onclick = () => this.style.backgroundColor = "red"
    </script>
    <script src="js/escolha.js"></script>
    <?php include "adicionalmente/fim.php" ?>

</body>

</html>