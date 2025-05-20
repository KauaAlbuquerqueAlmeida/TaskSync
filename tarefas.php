<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tasksync";

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Adicionar nova tarefa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $afazer = $_POST['afazer'];
    $fazendo = $_POST['fazendo'];
    $concluido = $_POST['concluido'];
    $id_user = $_POST['usuario'];

    // Buscar o username correspondente ao id_user
    $stmtUser = $conn->prepare("SELECT username FROM users WHERE id_user = ?");
    $stmtUser->bind_param("i", $id_user);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userRow = $resultUser->fetch_assoc();
    $username = $userRow['username'] ?? 'Desconhecido';
    $stmtUser->close();

    // Inserir tarefa
    $stmt = $conn->prepare("INSERT INTO tabela (titulo, afazer, fazendo, concluido, usuario) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $titulo, $afazer, $fazendo, $concluido, $username);
    $stmt->execute();
    $stmt->close();
}

// Buscar tarefas existentes
$sql_tarefas = "SELECT titulo, afazer, fazendo, concluido, usuario FROM tabela ORDER BY id DESC";
$result_tarefas = $conn->query($sql_tarefas);

// Buscar usuários para o select
$sql_usuarios = "SELECT id_user, username FROM users ORDER BY username ASC";
$result_usuarios = $conn->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="./css/tarefas.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img class="logo" src="./img/tasksynclogo-removebg-preview.png" alt="Logo TaskSync">
        </div>
    
        <h1>Gerenciador de Tarefas</h1>

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Afazer</th>
                    <th>Fazendo</th>
                    <th>Concluído</th>
                    <th>Responsável</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_tarefas && $result_tarefas->num_rows > 0) {
                    while($row = $result_tarefas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['afazer']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fazendo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['concluido']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhuma tarefa encontrada</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Adicionar Nova Tarefa</h2>
        <form method="POST" action="tarefas.php">
            <input type="text" name="titulo" placeholder="Título da tarefa" required>
            <input type="text" name="afazer" placeholder="Afazer" required>
            <input type="text" name="fazendo" placeholder="Fazendo" required>
            <input type="text" name="concluido" placeholder="Concluído" required>

            <select name="usuario" required>
                <option value="">Selecione o responsável</option>
                <?php
                if ($result_usuarios && $result_usuarios->num_rows > 0) {
                    while($usuario = $result_usuarios->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($usuario['id_user']) . "'>" . htmlspecialchars($usuario['username']) . "</option>";
                    }
                } else {
                    echo "<option disabled>Nenhum usuário encontrado</option>";
                }
                ?>
            </select>

            <button type="submit">Adicionar Tarefa</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
