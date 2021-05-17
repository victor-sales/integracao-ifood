<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="estilo.css">
    <title>Ifood</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid" style="color: white">
            <h3 class="navbar-brand" href="#">Ifood Demo</h3>
            <div class="navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="pedidos.php">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="cardapios.php">Cardapio</a>
                    </li>
                </ul>
            </div>
            <div class="float-end">
                <button class="btn btn-light" disabled><i class="fas fa-sync"> <span id="contador"><span></i></button>
            </div>
        </div>
    </nav> 
     
    <div class="container-fluid">
        <div id="pedidos" class="row">

        </div>
        
    </div>

    <div id="modal-motivo-cancelamento" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Motivo do Cancelamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                    <select class="form-select" name="motivo-cancelamento" id="motivo-cancelamento">
                        <option value="" selected disabled>Motivo...</option>
                        <option id="501" value="501">PROBLEMAS DE SISTEMA</option>
                        <option id="502" value="502">PEDIDO EM DUPLICIDADE</option>
                        <option id="503" value="503">ITEM INDISPONÍVEL</option>
                        <option id="504" value="504">RESTAURANTE SEM MOTOBOY</option>
                        <option id="505" value="505">CARDÁPIO DESATUALIZADO</option>
                        <option id="506" value="506">PEDIDO FORA DA ÁREA DE ENTREGA</option>
                        <option id="507" value="507">CLIENTE GOLPISTA / TROTE</option>
                        <option id="508" value="508">FORA DO HORÁRIO DO DELIVERY</option>
                        <option id="509" value="509">DIFICULDADES INTERNAS DO RESTAURANTE</option>
                        <option id="511" value="511">ÁREA DE RISCO</option>
                        <option id="512" value="512">RESTAURANTE ABRIRÁ MAIS TARDE</option>
                        <option id="513" value="513">RESTAURANTE FECHOU MAIS CEDO</option>
                        <option id="803" value="803">ITEM INDISPONÍVEL</option>
                        <option id="805" value="805">RESTAURANTE SEM MOTOBOY</option>
                        <option id="801" value="801">PROBLEMAS DE SISTEMA</option>
                        <option id="804" value="804">CADASTRO DO CLIENTE INCOMPLETO - CLIENTE NÃO ATENDE</option>
                        <option id="807" value="807">PEDIDO FORA DA ÁREA DE ENTREGA</option>
                        <option id="808" value="808">CLIENTE GOLPISTA / TROTE</option>
                        <option id="809" value="809">FORA DO HORÁRIO DO DELIVERY</option>
                        <option id="815" value="815">DIFICULDADES INTERNAS DO RESTAURANTE</option>
                        <option id="818" value="818">TAXA DE ENTREGA INCONSISTENTE</option>
                        <option id="820" value="820">ÁREA DE RISCO</option>
                    </select>
                    <label for="motivo-cancelamento">Selecione o motivo do cancelamento</label>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button id="confirmar-cancelar-pedido" type="button" class="btn btn-primary">Enviar</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/5583d7f484.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="javascript/pedidos.js"></script>
</body>
</html>