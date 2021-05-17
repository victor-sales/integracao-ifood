<?php

include_once('token.php');

$opcao = file_get_contents("php://input");
$opcao = json_decode($opcao, true);

switch ($opcao['opcao']) {
    case '1':
        listItems();
        break;
    
    default:
        # code...
        break;
}

function getMerchantId() {
    $header = [];
    $header[] = 'Authorization: Bearer '.lerToken();

    $url = "https://pos-api.ifood.com.br/v1.0/merchants";
    
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
        CURLOPT_HTTPHEADER => $header
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return $err;
    } else {
        $response = json_decode($response, true);
        // listProducts($response);
        return $response;
    }
}

function getCatalogId() {

    $merchants = getMerchantId();
    foreach ($merchants as $merchant) {
        
        $merchantId = $merchant['id'];
    }

    $header = array (
            
        'Authorization: Bearer '.lerToken(), 
        'Accept: application/json'
    );

    $url = "https://pos-api.ifood.com.br/catalog/v2.0/merchants/".$merchantId."/catalogs";
    
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
        CURLOPT_HTTPHEADER => $header
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return $err;
    } else {
        $response = json_decode($response, true);
        return $response;
    }
}

function listItems() {

    $header = array(
        'Authorization: Bearer '.lerToken(),
        'Content-Type: application/json'
    );

    $merchants = getMerchantId();
    $catalogs = getCatalogId();

    foreach ($merchants as $merchant) {
        
        $merchantId = $merchant['id'];
    }

    foreach ($catalogs as $catalog) {
        
        $catalogid = $catalog['catalogId'];

        $url = "https://pos-api.ifood.com.br/catalog/v2.0/merchants/".$merchantId."/catalogs/".$catalogid."/categories";
    
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
            CURLOPT_HTTPHEADER => $header
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo $err;
        } else {
            echo $response;
        }
    }

}