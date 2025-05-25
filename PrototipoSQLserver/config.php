<?php

$serverName = "localhost\\AULAS";
$connectionOptions = array(
    "Database" => "jidr_locar",
    "UID" => "",   // deixar em branco
    "PWD" => ""    // deixar em branco
);

$conexao = sqlsrv_connect($serverName, $connectionOptions);


if ($conexao === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    echo 'Parabéns! Conexão realizada com êxito!';
}
  
?>
