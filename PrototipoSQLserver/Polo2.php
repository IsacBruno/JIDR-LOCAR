<?php
if (isset($_POST['submit'])) {
    include_once('config.php');

    // Dados do cliente
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $status_conta = "Conta ativada";
    $cnh = $_POST['cnh'];
    $senha_login = $_POST['senha-login'];
    $genero = $_POST['genero'];
    $data_nasc = $_POST['data_nascimento'];

    $sqlCliente = "INSERT INTO Cliente(nome, cpf, telefone, email, endereco, status_conta, cnh, senha_login, genero, data_Nasc) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $paramsCliente = array($nome, $cpf, $telefone, $email, $endereco, $status_conta, $cnh, $senha_login, $genero, $data_nasc);

    $stmtCliente = sqlsrv_query($conexao, $sqlCliente, $paramsCliente);

    if ($stmtCliente === false) {
        echo "<strong>Erro ao inserir cliente:</strong><br>";
        die(print_r(sqlsrv_errors(), true));
    }

    $sqlGetId = "SELECT SCOPE_IDENTITY() AS id_cliente";
    $stmtId = sqlsrv_query($conexao, $sqlGetId);
    $row = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
    $id_cliente_fk = $row['id_cliente'];
    

    // Dados da reserva
    $data_inicio = $_POST['start-date'];
    $data_fim  = $_POST['end-date'];
    $valor_total = $_POST['valor_total'];
    $status_rese = 'Alocado';
    $placa_fk = $_POST['placa'];
    $id_cliente_fk = 1234;
    $KM_inicial = 0;
    $cidade_retirada = $_POST['cidade-retirada'];

    $sqlReserva = "INSERT INTO Reserva(data_inicio, data_fim, valor_total, status_rese, placa_fk, id_cliente_fk, KM_inicial, cidade_retirada)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $paramsReserva = array($data_inicio, $data_fim, $valor_total, $status_rese, $placa_fk, $id_cliente_fk, $KM_inicial, $cidade_retirada);
    $stmtReserva = sqlsrv_query($conexao, $sqlReserva, $paramsReserva);

    if ($stmtReserva === false) {
        echo "<strong>Erro ao inserir reserva:</strong><br>";
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo '<br><strong>Cliente e reserva inseridos com sucesso!</strong>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Polo - Volkswagen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            height: 100vh;
            background: url('Fundo-forms.jpeg') no-repeat center center fixed;
            background-size: cover;
            background-color: rgba(0, 0, 1, 1.9);
        }
        .box {
            margin-top: 18vh;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 15px;
            color: white;
            width: 60%;
        }
        fieldset {
            border: 4px solid orangered;
        }
        legend {
            border: 2px solid orangered;
            padding: 10px;
            background-color: orangered;
            border-radius: 8px;
            text-align: center;
        }
        .inputbox {
            position: relative;
        }
        .inputUser {
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;
        }
        .labelInput {
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput, 
        .inputUser:valid ~ .labelInput {
            top: -18px;
            font-size: 12px;
            color: orangered;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
            width: 22px;
            height: 22px;
        }
        #submit {
            background-image: linear-gradient(to right, rgb(253, 51, 24), rgb(204, 185, 17));
            width: 100%;
            border: none;
            padding: 10px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
        }
        #submit:hover {
            background-image: linear-gradient(to right, rgb(184, 54, 37), rgb(160, 148, 43));
        }
    </style>
</head>
<body>
    <div class="box">
        <form action="polo.php" method="POST">
            <fieldset>
                <legend><b>Formulário de locação - Polo</b></legend><br><br>

                <input type="hidden" name="placa" value="GTR4D29">
                <input type="hidden" name="senha-login" value="6969">
                <input type="hidden" id="diaria" value="84.00">

                <div class="inputbox">
                    <input type="text" name="nome" class="inputUser" required>
                    <label class="labelInput">Nome completo do cliente</label>
                </div><br>

                <div class="inputbox">
                    <input type="text" name="cpf" class="inputUser" required pattern="\d{11}">
                    <label class="labelInput">CPF</label>
                </div><br>

                <div class="inputbox">
                    <input type="tel" name="telefone" class="inputUser" required>
                    <label class="labelInput">Telefone</label>
                </div><br>

                <div class="inputbox">
                    <input type="text" name="endereco" class="inputUser" required>
                    <label class="labelInput">Digite seu endereço</label>
                </div><br>

                <div class="inputbox">
                    <input type="email" name="email" class="inputUser" required>
                    <label class="labelInput">Melhor E-mail</label>
                </div><br>

                <b><p>Gênero:</p></b>
                <input type="radio" id="feminino" name="genero" value="feminino" required>
                <label for="feminino">Feminino</label><br>
                <input type="radio" id="masculino" name="genero" value="masculino" required>
                <label for="masculino">Masculino</label><br>
                <input type="radio" id="outro" name="genero" value="outro" required>
                <label for="outro">Outro</label><br><br>

                <div class="inputbox">
                    <input type="number" name="cnh" class="inputUser" required>
                    <label class="labelInput">CNH</label>
                </div><br>

                <p id="valorBruto">Valor total da locação: R$ 0,00</p>
                <p id="diasLocacao">Dias de locação: 0</p>

                <input type="hidden" name="valor_total" id="valor_total">

                <div class="inputbox">
                    <label class="labelInput"><strong>Escolha a data de início da locação:</strong></label><br>
                    <input type="date" name="start-date" id="start-date" class="inputUser" required>
                </div><br>

                <div class="inputbox">
                    <label class="labelInput"><strong>Escolha a data fim da locação:</strong></label><br>
                    <input type="date" name="end-date" id="end-date" class="inputUser" required>
                </div><br>

                <div class="inputbox">
                    <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                    <input type="date" name="data_nascimento" id="data_nascimento" class="inputUser" required>
                </div><br>

                <div class="inputbox">
                    <label><b>Escolha a cidade de retirada:</b></label><br>
                    <select id="cidade-retirada" name="cidade-retirada">
                        <option value="samambaia">Samambaia</option>
                        <option value="asa-sul">Asa Sul</option>
                        <option value="ceilandia">Ceilândia</option>
                        <option value="taguatinga">Taguatinga</option>
                        <option value="candangolandia">Candangolândia</option>
                    </select>
                </div><br><br>

                <input type="submit" name="submit" id="submit">
            </fieldset>
        </form>
    </div>

    <script>
        
       
       
        const startDate = document.getElementById("start-date");
        const endDate = document.getElementById("end-date");
        const valorBruto = document.getElementById("valorBruto");
        const valorTotalInput = document.getElementById("valor_total");
        const diasLocacao = document.getElementById("diasLocacao");
        const diaria = parseFloat(document.getElementById("diaria").value);

        function calcularValorTotal() {
            const inicio = new Date(startDate.value);
            const fim = new Date(endDate.value);

            if (!isNaN(inicio) && !isNaN(fim) && fim >= inicio) {
                const diffTime = Math.abs(fim - inicio);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;

                const valorTotal = (diaria * diffDays).toFixed(2);
                valorBruto.textContent = `Valor total da locação: R$ ${valorTotal}`;
                diasLocacao.textContent = `Dias de locação: ${diffDays}`;
                valorTotalInput.value = valorTotal;
            } else {
                valorBruto.textContent = `Valor total da locação: R$ 0,00`;
                diasLocacao.textContent = `Dias de locação: 0`;
                valorTotalInput.value = "0.00";
            }
        }

        startDate.addEventListener("change", calcularValorTotal);
        endDate.addEventListener("change", calcularValorTotal);
    </script>
</body>
</html>
