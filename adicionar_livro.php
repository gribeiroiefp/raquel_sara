
<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Livro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Adicionar Novo Livro</h1>
    <form action="processa_livro.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Título*</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ano</label>
            <input type="number" name="ano" class="form-control" min="0" max="<?= date('Y') ?>">
        </div>
        <div class="mb-3">
            <label>Capa</label>
            <input type="file" name="capa" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        <div class="mb-3">
            <label>Autor(es)*</label>
            <select name="autor_id[]" class="form-select" required multiple>
                <?php
                $result = $conn->query("SELECT * FROM autores ORDER BY nome");
                while($autor = $result->fetch_assoc()){
                    echo "<option value='{$autor['id']}'>{$autor['nome']}</option>";
                }
                ?>
            </select>
            <small class="text-muted">Segure Ctrl (Windows) ou Cmd (Mac) para selecionar múltiplos autores</small>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Livro</button>
    </form>
</div>
</body>
</html>

