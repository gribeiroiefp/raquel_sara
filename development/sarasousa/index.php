<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'livros_db');
if (!$conn) {
    die('Erro na ligação: ' . mysqli_connect_error());
}

// Query dos 3 livros mais recentes
$sqlLivros = 'SELECT id, titulo, anos, capa FROM livros ORDER BY anos DESC LIMIT 3';
$resultadoLivros = mysqli_query($conn, $sqlLivros);

// Query dos 3 autores com mais livros (usando tabela de relação)
$sqlAutores = "
    SELECT a.nome, COUNT(al.livro_id) AS total_livros
    FROM autores a
    JOIN autor_livro al ON a.id = al.autor_id
    GROUP BY a.id
    ORDER BY total_livros DESC
    LIMIT 3";
$resultadoAutores = mysqli_query($conn, $sqlAutores);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="./../css/styles.css">
</head>
<body>
    <header class="container-fluid">
        <div class="container-lg">
            <div class="row align-items-center">
                <h1 class="col-4">Livros</h1>
                <nav class="col text-end">
                    <a href="index.html">Página inicial</a>
                    <a href="pesquisa.html">Pesquisa</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container-lg home">
        <h2>Livros mais recentes</h2>
        <div class="row">
            <?php
            if ($resultadoLivros && mysqli_num_rows($resultadoLivros) > 0) {
                while ($row = mysqli_fetch_assoc($resultadoLivros)) {
                    $id = $row['id'];
                    $titulo = htmlspecialchars($row['titulo']);
                    $anos = htmlspecialchars($row['anos']);
                    $capa = htmlspecialchars($row['capa']);

                    echo <<<HTML
<div class="livro-recente-cont col">
    <div class="livro-recente container" style="background-image: url('$capa');">
        <a href="./livro.php?id=$id">
            <div class="row align-items-end">
                <div class="col">
                    <h3>$titulo</h3>
                    <p>$anos</p>
                </div>
            </div>
        </a>
    </div>
</div>
HTML;
                }
            }
            ?>
        </div>

        <h2 class="mt-5">Autores com mais livros</h2>
        <ul class="list-group">
            <?php
            if ($resultadoAutores && mysqli_num_rows($resultadoAutores) > 0) {
                while ($row = mysqli_fetch_assoc($resultadoAutores)) {
                    $nomeAutor = htmlspecialchars($row['nome']);
                    $total = $row['total_livros'];

                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            $nomeAutor
                            <span class='badge bg-primary rounded-pill'>$total</span>
                          </li>";
                }
            }
            ?>
        </ul>
    </div>
</body>
</html>
