@extends('layouts.app')

@section('content')
    <style>
        @import "https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap";

        .update-pass-main {
            background-color: #eaeaea;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 25px;
            text-align: center;
        }

        .logo-pass {
            display: block;
            width: 100%;
            padding-bottom: 50px;
        }

        .update-body {
            background-color: #fff;
            padding: 30px;
        }

        .copy-right-bottom {
            background-color: #310f4c;
            padding: 10px 0;
        }

        .update-content h3 {
            font-family: 'Montserrat', sans-serif;
            font-weight: bold;
            text-transform: capitalize;
            font-size: 25px;
            line-height: normal;
            color: #000;
        }

        .update-content p {

            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            line-height: 20px;
            color: #000;
        }

        .update-form {
            background-color: #e3e3e3;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 35px;
            padding: 40px 10px;
            border-radius: 5px;
        }

        .update-form form {
            max-width: 300px;
            margin: 0 auto;
        }

        .update-form form input {
            border: 0;
            background-color: #fff;
            border-radius: 30px;
            text-indent: 0px;
            height: 35px;
            font-size: 12px;
            letter-spacing: 0;
            color: #000;
            width: 100%;
            outline: none;
            font-family: 'Montserrat', sans-serif;
            box-shadow: none !important;
            padding: 0 20px;
        }

        .update-form form .btn[type="submit"] {
            width: 100%;
            border: 0;
            background-color: #310f4c;
            color: #fff;
            text-transform: uppercase;
            font-size: 13px;
            height: 35px;
            border-radius: 30px;
            font-weight: 600;
            padding: 0;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            line-height: normal;
        }

        .copy-right-bottom p {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            line-height: 20px;
            color: #fff;
        }

        .thanks-content p {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            line-height: 20px;
            color: #000;
        }

        .border-line {
            height: 1px;
            width: 100px;
            background-color: #d0d0d0;
            margin: 0 auto 40px;
            display: block;
        }
    </style>

    @php
        use App\Models\SiteInfo;
        use App\Models\EmailTemplate;
        $siteinfo = SiteInfo::first();
        $emailContent = EmailTemplate::where('group','reset_password')->first();
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">



                <div class="update-pass-main">

                    <div class="logo-pass">
                        <a href="#"><img src="{{ env('AWS_URL').'public/storage/'.$siteinfo->logo }}" alt="{{ $siteinfo->logo_alt }}" /></a>
                    </div>

                    <div class="update-body">

                        <div class="update-content">
                            <h3>{{ __('Reset Password') }}</h3>
                            <p>{!! $emailContent->content !!}</p>
                        </div>

                        <div class="border-line"></div>

                        <div class="update-form">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="row mb-3">


                                    <div class="col-md-12">
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">


                                    <div class="col-md-12">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">


                                    <div class="col-md-12">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row mb-0 button">
                                    <div class="col-md-12 offset-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reset Password') }}
                                        </button>
                                    </div>
                                </div>
                            </form>




                        </div>

                        <div class="thanks-content">
                            <p>Thanks,<br>
                                The {!! $siteinfo->name !!} Team </p>
                        </div>



                    </div>
                    <div class="copy-right-bottom">
                        <p>{{ $siteinfo->copyright }}</p>
                    </div>


                </div>








                <!-- <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>--->

            </div>
        </div>
    </div>
@endsection
