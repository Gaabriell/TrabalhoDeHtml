<?php
// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos estão presentes
    if (isset($_POST['nome']) && isset($_POST['telefone']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['cpf']) && isset($_POST['sexo'])) {
        
        // Estabelece a conexão com o banco de dados
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "carteirasegura";

        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Verifica a conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }
        
        // Escapar caracteres especiais para evitar injeção de SQL
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);
        $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
        $sexo = mysqli_real_escape_string($conn, $_POST['sexo']);

        // Verifica se o email ou o CPF já existem no banco de dados
        $check_email_cpf = "SELECT * FROM dadoscadastro WHERE email='$email' OR cpf='$cpf'";
        $result = $conn->query($check_email_cpf);
        if ($result->num_rows > 0) {
            echo "<script>alert('Este email ou CPF já está em uso. Por favor, escolha outro.'); window.location='cadastro.html';</script>";
            exit(); // Certifique-se de sair após redirecionar
        }

        // Cria a consulta SQL para inserir os dados no banco de dados
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT); // Hash da senha antes de armazenar
        $sql = "INSERT INTO dadoscadastro (nome, telefone, email, senha, cpf, sexo) VALUES ('$nome', '$telefone', '$email', '$senha_hash', '$cpf', '$sexo')";

        if ($conn->query($sql) === TRUE) {
            // Exibe a mensagem de conclusão de envio de dados
            echo "<script>alert('Cadastrado com sucesso!'); window.location='cadastro.html';</script>";
            exit(); // Certifique-se de sair após o redirecionamento
        } else {
            echo "Erro ao inserir os dados: " . $conn->error;
        }
        
        // Fecha a conexão com o banco de dados
        $conn->close();
    } else {
        echo "Todos os campos do formulário são obrigatórios!";
    }
} else {
    echo "O formulário não foi submetido!";
}
?>
