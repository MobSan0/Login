<?php
require_once '../Usuario/connect.php';
require_once '../Usuario/header.php';
require_once '../Usuario/adress_component.php'; // Inclui o componente de endereço

// Permissão para editar cliente: apenas admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: clients.php'); // Redireciona se não tiver permissão
    exit;
}

$client_id = 0;
$row = [];

if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];
    $stmt = $con->prepare("SELECT client_id, firstname, lastname, email, phone, rua, numero, bairro, cidade, estado, cep FROM clients WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo "<div class='alert alert-danger'>Cliente não encontrado.</div>";
        require_once '../Usuario/footer.php';
        exit;
    }
} else {
    header('Location: clients.php');
    exit;
}

if (isset($_POST['update'])) {
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
        $client_id = intval($_POST['client_id']); // Garante que o ID é um inteiro

        // Verificar se o email já existe para outro cliente (excluindo o cliente atual)
        $stmt = $con->prepare("SELECT client_id FROM clients WHERE email = ? AND client_id <> ?");
        $stmt->bind_param("si", $email, $client_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<div class='alert alert-danger'>Este email já está cadastrado para outro cliente.</div>";
        } else {
            $stmt = $con->prepare("UPDATE clients SET firstname=?, lastname=?, email=?, phone=?, rua=?, numero=?, bairro=?, cidade=?, estado=?, cep=? WHERE client_id=?");
            $stmt->bind_param("ssssssssssi", $firstname, $lastname, $email, $phone, $rua, $numero, $bairro, $cidade, $estado, $cep, $client_id);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Cliente atualizado com sucesso</div>";
                // Atualiza $row para refletir os dados mais recentes no formulário
                $row['firstname'] = $firstname;
                $row['lastname'] = $lastname;
                $row['email'] = $email;
                $row['phone'] = $phone;
                $row['rua'] = $rua;
                $row['numero'] = $numero;
                $row['bairro'] = $bairro;
                $row['cidade'] = $cidade;
                $row['estado'] = $estado;
                $row['cep'] = $cep;
            } else {
                echo "<div class='alert alert-danger'>Erro ao atualizar cliente: " . $stmt->error . "</div>";
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
                <h3><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Cliente</h3>
                <form action="" method="POST">
                    <input type="hidden" name="client_id" value="<?php echo $row['client_id']; ?>">

                    <div class="form-group">
                        <label for="firstname">Nome</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Sobrenome</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                    </div>
                    
                    <?php renderAddressFields($row); // Renderiza os campos de endereço com busca de CEP, passando os dados existentes ?>

                    <input type="submit" name="update" class="btn btn-primary" value="Atualizar Cliente">
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