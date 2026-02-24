<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "adicionalmente/config.php";

    session_start();
    if (!isset($_SESSION)) {
        header('location: index.php');
        exit;
    }


    // Informa se a variável $_POST foi iniciada
    if (isset($_POST)) {
        $id = $_SESSION['Num_Utilizador'];
        //Acesso aos dados do formulário

        $Quantidade_venda =    $_POST["Quantidade_venda"];
        $Telemovel        =    $_POST["Telemovel"];
        $Nome             =    $_POST["Nome"];
        $Apelido          =    $_POST["Apelido"];
        $Email_Cliente    =    $_POST["Email_Cliente"];
        $Cod_Equipamento  =    $_POST["Cod_Equipamento"];
        $Morada           =    $_POST["Morada"];
        $CodPostal        =    $_POST["CodPostal_emprestimo"];
        $Localidade       =    $_POST["Localidade"];
        $Distrito         =    $_POST["Distrito"];

        // Query para a inserção de dados na BD
        try {

            $sql = "INSERT INTO Vendas ( Num_Utilizador, Telemovel, Quantidade_venda, Nome, Apelido, Email_Cliente, Cod_Equipamento, Morada, CodPostal, Localidade, Distrito)
            VALUES ('$id', '$Telemovel', '$Quantidade_venda', '$Nome', '$Apelido', '$Email_Cliente', '$Cod_Equipamento', '$Morada', '$CodPostal', '$Localidade', '$Distrito')";

            $query = "UPDATE Equipamentos Set Quantidade = Quantidade - $Quantidade_venda, Emprestimo_Ativo = Emprestimo_Ativo + $Quantidade_venda Where Cod_Equipamento = $Cod_Equipamento";

            $query1 = "SELECT * FROM Equipamentos WHERE Cod_Equipamento = $Cod_Equipamento";

            // Executa a query e verifica se deu erro
            $conn->begin_transaction();

            if (!mysqli_query($conn, $sql)) throw new Exception();
            if (!mysqli_query($conn, $query)) throw new Exception();

            $result = mysqli_query($conn, $query1);

            // Verifica se a query SELECT foi bem-sucedida
            if (!$result) {
                $conn->rollback();
                throw new Exception("Erro ao executar a query SELECT: " . mysqli_error($conn));
            }

            $row = mysqli_fetch_assoc($result);
            $Nome_Equipamento = $row['Nome_Equipamento'];
            $Preco = $row['Preco'];
            $Total = $Preco * $Quantidade_venda;

            // Confirma a transação
            $conn->commit();

            include "auxEnviaEmail.php";


            $assuntoMsg = 'Second Life - Compra Concluída';

            // Envio de e-mail
            $mensagem = "Olá Sr.º/Sr.ª $Nome,<br><br>

            Agradecemos pela compra efetuada!<br><br>

            Detalhes da Compra:<br>
            Equipamento: $Nome_Equipamento<br>
            Quantidade: $Quantidade_venda<br>
            Morada: $Morada<br><br>
            Preço Total: $Total €<br><br>

            Obrigado por escolher os nossos serviços. Esperamos que aproveite o seu equipamento!<br><br>

            Atenciosamente,<br>
            Equipa SecondLife";

            enviaEmail($Nome, $Email_Cliente, $assuntoMsg, $mensagem);

            header("location:lista_emprestimo.php?insere=1");
        } catch (Exception $e) {
            $conn->rollback();

            echo "<script language='javascript' type='text/javascript' align='center'>
                    alert('$e');
                    window.location='index.php'; </script>";
        }
    }


    include "adicionalmente/close.php";
}

include "adicionalmente/head.php" ?>

<title>SecondLife</title>

<script type="text/javascript">
    function confirmacao(Cod_Equipamento) {
        var resposta = confirm("Deseja apagar o Produto?");

        if (resposta == true) {
            window.location.href = "3-apagar.php?Cod_Equipamento=" + Cod_Equipamento;
        }
    }

    function confirmar(Id_Categoria) {
        var resposta = confirm("Deseja apagar esta Categoria?");

        if (resposta == true) {
            window.location.href = "apagarcategoria.php?Id_Categoria=" + Id_Categoria;
        }
    }
</script>

<link href="vendor/css/sb-admin-2.min.css" rel="stylesheet">
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

    .img1 {

        min-height: 300px;
        max-height: 300px;
        object-fit: cover;
    }

    .icon-container {
        position: absolute;
        top: 10px; 
        right: 10px; 
    }

    .w3-row {
        display: flex;
        justify-content: flex-end;
    }
</style>

<body class="w3-light-grey w3-content" style="max-width:2000px" id="page-top">
    <?php
    include "adicionalmente/header.php" ?>
    <!-- !PAGE CONTENT! -->

    <div class="w3-main" style="margin-left:300px">

        <!-- Header -->
        <header id="portfolio" class="w3-main w3-top w3-light-grey">
            <a href="#"><img src="imagens/logo6.png" style="width:65px;" class=" w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
            <span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
            <div class="w3-container">
                <h1><b>Equipamentos</b></h1>
                <div class="w3-section w3-bottombar w3-padding" id="categoria">
                    <span class="w3-margin-right">CATEGORIA:</span>
                    
                    <?php

                    include "adicionalmente/config.php";

                    $search = false;

                    if (isset($_GET['q'])) {
                        $q = $_GET['q'];
                        $search = true;
                    }

                    $sql    = "SELECT * FROM categorias";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <span class="wrapper">
                            <a href="?cat=<?php echo $row['Id_Categoria'] ?><?php echo $search ? "&q=$q" : "" ?>">
                                <button class="w3-white btn bot-click" id="<?php echo $row['Id_Categoria'] ?>" name="categorias[]"><?php echo $row['Nome_Categoria'] ?></button>
                            </a>
                            <?php if (isset($_SESSION['Num_Utilizador']) && isset($_SESSION['User_Admin']) && $_SESSION['User_Admin'] == 'sim') { ?> 
                                <div class="tooltip">
                                    <a href="javascript:func()" onclick="confirmar(<?php echo $row['Id_Categoria']; ?>)">
                                        <i class="material-icons w3-hover-text-grey" style="color: #04748c">delete</i>
                                    </a>
                                    <a href="alterar.php?Id_Categoria=<?php echo $row['Id_Categoria']; ?>">
                                        <i class="material-icons w3-hover-text-grey" style="color: #04748c">mode_edit</i>
                                    </a>
                                </div>
                            <?php } ?>
                        </span>
                        <?php
                    }
                    
                    
                    
if (isset($_SESSION['User_Admin']) && $_SESSION['User_Admin'] == 'sim') {
    ?>
    <a href="categorias.php">
        <button class="w3-white btn bot-click" id="adicionarcat">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    </a>
<?php 
}
?>

                </div>
            </div>
            <span>
                <form class="example w3-right" style="width: 30vw; margin-right:8vw; transform:translateY(-350%)">
                    <?php
                    $cat = 0;

                    if (isset($_GET['cat']) and (int) $_GET['cat'] >= 1) {
                        $cat = (int) $_GET['cat'];
                    }

                    if ($cat <> 0) {
                    ?>
                        <input type="hidden" name="cat" value="<?php echo $cat ?>">
                    <?php
                    }
                    ?>
                    <input type="text" placeholder="Procura.." name="q" style="display: inline-block;">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </span>
            <?php 
if (isset($_SESSION['User_Admin']) && $_SESSION['User_Admin'] == 'sim') {
    ?>
    <button onclick="printDiv('pdf')" class="button button5 w3-right w3-text-center" style="transform: translate(15vw,-0.5vw);">
        <i class="material-icons" style="font-size:24px;">print</i>
    </button>
<?php 
}
?>

        </header>
        
        <div class="w3-container">
        
            <br><br><br><br><br><br>
            
            <?php


            include "adicionalmente/config.php";



            $sql = "SELECT * FROM Equipamentos";

            if ($cat <> 0) $sql .= " INNER JOIN Categorias_Equipamentos ON Equipamentos.Cod_Equipamento = Categorias_Equipamentos.Cod_Equipamento WHERE id_categoria = '$cat'";

            if ($search && $cat <> 0) {
                $sql .= " and match(Nome_Equipamento) against('$q*' in boolean mode)";
            } else if ($search) {
                $sql .= " WHERE match(Nome_Equipamento) against('$q*' in boolean mode)";
            }

            $sql .= " ORDER BY Quantidade DESC";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
            ?>
                <div id="pdf">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <div class="w3-third w3-container w3-margin-bottom" style="margin-bottom: 0;">
    <img src="foto/<?php echo $row['Imagem'] ?>" style="width:100%" onclick="onClick(this)" class="w3-hover-opacity img1">
    <div class="w3-container w3-white">

        <p style="margin-bottom: 0;"><b><?php echo $row['Nome_Equipamento'] ?></b></p>
        <p class="hyphenate" style="margin-bottom: 0;"><?php echo $row['Descricao'] ?></p>

        <p style="font-size: 20px; text-align: right; margin-bottom: 0;"><?php echo number_format($row['Preco'], 2, ',', '.') ?> €</p>

        <?php
        if ($row['Quantidade'] > 0) {
        ?>
            <button type="button" class="botaoreserva button button3 w3-right" id="emprestar" style="transform: translateY(-430%);" onclick="abrirmodal( <?php echo $row['Cod_Equipamento'] ?> )"> Comprar </button>
        <?php
        } 
        ?>
        <p style="margin-bottom: 0;"><b>Qtd:</b> <?php echo "<span id='quantidade' value='" . $row['Quantidade'] . "'>" . $row['Quantidade'] . "</span>" ?> &nbsp; &nbsp; <b>Vendidos:</b> <?php echo "<span id='vendidos' value='" . $row['Emprestimo_Ativo'] . "'>" . $row['Emprestimo_Ativo'] . "</span>" ?> </p>

        <?php
        if (
            (isset($_SESSION['User_Admin']) && $_SESSION['User_Admin'] == 'sim') ||
            (isset($_SESSION['User_Client']) && $_SESSION['User_Client'] == 'sim' && isset($row['Num_Utilizador']) && $row['Num_Utilizador'] == $_SESSION['Num_Utilizador'])
        ) {
            echo '
                <div class="w3-row">
                    <div class="w3-col s12">
                        <a href="javascript:func()" onclick="confirmacao(' . $row['Cod_Equipamento'] . ')">
                            <i class="fa fa-trash w3-white" style="font-size: 18px;"></i>
                        </a>
                        <a href="3-alterar_equipamento.php?id=' . $row['Cod_Equipamento'] . '">
                            <i class="fas fa-pencil-alt w3-white" style="font-size: 18px; margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            ';
        }
        ?>

        
    </div>
</div>

                    <?php
                    } ?>
                </div>
            <?php
            } else {
                echo "Não existem equipamentos!";
            }
            include "adicionalmente/close.php";
            ?>
        </div>
        <br>
    </div>
    <br><br>

    <div id="modal01" class="w3-modal " style="padding-top:0" onclick="this.style.display='none'">
        <span class="w3-button w3-xlarge w3-display-topright">×</span>
        <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
            <img id="img01" class="w3-image">
            <p id="caption"></p>
        </div>
    </div>

    <!-----------------------------------------------Modal Vendas-------------------------------->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    
    <?php
    include "adicionalmente/config.php";

    // Verificar se o Utilizador está autenticado
    if (!isset($_SESSION['Num_Utilizador'])) {
        // Utilizador não autenticado, redirecionar para a página de login
        header('location: login.php');
        exit;
    } else {
        // Modal HTML
    ?>
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalhes Compra</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"> X </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="Cod_Equipamento" value="" id="Codigo">
                        <br>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example1">Primeiro Nome</label>
                                    <input type="text" id="form6Example1" class="form-control" name="Nome" required />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example2">Apelido</label>
                                    <input type="text" id="form6Example2" class="form-control" name="Apelido" required />
                                </div>
                            </div>
                        </div>
                        <!-- Email input -->
                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example5">Email</label>
                                    <input type="email" id="form6Example3" class="form-control" name="Email_Cliente" required />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example5">Telemovel</label>
                                    <input type="tel" id="form6Example3" class="form-control" name="Telemovel" pattern="[0-9]{9}" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-outline mb-2">
                            <label class="form-label" for="form6Example3">Quantidade</label>
                            <input type="number" id="form6Example4" class="form-control" name="Quantidade_venda" required />
                        </div>

                        <div class="form-outline mb-2" id="Morada">
                            <label class="w3-tamanho18 form-label" for="form6Example3">Morada</label>
                            <input type="text" id="form6Example4" class="form-control" name="Morada" required />
                        </div>

                        <div class="form-outline mb-2" id="CodPostal">
                            <label class="w3-tamanho18 form-label" for="form6Example3">Codigo Postal</label>
                            <input type="text" id="form6Example4" class="form-control" name="CodPostal_emprestimo" required />
                        </div>

                        <div class="row mb-2">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example1">Localidade</label>
                                    <input type="text" id="form6Example1" class="form-control" name="Localidade" required />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example2">Distrito</label>
                                    <input type="text" id="form6Example2" class="form-control" name="Distrito" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Finalizar Compra</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php } ?>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top" href="#">
        <i class="fa fa-angle-up" style="font-size: 26px; margin-left:12px; margin-top:7px"></i>
    </a>

    <footer class="w3-black w3-center w3-padding-16">Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-opacity">w3.css</a></footer>

    <script>
        setTimeout(function email(){ $("#email").hide(); }, 2000);
        // Modal Image Gallery
        function onClick(element) {
            document.getElementById("img01").src = element.src;
            document.getElementById("modal01").style.display = "block";
            var captionText = document.getElementById("caption");
            captionText.innerHTML = element.alt;
        }

        function abrirmodal(id) {
            document.getElementById("Codigo").value = id;
            $('#exampleModal').modal('show');
        }

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = "<html><head><title></title></head><body><h1 align='center'>Produtos Usados </h1>" + printContents + "</body>";
            

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    <script src="js/escolha.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <?php include "adicionalmente/fim.php" ?>
</body>

</html>