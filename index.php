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
    $email = $_POST['username'];
    $senha = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertMessage = "Email inválido.";
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['tipo'] = $tipo;
                $_SESSION['autenticado'] = TRUE;
                header("Location: index.php");
                exit;
            } else {
                $alertMessage = "Usuário ou senha incorretos.";
            }
        } else {
            $alertMessage = "Usuário não encontrado.";
        }

        $stmt->close();
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
    <link rel="stylesheet" href="styles.css">
    <style>
        .modal {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.5s ease;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 300px;
            animation: slideIn 0.5s ease;
        }
        .modal-content p {
            font-size: 1.2em;
            color: #333;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5em;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>LOGIN</h1>
            <form method="post" action="">
                <label for="username">Email</label>
                <input type="text" id="username" name="username" autocomplete="off" required>

                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
                <br>

                <button type="submit">Entrar</button>
            </form> <br>

            <p>Não tem uma conta?</p> <br>
            <button type="button" onclick="window.location.href='login_cadastro.php';">Cadastre-se</button>
        </div>
        <div class="image-box">
        </div>
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
