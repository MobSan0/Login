<?php
session_start();
if(!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Sistema CRUD</title>
    <link rel="stylesheet" type="text/css" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style type="text/css">
    .box{
        background: #f0f0f0; 
        padding: 20px; 
    }
    </style>
</head>
<body> 
<div class="container"> 
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Alternar navegação</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Sistema CRUD</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php">Início</a></li>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li><a href="insert.php">Adicionar Usuário</a></li> 
                    <?php endif; ?>
                    <li><a href="users.php">Visualizar Usuários</a></li>
                    <li><a href="products.php">Produtos</a></li> </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li><a href="users.php"><i class="glyphicon glyphicon-cog"></i> Painel Admin</a></li>
                    <?php endif; ?>
                    <li><a href="view.php?id=<?php echo $_SESSION['user_id']; ?>"><i class="glyphicon glyphicon-user"></i> Meu Perfil</a></li>
                    <li><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Sair (<?php echo $_SESSION['username']; ?>)</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>