@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col m8 offset-m2 s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">{{ __('Register') }}</span>
                    <div class="row">
                        <form class="col s12" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="name" type="text" class="validate" name="name" value="{{ old('name') }}" maxlength="35" required autofocus>
                                    <label for="name" data-error="Rellene este campo." data-success="Campo validado.">Nombre completo</label>
                                </div>

                                <div class="input-field col s12">
                                    <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" maxlength="35" required>
                                    <label for="email" data-error="Verifique este campo." data-success="Campo validado.">Email</label>
                                </div>

                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate" name="password" pattern=".{6,}" required>
                                    <label for="password" data-error="Verifique este campo." data-success="Campo validado.">Contraseña</label>
                                </div>

                                <div class="input-field col s12">
                                    <input id="password-confirm" type="password" class="validate" name="password_confirmation" pattern=".{6,}" required>
                                    <label for="password-confirm" data-error="Verifique este campo." data-success="Campo validado.">Confirma tu contraseña</label>
                                </div>

                                <div class="input-field col s12">
                                    <button type="submit" class="btn btn-secondary">
                                        {{ __('Registrarme') }}
                                    </button>
                                </div>

                                <div class="input-field col s12">
                                    <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">{{ __('Inicia sesión') }}</a></p>
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
