window.onload = function () {
    polling();
    timer();
    pollingAutomatico();
    botoesDetalhePedido();
}

const btn_confirmar_cancelar_pedido = document.getElementById('confirmar-cancelar-pedido');

btn_confirmar_cancelar_pedido.addEventListener('click', function(){

    const id = this.getAttribute('flag-id');
    const correlationId = this.getAttribute('flag-correlationid');
    const cod_cancelamento = document.getElementById('motivo-cancelamento').value;
    const detalhe_cancelamento = document.getElementById(cod_cancelamento).textContent;

    console.log(id+' '+correlationId+' '+cod_cancelamento+' '+detalhe_cancelamento);

    const body = {
        opcao: '5',
        id: id,
        reference: correlationId,
        cod_cancelamento: cod_cancelamento,
        detalhe_cancelamento: detalhe_cancelamento
    }

    const jsonbody = JSON.stringify(body);
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr);
        }
    }

    xhr.open("POST", "funcoes/pedido.php");
    xhr.send(jsonbody);
    
});


function gerarToken() {
    const body = {
        opcao: "gerar-token"
    }

    const jsonbody = JSON.stringify(body);

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {
            polling();
        }
    }

    xhr.open("POST", "funcoes/token.php");
    xhr.send(jsonbody);
}

function timer() {
    const contador = document.getElementById('contador');
    var t = 30
    setInterval(() => {
        contador.innerText = t;
        t--;
        if (t <= 0) {
            t = 30;
        }
    }, 1000);
}

function polling() {
    const body = {
        opcao: "1"
    }

    const jsonbody = JSON.stringify(body);

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {

            var html = "";

            if (xhr.responseText === "") {
                console.log(xhr);
                html += "Ainda não há novos pedidos";
                document.getElementById('pedidos').innerHTML = html;
                return false;

            }

            var response = JSON.parse(xhr.responseText);

            if (response.hasOwnProperty('message')) {
                gerarToken();

            } else {
                console.log(response);
                for (var i = 0; i < response.length; i++) {

                    if (response[i]["code"] == "PLACED") {
                        
                        response[i]["code"] = "Novo Pedido";

                        html += '<div class="col-4">'
                        html += '<div class="card shadow p-3 mb-5 bg-body rounded">';
                        html += '<div class="card-body">';
                        html += '<h3>' + response[i]["code"] + '</h3>';
                        html += '<hr>';
                        html += '<ul class="list-group list-group-flush">'

                        for (var j = 0; j < response[i][0]['items'].length; j++) {

                            html += '<li class="list-group-item"><span>' + response[i][0]['items'][j]['quantity'] + 'x<span> ' + response[i][0]['items'][j]['name'] + '</li>'

                        }

                        html += '</ul>'
                        html += '</br>'
                        html += '<div class="row">';
                        html += '<div class="col-6 d-grid gap-2">';
                        html += '<button flag-correlationid="' + response[i][0]['reference'] + '" flag-id="' + response[i]['id'] + '" class="btn btn-block btn-danger cancelar acao-pedido" disabled>Recusar</button>';
                        html += '</div>';
                        html += '<div class="col-6 d-grid gap-2">';
                        html += '<button flag-correlationid="' + response[i][0]['reference'] + '" flag-id="' + response[i]["id"] + '" class="btn btn-block btn-success confirmar acao-pedido" disabled>Confirmar</button>'
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                    } else if (response[i]["code"] == "CONFIRMED") {
                        
                        response[i]["code"] = "Pedido Confirmado";

                        html += '<div class="col-4">'
                        html += '<div class="card shadow p-3 mb-5 bg-body rounded">';
                        html += '<div class="card-body">';
                        html += '<h3>' + response[i]["code"] + '</h3>';
                        html += '<hr>';
                        html += '<ul class="list-group list-group-flush">'

                        for (var j = 0; j < response[i][0]['items'].length; j++) {

                            html += '<li class="list-group-item"><span>' + response[i][0]['items'][j]['quantity'] + 'x<span> ' + response[i][0]['items'][j]['name'] + '</li>'

                        }

                        html += '</ul>'
                        html += '</br>'
                        html += '<div class="row">';
                        html += '<div class="col-12 d-grid gap-2">';
                        html += '<button flag-correlationid="' + response[i][0]['reference'] + '" flag-id="' + response[i]["id"] + '" class="btn btn-block btn-success despachar acao-pedido" disabled>Despachar</button>'
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                    } else {
                        var id = response[i]["id"];
                        acknowledgmentAutomatico(id);
                    }
                }

                document.getElementById('pedidos').innerHTML = html;
            }
        }
    }

    xhr.open("POST", "funcoes/pedido.php");
    xhr.send(jsonbody);
}

function pollingAutomatico() {
    // Atualização de pedidos a casa 30 segundos
    setInterval(() => {
        polling();
        botoesDetalhePedido();
    }, 30000);
}

function botoesDetalhePedido() {
    setTimeout(() => {
        if (document.readyState === "complete") {

            var detalhes_pedido = document.getElementsByClassName('acao-pedido');

            for (var i = 0; i < detalhes_pedido.length; i++) {
                detalhes_pedido[i].disabled = false;
                detalhes_pedido[i].addEventListener("click", function () {
                    var id = this.getAttribute('flag-id');
                    var correlationId = this.getAttribute('flag-correlationid');

                    if (this.classList.contains('confirmar')) {
                        confirmarPedido(id, correlationId);
                    } else if (this.classList.contains('despachar')) {
                        despacharPedido(id, correlationId);
                    } else {
                        prepararCancelarPedido(id, correlationId);
                    }
                });
            }
        }
    }, 3000);
}

function confirmarPedido(id, correlationId) {
    const body = {
        opcao: "2",
        id: id,
        reference: correlationId
    }

    const jsonbody = JSON.stringify(body);

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr);
        }
    }

    xhr.open("POST", "funcoes/pedido.php");
    xhr.send(jsonbody);
}

function prepararCancelarPedido(id, correlationId) {

    const modal_cancelar_pedido = new bootstrap.Modal(document.getElementById('modal-motivo-cancelamento'));

    btn_confirmar_cancelar_pedido.setAttribute("flag-id", id);
    btn_confirmar_cancelar_pedido.setAttribute("flag-correlationid", correlationId);

    modal_cancelar_pedido.show();
}

function despacharPedido(id, correlationId) {
    const body = {
        opcao: "4",
        id: id,
        reference: correlationId
    }

    const jsonbody = JSON.stringify(body);
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr);
        }
    }

    xhr.open("POST", "funcoes/pedido.php");
    xhr.send(jsonbody);
}

function acknowledgmentAutomatico(id) {
    const body = {
        opcao: "3",
        id: id,
    }

    const jsonbody = JSON.stringify(body);

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr);
        }
    }

    xhr.open("POST", "funcoes/pedido.php");
    xhr.send(jsonbody);
}

