<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

$resultados = [];

if (isset($_GET['texto']) && !empty($_GET['texto'])) {
    $string = mysqli_real_escape_string($conn, $_GET['texto']);
    $sql = "SELECT id, titulo, capa, ano FROM livros WHERE titulo LIKE '%$string%'";
    $resultados = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisar Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
<header class="container-fluid">
    <div class="container-lg">
        <div class="row align-items-center">
            <h1 class="col-4">Pesquisar</h1>
            <nav class="col text-end">
                <a href="index.php">Página inicial</a>
            </nav>
        </div>
    </div>
</header>

<div class="container-lg pesquisa">
    <div class="pesquisa-form">
        <h2>Pesquisar Livro</h2>
        <form action="" method="get" class="inserir mb-5">
            <div class="sombra-form d-flex">
                <input type="text" name="texto" placeholder="Procurar por título" required class="form-control">
                <button type="submit" class="btn btn-opcao ms-2">Pesquisar</button>
            </div>
        </form>
    </div>
   
    <div class="lista col">
        <h3>Resultados</h3>
        <?php
        if (!empty($resultados) && mysqli_num_rows($resultados) > 0) {
            while ($livro = mysqli_fetch_assoc($resultados)) {
                echo '<a href="livro.php?id=' . $livro['id'] . '" class="livro_link row align-items-end mb-3 text-decoration-none bg-light p-2 rounded">';
                echo '<img src="uploads/capa/' . htmlspecialchars($livro['capa']) . '" alt="' . htmlspecialchars($livro['titulo']) . '" class="col-3 img-thumbnail">';
                echo '<div class="livro col">';
                echo '<h4 class="mb-1">' . htmlspecialchars($livro['titulo']) . '</h4>';
                echo '<p class="mb-0">' . htmlspecialchars($livro['ano']) . '</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo '<p>Nenhum livro encontrado.</p>';
        }
        ?>
    </div>
    <div class="editar mt-4">
        <h2>Opções</h2>
        <a href="inserir_livro.php" class="btn btn-opcao me-2">Inserir Livro</a>
        <a href="inserir_autor.php" class="btn btn-opcao">Inserir Autor</a>
    </div>
</div>
<footer class="container-fluid text-center mt-4">
    <div class="container-lg">
        <p>&copy; 2025 Livros.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
