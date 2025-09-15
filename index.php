<?php
$conn = new mysqli("localhost", "root", "", "livros_db");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sqlLivros = "SELECT * FROM livros ORDER BY ano DESC LIMIT 3";
$resultLivros = $conn->query($sqlLivros);

$sqlAutores = "SELECT autores.*, COUNT(autor_livro.livro_id) AS total_livros
               FROM autores
               JOIN autor_livro ON autores.id = autor_livro.autor_id
               GROUP BY autores.id
               ORDER BY total_livros DESC
               LIMIT 3";
$resultAutores = $conn->query($sqlAutores);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Livros & Autores</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header class="container-fluid">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <h1>Livros & Autores</h1>
        <nav>
            <a href="autor.php?id=1">Autores</a>
            <a href="livro.php?id=1">Livros</a>
            <a href="pesquisa.php">Pesquisa</a>
        </nav>
    </div>
</header>

<div class="container-lg mt-4">
    <h2>📚 Livros Recentes</h2>
    <div class="row">
        <?php while($livro = $resultLivros->fetch_assoc()): ?>
        <div class="col-md-4 text-center mb-4">
            <img src="uploads/capa/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($livro['titulo']) ?>">
            <h4><?= htmlspecialchars($livro['titulo']) ?></h4>
            <p><strong>Ano:</strong> <?= htmlspecialchars($livro['ano']) ?></p>
        </div>
        <?php endwhile; ?>
    </div>

    <h2 class="mt-5">👤 Autores com mais livros</h2>
    <div class="row">
        <?php while($autor = $resultAutores->fetch_assoc()): ?>
        <div class="col-md-4 text-center mb-4">
            <img src="uploads/pictures/<?= htmlspecialchars($autor['foto']) ?>" class="img-fluid rounded-circle" width="150" height="150" alt="<?= htmlspecialchars($autor['nome']) ?>">
            <h4><?= htmlspecialchars($autor['nome']) ?></h4>
            <p><strong>Total de livros:</strong> <?= $autor['total_livros'] ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<footer>
    <p>&copy; 2025 Livros.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


