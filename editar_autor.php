<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

// Pega o ID do autor
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("ID do autor não fornecido.");
}

// Pega os dados do autor
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
    $foto_caminho = $autor['foto']; // mantém a foto atual por padrão

    // Upload de nova foto (só se o usuário enviar)
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $diretorio = "uploads/pictures/";
        if (!is_dir(__DIR__ . "/" . $diretorio)) {
            mkdir(__DIR__ . "/" . $diretorio, 0755, true);
        }

        $foto_nome = time() . "_" . basename($_FILES['foto']['name']);
        if (getimagesize($_FILES['foto']['tmp_name'])) {
            move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/" . $diretorio . $foto_nome);
            $foto_caminho = $foto_nome; // só altera se o upload for válido
        } else {
            $msg = "Erro: o ficheiro enviado não é uma imagem.";
        }
    }

    if (!$msg) {
        // Atualiza apenas o autor selecionado
        $sql = "UPDATE autores SET 
                    nome='" . mysqli_real_escape_string($conn, $nome) . "', 
                    nascimento='" . mysqli_real_escape_string($conn, $nascimento) . "', 
                    nacionalidade='" . mysqli_real_escape_string($conn, $nacionalidade) . "', 
                    foto='" . mysqli_real_escape_string($conn, $foto_caminho) . "' 
                WHERE id = $id";
        mysqli_query($conn, $sql);

        // Atualiza $autor com os dados mais recentes
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
</head>
<body>
<div class="container my-4">
    <h3><?= htmlspecialchars($autor['nome']) ?></h3>

    <!-- Exibe a foto do autor ou imagem padrão -->
    <?php 
        $foto = (!empty($autor['foto']) && file_exists(__DIR__ . "/uploads/pictures/" . $autor['foto'])) 
                ? "uploads/pictures/" . $autor['foto'] 
                : "uploads/pictures/sem-foto.png"; 
    ?>
    <div class="mb-3">
        <img src="/Livros/<?= htmlspecialchars($foto) ?>" 
             alt="Foto de <?= htmlspecialchars($autor['nome']) ?>" 
             class="img-thumbnail" style="max-width:200px;">
    </div>

    <!-- Formulário de edição com confirmação -->
    <form action="" method="post" enctype="multipart/form-data"
          onsubmit="return confirm('Tem certeza que deseja salvar as alterações deste autor?')">
        <div class="mb-3">
            <input type="text" name="nome" class="form-control" placeholder="Nome"
                   value="<?= htmlspecialchars($autor['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="nascimento" class="form-control" placeholder="Nascimento"
                   value="<?= htmlspecialchars($autor['nascimento']) ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="nacionalidade" class="form-control" placeholder="Nacionalidade"
                   value="<?= htmlspecialchars($autor['nacionalidade']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto do autor (opcional)</label>
            <input type="file" name="foto" id="foto" class="form-control">
            <small class="text-muted">Se não selecionar nenhuma foto a atual será mantida.</small>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>

    <?php if ($msg): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
</div>
</body>
</html>
