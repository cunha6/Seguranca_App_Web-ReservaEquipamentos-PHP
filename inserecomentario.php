<?php
include "adicionalmente/config.php";
include "auxEnviaEmail.php";

$NumReserva = $_GET["codigo"];

$sql = "SELECT * FROM Reservas INNER JOIN equipamentos ON Reservas.Cod_Equipamento=equipamentos.Cod_Equipamento where NumReserva=$NumReserva";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nome=$row['Nome'];
$email=$row['Email_Reservante'];

if (mysqli_query($conn, $sql)) {
        header("location:index.php?email=1");
} 
else {
        echo "Erro: " . $sql . "<br>" . mysqli_error($conn);
}

$assuntoMsg = '[AEFH] Reserva do Equipamento';
$mensagem = "Caro(a) Sr.(a) ". $row['Nome'] ." " . $row['Apelido'] ."<br><br>
        Obrigado pela sua Reserva.<br>
        O seu Equipamento já está disponivel para entrega.<br>
        Esperamos vê-lo em breve.<br><br>
        Atentamente, <br>
        AE Francisco de Holanda<br><br><br>
        Cod. Equipamento: ". $row['Cod_Equipamento'] ."<br>
        Nome Equipamento: ". $row['Nome_Equipamento'] ."";
	
enviaEmail($nome, $email, $assuntoMsg, $mensagem);
		
	
include "adicionalmente/close.php";
?>