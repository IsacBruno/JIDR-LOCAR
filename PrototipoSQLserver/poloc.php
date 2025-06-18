<?php
// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) { 

    // Inclui o arquivo de configuração com a conexão ao banco de dados
    include_once('config.php');

    // =======================
    // Inserção dos dados do cliente
    // =======================

    // Recebendo os valores enviados pelo formulário (campos do cliente)
    $idCliente = $_POST['Fk_cliente'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $status_conta = "Conta ativada"; // Valor fixo
    $cnh = $_POST['cnh'];
    $senha_login = $_POST['senha-login'];
    $genero = $_POST['genero'];
    $data_nasc = $_POST['data_nascimento'];

    // Query SQL para inserir os dados na tabela Cliente
    $sqlCliente = "INSERT INTO Cliente(id_cliente, nome, cpf, telefone, email, endereco, status_conta, cnh, senha_login, genero, data_Nasc) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Array com os parâmetros a serem passados para a query (ordem deve ser a mesma da query)
    $paramsCliente = array($idCliente, $nome, $cpf, $telefone, $email, $endereco, $status_conta, $cnh, $senha_login, $genero, $data_nasc);

    // Executa a query para inserir o cliente
    $stmtCliente = sqlsrv_query($conexao, $sqlCliente, $paramsCliente);

    // Caso dê erro na inserção do cliente, exibe o erro e encerra o script
    if ($stmtCliente === false) {
        echo "<strong>Erro ao inserir cliente:</strong><br>";
        die(print_r(sqlsrv_errors(), true));
    }

    // =======================
    // Inserção dos dados da reserva
    // =======================

    // Recebendo os valores enviados pelo formulário (campos da reserva)
    $data_inicio = $_POST['start-date'];
    $data_fim  = $_POST['end-date'];
    $valor_total = $_POST['valor_total'];
    $status_rese = 'Alocado'; // Valor fixo para indicar que a reserva está ativa
    $placa_fk = $_POST['placa'];
    $id_cliente_fk = $_POST['Fk_cliente'];
    $KM_inicial = 0; // Valor fixo (quilometragem inicial padrão)
    $cidade_retirada = $_POST['cidade-retirada'];

    // Query SQL para inserir os dados na tabela Reserva
    $sqlReserva = "INSERT INTO Reserva(data_inicio, data_fim, valor_total, status_rese, placa_fk, id_cliente_fk, KM_inicial, cidade_retirada)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Array com os parâmetros da reserva (na mesma ordem da query)
    $paramsReserva = array($data_inicio, $data_fim, $valor_total, $status_rese, $placa_fk, $id_cliente_fk, $KM_inicial, $cidade_retirada);

    // Executa a query para inserir a reserva
    $stmtReserva = sqlsrv_query($conexao, $sqlReserva, $paramsReserva);

    /*
    // Trecho comentado de validação de inserção da reserva
    if ($stmtReserva === false) {
        echo "<strong>Erro ao inserir reserva:</strong><br>";
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo '<br><strong>Cliente e reserva inseridos com sucesso!</strong>';
    }
    */       
}
?>

<!-------------------------------------HTML / CSS --------------------------------------------------> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polo - VOLKSWAGEN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style> 

    /* Remove todas as margens padrão dos elementos da página */
    *{
        margin: 0;
    }

    /* Estilização geral do corpo da página */
    body{
        font-family: Arial, Helvetica, sans-serif; /* Define a fonte padrão */
        height: 100vh; /* Faz o body ocupar toda a altura da tela */
        background: url('Fundo-forms.jpeg') no-repeat center center fixed; /* Define uma imagem de fundo fixa, centralizada */
        background-size: cover; /* Faz a imagem cobrir toda a área disponível */
        position: relative; /* Permite o uso de posicionamentos relativos para elementos filhos */
        z-index: 30; /* Define a camada de empilhamento do body */
    }

    /* Estilo da caixa de conteúdo principal (formulário) */
    .box{
        margin: 6vh auto; /* Margem vertical proporcional à altura da tela e centralizado horizontalmente */
        background-color: rgba(0, 0, 0, 0.6); /* Fundo preto com 60% de transparência */
        padding: 15px; /* Espaçamento interno */
        border-radius: 15px; /* Cantos arredondados */
        color: white; /* Cor do texto */
        width: 70%; /* Largura de 70% da tela */
    }

    /* Estilo das bordas do fieldset */
    fieldset{
        border: 4px solid orangered; /* Borda grossa e laranja */
    }

    /* Estilo da legenda do fieldset */
    legend{
        border: 2px solid orangered; /* Borda mais fina e laranja */
        padding: 10px; /* Espaçamento interno */
        text-align: center; /* Centraliza o texto */
        background-color: orangered; /* Fundo laranja */
        border-radius: 8px; /* Cantos arredondados */
    }

    /* Estilo para caixas de input com label flutuante */
    .inputbox{
        position: relative; /* Define o contexto para posicionamento absoluto da label dentro */
    }

    /* Estilo para os campos de input do usuário */
    .inputUser{
        background: none; /* Sem fundo */
        border: none; /* Sem borda padrão */
        border-bottom: 1px solid white; /* Apenas uma linha branca na parte de baixo */
        outline: none; /* Remove o contorno azul padrão do navegador */
        color: white; /* Cor do texto digitado */
        font-size: 15px; /* Tamanho da fonte */
        width: 100%; /* Ocupa toda a largura disponível */
        letter-spacing: 2px; /* Espaçamento entre letras */
    }

    /* Estilo para os labels flutuantes (que ficam sobre o input até o usuário digitar algo) */
    .labelInput{
        position: absolute; /* Fica sobre o input */
        top: 0px; /* Alinhado ao topo da .inputbox */
        left: 0px; /* Alinhado à esquerda da .inputbox */
        pointer-events: none; /* Faz o label ignorar cliques do mouse */
        transition: .5s; /* Animação de transição suave ao flutuar */
    }

    /* Quando o input está em foco ou preenchido, move o label para cima */
    .inputUser:focus ~ .labelInput, 
    .inputUser:valid ~ .labelInput{
        top: -18px; /* Move o label para cima */
        font-size: 12px; /* Diminui o tamanho da fonte */
        color: orangered; /* Muda a cor para laranja */
    }

    /* Personalização do seletor de calendário (ícone do input tipo date) para navegadores baseados em Webkit (Chrome, Edge, etc) */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1); /* Inverte as cores (deixa o ícone branco) */
        cursor: pointer; /* Muda o cursor para mãozinha ao passar o mouse */
        width: 22px; /* Largura do ícone */
        height: 22px; /* Altura do ícone */
    }

    /* Estilo do botão de envio (submit) */
    #submit{
        background-image: linear-gradient(to right, rgb(253, 51, 24), rgb(204, 185, 17)); /* Degradê da esquerda para a direita (vermelho para amarelo) */
        width: 100%; /* Ocupa toda a largura */
        border: none; /* Sem borda */
        padding: 10px; /* Espaçamento interno */
        color: white; /* Cor do texto */
        font-size: 15px; /* Tamanho da fonte */
        cursor: pointer; /* Muda o cursor para mãozinha */
        border-radius: 10px; /* Cantos arredondados */
    }

    /* Efeito hover (quando o usuário passa o mouse sobre o botão submit) */
    #submit:hover{
        background-image: linear-gradient(to right, rgb(184, 54, 37), rgb(160, 148, 43)); /* Escurece o degradê */
    }

    /* Estilo do botão "Voltar" (ancora estilizada como botão) */
    .come-back{
        display: block;  /* Faz a tag <a> se comportar como bloco (para ter largura, altura, padding etc) */
        background-image: linear-gradient(to right, rgb(253, 51, 24), rgb(204, 185, 17)); /* Degradê igual ao submit */
        width: 60%; /* Largura quase total (com pequena margem para espaçamento) */
        border: none; /* Sem borda */
        text-decoration: none; /* Remove sublinhado de links */
        margin: 10px auto 0;
        padding: 10px; /* Espaçamento interno */
        color: white; /* Cor do texto */
        font-size: 15px; /* Tamanho da fonte */
        cursor: pointer; /* Cursor de clique */
        border-radius: 10px; /* Cantos arredondados */
        text-align: center; /* Centraliza o texto */
    }

    /* Efeito hover para o botão voltar */
    .come-back:hover{
        background-image: linear-gradient(to right, rgb(184, 54, 37), rgb(160, 148, 43)); /* Escurece o degradê */
    }

    /* Estilo do rodapé da página */
    footer {  
        width: 100%; /* Largura total */
        position: 100vh; /* ERRADO → Isso aqui deveria ser uma posição válida (exemplo: fixed, relative, etc). Corrigir! */
        background-image: url('./img/capa\ 01.jpg'); /* Caminho da imagem de fundo do rodapé */
        background-size: cover; /* Faz a imagem cobrir todo o espaço do rodapé */
        background-position: center; /* Centraliza a imagem horizontal e verticalmente */
        padding: 5px 3px; /* Espaçamento interno (top/bottom: 5px, sides: 3px) */
        text-align: center; /* Centraliza o texto */
        color: white; /* Cor do texto */
        z-index: 1; /* Camada de empilhamento */
        overflow: hidden; /* Impede que conteúdo ultrapasse os limites do rodapé */
        background-image: rgba(0, 0, 0, 0.6); /* CORREÇÃO: Esse comando é inválido! Aqui deveria ser: background-color: rgba(0, 0, 0, 0.6); */
    }

</style>
</head>
<body>

    <!-- Caixa principal que envolve todo o formulário -->
    <div class="box">

        <!-- Início do formulário de locação, método POST enviando para poloc.php -->
        <form id="form-locacao" action="poloc.php" method="POST">
           
            <!-- Fieldset delimita visualmente o formulário -->
            <fieldset>
                <!-- Título do formulário -->
                <legend><b>Formulário de locação - VOLKSWAGEN Polo</b></legend>
                <br><br>

                <!-- Campo oculto com a placa do veículo -->
                <input type="hidden" name="placa" value="GTR4D29">

                <!-- Campo oculto para o ID do cliente (será preenchido via JavaScript ou backend) -->
                <input type="hidden" name="Fk_cliente" id="Fk_cliente">

                <!-- Campo de Nome completo do cliente -->
                <div class="inputbox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome" class="labelInput">Nome completo do cliente</label>
                </div>
                <br>

                <!-- Campo de CPF -->
                <div class="inputbox">
                    <input type="text" name="cpf" id="cpf" class="inputUser" autocomplete="off" maxlength="14" required>
                    <label for="cpf" class="labelInput">CPF</label>
                </div>

                <!-- Campo de Data de nascimento -->
                <div class="inputbox">
                    <br>
                    <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                    <input type="text" name="data_nascimento" id="data_nascimento" class="inputUser" placeholder="dd/mm/aaaa" required onchange="verificarIdade()"> 
                </div>
                <br>

                <!-- Campo de Telefone -->
                <div class="inputbox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser" maxlength="20" required>
                    <label for="telefone" class="labelInput">Telefone</label>
                </div>
                <br>

                <!-- Campo de Endereço -->
                <div class="inputbox">
                    <input type="text" name="endereco" id="endereco" class="inputUser" required>
                    <label for="endereco" class="labelInput">Digite seu endereço</label>
                </div>
                <br>

                <!-- Campo oculto com uma senha fixa -->
                <input type="hidden" name="senha-login" value="6969">
               
                <!-- Campo de E-mail -->
                <div class="inputbox">
                    <input type="email" name="email" id="email" class="inputUser" required>
                    <label for="email" class="labelInput">Melhor E-mail</label>
                </div>

                <!-- Escolha de Gênero (radio buttons) -->
                <b><p>Gênero:</p></b>
                <input type="radio" id="feminino" name="genero" value="feminino" required>
                <label for="feminino">Feminino</label>
                <br>
                <input type="radio" id="masculino" name="genero" value="masculino" required>
                <label for="masculino">Masculino</label>
                <br>
                <input type="radio" id="outro" name="genero" value="outro" required>
                <label for="outro">Outro</label>
                <br><br>

                <!-- Campo de CNH (Número da carteira de habilitação) -->
                <div class="inputbox">
                    <input type="text" name="cnh" id="cnh" class="inputUser" maxlength="11" required>
                    <label for="cnh" class="labelInput">CNH</label>
                </div>
                
                <!-- Valor da diária (oculto, já fixo) -->
                <input type="hidden" id="diaria" name="diaria" value="84.00" />

                <!-- Exibição do valor total (calculado via JS) -->
                <p id="valorBruto" name="valor_total">Valor total da locação: R$ 0,00</p>

                <!-- Exibição dos dias de locação -->
                <p id="diasLocacao">Dias de locação: 0</p>

                <!-- Campo oculto para o valor total (enviado ao PHP) -->
                <input type="hidden" name="valor_total" id="valor_total">

                <!-- Data de locação -->
                <div class="inputbox">
                    <label for="start-date" class="labelInput"><strong>Data de locação:</strong></label>
                    <br>
                    <input type="text" name="start-date" id="start-date" class="inputUser" required>  
                </div>
                <br>

                <!-- Data de devolução -->
                <div class="inputbox">
                    <label for="end-date" class="labelInput"><strong>Data de devolução:</strong></label>
                    <br>
                    <input type="text" name="end-date" id="end-date" class="inputUser" required>
                </div>
                <br>

                <!-- Escolha da cidade de retirada do veículo -->
                <div class="inputbox">
                    <label for="cidade-retirada" class="labelInput" id="cidade-retirada"><b>Escolha a cidade de retirada:</b></label>
                    <br><br><br>
                    <select id="cidade-retirada" name="cidade-retirada">
                        <option value="samambaia">Samambaia</option>
                        <option value="asa-sul">Asa Sul</option>
                        <option value="ceilandia">Ceilândia</option>
                        <option value="taguatinga">Taguatinga</option>
                        <option value="candangolandia">Candangolândia</option>
                    </select>
                </div>
                <br><br>

                <!-- Botão de envio do formulário -->
                <input type="submit" name="submit" id="submit" onclick="mostrarPopup()" value="Enviar">
                <br><br>

                <!-- Link para voltar à página inicial -->
                <a class="come-back" href="index.html">Voltar</a>
                <br>

            </fieldset>
        </form>
    </div>
    <br>

    <!-- Rodapé da página -->
    <footer>
        <p>© Todos os direitos reservados. Desenvolvimento de sites JIDR LOCAR</p>
    </footer>

</body>

    
        <script>
            const cpfInput = document.getElementById('cpf');  // Pega o campo de input do CPF
            cpfInput.addEventListener('keypress', () => {  // Adiciona um evento toda vez que o usuário digitar uma tecla dentro do campo CPF
            let cpfInputLenght = cpfInput.value.length  // Conta quantos caracteres já foram digitados no campo

                if (cpfInputLenght === 3 || cpfInputLenght === 7 ){  // Quando tiver 3 ou 7 caracteres, coloca um ponto
                    cpfInput.value += '.'    
                } else if(cpfInputLenght === 11){  // Quando tiver 11 caracteres, coloca um traço
                    cpfInput.value += '-'
                }

                 console.log(cpfInputLenght);  // Mostra no console quantos caracteres tem até agora (só para debug)
            });

        
            const diaInicio = document.getElementById("start-date");  // Pega o campo da data de início da locação
            const diaFim = document.getElementById("end-date");  // Pega o campo da data de devolução
            const diaria = document.getElementById("diaria");  // Pega o campo escondido que contém o valor da diária
            const valorBruto = document.getElementById("valorBruto");  // Onde o valor total vai ser mostrado na tela
            const diasLocacao = document.getElementById("diasLocacao");  // Onde será mostrado a quantidade de dias alugados

            

            function parseDateBRtoDateObject(dataBR) {
              const partes = dataBR.split('/');  // Divide a data em dia, mês e ano
                 if (partes.length !== 3) return null;  // Se não tiver as 3 partes, retorna nulo (inválido)

              const dia = partes[0].padStart(2, '0');  // Garante que o dia tenha dois dígitos
              const mes = partes[1].padStart(2, '0');  // Garante que o mês tenha dois dígitos
              const ano = partes[2];  // Ano não precisa de ajuste

                return new Date(`${ano}-${mes}-${dia}`);  // Retorna a data no formato padrão do JavaScript (YYYY-MM-DD)
            };

            function calcularValorTotal(){
                if(diaInicio.value && diaFim.value && diaria.value){  // Só faz o cálculo se todos os campos tiverem valor
                    const inicioloc = parseDateBRtoDateObject(diaInicio.value);  // Converte a data de início
                    const fimloc = parseDateBRtoDateObject(diaFim.value);  // Converte a data de fim
                    const totalDias = (fimloc - inicioloc) / (1000 * 60 * 60 * 24);  // Calcula a diferença em dias (conversão de milissegundos)

                if(totalDias > 0 ){  // Só calcula se o número de dias for positivo
                    const textoDias = totalDias === 1 ? "1 dia" : `${totalDias} dias`;  // Ajusta o texto se for singular ou plural
                    diasLocacao.innerText = `Dias de locação: ${textoDias}`;  // Mostra o número de dias na tela

                    const total = totalDias * parseFloat(diaria.value.replace(",", "."));  // Multiplica os dias pela diária (convertendo para número)
                    valorBruto.innerText = `Valor total da locação: R$ ${total.toFixed(2)}`;  // Mostra o valor total na tela

                    document.getElementById("valor_total").value = total.toFixed(2);  // Envia o valor total para o campo hidden, para ser enviado no formulário
                } else {
                    valorBruto.innerText = `Digite uma data válida!`;  // Se a data estiver errada, avisa o usuário
                    diasLocacao.innerText = `Dias de locação: 0`;  // Mostra zero dias

                    document.getElementById("valor_total").value = "";  // Limpa o valor total enviado ao PHP
            }
        }
    }


                diaInicio.addEventListener("change", calcularValorTotal);  // Sempre que a data de início mudar
                diaFim.addEventListener("change", calcularValorTotal);  // Sempre que a data de fim mudar


                // Função para converter datas do formato "dd/mm/yyyy" para um objeto Date que o JavaScript entenda
                function parseDateBR(dataBR) {
                const partes = dataBR.split('/');  // Quebra a data em partes: dia, mês, ano
                    if (partes.length !== 3) return null;  // Se não tiver 3 partes, é uma data inválida

                    const dia = partes[0];
                    const mes = partes[1] - 1;  // Em JavaScript os meses vão de 0 a 11 (Janeiro = 0)
                    const ano = partes[2];

                    return new Date(ano, mes, dia);  // Retorna a data no formato que o JS consegue trabalhar
                }

                function verificarIdade() {
                const nascimento = document.getElementById("data_nascimento").value;  // Pega o valor do input de nascimento
                const dataNasc = parseDateBR(nascimento);  // Converte a data usando a função acima

                // Verifica se a data é válida (se for NaN ou nula, dá erro)
                if (!dataNasc || isNaN(dataNasc.getTime())) {
                    alert("Por favor, insira uma data de nascimento válida!");
                return;  // Para a execução da função se a data for inválida
                }

                const hoje = new Date();  // Pega a data de hoje
                let idade = hoje.getFullYear() - dataNasc.getFullYear();  // Calcula a diferença de anos
                const m = hoje.getMonth() - dataNasc.getMonth();  // Verifica diferença de meses

                // Ajusta a idade se o mês atual ainda não tiver chegado ou se o dia do mês ainda não tiver passado
                if (m < 0 || (m === 0 && hoje.getDate() < dataNasc.getDate())) {
                idade--;
                }

                // Verifica se a idade é menor que 18
                if (idade < 18) {
                    alert('Desculpa, mas você precisa ser maior de idade para alugar veículos nesse site!');
               } else {
                    alert('Idade válida, prossiga...');
            }
    }




         const id = Math.floor(10000 + Math.random() * 90000); //Gera um número entre 10000 e 99999
            document.getElementById('Fk_cliente').value = id; //Coloca esse ID no campo hidden do formulário
            console.log('ID gerado:', id); //Mostra o ID no console
        </script>

<!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Idioma Português -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
<!-- Configuração do Flatpickr -->
    <script>
        flatpickr("#data_nascimento", {
        dateFormat: "d/m/Y", //Formato Brasileiro
        locale: "pt", //Idioma Português
        allowInput: true //Permite o usuário digitar a data no campo de input
        });

        flatpickr("#start-date", {
        dateFormat: "d/m/Y",
        locale: "pt",
        allowInput: true
        });

        flatpickr("#end-date", {
        dateFormat: "d/m/Y",
        locale: "pt",
        allowInput: true
        });

            function mostrarPopup() { //Allert que dispara toda vez que uma reserva é feita
                alert('Reserva registrada com sucesso!');
                return true; // Retorna verdadeiro (permite o envio do formulário)
            }

         
        
    </script>

    

</body>
</html>