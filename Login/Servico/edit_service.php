<?php
require_once '../Usuario/connect.php';
require_once '../Usuario/header.php';

// Permissão para editar serviço: apenas admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: services.php'); // Redireciona se não tiver permissão
    exit;
}

$service_id = 0;
$row = [];

if (isset($_GET['id'])) {
    $service_id = (int)$_GET['id'];
    $stmt = $con->prepare("SELECT service_id, name, description, price FROM services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo "<div class='alert alert-danger'>Serviço não encontrado.</div>";
        require_once '../Usuario/footer.php';
        exit;
    }
} else {
    header('Location: services.php');
    exit;
}

if (isset($_POST['update'])) {
    if (empty($_POST['name']) || !isset($_POST['price'])) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios (Nome, Preço).</div>";
    } else {
        $name = $con->real_escape_string($_POST['name']);
        $description = $con->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $service_id = intval($_POST['service_id']); // Garante que o ID é um inteiro

        $stmt = $con->prepare("UPDATE services SET name=?, description=?, price=? WHERE service_id=?");
        $stmt->bind_param("ssdi", $name, $description, $price, $service_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Serviço atualizado com sucesso</div>";
            // Atualiza $row para refletir os dados mais recentes no formulário
            $row['name'] = $name;
            $row['description'] = $description;
            $row['price'] = $price;
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar serviço: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Serviço</h3>
                <form action="" method="POST">
                    <input type="hidden" name="service_id" value="<?php echo $row['service_id']; ?>">

                    <div class="form-group">
                        <label for="name">Nome do Serviço</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Preço</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?php echo $row['price']; ?>" required>
                    </div>

                    <input type="submit" name="update" class="btn btn-primary" value="Atualizar Serviço">
                    <a href="services.php" class="btn btn-default">
                        <i class="glyphicon glyphicon-arrow-left"></i> Voltar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../Usuario/footer.php';
?>