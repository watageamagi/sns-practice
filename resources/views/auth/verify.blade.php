{{--@extends('layouts.app')--}}

@section('content')
{{--    @component('layouts.default')--}}
{{--        @slot('content')--}}
            <div class="d-flex justify-content-center align-items-center sp-register-card">
                <div class="normal-form">
                    <div class="register-card">
                        <h2 class="register-card_title">メール認証が未完了です</h2>

                        <div class="card-body text-center">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('メールの送信が完了しました') }}
                                </div>
                            @endif

                            {{ __('登録したメールアドレスにメールがきていないか確認してください。') }}<br>
                            {{ __('メールが届いていない場合はこちら') }}<br>
                            <form class="d-inline d-flex justify-content-center" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="register-btn">{{ __('再送信') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
{{--        @endslot--}}
{{--    @endcomponent--}}
@endsection

