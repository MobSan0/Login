<?php
require_once '../Usuario/connect.php';
session_start(); // Inicia a sessão para verificar o papel do usuário

// Permissão para excluir serviço: apenas admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: services.php'); // Redireciona se não tiver permissão
    exit;
}

if (isset($_GET['id'])) {
    $service_id = (int)$_GET['id'];

    $stmt = $con->prepare("DELETE FROM services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success'>Serviço excluído com sucesso.</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Erro ao excluir serviço: " . $stmt->error . "</div>";
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "<div class='alert alert-warning'>ID do serviço não especificado.</div>";
}

header('Location: services.php'); // Redireciona de volta para a lista de serviços
exit;
?>