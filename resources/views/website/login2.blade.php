@extends('website.index')
@section('content')

    <!-- Login -->
    <section id="login" class="padding">

        <div class="container">

            @include('admin.error')
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="profile-login">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">{{ __('admin.login') }}</a></li>
                            <li role="presentation"><a href="#register" aria-controls="profile" role="tab" data-toggle="tab">{{ __('admin.Register') }}</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content padding_half">
                            <div role="tabpanel" class="tab-pane fade in active" id="home">
                                <div class="agent-p-form">
                                    <form action="{{ url('check_login') }}" class="callus clearfix" method="post">
                                        {{ csrf_field() }}
                                        <div class="single-query form-group col-sm-12">
                                            <input type="email" class="keyword-input" placeholder="{{ __('admin.email') }}" name="email">
                                        </div>
                                        <div class="single-query form-group  col-sm-12">
                                            <input type="password" class="keyword-input" placeholder="{{ __('admin.password') }}" name="password">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-6">
                                                    <div class="search-form-group white form-group text-left">
                                                        <div class="check-box-2"><i><input type="checkbox" name="check-box"></i></div>
                                                        <span>{{ __('admin.Remember Me') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 text-right">
                                                    <a href="#" class="lost-pass" style="color: #fff;">{{ __('admin.Lost your password?') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-sm-12">
                                            <input type="submit" value="{{ __('admin.submit') }}" class="hub-btn btn-slide border_radius">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="register">
                                <div class="agent-p-form">
                                    <form class="callus clearfix" action="{{ url('add_lead') }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="col-xs-6 form-group" name="prefix">
                                            <select  data-placeholder="Prefix Name" style="width: 90%">
                                                <option disabled></option>
                                                <option >{{ __('admin.mr') }}</option>
                                                <option >{{ __('admin.mrs') }}</option>
                                                <option >{{ __('admin.ms') }}</option>

                                            </select>
                                        </div>
                                        <div class="col-xs-6 form-group">
                                            <input type="text" class="keyword-input" name="fName" placeholder="{{ __('admin.first_name') }}" required>
                                        </div>
                                        <div class="col-xs-6 form-group">
                                            <input type="text" class="keyword-input" name="mName" placeholder="{{ __('admin.middle_name') }}">
                                        </div>
                                        <div class="col-xs-6 form-group">
                                            <input type="text" class="keyword-input" name="lName"placeholder="{{ __('admin.last_name') }}" required>
                                        </div>
                                        <div class=" single-query col-sm-12 form-group">
                                            <input type="text" class="keyword-input" placeholder="{{ __('admin.phone') }}" name="phone" required>
                                        </div>
                                        {{--<div class=" single-query col-sm-12 form-group social-phone">--}}
                                            {{--<div class="search-form-group white col-sm-4 form-group text-left">--}}
                                                {{--<div class="check-box-2"><i><input type="checkbox" name="check-box" ></i></div>--}}
                                                {{--<span>Viber</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="search-form-group white col-sm-4 form-group text-left">--}}
                                                {{--<div class="check-box-2"><i><input type="checkbox" name="check-box"></i></div>--}}
                                                {{--<span>Whats App</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="search-form-group white col-sm-4 form-group text-left">--}}
                                                {{--<div class="check-box-2"><i><input type="checkbox" name="check-box"></i></div>--}}
                                                {{--<span>SMS</span>--}}
                                            {{--</div>--}}

                                        {{--</div>--}}
                                        <div class="single-query col-sm-12 form-group">
                                            <input type="text" class="keyword-input" name="email" placeholder="{{ __('admin.email') }}">
                                        </div>
                                        <div class="single-query col-sm-12 form-group">
                                            <input type="password" class="keyword-input" placeholder="{{ __('admin.password') }}" name="password">
                                        </div>
                                        <div class="single-query col-sm-12 form-group">
                                            <input type="password" class="keyword-input" placeholder="{{ __('admin.confirm_password') }}" name="passwordConfirm">
                                        </div>
                                        {{--<div class="search-form-group white col-sm-12 form-group text-left">--}}
                                            {{--<div class="check-box-2"><i><input type="checkbox" name="check-box"></i></div>--}}
                                            {{--<span>Receive Newsletter</span>--}}
                                        {{--</div>--}}
                                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                            <div class="query-submit-button">
                                                <input type="submit" value="{{ __('admin.Create an Account') }}" class="btn-slide">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login end -->
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection