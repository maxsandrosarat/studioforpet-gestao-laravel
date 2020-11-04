<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Studio For Pet</title>
    <link rel="shortcut icon" href="/storage/favicon.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container-xl">
            <header>
                @component('components.componente_navbar', ["current"=>$current ?? ''])
                @endcomponent
            </header>
            <main>
                @hasSection ('body')
                    @yield('body')   
                @endif
            </main>
            @component('components.componente_footer')
            @endcomponent
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function id(campo){
            return document.getElementById(campo);
        }

        //view(auth/register)
        function validarSenhaForca(){
            var senha = id('senhaForca').value;
            var forca = 0;
            if((senha.length >= 4) && (senha.length <= 8)){
                forca += 10;
            }else if(senha.length > 8){
                forca += 25;
            }
            if((senha.length >= 5) && (senha.match(/[a-z]+/))){
                forca += 10;
            }
            if((senha.length >= 6) && (senha.match(/[A-Z]+/))){
                forca += 20;
            }
            if((senha.length >= 7) && (senha.match(/[@#$%&;*]/))){
                forca += 25;
            }
            if(senha.match(/([1-9]+)\1{1,}/)){
                forca += -25;
            }
            mostrarForca(forca);
        }
        
        function mostrarForca(forca){
        
            if(forca < 30 ){
                id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }else if((forca >= 30) && (forca < 50)){
                id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }else if((forca >= 50) && (forca < 70)){
                id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }else if((forca >= 70) && (forca < 100)){
                id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }
        }
        
        //view(auth/login)
        function mostrarSenha(){
            var tipo = id('senha');
            if(tipo.type=="password"){
                tipo.type = "text";
                id('icone-senha').innerHTML = "visibility_off";
                id('botao-senha').className = "btn btn-warning btn-sm";
                id('botao-senha').title = "Ocultar Senha";
            } else {
                tipo.type = "password";
                id('icone-senha').innerHTML = "visibility";
                id('botao-senha').className = "btn btn-success btn-sm";
                id('botao-senha').title = "Mostrar Senha";
            }
        }

        //view(vendas/venda_produtos)
        function valorProduto(){
            var d = id('produto');
            var displaytext = d.options[d.selectedIndex].title;
            id('valor').value = displaytext;
        }

        //view(vendas/venda_servicos)
        function valorServico(){
            var d = id('servico');
            var displaytext = d.options[d.selectedIndex].title;
            id('valor').value = displaytext;
        }

        //view(cadastro/pets)
        function valorPlanoPet(){
            var d = id('plano');
            var displaytext = d.options[d.selectedIndex].title;
            id('valorPlano').value = displaytext;
        }

        function valorPlanoPetE(){
            var d = id('planoE');
            var displaytext = d.options[d.selectedIndex].title;
            id('valorPlanoE').value = displaytext;
        }

        $(document).ready(function(){
            //OPÇÕES DE PLANOS PARA PET (CADASTRO)
            $('#principalSelect').children('div').hide();
            $('#selectGeral').on('change', function(){
                
                var selectValorGeral = '#'+$(this).val();
                $('#principalSelect').children('div').hide();
                $('#principalSelect').children(selectValorGeral).show();
                if($(this).val() == 0){
                    id('qtdParcelas').removeAttribute("required");
                    id('valorParcela').removeAttribute("required");
                }
            });

            //OPÇÕES DE PLANOS PARA PET (EDITAR)
            $('#principalSelectPlano').children('div').hide();
            $('#selectPlano').on('change', function(){
                
                var selectValorGeral = '#'+$(this).val();
                $('#principalSelectPlano').children('div').hide();
                $('#principalSelectPlano').children(selectValorGeral).show();

            });

        });


        function getValor(campo){
            var valor = id(campo).value.replace(',','.');
            id(campo).value = parseFloat(valor);
        }

        function formataNumeroTelefone() {
            var numero = id('telefone').value;
            var length = numero.length;
            var telefoneFormatado;
            
            if (length == 10) {
            telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 6) + '-' + numero.substring(6, 10);
            } else if (length == 11) {
            telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 7) + '-' + numero.substring(7, 11);
            } else {
                id('telefone').value=("");
                alert("Número Inválido, digite número com DDD.");
            }
            id('telefone').value = telefoneFormatado;
        }

        function formataNumeroTelefoneNovo() {
            var numero = id('telefoneNovo').value;
            var length = numero.length;
            var telefoneFormatado;
            
            if (length == 10) {
            telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 6) + '-' + numero.substring(6, 10);
            } else if (length == 11) {
            telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 7) + '-' + numero.substring(7, 11);
            } else {
                id('telefoneNovo').value=("");
                alert("Número Inválido, digite número com DDD.");
            }
            id('telefoneNovo').value = telefoneFormatado;
        }

        function formatarCpf() {
            var numero = id('cpf').value;
            var length = numero.length;
            var cpfFormatado;
            
            if (length == 11) {
                cpfFormatado = numero.substring(0, 3) + '.' + numero.substring(3, 6) + '.' + numero.substring(6, 9) + '-' + numero.substring(9, 11);
            } else {
                id('cpf').value=("");
                alert("CPF inválido, digite os 11 números.");
            }
            id('cpf').value = cpfFormatado;
        }

        function formatarCpfE() {
            var numero = id('cpfE').value;
            var length = numero.length;
            var cpfFormatado;
            
            if (length == 11) {
                cpfFormatado = numero.substring(0, 3) + '.' + numero.substring(3, 6) + '.' + numero.substring(6, 9) + '-' + numero.substring(9, 11);
            } else {
                id('cpfE').value=("");
                alert("CPF inválido, digite os 11 números.");
            }
            id('cpfE').value = cpfFormatado;
        }
    
        function limpa_formulário_cep() {
                //Limpa valores do formulário de cep.
                id('rua').value=("");
                id('bairro').value=("");
                id('cidade').value=("");
                id('uf').value=("");
                id('ibge').value=("");
        }
    
        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                id('rua').value=(conteudo.logradouro);
                id('bairro').value=(conteudo.bairro);
                id('cidade').value=(conteudo.localidade);
                id('uf').value=(conteudo.uf);
                id('ibge').value=(conteudo.ibge);
            } //end if.
            else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }
            
        function pesquisacep(valor) {
    
            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');
    
            //Verifica se campo cep possui valor informado.
            if (cep != "") {
    
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;
    
                //Valida o formato do CEP.
                if(validacep.test(cep)) {
    
                    //Preenche os campos com "..." enquanto consulta webservice.
                    id('rua').value="...";
                    id('bairro').value="...";
                    id('cidade').value="...";
                    id('uf').value="...";
                    id('ibge').value="...";
    
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
    
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
    
                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                    var numero = id('cep').value;
                    var length = numero.length;

                    if(length == 8){
                        id('cep').value = numero.substring(0, 5) + '-' + numero.substring(5, 8);
                    }
    
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        };


        function limpa_formulário_cepE() {
            //Limpa valores do formulário de cep.
            id('ruaE').value=("");
            id('bairroE').value=("");
            id('cidadeE').value=("");
            id('ufE').value=("");
            id('ibgeE').value=("");
    }

    function meu_callbackE(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            id('ruaE').value=(conteudo.logradouro);
            id('bairroE').value=(conteudo.bairro);
            id('cidadeE').value=(conteudo.localidade);
            id('ufE').value=(conteudo.uf);
            id('ibgeE').value=(conteudo.ibge);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cepE();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacepE(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                id('ruaE').value="...";
                id('bairroE').value="...";
                id('cidadeE').value="...";
                id('ufE').value="...";
                id('ibgeE').value="...";
                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callbackE';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

                var numero = id('cepE').value;
                var length = numero.length;

                if(length == 8){
                    id('cepE').value = numero.substring(0, 5) + '-' + numero.substring(5, 8);
                }

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cepE();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cepE();
        }
    };
    </script>
</body>
</html>
