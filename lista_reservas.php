<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
include "adicionalmente/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (!isset($_SESSION)) {
        header('location:login.php');
        exit;
    }
    // Informa se a variável $_POST foi iniciada
    if (isset($_POST)) {
        $id = $_SESSION['Num_Utilizador'];
        $id_reserva = $_POST['id'];

        $sql1 = "SELECT * FROM Reservas WHERE NumReserva = $id_reserva";
        $result = mysqli_query($conn, $sql1);
        $row = mysqli_fetch_assoc($result);

        $tipo_reservante        =    $row["Tipo_Reservante"];
        $nome                   =    $row["Nome"];
        $apelido                =    $row["Apelido"];
        $email_reservante       =    $row["Email_Reservante"];
        $nrcartao               =    $row["NrCartao"];
        $data_prevfim           =    $row["Data_prevfim_reserva"];
        $ano                    =    $row["Ano"];
        $curso                  =    $row["Curso"];
        $turma                  =    $row["Turma"];
        $quantidade             =    $row["Quantidade_reserva"];
        $Cod_Equipamento        =    $row["Cod_Equipamento"];
            // Query para a inserção de dados na BD
            try {

                $sql = "INSERT INTO Vendas (Cod_Equipamento, tipo_reservante, nome, apelido, email_reservante, nrcartao, data_prevfim, ano, curso, turma, quantidade_emprestimo, Num_Utilizador)
                VALUES ('$Cod_Equipamento','$tipo_reservante', '$nome', '$apelido', '$email_reservante', '$nrcartao',  date_add(current_date, interval $data_prevfim day), '$ano', '$curso', '$turma', '$quantidade', '$id')";
    
                $query = "UPDATE Equipamentos Set Quantidade = Quantidade - $quantidade, Emprestimo_Ativo = Emprestimo_Ativo + $quantidade Where Cod_Equipamento = $Cod_Equipamento";
    
                $delete = "DELETE FROM Reservas WHERE NumReserva = $id_reserva";
                // Executa a query e verifica se deu erro
                $conn->begin_transaction();
                if (!mysqli_query($conn, $sql)) throw new Exception();
                if (!mysqli_query($conn, $query)) throw new Exception();
                if (!mysqli_query($conn, $delete)) throw new Exception();
                $conn->commit();
                header("location:lista_emprestimo.php?insere=1");
            } catch (Exception $e) {
                $conn->rollback();
    
    
                echo "<script language='javascript' type='text/javascript' align='center'>
                        alert('Erro ao concluir Empréstimo; Razão: A quantidade inserida ultrapassa a quantidade de produtos disponiveis.');
                        window.location='index.php'; </script>";
            }

    }
    include "adicionalmente/close.php";
}

include "adicionalmente/head.php" ?>
<title>Reservas</title>
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
                <h1><b>Reservas ativas</b></h1>
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
                                    td = tr[i].getElementsByTagName("td")[3];
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
                            $sql = "SELECT * FROM Reservas INNER JOIN Equipamentos ON Reservas.Cod_Equipamento = Equipamentos.Cod_Equipamento";

                            // Executar a query
                            $result = mysqli_query($conn, $sql);

                            // Verifica se recebeu pelo menos um registo
                            if (mysqli_num_rows($result) > 0) { ?>
                                <tr class="w3-teal">
                                    <th>Nome</th>
                                    <th>Ano / Turma</th>
                                    <th>Tipo</th>
                                    <th>Nome do Equipamento</th>
                                    <th>Quantidade</th>
                                    <th style="width: 100px;">Data Prevista Entrega</th>
                                    <th class="w3-center" colspan="3">Operações</th>
                                </tr>

                                <?php
                                // Obter cada registo da base de dados para a variável $row
                                while ($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr>
                                        <td style="font-size: 16px;"><?php echo $row['Nome']; ?> <?php echo $row['Apelido']; ?></td>
                                        <td style="font-size: 16px;"><?php echo $row['Ano']; ?> <?php echo $row['Turma']; ?></td>
                                        <td style="font-size: 16px;"><?php echo $row['Tipo_Reservante']; ?></td>
                                        <td style="font-size: 16px;"><?php echo $row['Nome_Equipamento']; ?></td>
                                        <td style="font-size: 16px;"><?php echo $row['Quantidade_reserva']; ?></td>
                                        <td style="font-size: 16px;"><?php echo $row['Data_prevfim_reserva']; ?> Dias</td>
                                        <td style="width:5%; border-right: solid 0px #ccc;">
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['NumReserva']; ?>">
                                            <input class="w3-button w3-border w3-text-teal" id="Emprestar" type="submit" value="Emprestar">
                                        </form>
                                        </td>
                                        <td style="width:5%; border-left:  solid 0px #ccc;">
                                            <a href="visualizar_reserva.php?codigo=<?php echo $row['NumReserva']; ?>">
                                                <button class="w3-button w3-border w3-text-teal" type="button"> Detalhes </button>
                                            </a>
                                        </td>
                                        <td style="border-left: hidden;">
                                        <a href="inserecomentario.php?codigo=<?php echo $row['NumReserva']; ?>">
                                        <i class="fa fa-envelope-open w3-text-teal" style="font-size: 20px; transform: translateY(30%)" aria-hidden="true"></i>
                                    </a>
                                        </td>
                                    </tr>

                            <?php }
                            } else {
                                echo "Não existem Reservas na BD.";
                            } ?>
                        </table>

                        <?php include "adicionalmente/close.php"; ?>
                    </div>
                </div>
                <script src="js/escolha.js"></script>
                <?php include "adicionalmente/fim.php" ?>
</body>

</html>