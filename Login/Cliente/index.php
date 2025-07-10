<?php
// Autor: Samuel
require_once "header.php";
?>
<div class="container">
    <div class="jumbotron">
        <h1>Sistema de Gerenciamento CRUD</h1>
        <p>Escolha uma opção abaixo para continuar:</p>
        <p>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="insert.php" class="btn btn-primary btn-lg">
                    <i class="glyphicon glyphicon-user"></i> Criar Novo Usuário
                </a>
            <?php endif; ?>
            <a href="../Produto/products.php" class="btn btn-success btn-lg">
                <i class="glyphicon glyphicon-shopping-cart"></i> Cadastrar/Ver Produtos
            </a>
            <a href="../Servico/services.php" class="btn btn-info btn-lg">
                <i class="glyphicon glyphicon-briefcase"></i> Cadastrar/Ver Serviços
            </a>
            <a href="../Cliente/clients.php" class="btn btn-primary btn-lg"> <i class="glyphicon glyphicon-handshake"></i> Cadastrar/Ver Clientes
            </a>
            <a href="users.php" class="btn btn-warning btn-lg">
                <i class="glyphicon glyphicon-list-alt"></i> Visualizar Usuários
            </a>
        </p>
    </div>
</div>
<?php
require_once 'footer.php';
?>