@extends('admin.index')

@section('content')
    <div class="box">
               
        <div class="box-header with-border">
            <h3 class="box-title">Facebook Page Subscribe</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <a data-toggle="modal" data-target="#delete"
                               class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</a></td>
            </div>
        </div>
        <div class="box-body">
<?php   $settings = App\Setting::find(1); ?>
<script>
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : '{{ App\Setting::first()->facebook_api }}',
                        xfbml      : true,
                        version    : 'v2.8'
                    });
                };
                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
                function FBLogin(){
                    FB.getLoginStatus(function(response) {
                        if (response.status === 'connected') {
                            console.log('Logged in.');
                        }
                        else {
                            FB.login();
                        }
                    });
                }
                function subapp(page_id,page_access_token,page_name){
                    console.log("Page ID: " + page_id);
                    document.getElementById(page_id).innerHTML = page_name + " <em> Subscribed!</em> page ID: "+page_id;
                    FB.api('/' + page_id + '/subscribed_apps',
                        'post',
                        {access_token: page_access_token},
                        function(response){
                            console.log(response);
                        })
                }
                // Only works after `FB.init` is called
                function myFacebookLogin() {
                    FB.login(function(response){
                        console.log("Successufuly Loged in ", response);
                        FB.api('/me/accounts',function(response){
                            console.log("Successfully retrieved pages", response);
                            var pages = response.data;
                            var ul = document.getElementById('list');
                            for(var i = 0, len = pages.length; i < len ; i++){
                                var page = pages[i];
                                var li = document.createElement('li');
                                var a = document.createElement('a');
                                a.href = "#";
                                a.id = page.id;
                                a.onclick = subapp.bind(this,page.id,page.access_token,page.name);
                                a.innerHTML = page.name;
                                li.appendChild(a);
                                ul.appendChild(li);
                            }
                        })
                    }, {scope: 'manage_pages'});
                }
                console.log("{{ App\Setting::first()->facebook_api }}");
            </script>
            <center>
                <h2>Lead Gen</h2>
                <button onclick="myFacebookLogin()">Login with Facebook</button>
                <ul id="list"></ul>
            </center>
             <div id="delete" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.facebook') }}</h4>
                                </div>
                                <div class="modal-body">
                                      
                                    <form method="post" action="{{ url(adminPath().'/facebook_post') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group col-md-6 @if($errors->has('app_id')) has-error @endif">
                    <label>Facebook app id</label>
                    <input type="text" name="app_id" class="form-control" value="{{ $settings->facebook_api }}"
                           placeholder="Facebook app id">
                </div>
                  <div class="form-group col-md-6 @if($errors->has('fb_token')) has-error @endif">
                    <label>Facebook Access Token</label>
                    <input type="text" name="fb_token" class="form-control" value="{{ $settings->fb_token }}"
                           placeholder="Facebook app id">
                </div>
                                    <button type="button" class="btn btn-default btn-flat"
                                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                                    <button type="submit"
                                            class="btn btn-warning btn-flat">{{ trans('admin.edit') }}</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
        </div>
        @endsection
        
 
