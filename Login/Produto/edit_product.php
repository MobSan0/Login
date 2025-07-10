<?php
require_once '../Usuario/connect.php'; // Caminho corrigido
require_once '../Usuario/header.php'; // Caminho corrigido

// Permissão para editar produto: apenas admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: products.php'); // products.php está na mesma pasta Produto
    exit;
}

$product_id = 0;
$row = [];

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $stmt = $con->prepare("SELECT product_id, name, description, price, stock FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo "<div class='alert alert-danger'>Produto não encontrado.</div>";
        require_once '../Usuario/footer.php'; // Caminho corrigido
        exit;
    }
} else {
    header('Location: products.php'); // products.php está na mesma pasta Produto
    exit;
}

if (isset($_POST['update'])) {
    if (empty($_POST['name']) || !isset($_POST['price']) || empty($_POST['stock'])) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios (Nome, Preço, Estoque).</div>";
    } else {
        $name = $con->real_escape_string($_POST['name']);
        $description = $con->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $product_id = intval($_POST['product_id']); // Garante que o ID é um inteiro

        $stmt = $con->prepare("UPDATE products SET name=?, description=?, price=?, stock=? WHERE product_id=?");
        $stmt->bind_param("ssdii", $name, $description, $price, $stock, $product_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Produto atualizado com sucesso</div>";
            // Atualiza $row para refletir os dados mais recentes no formulário
            $row['name'] = $name;
            $row['description'] = $description;
            $row['price'] = $price;
            $row['stock'] = $stock;
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar produto: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Produto</h3>
                <form action="" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">

                    <div class="form-group">
                        <label for="name">Nome do Produto</label>
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

                    <div class="form-group">
                        <label for="stock">Estoque</label>
                        <input type="number" id="stock" name="stock" class="form-control" min="0" value="<?php echo $row['stock']; ?>" required>
                    </div>

                    <input type="submit" name="update" class="btn btn-primary" value="Atualizar Produto">
                    <a href="products.php" class="btn btn-default"> <i class="glyphicon glyphicon-arrow-left"></i> Voltar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../Usuario/footer.php'; // Caminho corrigido
?>