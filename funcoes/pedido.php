<?php

include_once('token.php');

$opcao = file_get_contents("php://input");
$opcao = json_decode($opcao, true);

switch ($opcao['opcao']) {
    case '1':
        eventsPolling();
        break;
    case '2':
        $id = $opcao['id'];
        $reference = $opcao['reference'];

        if (acknowledgment($id) == "") {
           
           if (confirmIntegration($reference) == ""){

                orderConfirmation($reference);
           }
        }
        break;
    case '3':
        $id = $opcao['id'];
        acknowledgment($id);
        break;
    case '4':
        $id = $opcao['id'];
        $reference = $opcao['reference'];

        if (acknowledgment($id) == "") {
           
            dispatchOrder($reference);

         }
        break;
    case '5':
        $id = $opcao['id'];
        $reference = $opcao['reference'];
        $codido = $opcao['cod_cancelamento'];
        $detalhe = $opcao['detalhe_cancelamento'];

        if (acknowledgment($id) == "") {
           
            cancelOrder($reference, $codido, $detalhe);
        }
        break;
    default:
        # code...
        break;
}

///// ------------------- CAPTURA DE NOVOS EVENTOS /////////////
function eventsPolling() {

    $header = [];
    $header[] = 'Authorization: Bearer '.lerToken();

    $url = "https://pos-api.ifood.com.br/v3.0/events:polling";
    
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
        if ($response != "") {
            orderDetail($response);
        } else {
            echo $response;
        }
    }
}

function orderDetail($response) {
    
    
    $header = [];
    $header[] = 'Authorization: Bearer '.lerToken();

    $pedidos = [];
    
    $eventos = json_decode($response, true);

    foreach ($eventos as $evento) {

        if (isset($evento["correlationId"]) && $evento["correlationId"] != null) {
            $code = $evento["code"];
            $id = $evento['id'];
            $correlationId = $evento["correlationId"];

            $url = "https://pos-api.ifood.com.br/v3.0/orders/".$correlationId;
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
                $pedidos[] = $err;
            } else {
                $pedidos[] = [json_decode($response), 'code' => $code, 'id' => $id, 'correlationId' => $correlationId];
            }
        }
        
    }

    if ($pedidos != []) {
        echo json_encode($pedidos);
    } else {
        echo json_encode($eventos);
    }
}

///// ------------------- CAPTURA DE NOVOS EVENTOS /////////////


function acknowledgment($id) {

    $header = array(
        'Authorization: Bearer '.lerToken(),
        'Cache-Control: no-cache',
        'Content-Type: application/json',
    );

    $body = array (
        array (
            "id" => $id
        )
    );

    $url = "https://pos-api.ifood.com.br/v1.0/events/acknowledgment";
    
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
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_HTTPHEADER => $header
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

function confirmIntegration($reference) {
    
    $header = array(
        'Authorization: Bearer '.lerToken(),
    );

    $url = "https://pos-api.ifood.com.br/v1.0/orders/".$reference."/statuses/integration";

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
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => $header
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

function orderConfirmation($reference) {

    $header = [];
    $header[] = 'Authorization: Bearer '.lerToken();;

    $url = "https://pos-api.ifood.com.br/v1.0/orders/".$reference."/statuses/confirmation";
    
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
        CURLOPT_POSTFIELDS => "",
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

function dispatchOrder($reference) {

    $header = [];
    $header[] = 'Authorization: Bearer '.lerToken();

    $url = "https://pos-api.ifood.com.br/v1.0/orders/".$reference."/statuses/dispatch";
    
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
        CURLOPT_POSTFIELDS => "",
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

function cancelOrder($reference, $codigo, $detalhe) {
    $header = array(
        'Authorization: Bearer '.lerToken(),
        'Content-Type: application/json'
    );

    $url = "https://pos-api.ifood.com.br/v3.0/orders/".$reference."/statuses/cancellationRequested";

    $body = array (
        "cancellationCode" => $codigo,
        "details" => $detalhe
    );
    
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
        CURLOPT_POSTFIELDS => json_encode($body),
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