session_start(); // Inicie a sessão para usar variáveis de sessão

// Verifique se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carteirasegura";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$message = ''; // Variável para armazenar mensagens de feedback

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Pega o ID do usuário da sessão

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'delete') {
            // Excluir registro
            $id = $conn->real_escape_string($_POST['id']);
            $sql = "DELETE FROM dadosfinanceiro WHERE id='$id' AND user_id='$user_id'";
            
            if ($conn->query($sql) === TRUE) {
                $message = '<div class="alert alert-success" role="alert">Registro excluído com sucesso!</div>';
            } else {
                $message = '<div class="alert alert-danger" role="alert">Erro ao excluir registro: ' . $conn->error . '</div>';
            }
        } elseif ($_POST['action'] == 'edit') {
            // Editar registro
            $id = $conn->real_escape_string($_POST['id']);
            $sql = "SELECT * FROM dadosfinanceiro WHERE id='$id' AND user_id='$user_id'";
            $editResult = $conn->query($sql);

            if ($editResult->num_rows > 0) {
                $editRow = $editResult->fetch_assoc();
            }
        } elseif ($_POST['action'] == 'update') {
            // Atualizar registro
            $id = $conn->real_escape_string($_POST['id']);
            $tipo = $conn->real_escape_string($_POST['tipo']);
            $descricao = $conn->real_escape_string($_POST['descricao']);
            $valor = $conn->real_escape_string($_POST['valor']);

            $sql = "UPDATE dadosfinanceiro SET tipo='$tipo', descricao='$descricao', valor='$valor' WHERE id='$id' AND user_id='$user_id'";
            
            if ($conn->query($sql) === TRUE) {
                $message = '<div class="alert alert-success" role="alert">Registro atualizado com sucesso!</div>';
            } else {
                $message = '<div class="alert alert-danger" role="alert">Erro ao atualizar registro: ' . $conn->error . '</div>';
            }
        }
    } else {
        // Inserir registro
        $tipo = $conn->real_escape_string($_POST['tipo']);
        $descricao = $conn->real_escape_string($_POST['descricao']);
        $valor = $conn->real_escape_string($_POST['valor']);

        $sql = "INSERT INTO dadosfinanceiro (tipo, descricao, valor, user_id) VALUES ('$tipo', '$descricao', '$valor', '$user_id')";
        
        if ($conn->query($sql) === TRUE) {
            $message = '<div class="alert alert-success" role="alert">Registro inserido com sucesso!</div>';
        } else {
            $message = '<div class="alert alert-danger" role="alert">Erro ao inserir registro: ' . $conn->error . '</div>';
        }
    }
}

// Consultar as transações do banco de dados para o usuário logado
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM dadosfinanceiro WHERE user_id='$user_id'";
$result = $conn->query($sql);

// Calcular total de receitas e despesas
$total_receitas = 0;
$total_despesas = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['tipo'] == 'receita') {
            $total_receitas += $row['valor'];
        } elseif ($row['tipo'] == 'despesa') {
            $total_despesas += $row['valor'];
        }
    }
}

$saldo = $total_receitas - $total_despesas;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Finanças</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="financeiro.css">
</head>
<body>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: white;">Controle de Finanças</h2>
        <a href="logout.php" class="btn btn-danger">Sair</a>
    </div>

    <?php echo $message; // Exibe a mensagem de feedback ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Inserir Transação</h2>
                    <form action="financeiro.php" method="post">
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo:</label>
                            <select name="tipo" id="tipo" class="form-select">
                                <option value="receita">Receita</option>
                                <option value="despesa">Despesa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor:</label>
                            <input type="number" id="valor" name="valor" min="0" step="0.01" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($editRow)) { ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h2 class="text-center mb-4">Editar Transação</h2>
                    <form action="financeiro.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $editRow['id']; ?>">
                        <input type="hidden" name="action" value="update">
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo:</label>
                            <select name="tipo" id="tipo" class="form-select">
                                <option value="receita" <?php if ($editRow['tipo'] == 'receita') echo 'selected'; ?>>Receita</option>
                                <option value="despesa" <?php if ($editRow['tipo'] == 'despesa') echo 'selected'; ?>>Despesa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição:</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" value="<?php echo $editRow['descricao']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor:</label>
                            <input type="number" id="valor" name="valor" min="0" step="0.01" class="form-control" value="<?php echo $editRow['valor']; ?>" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>

            <div class="card mt-4">
                <div class="card-body">
                    <h2 class="text-center mb-4">Transações</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                          
                            <tr>
                                <th>Saldo:</th>
                                <td><?php echo $saldo; ?></td>
                                <td><?php echo $Valor; ?></td>
                                <td><?php echo $Ações; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Resetar o ponteiro do resultado para o início
                            $result->data_seek(0); 
                            while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['tipo']; ?></td>
                                <td><?php echo $row['descricao']; ?></td>
                                <td><?php echo $row['valor']; ?></td>
                                <td>
                                    <form action="financeiro.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="edit">
                                        <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                                    </form>
                                    <form action="financeiro.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            

        </div>
    </div>
</div>

</body>
</html>
