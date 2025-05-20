<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: tarefas.php");
    exit();
}

$hostname = "localhost";
$username = "root";
$password = "";
$database = "tasksync";
$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nome'];
    $password = $_POST['senha'];
    }

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="./css/cadastro.css">
</head>
<body>
    <div class="container">
         <div class="logo-container">
            <img class="logo" src="./img/tasksynclogo-removebg-preview.png" alt="Logo" class="logo">
        </div>
        <h1>Cadastro</h1>
        <form method="POST" action="login_cadastro.php">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <label for="senha_confirmacao">Confirme a Senha:</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>

