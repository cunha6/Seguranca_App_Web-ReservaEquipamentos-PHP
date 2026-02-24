<?php 
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
include "adicionalmente/config.php" ?>
<?php include "adicionalmente/head.php" ?>
<title>Visualizar Empréstimo</title>
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
        <header id="portfolio" class="w3-main w3-light-grey">
            <a href="#"><img src="imagens/logo.jpg" style="width:65px;" class="w3-circle w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
            <span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
            <div class="w3-container w3-bottombar">
                <h1><b>Ver Pedido</b></h1>
            </div>
        </header>


        <div class="container">
            
            <?php

            $Num_Venda = $_GET["codigo"];

            $sql_Vendas = "SELECT * FROM Vendas where Num_Venda=$Num_Venda";
            $result_Vendas = mysqli_query($conn, $sql_Vendas);
            $row_Vendas = mysqli_fetch_assoc($result_Vendas); ?>
            <form action="">
                
                
                
                <div class="row mb-4">
                    <div class="col">

                        <div class="form-outline">
                            <label class="form-label">Primeiro Nome</label>
                            <input type="text" id="Nome" class="form-control" value="<?php echo $row_Vendas["Nome"]; ?>" readonly />
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-outline">
                            <label class="form-label">Apelido</label>
                            <input type="text" id="Apelido" class="form-control" value="<?php echo $row_Vendas["Apelido"]; ?>" readonly />
                        </div>
                    </div>
                </div>
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" id="Email_Cliente" class="form-control" value="<?php echo $row_Vendas["Email_Cliente"]; ?>" readonly />
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label">Data</label>
                    <input type="date" id="Data_Venda" class="form-control" value="<?php echo $row_Vendas["Data_Venda"]; ?>" readonly />
                </div>

                <div class="form-outline mb-4">
                    <label class="form-label">Quantidade</label>
                    <input type="number" id="Quantidade_venda" class="form-control" value="<?php echo $row_Vendas["Quantidade_venda"]; ?>" readonly />
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <div class="form-outline mb-4">
                            <label class="w3-tamanho18 form-label" for="form6Example5">Morada</label>
                            <input type="text" id="Morada" class="form-control" value="<?php echo $row_Vendas['Morada']; ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <div id="Turma">
                            <div class="form-outline mb-4">
                                <label class="w3-tamanho18 form-label" for="form6Example7">Codigo Postal</label>
                                <input type="text" id="CodPostal" class="form-control" value="<?php echo $row_Vendas['CodPostal']; ?>" readonly />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="form-outline mb-4">
                            <label class="w3-tamanho18 form-label" for="form6Example5">Localidade</label>
                            <input type="text" id="Localidade" class="form-control" value="<?php echo $row_Vendas['Localidade']; ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div id="Turma">
                            <div class="form-outline mb-4">
                                <label class="w3-tamanho18 form-label" for="form6Example7">Distrito</label>
                                <input type="text" id="Distrito" class="form-control" value="<?php echo $row_Vendas['Distrito']; ?>" readonly />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
            include "adicionalmente/close.php";
            ?>
            <div class="footer">
                <a href="lista_emprestimo.php" type="submit" class="btn btn-primary" style="margin-top: 0%; margin-bottom:0%" data-dismiss="modal" aria-label="Close">Voltar</a>
            </div>
        </div>

        <br>
    </div>

</body>

</html>