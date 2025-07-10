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
                <a href="insert.php" class="btn btn-primary btn-lg" style="margin-right: 10px; margin-bottom: 10px;">
                    <i class="glyphicon glyphicon-user"></i> Criar Novo Usuário
                </a>
            <?php endif; ?>
            <a href="../Produto/products.php" class="btn btn-success btn-lg" style="margin-right: 10px; margin-bottom: 10px;">
                <i class="glyphicon glyphicon-shopping-cart"></i> Gerenciar Produtos
            </a>
            <a href="../Servico/services.php" class="btn btn-info btn-lg" style="margin-right: 10px; margin-bottom: 10px;">
                <i class="glyphicon glyphicon-briefcase"></i> Gerenciar Serviços
            </a>
            <a href="../Cliente/clients.php" class="btn btn-primary btn-lg" style="margin-right: 10px; margin-bottom: 10px;">
                <i class="glyphicon glyphicon-user"></i> Gerenciar Clientes
            </a>
            <a href="../Cliente/clients.php" class="btn btn-warning btn-lg" style="margin-right: 10px; margin-bottom: 10px;">
                <i class="glyphicon glyphicon-list-alt"></i> Visualizar Clientes
            </a>
            <a href="users.php" class="btn btn-warning btn-lg" style="margin-bottom: 10px;">
                <i class="glyphicon glyphicon-list-alt"></i> Visualizar Usuários
            </a>
        </p>
    </div>
</div>
<?php
require_once 'footer.php';
?>