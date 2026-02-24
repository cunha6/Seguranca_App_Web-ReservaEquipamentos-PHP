<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) {
    header('location: login.php');
    exit;
}
include('adicionalmente/config.php');

function function_alert($message)
{
    echo "<script>alert('$message');</script>";
}

$Num_Utilizador        =   $_POST['Num_Utilizador'];
$nome_utilizador       =   $_POST['Nome_Utilizador'];
$email                 =   $_POST['Email'];

if (!($nome_utilizador and $email and $Num_Utilizador)) {
    $continuacao = "Location: 4-perfil.php";

    if ($Num_Utilizador) $continuacao = "Location: 4-alterar_perfil.php?erro=obrigatorio&Num_Utilizador=$Num_Utilizador";

    header($continuacao);
    exit;
}

$conn = mysqli_connect($host, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");
if (!$conn) {
    die("Conexão falhada: " . mysqli_connect_error());
}

# trabalhar com ficheiros
if ((count($_FILES) > 0) && isset($_FILES['nova_imagem']) && (trim($_FILES['nova_imagem']['name']) != "")) {
    $target_dir = "foto/"; // Pasta onde ficarão guardadas as imagens
    $target_file = $target_dir . basename($_FILES["nova_imagem"]["name"]); // caminho pasta + nome do ficheiro
    // variável que guarda o nome do ficheiro
    $ficheiro     = $_FILES['nova_imagem']['name'];
    //Pasta onde o ficheiro vai ser Gravado
    $_UP['pasta'] = 'foto/';
    //Tamanho máximo do ficheiro em Bytes
    $_UP['tamanho'] = 1024 * 1024 * 4; //5mb
    //Array com a extensões permitidas
    $_UP['extensoes'] = array('png', 'jpg', 'jpeg', 'gif');

    $nome_final = $_FILES['nova_imagem']['name'];

    //Faz a verificação da extensao do ficheiro
    $img_separador = explode('.', $ficheiro);
    $extensao = strtolower(end($img_separador));

    if (array_search($extensao, $_UP['extensoes']) === false) {
        //Array com os tipos de erros de upload do PHP
        $response = array(
            "message" => "O ficheiro contêm uma extensão inválida ou Nenhum ficheiro selecionado!!"
        );
    }

    //Faz a verificação do tamanho do ficheiro
    else if ($_UP['tamanho'] < $_FILES['nova_imagem']['size']) {
        //Array com os tipos de erros de upload do PHP
        $response = array(
            "message" => "O ficheiro ultrapassa o limite de tamanho especificado no HTML"
        );
    } //O ficheiro passou em todas as verificações, está na altura de move-lo para a pasta foto
    else if (file_exists($target_file)) {
        $date = new DateTime();
        $nome_final = $date->format('His') . $_FILES['nova_imagem']['name'];
        //elimina o anterior, pois já será outro diferente 
        $ficheiro_eliminar = "foto/" . $nome_final;
        unlink($ficheiro_eliminar);
        move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $_UP['pasta'] . $nome_final);
        $response = array(
            "message" => ""
        );
    } else {
        //mantem o nome original do ficheiro
        $nome_final = $_FILES['nova_imagem']['name'];
        //elimina o anterior, pois já será outro diferente 
        $ficheiro_eliminar = "foto/" . $nome_final;
        unlink($ficheiro_eliminar);
        //Verificar se é possivel mover o ficheiro para a pasta escolhida
        move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $_UP['pasta'] . $nome_final);
    }
}

if ($response["message"] != "") {
    function_alert($response["message"]);
    echo "
         <script type=\"text/javascript\">
              window.history.back();
         </script>
     ";
} else {
    //Upload efetuado com sucesso				
    $files = utf8_encode($nome_final);

    $sql = "UPDATE utilizadores SET Nome_Utilizador='$nome_utilizador', Email='$email', Senha='$password', Imagem='$files' WHERE Num_Utilizador=$Num_Utilizador";

    try {
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error Processing Requests", 1);
        }
    } catch (Exception $e) {
    }
    header("Location:4-alterar-perfil.php");
}

include "adicionalmente/close.php";
?>
