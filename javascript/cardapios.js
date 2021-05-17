
window.onload = function (){
    cardapio();
}

function cardapio() {
    const body = {
        opcao: "1"
    }

    const jsonbody = JSON.stringify(body);
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var html = "";
            console.log(JSON.parse(xhr.responseText));
            var response = JSON.parse(xhr.responseText);

            html += '<table class="table">'
            html += '<thead>'
            html += '<tr>'
            html += '<th scope="col-8">Produto</th>'
            html += '<th scope="col-2">Preço</th>'
            html += '<th scope="col-2">#</th>'
            html += '</tr>'
            html += '</thead>'
            html += '<tbody>'

            for (var i = 0; i < response.length; i++) {
                if (response[i]['template'] !== 'PIZZA') {

                    for(var j = 0; j < response[i]['items'].length; j++) {

                        var nome = response[i]['items'][j]['name'];
                        var preco = response[i]['items'][j]['price']['value'];
                    
                        html += '<tr>'
                        html += '<td>'+nome+'</td>'
                        html += '<td>R$ '+preco+'</td>'
                        html += '<td><button class="btn btn-danger">Remover</button></td>'
                        html += '</tr>'
                    }
                }
            }
            html += '</tbody>'
            html += '</table>'
            
            document.getElementById('cardapio').innerHTML = html;
        }
    }

    xhr.open("POST", "funcoes/cardapio.php");
    xhr.send(jsonbody);
}

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