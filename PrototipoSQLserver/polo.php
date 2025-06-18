<?php
if (isset($_POST['submit'])) {
    include_once('config.php');

    // Dados do cliente
    $idCliente = $_POST['Fk_cliente'];
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

    $sqlCliente = "INSERT INTO Cliente(id_cliente, nome, cpf, telefone, email, endereco, status_conta, cnh, senha_login, genero, data_Nasc) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $paramsCliente = array($idCliente, $nome, $cpf, $telefone, $email, $endereco, $status_conta, $cnh, $senha_login, $genero, $data_nasc);

    $stmtCliente = sqlsrv_query($conexao, $sqlCliente, $paramsCliente);

    if ($stmtCliente === false) {
        echo "<strong>Erro ao inserir cliente:</strong><br>";
        die(print_r(sqlsrv_errors(), true));
    }

    // Pegar o ID do último cliente inserido
/*
    $sqlGetId = "SELECT SCOPE_IDENTITY() AS id_cliente";
    $stmtId = sqlsrv_query($conexao, $sqlGetId);
    $row = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
    $id_cliente_fk = $row['id_cliente'];
*/
   
    // Dados da reserva
    $data_inicio = $_POST['start-date'];
    $data_fim  = $_POST['end-date'];
    $valor_total = $_POST['valor_total'];
    $status_rese = 'Alocado'; // fixo
    $placa_fk = $_POST['placa'];
    $id_cliente_fk = $_POST['Fk_cliente'];
    $KM_inicial = 0; // fixo
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polo - Volkswagen</title>
    <style>
        

         body{
            font-family: Arial, Helvetica, sans-serif;
            height: 100vh;
            background: url('Fundo-forms.jpeg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            background-color: rgba(0, 0, 1, 1.9); /* opacidade fosca */
            z-index: 30;
        }

        .box{
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
            margin-bottom: 18vh;
        }
        fieldset{
            
            border: 4px solid orangered;

        }
        legend{
            border: 2px solid orangered;
            padding: 10px;
            text-align: center;
            background-color: orangered;
            border-radius: 8px;
        }
        .inputbox{
            position: relative;
        }
        .inputUser{
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;
        }
        .labelInput{
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput, 
        .inputUser:valid ~ .labelInput{
            top: -18px;
            font-size: 12px;
            color: orangered;

        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);         /* Deixa o ícone branco */
            cursor: pointer;
            width: 22px;               /* Aumenta a largura do ícone */
            height: 22px;              /* Aumenta a altura do ícone */
        }
        #submit{
            background-image: linear-gradient(to right, rgb(253, 51, 24), rgb(204, 185, 17));
            width: 100%;
            border: none;
            padding: 10px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            
        }
        #submit:hover{
            background-image: linear-gradient(to right, rgb(184, 54, 37), rgb(160, 148, 43));
    
        }
    </style>
</head>
<body>
    <div class="box">
        <form id="form-locacao" action="polo.php" method="POST">
           
            <fieldset>
               <legend><b> Formulário de locação - Polo </b></legend>
               <br><br>
               <input type="hidden" name="placa" value="GTR4D29">

              
              <input type="hidden" name="Fk_cliente" id="Fk_cliente">
              

               
               <div class="inputbox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome" class="labelInput">Nome completo do cliente</label>
               </div>
                <br>
                <div class="inputbox">
                    <input type="number" name="cpf" id="cpf" class="inputUser"  required pattern="\d{11}" >
                    <label for="cpf" class="labelInput">CPF</label>

               </div>
               <br>
               <div class="inputbox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser" required>
                    <label for="telefone" class="labelInput">Telefone</label>
               </div>
               <br>
                <div class="inputbox">
                    <input type="text" name="endereco" id="endereco" class="inputUser" required>
                    <label for="endereco" class="labelInput">Digite seu endereço</label>
               </div>
                <br>
               <input type="hidden" name="senha-login" value="6969">
               
               <div class="inputbox">
                    <input type="email" name="email" id="email" class="inputUser" required>
                    <label for="text" class="labelInput">Melhor E-mail</label>
                 </div>
                

    <!--Parte do status da conta do cliente "ativada com o PHP"-->
               
             <b><p>Genero:</p> </b>
               
                <input type="radio" id="feminino" name="genero" value="feminino" required>
                <label for="Feminino">Feminino</label>
                <br>
                <input type="radio" id="masculino" name="genero" value="masculino" required>
                <label for="Masculino">Masculino</label>
                <br>
                <input type="radio" id="outro" name="genero" value="outro" required>
                <label for="outro">Outro</label>
               <br>
               <br>
                
               <div class="inputbox">
                    <input type="number" name="cnh" id="cnh" class="inputUser" required>
                    <label for="endereco" class="labelInput">CNH</label>
               </div>
                
                 <!-- Diária fixa (campo oculto) -->
                <input type="hidden" id="diaria" name="diaria" value="84.00" />

                <!-- Exibição do valor total calculado -->
                <p id="valorBruto" name="valor_total ">Valor total da locação: R$ 0,00</p>
                

                <p id="diasLocacao">Dias de locação: 0</p>

                <!-- Campo oculto para enviar o valor total ao PHP -->
                <input type="hidden" name="valor_total" id="valor_total">


                <div class="inputbox">
                    <label for="date" class="labelInput"><strong>Escolha a data de início da locação:</strong></label>
                    <br>
                    <input type="date" name="start-date" id="start-date" class="inputUser" required>  
               </div>
               <br>
               <div class="inputbox">
                    <label for="date" class="labelInput"><strong>Escolha a data fim da locação:</strong></label>
                    <br>
                    <input type="date" name="end-date" id="end-date" class="inputUser" required>
                    
               </div>
                
                
                <div class="inputbox">
                    <br>
                    <label for="data_nascimento" ><b> Data de Nascimento: </b></label>
                    <input type="date" name="data_nascimento" id="data_nascimento" class="inputUser" required onchange="verificarIdade()">
   
               </div>
                <br>
               <div class="inputbox">
                    <label for="cidade" class="labelInput" id="cidade-retirada"><b>Escolha a cidade de retirada:</b></label>
                    <br>

                    <select id="cidade-retirada" name="cidade-retirada">
                    <option value="samambaia">Samambaia</option>
                    <option value="asa-sul">Asa Sul</option>
                    <option value="ceilandia">Ceilândia</option>
                    <option value="taguatinga">Taguatinga</option>
                    <option value="candangolandia">Candangolândia</option>
                    </select>
               </div>
               <br>
               <br> 
                <br>
               <input type="submit" name="submit" id="submit">
            </fieldset>
        </form>
    </div>
    <br>

        <script>
            const diaInicio = document.getElementById("start-date");
            const diaFim = document.getElementById("end-date");
            const diaria = document.getElementById("diaria");
            const valorBruto = document.getElementById("valorBruto");
            const diasLocacao = document.getElementById("diasLocacao");

            function calcularValorTotal(){
              if(diaInicio.value && diaFim.value && diaria.value){
                const inicioloc = new Date(diaInicio.value);
                const fimloc = new Date(diaFim.value);
                const totalDias = (fimloc - inicioloc) / (1000 * 60 * 60 * 24); 
                 if(totalDias > 0 ){
                    const textoDias = totalDias === 1 ? "1 dia" : `${totalDias} dias`;
                    diasLocacao.innerText = `Dias de locação: ${textoDias}`;
      
                    const total = totalDias * parseFloat(diaria.value.replace(",", "."));
                    valorBruto.innerText = `Valor total da locação: R$ ${total.toFixed(2)}`;

                     document.getElementById("valor_total").value = total.toFixed(2);

                    } else {
                    valorBruto.innerText = `Digite uma data válida!`;
                    diasLocacao.innerText = `Dias de locação: 0`;

                    document.getElementById("valor_total").value = "";

                }
            }
        }

              // Dispara o cálculo sempre que mudar data
         diaInicio.addEventListener("change", calcularValorTotal);
         diaFim.addEventListener("change", calcularValorTotal);

        function verificarIdade() {
          const nascimento = document.getElementById("data_nascimento").value;
            if (!nascimento) {
                alert("Por favor, insira sua data de nascimento!");
            return;
            }

          const hoje = new Date(); // Corrigido: "Date" com D maiúsculo
          const dataNasc = new Date(nascimento); // Corrigido: "Date" com D maiúsculo

          let idade = hoje.getFullYear() - dataNasc.getFullYear();
          const mes = hoje.getMonth() - dataNasc.getMonth();
          const dia = hoje.getDate() - dataNasc.getDate();

              if (mes < 0 || (mes === 0 && dia < 0)) {
              idade--;
             }

              if (idade < 18) {
              alert('Desculpa, mas você precisa ser maior de idade para estar apto a alocar veículos nesse site!');
              } else {
              alert('Idade válida, prossiga...');

             }
         }



         const id = Math.floor(10000 + Math.random() * 90000);
            document.getElementById('Fk_cliente').value = id;
            console.log('ID gerado:', id); //


        </script>

</body>
</html>