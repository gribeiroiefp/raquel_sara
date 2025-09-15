<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id = (int)($_POST['livro_id'] ?? 0);
    $autor_id = (int)($_POST['autor_id'] ?? 0);

    if ($livro_id > 0 && $autor_id > 0) {
        $check = mysqli_query($conn, "SELECT * FROM autor_livro WHERE livro_id = $livro_id AND autor_id = $autor_id");
        if (mysqli_num_rows($check) === 0) {
            mysqli_query($conn, "INSERT INTO autor_livro (livro_id, autor_id) VALUES ($livro_id, $autor_id)");
            $msg = "Autor associado ao livro com sucesso!";
        } else {
            $msg = "Essa associação já existe.";
        }
    } else {
        $msg = "Selecione um livro e um autor válidos.";
    }
}

if (isset($_GET['delete_livro_id']) && isset($_GET['delete_autor_id'])) {
    $delete_livro_id = (int)$_GET['delete_livro_id'];
    $delete_autor_id = (int)$_GET['delete_autor_id'];
    mysqli_query($conn, "DELETE FROM autor_livro WHERE livro_id = $delete_livro_id AND autor_id = $delete_autor_id");
    $msg = "Associação removida com sucesso!";
}

$livros_result = mysqli_query($conn, "SELECT id, titulo FROM livros ORDER BY titulo");
$autores_result = mysqli_query($conn, "SELECT id, nome FROM autores ORDER BY nome");

$associacoes_result = mysqli_query($conn, "
    SELECT la.livro_id, la.autor_id, l.titulo, a.nome 
    FROM autor_livro la
    JOIN livros l ON la.livro_id = l.id
    JOIN autores a ON la.autor_id = a.id
    ORDER BY l.titulo, a.nome
");

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associação de Autores e Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container my-4">
    <h2>Associar Autor a Livro</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form action="" method="post" class="mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <label for="livro_id" class="form-label">Livro</label>
                <select name="livro_id" id="livro_id" class="form-select" required>
                    <option value="">-- Escolha um livro --</option>
                    <?php while($livro = mysqli_fetch_assoc($livros_result)): ?>
                        <option value="<?= $livro['id'] ?>"><?= htmlspecialchars($livro['titulo'], ENT_QUOTES) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label for="autor_id" class="form-label">Autor</label>
                <select name="autor_id" id="autor_id" class="form-select" required>
                    <option value="">-- Escolha um autor --</option>
                    <?php while($autor = mysqli_fetch_assoc($autores_result)): ?>
                        <option value="<?= $autor['id'] ?>"><?= htmlspecialchars($autor['nome'], ENT_QUOTES) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Associar</button>
            </div>
        </div>
    </form>

    <h4>Associações existentes</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Livro</th>
                <th>Autor</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while($assoc = mysqli_fetch_assoc($associacoes_result)): ?>
                <tr>
                    <td><?= htmlspecialchars($assoc['titulo'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($assoc['nome'], ENT_QUOTES) ?></td>
                    <td>
                        <a href="?delete_livro_id=<?= $assoc['livro_id'] ?>&delete_autor_id=<?= $assoc['autor_id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Tem certeza que deseja remover esta associação?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
