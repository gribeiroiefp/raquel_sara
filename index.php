
<?php
// Conexão com a base de dados
$servername = "localhost";
$username = "root"; // padrão no XAMPP
$password = ""; // sem senha por padrão
$database = "livros_db";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Buscar os 3 livros mais recentes
$sqlLivros = "SELECT * FROM livros ORDER BY ano DESC LIMIT 3";
$resultLivros = $conn->query($sqlLivros);

// Buscar os 3 autores com mais livros
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
    <title>Livros & Autores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Livros e Autores</h1>
        <nav class="col text-end">
            <a href="pesquisa.php">Pesquisa</a>
            <a href="autor.php?id=1">Autores</a>
            <a href="livro.php?id=1">Livros</a>
        </nav>
    <!-- Seção de Livros -->
    <h2 class="mb-3">📚 Livros Recentes</h2>
    <div class="row">
        <?php while($livro = $resultLivros->fetch_assoc()): ?>
        <div class="col-md-4 text-center mb-4">
            <img src="imagens/<?= htmlspecialchars($livro['capa']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($livro['titulo']) ?>">
            <h4><?= htmlspecialchars($livro['titulo']) ?></h4>
            <p><strong>Ano:</strong> <?= htmlspecialchars($livro['ano']) ?></p>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Seção de Autores -->
    <h2 class="mt-5 mb-3">👤 Autores com mais livros</h2>
    <div class="row">
        <?php while($autor = $resultAutores->fetch_assoc()): ?>
        <div class="col-md-4 text-center mb-4">
            <img src="imagens/<?= htmlspecialchars($autor['foto']) ?>" class="img-fluid rounded-circle" width="150" height="150" alt="<?= htmlspecialchars($autor['nome']) ?>">
            <h4><?= htmlspecialchars($autor['nome']) ?></h4>
            <p><strong>Total de livros:</strong> <?= $autor['total_livros'] ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
