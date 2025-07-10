<?php
/**
 * Componente de Endereço com busca por CEP
 * * @param array $row Dados existentes (para edição)
 */
function renderAddressFields($row = []) {
    ?>
    <div class="form-group">
        <label>Endereço</label>
        <div class="row">
            <div class="col-md-5"> <div class="input-group">
                    <input type="text" name="cep" id="cep" class="form-control" placeholder="CEP" 
                           value="<?php echo isset($row['cep']) ? $row['cep'] : ''; ?>" required>
                    <span class="input-group-btn">
                        <button type="button" id="buscarCep" class="btn btn-info">
                            <i class="glyphicon glyphicon-search"></i> Buscar CEP
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-md-7"></div> </div>
        <div class="row mt-2">
            <div class="col-md-6"> <input type="text" name="rua" id="rua" class="form-control" placeholder="Rua" 
                       value="<?php echo isset($row['rua']) ? $row['rua'] : ''; ?>" required>
            </div>
            <div class="col-md-3"> <input type="text" name="numero" id="numero" class="form-control" placeholder="Número" 
                       value="<?php echo isset($row['numero']) ? $row['numero'] : ''; ?>" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade" 
                       value="<?php echo isset($row['cidade']) ? $row['cidade'] : ''; ?>" required>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-9"> 
                <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Bairro" 
                       value="<?php echo isset($row['bairro']) ? $row['bairro'] : ''; ?>" required>
            </div>
            <div class="col-md-3"> <select name="estado" id="estado" class="form-control" required>
                    <option value="">UF</option>
                    <?php
                    $estados = [
                        'AC'=>'Acre', 'AL'=>'Alagoas', 'AP'=>'Amapá', 'AM'=>'Amazonas',
                        'BA'=>'Bahia', 'CE'=>'Ceará', 'DF'=>'Distrito Federal', 'ES'=>'Espírito Santo',
                        'GO'=>'Goiás', 'MA'=>'Maranhão', 'MT'=>'Mato Grosso', 'MS'=>'Mato Grosso do Sul',
                        'MG'=>'Minas Gerais', 'PA'=>'Pará', 'PB'=>'Paraíba', 'PR'=>'Paraná',
                        'PE'=>'Pernambuco', 'PI'=>'Piauí', 'RJ'=>'Rio de Janeiro', 'RN'=>'Rio Grande do Norte',
                        'RS'=>'Rio Grande do Sul', 'RO'=>'Rondônia', 'RR'=>'Roraima', 'SC'=>'Santa Catarina',
                        'SP'=>'São Paulo', 'SE'=>'Sergipe', 'TO'=>'Tocantins'
                    ];
                    
                    foreach($estados as $sigla => $nome) {
                        $selected = (isset($row['estado']) && $row['estado'] == $sigla) ? 'selected' : '';
                        echo "<option value='$sigla' $selected>$sigla - $nome</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <script>
    // Script para busca de CEP
    document.getElementById('buscarCep').addEventListener('click', function() {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        
        if(cep.length !== 8) {
            alert('CEP inválido! Deve conter 8 dígitos.');
            return;
        }

        const btn = document.getElementById('buscarCep');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="glyphicon glyphicon-refresh spin"></i> Buscando...';
        btn.disabled = true;

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if(data.erro) {
                    alert('CEP não encontrado!');
                    return;
                }
                
                document.getElementById('rua').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('estado').value = data.uf || '';
                document.getElementById('numero').focus();
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                alert('Serviço indisponível. Preencha manualmente.');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    });

    // Máscara para CEP
    document.getElementById('cep').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });
    </script>
    <?php
}