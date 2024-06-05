<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteira Segura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="cabecalho">
        <div class="logo">
            <img src="img/CarteiraSegura (1).png" alt="Logo Carteira Segura">
        </div>
        <nav class="cabecalho-menu">
            <a class="cabecalho-menu-item" href="contato.html">Contato</a>
            <a class="cabecalho-menu-item" href="sobre.html">Sobre</a>
        </nav>
    </header>

    <main class="conteudo">
        <section class="conteudo-principal">
            <div class="container">
                <div class="row justify-content-center align-items-center" style="height: 100vh;">
                    <div class="col-md-6">
                        <div class="painel"> <!-- Div para o painel de fundo -->
                            <h1 class="text-center">Formulário de Login</h1>
                            <!-- Exibir mensagem de erro, se existir -->
                            <?php
                            session_start();
                            if (isset($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                                unset($_SESSION['error_message']); // Limpar a mensagem de erro após exibi-la
                            }
                            ?>
                            <form action="login.php" method="post">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Senha:</label>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                </div>
                                <button type="submit" class="btn btn-login">Entrar</button>
                                <button type="button" class="btn btn-cadastro" onclick="window.location.href='cadastro.html';">Criar conta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <section class="conteudo-segundario">
        <h2 class="conteudo-segundario-titulo">O que você precisa saber ?</h2>
        <p class="conteudo-segundario-paragrafo">1. É um <strong>sistema para Controles de Receitas e Despesas</strong></p>
        <p class="conteudo-segundario-paragrafo">2. Possui um <strong>sistema para Visualização de Dados e Segurança</strong></p>
    </section>

    <footer class="rodape">
        <hr>
        <p>&copy;2024 CarteiraSegura | Desenvolvido por Artur, Anderson e Gabriel</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/3qT3SQfawRcv/BIHPThkBvs00EvtFFmPF/lyI/Cxo=" crossorigin="anonymous"></script>
    <script src="scripts.js"></script>
</body>

</html>
