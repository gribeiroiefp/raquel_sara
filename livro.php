<?php

$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Livro não encontrado.");
}

$sql = "SELECT * FROM livros WHERE id = $id";
$resultado = mysqli_query($conn, $sql);
$livro = mysqli_fetch_assoc($resultado);
if (!$livro) {
    die("Livro não encontrado.");
}

$sql_autores = "SELECT autores.id, autores.nome, autores.foto, autores.nacionalidade, autor_livro.autor_id
                FROM autores 
                JOIN autor_livro ON autores.id = autor_livro.autor_id
                WHERE autor_livro.livro_id = $id";

$resultado_autores = mysqli_query($conn, $sql_autores);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $sql = "DELETE FROM livros WHERE id = $id";
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
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
<header class="container-fluid">
    <div class="container-lg">
        <div class="row align-items-center">
            <h1 class="col-4">Livros</h1>
            <nav class="col text-end">
                <a href="index.php">Página inicial</a>
                <a href="pesquisa.php">Pesquisa</a>
            </nav>
        </div>
    </div>
</header>

<div class="container-lg livro">
    <div class="row align-items-start info">
        <img src="uploads/capa/<?php echo htmlspecialchars($livro['capa']); ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>" class="col-3">
        <div class="col-8">
            <h2><?php echo htmlspecialchars($livro['titulo']); ?></h2>
            <p><span class="rotulo ano">Ano:</span> <?php echo htmlspecialchars($livro['ano']); ?></p>
        </div>
        <div class="col-1 opcoes">
            <a href="editar_livro.php?id=<?php echo $livro['id']; ?>" class="btn btn-primary btn-editar">Editar</a>
            <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este livro?')">
                <button class="btn btn-danger btn-remover" type="submit">Remover</button>
            </form>
        </div>
    </div>

    <div class="informacao">
    <div class="lista col">
    <h3>Autores</h3>
    <?php
    if ($resultado_autores && mysqli_num_rows($resultado_autores) > 0) {
        while ($autor = mysqli_fetch_assoc($resultado_autores)) {
            $autor_id = $autor['id']; 
            $nome = htmlspecialchars($autor['nome']);
            $foto = "uploads/pictures/" . htmlspecialchars($autor['foto']); 
            $nacionalidade = htmlspecialchars($autor['nacionalidade']); 

            echo <<<HTML
            <a href="autor.php?id=$autor_id" class="row align-items-end">
                <img src="$foto" alt="$nome" class="col-2">
                <div class="col-10">
                    <p class="nome">$nome</p>
                    <p class="nacionalidade">$nacionalidade</p>
                </div>
            </a>
            HTML;
        }
    }
    ?>
    </div>
    <div class="editar">
        <h2>Opções</h2>
        <a href="inserir_livro.php" class="btn btn-primary">Inserir livro</a>
        <a href="inserir_autor.php" class="btn btn-primary">Inserir autor</a>
    </div>
</div>

<footer class="container-fluid text-center">
    <div class="container-lg">
        <p>&copy; 2025 Livros.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
