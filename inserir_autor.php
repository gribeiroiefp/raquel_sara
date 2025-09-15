<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) die('Erro na ligação: ' . mysqli_connect_error());

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $nascimento = $_POST['nascimento'];
    $nacionalidade = $_POST['nacionalidade'];
    $diretorio_fotos = 'uploads/pictures/';

    if (!is_dir($diretorio_fotos)) mkdir($diretorio_fotos, 0755, true);

    $imagem = $_FILES['foto'];
    $fileName = basename($imagem['name']);
    $imagem_caminho = $diretorio_fotos . $fileName;

    if (getimagesize($imagem['tmp_name'])) {
        if (move_uploaded_file($imagem['tmp_name'], $imagem_caminho)) {
            $sql = 'INSERT INTO autores (nome, nascimento, nacionalidade, foto) VALUES (?, ?, ?, ?)';
            $query = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($query, 'ssss', $nome, $nascimento, $nacionalidade, $imagem_caminho);
            if (mysqli_stmt_execute($query)) {
                $msg = 'Autor inserido com sucesso!';
            } else {
                $msg = 'Erro ao inserir autor: ' . mysqli_error($conn);
            }
            mysqli_stmt_close($query);
        } else $msg = 'Erro ao enviar a imagem.';
    } else $msg = 'Arquivo enviado não é uma imagem válida.';
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inserir Autor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header class="container-fluid">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <h1>Inserir Autor</h1>
        <nav>
            <a href="index.php">Página inicial</a>
            <a href="pesquisa.php">Pesquisa</a>
        </nav>
    </div>
</header>

<div class="container-lg inserir mt-4">
    <h2>Inserir Novo Autor</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <form action="inserir_autor.php" method="POST" enctype="multipart/form-data" class="mb-5">
        <input type="text" name="nome" placeholder="Nome" required class="form-control mb-3">
        <input type="date" name="nascimento" required class="form-control mb-3">
        <input type="text" name="nacionalidade" placeholder="Nacionalidade" required class="form-control mb-3">
        <label for="foto" class="form-label">Foto do autor (imagem):</label>
        <input type="file" name="foto" id="foto" accept="image/*" required class="form-control mb-3">
        <button type="submit" class="btn btn-inserir-autor">Inserir Autor</button>
    </form>

    <div class="editar mb-4">
        <h2>Opções</h2>
        <a href="inserir_livro.php" class="btn btn-opcao mb-2">Inserir Livro</a>
    </div>
</div>

<footer class="container-fluid text-center mt-5">
    <p>&copy; 2025 Livros.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
