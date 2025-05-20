<?php
session_start();

$alertMessage = null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tasksync";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se o usuário existe
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: tarefas.php");
            exit();
        } else {
            $alertMessage = "Senha incorreta.";
        }
    } else {
        $alertMessage = "Usuário não encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img class="logo" src="./img/tasksynclogo-removebg-preview.png" alt="Logo" class="logo">
        </div>
            <h1>LOGIN</h1>
            <form method="post" action="">
                <label for="username">Nome</label>
                <input type="text" id="username" name="username" autocomplete="off" required>
                <br>
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
                <br>
                <br>
                <button type="submit">Entrar</button>
            </form> <br>

            <p>Não tem uma conta?</p> <br>
            <button type="button" onclick="window.location.href='login_cadastro.php';">Cadastre-se</button>
    </div>

    <?php if ($alertMessage): ?>
    <div id="alertModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p><?php echo $alertMessage; ?></p>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function closeModal() {
            const modal = document.getElementById('alertModal');
            modal.style.animation = 'fadeOut 0.5s ease';
            setTimeout(() => modal.style.display = 'none', 500);
        }

        window.onload = function() {
            const modal = document.getElementById('alertModal');
            if (modal) {
                setTimeout(closeModal, 3000);
            }
        }
    </script>
</body>
</html>
