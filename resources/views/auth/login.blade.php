@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col m8 offset-m2 s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">{{ __('Login') }}</span>
                    <div class="row">
                        <form class="col s12" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" maxlength="35" required>
                                    <label for="email" data-error="Verifique este campo." data-success="Campo validado.">Email</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate" name="password" pattern=".{6,}" required>
                                    <label for="password" data-error="Verifique este campo." data-success="Campo validado.">Contraseña</label>
                                </div>
                                <div class="col s12">
                                    <button type="submit" class="btn btn-primary">
                                            {{ __('Iniciar Sesión') }}
                                    </button>
                                </div>

                                <div class="input-field col s12">
                                    <p>¿Has olvidado tu contraseña? <a href="{{ route('password.request') }}">{{ __('Reestablecer mi contraseña')}}</a></p>
                                    <p>¿Aún no eres usuario? <a href="{{ route('register') }}">{{ __('Crea una cuenta') }}</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
