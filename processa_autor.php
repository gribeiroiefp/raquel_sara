<?php
include 'conexao.php';

$nome = trim($_POST['nome']);
$data_nascimento = $_POST['data_nascimento'] ?? null;
$nacionalidade = $_POST['nacionalidade'] ?? null;

// Validação
if (empty($nome)) {
    die("O nome é obrigatório.");
}

// Upload de foto
$foto = null;
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    if(!in_array(strtolower($ext), ['jpg','jpeg','png'])){
        die("Formato de imagem inválido. Apenas jpg, jpeg e png são permitidos.");
    }
    $foto = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], 'imagens/' . $foto);
}

// Inserir no banco
$stmt = $conn->prepare("INSERT INTO autores (nome, data_nascimento, nacionalidade, foto) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $data_nascimento, $nacionalidade, $foto);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit;
?>