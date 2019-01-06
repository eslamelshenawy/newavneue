@extends('website/index')
@section('content')
    <!-- Profile Start -->
    <section id="agent-2-peperty" class="profile padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="f-p-links margin_bottom">
                        <li><a href="{{ url('profile') }}" class="active"><i class="icon-icons230"></i>{{ __('admin.profile') }}</a></li>
                        {{--<li><a href="{{ url('my_properties') }}"><i class="icon-icons215"></i> My Properties</a></li>--}}
                        <li><a href="{{ url('add_properties') }}"><i class="icon-icons215"></i> {{ __('admin.submit_property') }}</a></li>
                        <li><a href="{{ url('favourite_properties') }}"><i class="icon-icons43"></i> {{ __('admin.favorites') }}</a></li>
                        <li><a href="login.html"><i class="icon-lock-open3"></i>{{ __('admin.logout') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-3">
            <div class="row">
                <form class="callus" method="post" action="{{ url('profile_update') }}" enctype="multipart/form-data">

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <h2 class="text-uppercase bottom30">my profile</h2>
                    <div class="agent-p-img">
                        @if(isset($lead->image))
                        <img src="{{ url('uploads/'.$lead->image) }}" class="img-responsive" alt="image"/>
                        @else
                        <img src="{{ url('uploads/profile-pictures.png') }}" class="img-responsive" alt="image"/>
                        @endif
                            <div class="box">
                                <input type="file" name="image" id="file-1" class="inputfile inputfile-1"  style="display: none"/>
                                <label for="file-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choose a file&hellip;</span></label>
                            </div>
                        {{--<p class="text-center">Minimum 215px x 215px<span>*</span></p>--}}
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="profile-form">
                        <div class="row">
                                {{ csrf_field() }}
                                <div class="col-sm-4">
                                    <div class="single-query">
                                        <label>{{ __('admin.name') }}:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="single-query form-group">
                                        <input type="text" placeholder="{{ __('admin.first_name') }}" name="first_name" value="{{ $lead->first_name }}" class="keyword-input col-md-4">
                                    </div>
                                    <div class="single-query form-group">
                                        <input type="text" placeholder="{{ __('admin.middle_name') }}" name="middle_name" value="{{ $lead->middle_name }}" class="keyword-input col-md-4">
                                    </div>
                                    <div class="single-query form-group">
                                    <input type="text" placeholder="{{ __('admin.last_name') }}"  name="last_name"     value="{{ $lead->last_name }}" class="keyword-input col-md-4">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="single-query">
                                        <label>{{ __('admin.phone') }}:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="single-query form-group">
                                        <input type="text" placeholder="{{ __('admin.phone') }}" name="phone" value="{{ $lead->phone }}" class="keyword-input">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="single-query">
                                        <label>{{ __('admin.email_address') }}:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="single-query form-group">
                                        <input type="text" placeholder="{{ __('admin.email_address') }}" name="email" value="{{ $lead->email }}"class="keyword-input">
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 text-right ">
                                    <button type="submit" class="btn-blue hub-btn border_radius " style="width: 200px;">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                {{--<div class="col-md-5 col-sm-6 col-xs-12 profile-form margin40">--}}
                    {{--<h3 class="bottom30 margin40">My Social Network</h3>--}}
                    {{--<div class="row">--}}
                        {{--<form class="callus">--}}
                            {{--<div class="col-sm-4">--}}
                                {{--<div class="single-query">--}}
                                    {{--<label>Facebook:</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-8">--}}
                                {{--<div class="single-query form-group">--}}
                                    {{--<input type="text" placeholder="http://facebook.com" class="keyword-input">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-4">--}}
                                {{--<div class="single-query">--}}
                                    {{--<label>Twitter:</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-8">--}}
                                {{--<div class="single-query form-group">--}}
                                    {{--<input type="text" placeholder="http://twitter.com" class="keyword-input">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-4">--}}
                                {{--<div class="single-query">--}}
                                    {{--<label>Google Plus:</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-8">--}}
                                {{--<div class="single-query form-group">--}}
                                    {{--<input type="text" placeholder="http://google-plus.com" class="keyword-input">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-4">--}}
                                {{--<div class="single-query">--}}
                                    {{--<label>Linkedin:</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-8">--}}
                                {{--<div class="single-query form-group">--}}
                                    {{--<input type="text" placeholder="http://linkedin.com" class="keyword-input">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-12 col-sm-12 col-xs-12 text-right">--}}
                                {{--<a class="btn-blue border_radius" href="#.">Save Changes</a>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-2 hidden-xs"></div>--}}
                <div class="col-md-5 col-sm-6 col-xs-12 centere profile-form margin40">
                    <h3 class=" bottom30 margin40">Change Your Password</h3>
                    <div class="row">
                        <form class="callus" method="post" action="{{ url('change_password') }}">
                            {{ csrf_field() }}
                            <div class="col-sm-4">
                                <div class="single-query">
                                    <label>{{ __('admin.current_password') }}:</label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="single-query form-group">
                                    <input type="password" name="current_password" class="keyword-input">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="single-query">
                                    <label>{{ __('admin.new_password') }}:</label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="single-query form-group">
                                    <input type="password" name="password" class="keyword-input">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="single-query">
                                    <label>{{ __('admin.confirm_password') }}:</label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="single-query form-group">
                                    <input type="password" name="confirm_password" class="keyword-input">
                                </div>
                            </div>
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn-blue hub-btn border_radius" href="#.">{{ __('admin.save_changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile end -->
@endsection