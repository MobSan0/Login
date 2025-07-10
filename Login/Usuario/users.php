<?php
require_once 'connect.php';
require_once 'header.php';

// Permissão para visualizar usuários: admin ou user
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Busca todos os usuários do banco de dados
$stmt = $con->prepare("SELECT user_id, firstname, lastname, username, role, cidade, estado, contact FROM users ORDER BY firstname ASC");
$stmt->execute();
$result = $stmt->get_result();

// Exibe mensagem de sessão, se houver (por exemplo, após exclusão de usuário)
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']); // Limpa a mensagem após exibir
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <h3><i class="glyphicon glyphicon-th-list"></i>&nbsp;Lista de Usuários</h3> <hr>

                <?php if ($_SESSION['role'] === 'admin') : ?>
                    <a href="insert.php" class="btn btn-success pull-right">
                        <i class="glyphicon glyphicon-plus"></i> Adicionar Usuário
                    </a>
                <?php endif; ?>

                <br><br> <?php if ($result->num_rows > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
                                    <th>Usuário</th>
                                    <th>Tipo</th>
                                    <th>Cidade/UF</th>
                                    <th>Contato</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['firstname']; ?></td>
                                        <td><?php echo $row['lastname']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['role']; ?></td>
                                        <td><?php echo $row['cidade'] . '/' . $row['estado']; ?></td>
                                        <td><?php echo $row['contact']; ?></td>
                                        <td>
                                            <?php if ($_SESSION['role'] === 'admin') : ?>
                                                <a href="edit.php?id=<?php echo $row['user_id']; ?>" class="btn btn-warning btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i> Editar
                                                </a>
                                                <a href="delete.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                                    <i class="glyphicon glyphicon-trash"></i> Excluir
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted">Sem permissão</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info">Nenhum usuário encontrado.</div>
                <?php endif; ?>

                <a href="index.php" class="btn btn-default">
                    <i class="glyphicon glyphicon-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
require_once 'footer.php';
?>