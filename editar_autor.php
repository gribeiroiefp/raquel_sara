<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("ID do autor não fornecido.");
}

$sql = "SELECT * FROM autores WHERE id = $id";
$resultado = mysqli_query($conn, $sql);
if (!$resultado || mysqli_num_rows($resultado) === 0) {
    die("Autor não encontrado.");
}
$autor = mysqli_fetch_assoc($resultado);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $nascimento = $_POST['nascimento'];
    $nacionalidade = $_POST['nacionalidade'];
    $foto_caminho = $autor['foto']; 

    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $diretorio = "uploads/pictures/";
        if (!is_dir(__DIR__ . "/" . $diretorio)) {
            mkdir(__DIR__ . "/" . $diretorio, 0755, true);
        }

        $foto_nome = time() . "_" . basename($_FILES['foto']['name']);
        if (getimagesize($_FILES['foto']['tmp_name'])) {
            move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/" . $diretorio . $foto_nome);
            $foto_caminho = $foto_nome; 
        } else {
            $msg = "Erro: o ficheiro enviado não é uma imagem.";
        }
    }

    if (!$msg) {
        $sql = "UPDATE autores SET 
                    nome='" . mysqli_real_escape_string($conn, $nome) . "', 
                    nascimento='" . mysqli_real_escape_string($conn, $nascimento) . "', 
                    nacionalidade='" . mysqli_real_escape_string($conn, $nacionalidade) . "', 
                    foto='" . mysqli_real_escape_string($conn, $foto_caminho) . "' 
                WHERE id = $id";
        mysqli_query($conn, $sql);

        $resultado = mysqli_query($conn, "SELECT * FROM autores WHERE id = $id");
        $autor = mysqli_fetch_assoc($resultado);

        $msg = 'Autor atualizado com sucesso!';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Autor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="container-fluid">
    <div class="container-lg">
        <div class="row align-items-center">
            <h1 class="col-4">Editar Autor</h1>
            <nav class="col text-end">
                <a href="index.php">Página inicial</a>
                <a href="pesquisa.php">Pesquisa</a>
            </nav>
        </div>
    </div>
</header>
<div class="container-lg inserir">
    <h2 class="mb-4"><?= htmlspecialchars($autor['nome']) ?></h2>
    <?php 
        $foto = (!empty($autor['foto']) && file_exists(__DIR__ . "/uploads/pictures/" . $autor['foto'])) 
                ? "uploads/pictures/" . $autor['foto'] 
                : "uploads/pictures/sem-foto.png"; 
    ?>
    <div class="mb-4">
        <img src="<?= htmlspecialchars($foto) ?>" 
             alt="Foto de <?= htmlspecialchars($autor['nome']) ?>" 
             class="img-thumbnail" style="max-width:200px;">
    </div>
    <form action="" method="post" enctype="multipart/form-data" class="mb-5 inserir"
          onsubmit="return confirm('Tem certeza que deseja salvar as alterações deste autor?')">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome"
                   value="<?= htmlspecialchars($autor['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nascimento" class="form-label">Nascimento</label>
            <input type="text" name="nascimento" id="nascimento" class="form-control" placeholder="Nascimento"
                   value="<?= htmlspecialchars($autor['nascimento']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nacionalidade" class="form-label">Nacionalidade</label>
            <input type="text" name="nacionalidade" id="nacionalidade" class="form-control" placeholder="Nacionalidade"
                   value="<?= htmlspecialchars($autor['nacionalidade']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto do autor (opcional)</label>
            <input type="file" name="foto" id="foto" class="form-control">
            <small class="text-muted">Se não selecionar nenhuma foto a atual será mantida.</small>
        </div>
        <button type="submit" class="btn btn-opcao">Salvar</button>
    </form>

    <?php if ($msg): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
</div>
<footer class="container-fluid text-center">
    <div class="container-lg">
        <p>&copy; 2025 Livros.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

