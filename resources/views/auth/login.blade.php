@extends('layouts.app')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b style="color:white;">Admin</b></a>
        </div>
        <div class="card card-outline card-dark bg-dark text-white">
            <div class="card-body login-card-body bg-dark">
                <p class="login-box-msg text-white">Войдите, чтобы начать сессию</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" placeholder="Email" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text bg-dark border-secondary text-white">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="Пароль" required>
                        <div class="input-group-append">
                            <div class="input-group-text bg-dark border-secondary text-white">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="icheck-dark">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember" class="text-white">Запомнить</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-outline-light btn-block">Войти</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
