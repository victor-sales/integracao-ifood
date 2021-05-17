<?php

$header = array("Content-Type: multipart/form-data");

$opcao = file_get_contents("php://input");

$opcao = json_decode($opcao, true);

switch ($opcao['opcao']) {
    case 'gerar-token':
        gerarToken($header);
        break;
    default:
        # code...
        break;
}

function gerarToken($header) {
    $client_id = "Nome Integradora";
    $client_secret = "Secret";
    $grant_type = "password";
    $username = "username";
    $password = "password";
    
    $body = array(
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "grant_type" => $grant_type,
        "username" => $username,
        "password" => $password
    );
    
    $jsonbody = json_encode($body);
    $url = "https://pos-api.ifood.com.br/oauth/token";
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $header
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo $err;
    } else {
        $response = json_decode($response, true);
        gravarToken($response);
    }
}

function gravarToken($response) {

    $novo_token = $response['access_token'];

    $arquivo = fopen("../token.txt", "r+");
    
    while(!feof($arquivo)){

        $conteudo = fgets($arquivo);     
    }

    $token = explode(":", $conteudo);

    if ($token[1] != $novo_token) {

        $novo_token = 'token:'.$novo_token;
        
        file_put_contents("../token.txt", $novo_token);
    }
}

function lerToken() {
    
    $arquivo = fopen("../token.txt", "r");
    
    while(!feof($arquivo)){

        $conteudo = fgets($arquivo);        
    }

    $token = explode(":", $conteudo);
    return $token[1];
}
