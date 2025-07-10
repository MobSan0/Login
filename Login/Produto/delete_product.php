<?php
require_once '../Usuario/connect.php'; // Caminho corrigido
session_start(); // Inicia a sessão para verificar o papel do usuário

// Permissão para excluir produto: apenas admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: products.php'); // products.php está na mesma pasta Produto
    exit;
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    $stmt = $con->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success'>Produto excluído com sucesso.</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Erro ao excluir produto: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "<div class='alert alert-warning'>ID do produto não especificado.</div>";
}

header('Location: products.php'); // products.php está na mesma pasta Produto
exit;
?>