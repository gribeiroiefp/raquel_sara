<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) die("Erro na conexão: " . mysqli_connect_error());

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) die("Autor não encontrado.");

// Busca autor
$sql = "SELECT * FROM autores WHERE id = $id";
$resultado = mysqli_query($conn, $sql);
$autor = mysqli_fetch_assoc($resultado);
if (!$autor) die("Autor não encontrado.");

// Busca livro_autor
$sql_livros = "SELECT livros.id, livros.titulo, livros.ano, livros.capa
               FROM livros
               JOIN autor_livro ON livros.id = autor_livro.livro_id
               WHERE autor_livro.autor_id = $id
               ORDER BY livros.ano DESC";
$resultado_livros = mysqli_query($conn, $sql_livros);

// Deleta autor
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sql = "DELETE FROM autores WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($autor['nome']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header class="container-fluid">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <h1>Autor</h1>
        <nav>
            <a href="index.php">Página inicial</a>
            <a href="pesquisa.php">Pesquisa</a>
        </nav>
    </div>
</header>

<div class="container-lg mt-4 autor">
    <div class="row align-items-start mb-4">
        <div class="col-md-3 text-center">
            <img src="uploads/pictures/<?= htmlspecialchars($autor['foto']) ?>" class="img-fluid rounded-circle" alt="<?= htmlspecialchars($autor['nome']) ?>">
        </div>
        <div class="col-md-8">
            <h2><?= htmlspecialchars($autor['nome']) ?></h2>
            <p><strong>Nascimento:</strong> <?= htmlspecialchars($autor['nascimento']) ?></p>
            <p><strong>Nacionalidade:</strong> <?= htmlspecialchars($autor['nacionalidade']) ?></p>
        </div>
        <div class="col-md-1 d-flex flex-column align-items-start">
            <a href="editar_autor.php?id=<?= $autor['id'] ?>" class="btn btn-editar mb-2">Editar</a>
            <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este autor?')">
                <button type="submit" class="btn btn-remover">Remover</button>
            </form>
        </div>
    </div>

    <div class="lista col mb-4">
        <h3>Livros</h3>
        <?php if ($resultado_livros && mysqli_num_rows($resultado_livros) > 0): ?>
            <?php while($livro = mysqli_fetch_assoc($resultado_livros)): ?>
            <a href="livro.php?id=<?= $livro['id'] ?>" class="row align-items-center mb-2 text-decoration-none">
                <div class="col-2 text-center">
                    <img src="uploads/capa/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($livro['titulo']) ?>">
                </div>
                <div class="col-10">
                    <p class="nome mb-0"><?= htmlspecialchars($livro['titulo']) ?></p>
                    <p class="ano mb-0"><?= htmlspecialchars($livro['ano']) ?></p>
                </div>
            </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum livro cadastrado para este autor.</p>
        <?php endif; ?>
    </div>

    <div class="editar mb-4">
        <h2>Opções</h2>
        <a href="inserir_livro.php" class="btn btn-opcao mb-2">Inserir Livro</a>
        <a href="inserir_autor.php" class="btn btn-opcao mb-2">Inserir Autor</a>
    </div>
</div>

<footer class="container-fluid text-center mt-5">
    <p>&copy; 2025 Livros.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
