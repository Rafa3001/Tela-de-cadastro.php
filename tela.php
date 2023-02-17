<?php
// Inclua o arquivo de conexão com o banco de dados
include('conexao.php');

// Inicialize as variáveis
$nome = '';
$email = '';
$senha = '';
$confirmarSenha = '';
$mensagem = '';

// Se o formulário for enviado, processe os dados
if (isset($_POST['enviar'])) {
  // Valide os dados de entrada
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];
  $confirmarSenha = $_POST['confirmar_senha'];

  if (empty($nome) || empty($email) || empty($senha) || empty($confirmarSenha)) {
    $mensagem = 'Todos os campos são obrigatórios.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $mensagem = 'Endereço de e-mail inválido.';
  } elseif ($senha !== $confirmarSenha) {
    $mensagem = 'As senhas não correspondem.';
  } else {
    // Hash a senha antes de salvar no banco de dados
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insira os dados do usuário no banco de dados
    $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)');
    $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senhaHash]);

    // Redirecione o usuário para a página de login
    header('Location: login.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registro de Usuário</title>
</head>
<body>
  <h1>Registro de Usuário</h1>

  <?php if (!empty($mensagem)): ?>
    <p style="color: red;"><?php echo $mensagem; ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?php echo $nome; ?>"><br><br>

    <label>E-mail:</label><br>
    <input type="email" name="email" value="<?php echo $email; ?>"><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha"><br><br>

    <label>Confirmar Senha:</label><br>
    <input type="password" name="confirmar_senha"><br><br>

    <input type="submit" name="enviar" value="Registrar">
  </form>
</body>
</html>
