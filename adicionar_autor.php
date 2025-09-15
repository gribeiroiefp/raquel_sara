

<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Autor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Adicionar Novo Autor</h1>
    <form action="processa_autor.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nome*</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Data de Nascimento</label>
            <input type="date" name="data_nascimento" class="form-control">
        </div>
        <div class="mb-3">
            <label>Nacionalidade</label>
            <input type="text" name="nacionalidade" class="form-control">
        </div>
        <div class="mb-3">
            <label>Foto</label>
            <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Autor</button>
    </form>
</div>
</body>
</html>
