<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) {
    header('location: login.php');
    exit;
}
include "adicionalmente/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (!isset($_SESSION)) {
        header('location:login.php');
        exit;
    }
    
    // Informa se a variável $_POST foi iniciada
    if (isset($_POST)) {
        $id = $_SESSION['Num_Utilizador'];

        //Acesso aos dados do formulário
        $Num_Venda          =    $_POST["id"];

        $date = new DateTime();

        $sql = "SELECT Quantidade_venda, Cod_Equipamento FROM Vendas WHERE Num_Venda = $Num_Venda";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $quantidade = $row['Quantidade_venda'];
            $Cod_Equipamento = $row['Cod_Equipamento'];

            // Query para a inserção de dados na BD
            try {

                $query = "UPDATE Equipamentos Set Quantidade = Quantidade + $quantidade, Emprestimo_Ativo = Emprestimo_Ativo - $quantidade Where Cod_Equipamento = $Cod_Equipamento";

                // Executa a query e verifica se deu erro
                $conn->begin_transaction();
                if (!mysqli_query($conn, $query)) throw new Exception();
                $conn->commit();

                header("location:index.php?concluir=1");
            } catch (Exception $e) {
                $conn->rollback();
                // Apresenta o erro
                echo "<script language='javascript' type='text/javascript' align='center'>
                    alert('Erro: Este Empréstimo já foi concluído.');
                    window.location='index.php'; </script>";
            }
        }
    }
    include "adicionalmente/close.php";
}
include "adicionalmente/head.php" ?>
<title>Empréstimos</title>
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
            <div class="w3-container w3-bottombar">
                <h1><b>Vendas</b></h1>
            </div>
        </header>

        <div class="w3-container">
            <div id="informatica" style="margin-top: 80px;">
                <div class="w3-row-padding">
                    <div class="w3-container">

                        <p>
                            <input class="w3-input w3-third" style="width:250px; margin-right: 10px;" type="text" id="filtraN" onkeyup="filtraN()" placeholder="Procura por nome..." title="Digita um nome...">
                            <input class="w3-input w3-third" style="width:250px;" type="text" id="filtraE" onkeyup="filtraE()" placeholder="Procura por equipamento... " title="Digita um equipamento...">
                        </p>

                        <script>
                            function filtraN() {
                                var input, filter, table, tr, td, i, txtValue;
                                input = document.getElementById("filtraN");
                                filter = input.value.toUpperCase();
                                table = document.getElementById("myTable");
                                tr = table.getElementsByTagName("tr");
                                for (i = 0; i < tr.length; i++) {
                                    td = tr[i].getElementsByTagName("td")[0];
                                    if (td) {
                                        txtValue = td.textContent || td.innerText;
                                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                            tr[i].style.display = "";
                                        } else {
                                            tr[i].style.display = "none";
                                        }
                                    }
                                }
                            }
                        </script>
                        <script>
                            function filtraE() {
                                var input, filter, table, tr, td, i, txtValue;
                                input = document.getElementById("filtraE");
                                filter = input.value.toUpperCase();
                                table = document.getElementById("myTable");
                                tr = table.getElementsByTagName("tr");
                                for (i = 0; i < tr.length; i++) {
                                    td = tr[i].getElementsByTagName("td")[1];
                                    if (td) {
                                        txtValue = td.textContent || td.innerText;
                                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                            tr[i].style.display = "";
                                        } else {
                                            tr[i].style.display = "none";
                                        }
                                    }
                                }
                            }
                        </script>

                        <br><br>
                        <table class="w3-table w3-bordered" id="myTable">

                            <?php
                            // Query para selecionar todos os dados da tabela Vendas
                            $sql = "SELECT * FROM Vendas INNER JOIN Equipamentos ON Vendas.Cod_Equipamento = Equipamentos.Cod_Equipamento ORDER BY Vendas.Data_Venda";

                            // Executar a query
                            $result = mysqli_query($conn, $sql);

                            // Verifica se recebeu pelo menos um registo
                            if (mysqli_num_rows($result) > 0) { ?>
                                <tr class="w3-teal">
                                    <th>Nome</th>
                                    <th>Nome do Equipamento</th>
                                    <th style="width: 110px;">Data Venda</th>
                                    <th>Quantidade</th>
                                    <th>Email</th>
                                    <th>Opções</th>
                                </tr>

                                <tbody id="filtrarVendas">
                                    <?php
                                    // Obter cada registo da base de dados para a variável $row
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <td style="font-size: 16px;"><?php echo $row['Nome']; ?> <?php echo $row['Apelido']; ?></td>
                                            <td style="font-size: 16px;"><?php echo $row['Nome_Equipamento']; ?></td>
                                            <td style="font-size: 16px;"><?php echo $row['Data_Venda']; ?></td>
                                            <td style="font-size: 16px;"><?php echo $row['Quantidade_venda']; ?></td>
                                            <td style="font-size: 16px;"><?php echo $row['Email_Cliente']; ?></td>
                                            <td style="width:5%; border-left:  solid 0px #ccc;">
                                                <a href="visualizar.php?codigo=<?php echo $row['Num_Venda']; ?>">
                                                    <button class="w3-button w3-border w3-text-black" type="button"> Detalhes </button>
                                                </a>
                                            </td>

                                        </tr>

                                    <?php } ?>
                                </tbody>
                            <?php
                            } else {
                                echo "Não existem Vendas na BD.";
                            } ?>
                        </table>

                        <?php include "adicionalmente/close.php"; ?>
                    </div>
                </div>
                <script src="js/escolha.js"></script>
                <?php include "adicionalmente/fim.php" ?>

</body>

</html>