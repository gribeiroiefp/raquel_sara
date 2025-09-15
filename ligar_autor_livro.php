<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Ligar Autor a Livro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h1>Ligar Autor a Livro</h1>
<form action="processa_ligacao.php" method="POST">
    <div class="mb-3">
        <label>Livro</label>
        <select name="livro_id" class="form-select" required>
            <?php
            $result = $conn->query("SELECT * FROM livros ORDER BY titulo");
            while($livro = $result->fetch_assoc()){
                echo "<option value='{$livro['id']}'>{$livro['titulo']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Autor</label>
        <select name="autor_id" class="form-select" required>
            <?php
            $result = $conn->query("SELECT * FROM autores ORDER BY nome");
            while($autor = $result->fetch_assoc()){
                echo "<option value='{$autor['id']}'>{$autor['nome']}</option>";
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Ligar</button>
</form>
</div>
</body>
</html>
