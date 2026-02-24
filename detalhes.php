<?php
session_start();
if (!isset($_SESSION['Num_Utilizador'])) { header('location: login.php'); exit; }
                            include "adicionalmente/config.php";

                            $altID = isset($_GET['id']) ? $_GET['id'] : null;

                            if (isset($_POST)) {
                                $DataInicio             = $_POST["datainicio"];
                                $Data_prevfim           = $_POST["data_prevfim"];
                                $Num_Utilizador         = $_POST["num_utilizador"];
                                $Quantidade_emprestimo  = $_POST["quantidade_emprestimo"];
                                $NrCartao               = $_POST["nrCartao"];
                                $Nome                   = $_POST["nome"];
                                $Apelido                = $_POST["apelido"];
                                $Email_Reservante       = $_POST["email_Reservante"];
                                $Tipo_Reservante        = $_POST["tipo_reservante"];
                                $Ano                    = $_POST["Ano"];
                                $Curso                  = $_POST["Curso"];
                                $Turma                  = $_POST["Turma"];

                                //print_r($_POST);

                                $sql = "SELECT * FROM Vendas WHERE Cod_Equipamento=$altID";
                                //echo "Teste: ".$sql."Fim";

                                if (mysqli_query($conn, $sql)) {
                                    //echo "Record updated successfully";
                                    header("location:lista_emprestimo.php?alterar=1");
                                } else {
                                    echo "Erro ao atualizar: " . mysqli_error($conn);
                                }
                            }
                            include "adicionalmente/close.php";
?>