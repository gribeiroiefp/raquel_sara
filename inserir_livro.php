<?php

$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (mysqli_connect_errno()) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

$msg = '';

// Buscar autores para o dropdown
$resultado_autores = mysqli_query($conn, "SELECT id, nome FROM autores ORDER BY nome ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $anos = (int)$_POST['anos'];
    $autor_id = (int)$_POST['autor']; 
    $diretorio_capas = 'uploads/capa/';

    if (!is_dir($diretorio_capas)) mkdir($diretorio_capas, 0755, true);

    $imagem = $_FILES['capa'];
    $fileName = basename($imagem['name']);
    $imagem_caminho = $diretorio_capas . $fileName;

    $check = getimagesize($imagem['tmp_name']);
    if ($check == false) {
        $msg = "O ficheiro enviado não é uma imagem válida";
    } else {
        if (move_uploaded_file($imagem['tmp_name'], $imagem_caminho)) {
            $sql = 'INSERT INTO livros (titulo, anos, capa) VALUES (?, ?, ?)';
            $query = mysqli_prepare($conn, $sql);
            if ($query) {
                mysqli_stmt_bind_param($query, 'sis', $titulo, $anos, $imagem_caminho);
                if (mysqli_stmt_execute($query)) {
                    $livro_id = mysqli_insert_id($conn);
                    // Inserir relação livro-autor
                    $sql_rel = 'INSERT INTO autor_livro (autor_id, livro_id) VALUES (?, ?)';
                    $query_rel = mysqli_prepare($conn, $sql_rel);
                    mysqli_stmt_bind_param($query_rel, 'ii', $autor_id, $livro_id);
                    mysqli_stmt_execute($query_rel);
                    mysqli_stmt_close($query_rel);
                    $msg = 'Livro inserido com sucesso!';
                } else $msg = 'Erro ao inserir livro: ' . mysqli_error($conn);
                mysqli_stmt_close($query);
            } else $msg = 'Erro na preparação da query: ' . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inserir Livro</title>
    <nav class="col text-end">
        <a href="index.php">Página inicial</a>
        <a href="pesquisa.php">Pesquisa</a>
    </nav>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="./css/styles.css">
</head>
<body>

<div class="container-lg inserir">
    <h2>Inserir Novo Livro</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <form action="inserir_livro.php" method="POST" enctype="multipart/form-data" class="mb-5 inserir">
        <input type="text" name="titulo" placeholder="Título" required class="form-control mb-3" />
        <input type="number" name="anos" placeholder="Ano" required min="1888" max="2099" step="1" class="form-control mb-3" />
        
        <!-- Dropdown de autores -->
        <label for="autor" class="form-label">Autor:</label>
        <select name="autor" id="autor" class="form-select mb-3" required>
            <option value="">Selecione um autor</option>
            <?php while($autor = mysqli_fetch_assoc($resultado_autores)): ?>
                <option value="<?= $autor['id'] ?>"><?= htmlspecialchars($autor['nome']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="capa" class="form-label">Capa do Livro (imagem):</label>
        <input type="file" name="capa" id="capa" accept="image/*" required class="form-control mb-3" />

        <button type="submit" class="btn btn-primary">Inserir Livro</button>
    </form>

    <div class="editar">
        <h2>Opções</h2>
        <a href="inserir_autor.php" class="btn btn-primary">Inserir Autor</a>
    </div>
</div>

<footer class="container-fluid text-center">
    <div class="container-lg">
        <p>&copy; 2025 Livros.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
