<?php
require_once 'connect.php';
require_once 'header.php';

echo "<div class='container'>";

if(!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];
$sql = "SELECT * FROM users WHERE user_id=$id";
$result = $con->query($sql);

if($result->num_rows != 1) {
    header('Location: users.php');
    exit;
}

$row = $result->fetch_assoc();
?>

<h2>Visualizar Usuário</h2>
<div class="box">
    <div class="form-group">
        <label>Nome</label>
        <p class="form-control-static"><?php echo htmlspecialchars($row['firstname']); ?></p>
    </div>
    
    <div class="form-group">
        <label>Sobrenome</label>
        <p class="form-control-static"><?php echo htmlspecialchars($row['lastname']); ?></p>
    </div>
    
    <div class="form-group">
        <label>Endereço</label>
        <p class="form-control-static">
            <?php echo htmlspecialchars($row['rua']) . ', ' . 
                  htmlspecialchars($row['numero']) . ' - ' . 
                  htmlspecialchars($row['bairro']); ?>
        </p>
        <p class="form-control-static">
            <?php echo htmlspecialchars($row['cidade']) . '/' . 
                  htmlspecialchars($row['estado']) . ' - CEP: ' . 
                  htmlspecialchars($row['cep']); ?>
        </p>
    </div>
    
    <div class="form-group">
        <label>Contato</label>
        <p class="form-control-static"><?php echo htmlspecialchars($row['contact']); ?></p>
    </div>
    
    <a href="users.php" class="btn btn-default">Voltar</a>
</div>

<?php
require_once 'footer.php';
?>