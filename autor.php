<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

// Receber o ID e garantir que é inteiro
$id = (int) $_GET['id'];

// Buscar dados do autor
$sql = "SELECT * FROM autores WHERE id = $id";
$resultado = mysqli_query($conn, $sql);

$autor = mysqli_fetch_assoc($resultado);

if (!$autor) {
    die("Autor não encontrado.");
}

$sql_livros = "SELECT livros.id, livros.titulo, livros.ano, livros.capa
               FROM livros
               JOIN autor_livro ON livros.id = autor_livro.livro_id
               WHERE autor_livro.autor_id = $id
               ORDER BY livros.ano DESC";

$resultado_livros = mysqli_query($conn, $sql_livros);
if (!$resultado_livros) {
    die("Erro na query livros: " . mysqli_error($conn));
}

// Deletar autor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "DELETE FROM autores WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="./../css/styles.css">
</head>
<body>
<header class="container-fluid">
    <div class="container-lg">
        <div class="row align-items-center">
            <h1 class="col-4">Autor</h1>
            <nav class="col text-end">
                <a href="index.php">Página inicial</a>
                <a href="pesquisa.php">Pesquisa</a>
            </nav>
        </div>
    </div>
</header>

<div class="autor container-lg">
    <div class="row align-items-center">
        <h2><?php echo htmlspecialchars($autor['nome']); ?></h2>
        <img src="uploads/pictures/<?php echo htmlspecialchars($autor['foto']); ?>" 
             alt="<?php echo htmlspecialchars($autor['nome']); ?>" class="col-3">
        <div class="informacao col-8">
            <p><span class="rotulo ano">Nascimento:</span> <?php echo htmlspecialchars($autor['nascimento']); ?></p>
            <p><span class="rotulo nacionalidade">Nacionalidade:</span> <?php echo htmlspecialchars($autor['nacionalidade']); ?></p>
        </div>
        <div class="col-1 opcoes align-self-start">
            <a href="editar_autor.php?id=<?php echo htmlspecialchars($autor['id']); ?>" class="btn btn-primary btn-editar">Editar</a>
            <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este autor?')">
                <button type="submit" class="btn btn-danger btn-remover">Remover</button>
            </form>
        </div>
    </div>
</div>


    <div class="lista col">
    <h3>Livros</h3>
    <?php while($livro = mysqli_fetch_assoc($resultado_livros)): ?>
    <a href="livro.php" class="row align-items-end">
        <img src="uploads/capa/<?php echo htmlspecialchars($livro['capa']); ?>" 
             alt="<?php echo htmlspecialchars($livro['titulo']); ?>" class="col-2">
        <div class="livro col-10">
            <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
            <p>Ano: <?php echo htmlspecialchars($livro['ano']); ?></p>
        </div>
    </a>
    <?php endwhile; ?>
</div>

    <div class="editar">
        <h2>Opções</h2>
        <a href="inserir_livro.php" class="btn btn-primary">Inserir Livro</a>
        <a href="inserir_autor.php" class="btn btn-primary">Inserir Autor</a>
    </div>
</div>

<footer class="container-fluid text-center">
    <div class="container-lg">
        <p>&copy; 2025 Livros.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
<.php>
