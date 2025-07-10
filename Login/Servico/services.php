<?php
require_once '../Usuario/connect.php';
require_once '../Usuario/header.php';

// Busca todos os serviços do banco de dados
$stmt = $con->prepare("SELECT service_id, name, description, price FROM services ORDER BY name ASC");
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
                <h3><i class="glyphicon glyphicon-briefcase"></i>&nbsp;Lista de Serviços</h3>
                <hr>

                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'user') : ?>
                    <a href="add_service.php" class="btn btn-success pull-right">
                        <i class="glyphicon glyphicon-plus"></i> Adicionar Serviço
                    </a>
                <?php endif; ?>

                <br><br>

                <?php if ($result->num_rows > 0) : ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['service_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td>R$ <?php echo number_format($row['price'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($_SESSION['role'] === 'admin') : ?>
                                            <a href="edit_service.php?id=<?php echo $row['service_id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="glyphicon glyphicon-edit"></i> Editar
                                            </a>
                                            <a href="delete_service.php?id=<?php echo $row['service_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este serviço?');">
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
                <?php else : ?>
                    <div class="alert alert-info">Nenhum serviço encontrado.</div>
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