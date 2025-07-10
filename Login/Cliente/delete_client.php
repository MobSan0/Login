<?php
require_once '../Usuario/connect.php';
session_start(); // Inicia a sessão para verificar o papel do usuário

// Permissão para excluir cliente: apenas admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: clients.php'); // Redireciona se não tiver permissão
    exit;
}

if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];

    $stmt = $con->prepare("DELETE FROM clients WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success'>Cliente excluído com sucesso.</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Erro ao excluir cliente: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "<div class='alert alert-warning'>ID do cliente não especificado.</div>";
}

header('Location: clients.php'); // Redireciona de volta para a lista de clientes
exit;
?>