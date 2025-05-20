<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: tarefas.php");
    exit();
}

$hostname = "localhost";
$db_username = "root";
$db_password = "";
$database = "tasksync";

// Conexão
$conn = new mysqli($hostname, $db_username, $db_password, $database);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['nome']);
    $password = $_POST['senha'];
    $confirm_password = $_POST['senha_confirmacao'];

    // Verificar se as senhas coincidem
    if ($password !== $confirm_password) {
        $erro = "As senhas não coincidem!";
    } else {
        // Verificar se o usuário já existe
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $erro = "Nome de usuário já está em uso.";
        } else {
            // Inserir novo usuário
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password); // Você pode usar hash da senha se quiser segurança
            if ($stmt->execute()) {
                $sucesso = "Cadastro realizado com sucesso!";
                // Redireciona para login ou tarefas, se desejar
                // $_SESSION['usuario'] = $username;
                // header("Location: tarefas.php");
                // exit();
            } else {
                $erro = "Erro ao cadastrar usuário: " . $stmt->error;
            }
        }
    }
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
            <img class="logo" src="./img/tasksynclogo-removebg-preview.png" alt="Logo TaskSync">
        </div>
        <h1>Cadastro</h1>

        <?php if ($erro): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php elseif ($sucesso): ?>
            <p style="color: green;"><?= $sucesso ?></p>
        <?php endif; ?>

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

