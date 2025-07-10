<?php
require_once '../Usuario/connect.php'; // Caminho corrigido
require_once '../Usuario/header.php'; // Caminho corrigido

// Permissão para adicionar produto: admin ou user
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user')) {
    header('Location: products.php'); // products.php está na mesma pasta Produto
    exit;
}

if (isset($_POST['addnew'])) {
    if (empty($_POST['name']) || !isset($_POST['price']) || empty($_POST['stock'])) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios (Nome, Preço, Estoque).</div>";
    } else {
        $name = $con->real_escape_string($_POST['name']);
        $description = $con->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);

        $stmt = $con->prepare("INSERT INTO products(name, description, price, stock) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $description, $price, $stock);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Produto adicionado com sucesso</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao adicionar produto: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Adicionar Novo Produto</h3>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="name">Nome do Produto</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Preço</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">Estoque</label>
                        <input type="number" id="stock" name="stock" class="form-control" min="0" required>
                    </div>

                    <input type="submit" name="addnew" class="btn btn-success" value="Adicionar Produto">
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