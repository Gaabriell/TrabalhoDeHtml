<?php
session_start(); // Inicie a sessão para usar variáveis de sessão

// Estabeleça a conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carteirasegura";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Processamento do formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);

    $sql = "SELECT id, email FROM dadoscadastro WHERE email='$email' AND senha='$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Autenticação bem-sucedida, redireciona para a próxima página
        $user = $result->fetch_assoc();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id']; // Armazena o ID do usuário na sessão
        header("Location: financeiro.php");
        exit();
    } else {
        // Credenciais inválidas, define uma mensagem de erro
        $_SESSION['error_message'] = "Credenciais inválidas. Por favor, tente novamente.";
        header("Location: index.php"); // Redireciona de volta para a página de login
        exit();
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
