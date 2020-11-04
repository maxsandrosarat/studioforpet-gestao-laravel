<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <img src="/storage/brasao.png" alt="logo_forpet" width="100" class="d-inline-block align-top" loading="lazy">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav mr-auto">
            @auth("web")
            <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/home">Home</a>
            </li>
            <li @if($current=="vendas") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/vendas">Vendas</a>
            </li>
            <li @if($current=="lancamentos") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/lancamentos">Lançamentos</a>
            </li>
            <li @if($current=="cadastros") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/cadastros">Cadastros</a>
            </li>
            <li @if($current=="estoque") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/estoque">Estoque</a>
            </li>
            <li @if($current=="despesas") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/despesas">Despesas</a>
            </li>
            <li @if($current=="historicos") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/historicos">Históricos</a>
            </li>
            @endauth

            <!--DESLOGADO-->
            @guest
            <li @if($current=="login") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            
            @if (Route::has('register'))
            <li @if($current=="register") class="nav-item active" @else class="nav-item" @endif>
               <a class="nav-link" href="{{ route('register') }}">{{ __('Cadastre-se') }}</a>
           </li>
            @endif

            <!--LOGADO-->
            @else
            <!--LOGOUT-->
            <li class="nav-item dropdown" class="nav-item">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown" class="nav-item">
                @if(Auth::user()->foto!="")
                <img style="border-radius: 20px;" src="/storage/{{Auth::user()->foto}}" alt="foto_perfil" width="10%">
                @endif
            </li>
            @endguest
        </ul>
    </div>
  </nav>