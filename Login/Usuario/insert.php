<?php 
require_once 'connect.php';
require_once 'header.php';
require_once 'adress_component.php'; // Inclui o componente de endereço

if($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<div class="container">
<?php 
if(isset($_POST['addnew'])){
    // Certifique-se de que todos os campos de endereço estão sendo validados
    if(empty($_POST['firstname']) || empty($_POST['lastname']) || 
       empty($_POST['rua']) || empty($_POST['numero']) || empty($_POST['bairro']) ||
       empty($_POST['cidade']) || empty($_POST['estado']) || empty($_POST['cep']) ||
       empty($_POST['contact']) || empty($_POST['username']) || empty($_POST['password']) ||
       empty($_POST['role'])) {
        echo "<div class='alert alert-danger'>Por favor, preencha todos os campos obrigatórios</div>";
    } else {
        $firstname = $con->real_escape_string($_POST['firstname']);
        $lastname = $con->real_escape_string($_POST['lastname']);
        $rua = $con->real_escape_string($_POST['rua']);
        $numero = $con->real_escape_string($_POST['numero']);
        $bairro = $con->real_escape_string($_POST['bairro']);
        $cidade = $con->real_escape_string($_POST['cidade']);
        $estado = $con->real_escape_string($_POST['estado']);
        $cep = $con->real_escape_string($_POST['cep']);
        $contact = $con->real_escape_string($_POST['contact']);
        $username = $con->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $con->real_escape_string($_POST['role']);

        // Verifica se o username já existe
        $stmt = $con->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt->num_rows > 0) {
            echo "<div class='alert alert-danger'>Este nome de usuário já está em uso. Por favor, escolha outro.</div>";
        } else {
            // Se não existir, insere o novo usuário
            $stmt = $con->prepare("INSERT INTO users(firstname, lastname, rua, numero, bairro, cidade, estado, cep, contact, username, password, role) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $firstname, $lastname, $rua, $numero, $bairro, $cidade, $estado, $cep, $contact, $username, $password, $role);
            
            if($stmt->execute()) {
                echo "<div class='alert alert-success'>Usuário adicionado com sucesso</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao adicionar usuário: " . $stmt->error . "</div>";
            }
        }
        $stmt->close();
    }
}
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="box">
            <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Adicionar Novo Usuário</h3> 
            <form action="" method="POST" id="userForm">
                <div class="form-group">
                    <label for="firstname">Nome</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="lastname">Sobrenome</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="username">Nome de usuário</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <small id="usernameHelp" class="form-text text-muted"></small>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Tipo de Usuário</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="user">Usuário Comum</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <?php renderAddressFields(); ?> <div class="form-group">
                    <label for="contact">Contato</label>
                    <input type="text" name="contact" id="contact" class="form-control" required>
                </div>
                
                <input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
            </form>
        </div>
    </div>
</div>
</div>

<script>
// Verificação em tempo real do username
document.getElementById('username').addEventListener('blur', function() {
    const username = this.value;
    const usernameHelp = document.getElementById('usernameHelp');
    
    if(username.length > 0) {
        fetch('check_username.php?username=' + username)
            .then(response => response.json())
            .then(data => {
                if(data.exists) {
                    usernameHelp.textContent = 'Este nome de usuário já está em uso!';
                    usernameHelp.style.color = 'red';
                } else {
                    usernameHelp.textContent = 'Nome de usuário disponível';
                    usernameHelp.style.color = 'green';
                }
            });
    }
});
</script>

<?php 
require_once 'footer.php';
?>