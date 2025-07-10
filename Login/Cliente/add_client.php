<?php
require_once '../Usuario/connect.php';
require_once '../Usuario/header.php';
require_once '../Usuario/adress_component.php'; // Inclui o componente de endereço

// Permissão para adicionar cliente: admin ou user
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user')) {
    header('Location: clients.php'); // Redireciona se não tiver permissão
    exit;
}

if (isset($_POST['addnew'])) {
    if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['phone']) ||
       empty($_POST['rua']) || empty($_POST['numero']) || empty($_POST['bairro']) ||
       empty($_POST['cidade']) || empty($_POST['estado']) || empty($_POST['cep'])) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios.</div>";
    } else {
        $firstname = $con->real_escape_string($_POST['firstname']);
        $lastname = $con->real_escape_string($_POST['lastname']);
        $email = $con->real_escape_string($_POST['email']);
        $phone = $con->real_escape_string($_POST['phone']);
        $rua = $con->real_escape_string($_POST['rua']);
        $numero = $con->real_escape_string($_POST['numero']);
        $bairro = $con->real_escape_string($_POST['bairro']);
        $cidade = $con->real_escape_string($_POST['cidade']);
        $estado = $con->real_escape_string($_POST['estado']);
        $cep = $con->real_escape_string($_POST['cep']);

        // Verificar se o email já existe
        $stmt = $con->prepare("SELECT client_id FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<div class='alert alert-danger'>Este email já está cadastrado para outro cliente.</div>";
        } else {
            $stmt = $con->prepare("INSERT INTO clients(firstname, lastname, email, phone, rua, numero, bairro, cidade, estado, cep) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $firstname, $lastname, $email, $phone, $rua, $numero, $bairro, $cidade, $estado, $cep);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Cliente adicionado com sucesso</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao adicionar cliente: " . $stmt->error . "</div>";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Adicionar Novo Cliente</h3>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="firstname">Nome</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Sobrenome</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>
                    
                    <?php renderAddressFields(); // Renderiza os campos de endereço com busca de CEP ?>

                    <input type="submit" name="addnew" class="btn btn-success" value="Adicionar Cliente">
                    <a href="clients.php" class="btn btn-default">
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