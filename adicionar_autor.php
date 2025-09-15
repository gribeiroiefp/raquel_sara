<?php
include "../conexao.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $titulo = trim($_POST['titulo']);
    $ano = $_POST['ano'];
    $capa = $_FILES['capa']['name'];
    $autor = $_POST['autor'];

    if($titulo && $ano && $capa && $autor){
        move_uploaded_file($_FILES['capa']['tmp_name'], "../imagens/".$capa);
        $stmt=$conn->prepare("INSERT INTO livros (titulo, ano, capa) VALUES (?,?,?)");
        $stmt->bind_param("sis",$titulo,$ano,$capa);
        $stmt->execute();
        $livroId=$conn->insert_id;
        $stmt2=$conn->prepare("INSERT INTO autor_livro (autor_id, livro_id) VALUES (?,?)");
        $stmt2->bind_param("ii",$autor,$livroId);
        $stmt2->execute();
        echo "<p>Livro adicionado!</p>";
    } else {
        echo "<p>Preencha todos os campos.</p>";
    }
}
$autores=$conn->query("SELECT id,nome FROM autores");
include "../menu.php";
?>
<link rel="stylesheet" href="../css/style.css">
<h2>Adicionar Livro</h2>
<form method="post" enctype="multipart/form-data">
  <label>Título* <input type="text" name="titulo" required></label>
  <label>Ano* <input type="number" name="ano" min="1500" max="2099" required></label>
  <label>Capa* <input type="file" name="capa" required></label>
  <label>Autor*
    <select name="autor" required>
      <option value="">Selecione...</option>
      <?php while($a=$autores->fetch_assoc()): ?>
        <option value="<?= $a['id'] ?>"><?= $a['nome'] ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <button type="submit">Salvar</button>
</form>
<?php include "../footer.php"; ?>



