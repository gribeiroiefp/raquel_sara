<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "livros_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>

<?php
include 'conexao.php';

$livro_id = $_POST['livro_id'];
$autor_id = $_POST['autor_id'];

// Verificar se já existe
$check = $conn->prepare("SELECT * FROM autor_livro WHERE livro_id=? AND autor_id=?");
$check->bind_param("ii", $livro_id, $autor_id);
$check->execute();
$result = $check->get_result();

if($result->num_rows > 0){
    die("Esta ligação já existe.");
}
$check->close();

// Inserir
$stmt = $conn->prepare("INSERT INTO autor_livro (autor_id, livro_id) VALUES (?, ?)");
$stmt->bind_param("ii", $autor_id, $livro_id);
$stmt->execute();
$stmt->close();

header("Location: index.php");
exit;
?>
