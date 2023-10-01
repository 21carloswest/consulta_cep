<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de CEP</title>
</head>
<body>

<form action = 'consulta_cep_com_api.php' method = 'POST'>
    <input type = 'number' name = 'cep' required></input>
    <input type = 'submit' name = 'submit' value = 'Consultar'></input>
</form>


<?php
$cep = @$_POST['cep'];

//consulta a api se o número tem 8 dígitos e o POST foi enviado

if(isset($_POST['submit']) and strlen($cep) == 8){

    //concatena o link da API com o CEP enviado utilizando um JSON 
    $url = "viacep.com.br/ws/".$cep."/json/";

    //inicia o curl
    $ch = curl_init($url);

    //se falso, a string é printada automaticamente para o usuário final
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //verifica o certificado SSL da API
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    //executa o curl e a transforma o JSON em um array associativo
    $consult = json_decode(curl_exec($ch), true);

    //se o CEP tem o formato válido, mas não é encontrado na DB da API, ela retorna o valor "true"
    //a função in_array verifica se existe o valor true no formato boolean
    if(in_array(true, $consult, true)){ 
        echo "CEP não encontrado no banco de dados.";
    } else {

        //retira alguns valores do array que não são úteis na minha opinião
        $aftermath = array_diff_key($consult, ['gia' => '', 'ddd' =>'', 'siafi' => '']);

        //lista o array
        foreach ($aftermath as $key => $value) {
        echo $key . ": " . $value . "<br>";
    }
    }
} else {
    //se não for inserido um número com 8 dígitos
    echo 'Insira o CEP apenas com os números e com 8 dígitos.';
}
?>
</body>
</html>
