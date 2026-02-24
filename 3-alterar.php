
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

$categorias         =   $_POST['categorias'];
$nomeequip          =   $_POST['nomeequip'];
$descricao          =   $_POST['descricao'];
$quantidade         =   $_POST['quantidade'];
$preco              =   $_POST['preco'];
$id                 =   $_POST['id'];
$url                =   $_POST['url'];
$nome_final         =   $_POST['url'];

if (!($categorias and $nomeequip and $descricao and $quantidade and $preco and $id)) {
    $continuacao = "Location: index.php";

    if ($id) $continuacao = "Location: 3-alterar_equipamento.php?erro=obrigatorio&id=$id";

    header($continuacao);
    exit;
}

// Criar a ligação a base de dados
$conn = mysqli_connect($host, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");
if (!$conn) {
    die("Conexão falhada: " . mysqli_connect_error());
}

# trabalhar com ficheiros
if ((count($_FILES) > 0) && isset($_FILES['ficheiro']) && (trim($_FILES['ficheiro']['name']) != "")) {
    $target_dir = "foto/"; // Pasta onde ficarão guardadas as imagens
    $target_file = $target_dir . basename($_FILES["ficheiro"]["name"]); // caminho pasta + nome do ficheiro
    // variável que guarda o nome do ficheiro
    $ficheiro     = $_FILES['ficheiro']['name'];
    //Pasta onde o ficheiro vai ser Gravado
    $_UP['pasta'] = 'foto/';
    //Tamanho máximo do ficheiro em Bytes
    $_UP['tamanho'] = 1024 * 1024 * 4; //5mb
    //Array com a extensões permitidas
    $_UP['extensoes'] = array('png', 'jpg', 'jpeg', 'gif');

    $nome_final = $_FILES['ficheiro']['name'];


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
    else if ($_UP['tamanho'] < $_FILES['ficheiro']['size']) {
        //Array com os tipos de erros de upload do PHP
        $response = array(
            "message" => "O ficheiro ultrapassa o limite de tamanho especificado no HTML"
        );
    } //O ficheiro passou em todas as verificações, está na altura de move-lo para a pasta foto

    else if (file_exists($target_file)) {

        $date = new DateTime();
        $nome_final = $date->format('His') . $_FILES['ficheiro']['name'];
        //elimina o anterior, pois já será outro diferente 
        $ficheiro_eliminar = "foto/" . $url;
        unlink($ficheiro_eliminar);
        //$nome_final = $_FILES['ficheiro']['name'];
        move_uploaded_file($_FILES['ficheiro']['tmp_name'], $_UP['pasta'] . $nome_final);
        $response = array(
            "message" => ""
        );
    } else {
        //mantem o nome original do ficheiro
        $nome_final = $_FILES['ficheiro']['name'];
        //elimina o anterior, pois já será outro diferente 
        $ficheiro_eliminar = "foto/" . $url;
        unlink($ficheiro_eliminar);
        //Verificar se é possivel mover o ficheiro para a pasta escolhida
        move_uploaded_file($_FILES['ficheiro']['tmp_name'], $_UP['pasta'] . $nome_final);
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

    $total  = "SELECT Total From equipamentos WHERE Cod_Equipamento=$id";
    $result = mysqli_query($conn, $total);
    $total  = mysqli_fetch_assoc($result)['Total'];

    $total2 = $quantidade - $total;

    $sql = "UPDATE equipamentos SET Nome_Equipamento='$nomeequip', Descricao='$descricao', Preco = '$preco', Total='$quantidade', Quantidade=Quantidade+$total2, Imagem='$files' WHERE Cod_Equipamento=$id";


    try {
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error Processing Requests", 1);
            
        } 
    } catch (Exception $e) {
    }

    $query = "DELETE FROM Categorias_Equipamentos WHERE Cod_Equipamento = ?";
    $stmt = mysqli_prepare($conn, $query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();

    foreach ($categorias as $posCategoria => $categoria) {
        $query = "INSERT INTO Categorias_Equipamentos VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        $stmt->bind_param("ss", $categoria, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location:3-alterar_equipamento.php");
}

include "adicionalmente/close.php";
?>