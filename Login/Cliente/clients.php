<?php
require_once '../Usuario/connect.php';
require_once '../Usuario/header.php';

// Permissão para visualizar clientes: admin ou user
if (!isset($_SESSION['role'])) {
    header('Location: ../Usuario/login.php');
    exit;
}

// Busca todos os clientes do banco de dados
$stmt = $con->prepare("SELECT client_id, firstname, lastname, email, phone, rua, numero, bairro, cidade, estado, cep FROM clients ORDER BY firstname ASC");
$stmt->execute();
$result = $stmt->get_result();

// Exibe mensagem de sessão, se houver (por exemplo, após exclusão)
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']); // Limpa a mensagem após exibir
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <h3><i class="glyphicon glyphicon-user"></i>&nbsp;Lista de Clientes</h3>
                <hr>

                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'user') : ?>
                    <a href="add_client.php" class="btn btn-success pull-right">
                        <i class="glyphicon glyphicon-plus"></i> Adicionar Cliente
                    </a>
                <?php endif; ?>

                <br><br>

                <?php if ($result->num_rows > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Endereço Completo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo $row['client_id']; ?></td>
                                        <td><?php echo $row['firstname']; ?></td>
                                        <td><?php echo $row['lastname']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td>
                                            <?php
                                            $endereco = [];
                                            if (!empty($row['rua'])) $endereco[] = $row['rua'];
                                            if (!empty($row['numero'])) $endereco[] = $row['numero'];
                                            if (!empty($row['bairro'])) $endereco[] = $row['bairro'];
                                            if (!empty($row['cidade'])) $endereco[] = $row['cidade'];
                                            if (!empty($row['estado'])) $endereco[] = $row['estado'];
                                            if (!empty($row['cep'])) $endereco[] = $row['cep'];
                                            echo implode(', ', $endereco);
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION['role'] === 'admin') : ?>
                                                <a href="edit_client.php?id=<?php echo $row['client_id']; ?>" class="btn btn-warning btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i> Editar
                                                </a>
                                                <a href="delete_client.php?id=<?php echo $row['client_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
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
                    <div class="alert alert-info">Nenhum cliente encontrado.</div>
                <?php endif; ?>

                <a href="../Usuario/index.php" class="btn btn-default">
                    <i class="glyphicon glyphicon-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
require_once '../Usuario/footer.php';
?>