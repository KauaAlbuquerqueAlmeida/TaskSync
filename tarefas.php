<?php
session_start();

$alertMessage = null;

$servername = "localhost";
$afazer = "root";
$fazendo = "";
$concluido = "";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Tarefas</title>
</head>
<body>
    <h1>Tabela de Tarefas</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Afazer</th>
                <th>Fazendo</th>
                <th>Concluído</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Aqui você pode adicionar a lógica para preencher a tabela com os dados do banco de dados
            ?>
        </tbody>
    </table>

    <form method="POST" action="tarefas.php">
        <input type="text" name="afazer" placeholder="Afazer" required>
        <input type="text" name="fazendo" placeholder="Fazendo" required>
        <input type="text" name="concluido" placeholder="Concluído" required>
        <button type="submit">Adicionar Tarefa</button>
    </form>
    
</body>
</html>