
<?php
include 'conexao.php';

$titulo = trim($_POST['titulo']);
$ano = $_POST['ano'] ?? null;
$autor_ids = $_POST['autor_id'] ?? [];

// Validação
if (empty($titulo) || empty($autor_ids)) {
    die("O título e pelo menos um autor são obrigatórios.");
}

// Upload da capa
$capa = null;
if(isset($_FILES['capa']) && $_FILES['capa']['error'] == 0){
    $ext = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
    if(!in_array(strtolower($ext), ['jpg','jpeg','png'])){
        die("Formato de imagem inválido.");
    }
    $capa = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['capa']['tmp_name'], 'imagens/' . $capa);
}

// Inserir livro
$stmt = $conn->prepare("INSERT INTO livros (titulo, ano, capa) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $titulo, $ano, $capa);
$stmt->execute();
$livro_id = $stmt->insert_id;
$stmt->close();

// Inserir ligações na tabela autor_livro
$stmt2 = $conn->prepare("INSERT INTO autor_livro (autor_id, livro_id) VALUES (?, ?)");
foreach($autor_ids as $autor_id){
    $stmt2->bind_param("ii", $autor_id, $livro_id);
    $stmt2->execute();
}
$stmt2->close();

header("Location: index.php");
exit;
?>