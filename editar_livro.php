<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("ID do livro não fornecido.");
}

$sql = "SELECT * FROM livros WHERE id = $id";
$resultado = mysqli_query($conn, $sql);
if (!$resultado || mysqli_num_rows($resultado) === 0) {
    die("Livro não encontrado.");
}
$livro = mysqli_fetch_assoc($resultado);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $ano = $_POST['ano'] ?? '';
    $capa_caminho = $livro['capa']; 

    
    if (!empty($_FILES['capa']['name']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
        $diretorio = "uploads/capa/";
        if (!is_dir(__DIR__ . "/" . $diretorio)) {
            mkdir(__DIR__ . "/" . $diretorio, 0755, true);
        }

        $capa_nome = time() . "_" . basename($_FILES['capa']['name']);
        if (getimagesize($_FILES['capa']['tmp_name'])) {
            move_uploaded_file($_FILES['capa']['tmp_name'], __DIR__ . "/" . $diretorio . $capa_nome);
            $capa_caminho = $capa_nome; // só altera se o upload for válido
        } else {
            $msg = "Erro: o ficheiro enviado não é uma imagem.";
        }
    }

    if (!$msg) {
        $sql = "UPDATE livros SET 
                    titulo='" . mysqli_real_escape_string($conn, $titulo) . "', 
                    ano='" . mysqli_real_escape_string($conn, $ano) . "', 
                    capa='" . mysqli_real_escape_string($conn, $capa_caminho) . "' 
                WHERE id = $id";
        mysqli_query($conn, $sql);

        $resultado = mysqli_query($conn, "SELECT * FROM livros WHERE id = $id");
        $livro = mysqli_fetch_assoc($resultado);

        $msg = 'Livro atualizado com sucesso!';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Livro</title>
    <nav class="col text-end">
        <a href="index.php">Página inicial</a>
        <a href="pesquisa.php">Pesquisa</a>
    </nav>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h3><?= htmlspecialchars($livro['titulo']) ?></h3>

    <?php 
        $capa = (!empty($livro['capa']) && file_exists(__DIR__ . "/uploads/capa/" . $livro['capa'])) 
                ? "uploads/capa/" . $livro['capa'] 
                : "uploads/capa/sem-capa.png"; 
    ?>
    <div class="mb-3">
        <img src="/Livros/<?= htmlspecialchars($capa) ?>" 
             alt="Capa do livro <?= htmlspecialchars($livro['titulo']) ?>" 
             class="img-thumbnail" style="max-width:200px;">
    </div>

    <!-- Formulário de edição -->
    <form action="" method="post" enctype="multipart/form-data"
      onsubmit="return confirm('Tem certeza que deseja salvar as alterações neste livro?')">
    <div class="mb-3">
        <input type="text" name="titulo" class="form-control" placeholder="Título"
               value="<?= htmlspecialchars($livro['titulo']) ?>" required>
    </div>
    <div class="mb-3">
        <input type="number" name="ano" class="form-control" placeholder="Ano"
               value="<?= htmlspecialchars($livro['ano']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="capa" class="form-label">Capa do livro (opcional)</label>
        <input type="file" name="capa" id="capa" class="form-control">
        <small class="text-muted">Se não selecionar uma capa a atual será mantida.</small>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>

    <?php if ($msg): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
</div>
</body>
</html>
