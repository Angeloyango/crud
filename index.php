<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ListaDeTarefas";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adicionar tarefa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
    $nome = $_POST["nome"];
    $data_execucao = $_POST["data_execucao"];
    $sql = "INSERT INTO tarefa (nome, data_execucao) VALUES ('$nome', '$data_execucao')";
    if ($conn->query($sql) === TRUE) {
        echo "Nova tarefa adicionada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Editar tarefa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_id"])) {
    $id = $_POST["editar_id"];
    $nome = $_POST["editar_nome"];
    $data_execucao = $_POST["editar_data_execucao"];
    $sql = "UPDATE tarefa SET nome='$nome', data_execucao='$data_execucao' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Tarefa editada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Eliminar tarefa
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    $sql = "DELETE FROM tarefa WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Tarefa eliminada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Marcar tarefa como concluída
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["complete_id"])) {
    $id = $_GET["complete_id"];
    $sql = "UPDATE tarefa SET concluida = TRUE WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Tarefa marcada como concluída!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Buscar tarefas
$sql = "SELECT * FROM tarefa";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Lista de Tarefas</h1>
    <form method="post" action="index.php">
        <input type="text" name="nome" placeholder="Digite a tarefa" required>
        <input type="date" name="data_execucao" placeholder="Data de Execução" required>
        <button type="submit">Adicionar Tarefa</button>
    </form>
    <h2>Tarefas</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li>" . $row["nome"] . " - " . $row["data_execucao"] . " - " . ($row["concluida"] ? "Concluída" : "Pendente") . 
                " <a href='index.php?delete_id=" . $row["id"] . "'>Eliminar</a>" . 
                " <a href='#' onclick='editarTarefa(" . $row["id"] . ", \"" . $row["nome"] . "\", \"" . $row["data_execucao"] . "\")'>Editar</a>" . 
                ($row["concluida"] ? "" : " <a href='index.php?complete_id=" . $row["id"] . "'>Concluir</a>") . "</li>";
            }
        } else {
            echo "Nenhuma tarefa encontrada";
        }
        ?>
    </ul>

    <div id="editarModal" style="display:none;">
        <form method="post" action="index.php">
            <input type="hidden" name="editar_id" id="editar_id">
            <input type="text" name="editar_nome" id="editar_nome" placeholder="Digite a tarefa" required>
            <input type="date" name="editar_data_execucao" id="editar_data_execucao" required>
            <button type="submit">Salvar</button>
        </form>
    </div>

    <script>
    function editarTarefa(id, nome, data_execucao) {
        document.getElementById('editar_id').value = id;
        document.getElementById('editar_nome').value = nome;
        document.getElementById('editar_data_execucao').value = data_execucao;
        document.getElementById('editarModal').style.display = 'block';
    }
    </script>
</body>
</html>

<?php
$conn->close();
?>
