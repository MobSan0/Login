<?php
require_once 'connect.php';
require_once 'header.php';
require_once 'adress_component.php'; // Inclui o componente de endereço

echo "<div class='container'>";

// Verificação de permissão reforçada
if(!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Apenas administradores podem editar
    if($_SESSION['role'] !== 'admin') {
        echo "<div class='alert alert-danger'>Acesso negado. Apenas administradores podem editar usuários.</div>";
        require_once 'footer.php';
        exit;
    }

    // Buscar os dados do usuário para preencher o formulário de edição
    $stmt = $con->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if(!$row) {
        echo "<div class='alert alert-danger'>Usuário não encontrado.</div>";
        require_once 'footer.php';
        exit;
    }

} else {
    header('Location: users.php');
    exit;
}

// Lógica para atualizar o usuário
if(isset($_POST['update'])) {
    $userid = (int)$_POST['userid'];
    $firstname = $con->real_escape_string($_POST['firstname']);
    $lastname = $con->real_escape_string($_POST['lastname']);
    $rua = $con->real_escape_string($_POST['rua']);
    $numero = $con->real_escape_string($_POST['numero']);
    $bairro = $con->real_escape_string($_POST['bairro']);
    $cidade = $con->real_escape_string($_POST['cidade']);
    $estado = $con->real_escape_string($_POST['estado']);
    $cep = $con->real_escape_string($_POST['cep']);
    $contact = $con->real_escape_string($_POST['contact']);

    $stmt = $con->prepare("UPDATE users SET firstname=?, lastname=?, rua=?, numero=?, bairro=?, cidade=?, estado=?, cep=?, contact=? WHERE user_id=?");
    $stmt->bind_param("sssssssssi", $firstname, $lastname, $rua, $numero, $bairro, $cidade, $estado, $cep, $contact, $userid);

    if($stmt->execute()) {
        echo "<div class='alert alert-success'>Usuário atualizado com sucesso.</div>";
        // Atualiza a variável $row para exibir os dados mais recentes no formulário
        $row = [
            'user_id' => $userid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'rua' => $rua,
            'numero' => $numero,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep,
            'contact' => $contact
        ];
    } else {
        echo "<div class='alert alert-danger'>Erro ao atualizar usuário: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<h2>Editar Usuário</h2>
<form action="" method="POST">
    <input type="hidden" name="userid" value="<?php echo $row['user_id']; ?>">
    
    <div class="form-group">
        <label>Nome</label>
        <input type="text" name="firstname" class="form-control" value="<?php echo $row['firstname']; ?>" required>
    </div>
    
    <div class="form-group">
        <label>Sobrenome</label>
        <input type="text" name="lastname" class="form-control" value="<?php echo $row['lastname']; ?>" required>
    </div>
    
    <?php renderAddressFields($row); ?> <div class="form-group">
        <label>Contato</label>
        <input type="text" name="contact" class="form-control" value="<?php echo $row['contact']; ?>" required>
    </div>
    
    <input type="submit" name="update" class="btn btn-primary" value="Atualizar">
</form>

<?php
require_once 'footer.php';
?>