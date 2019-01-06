<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<title>New Avenue | Log in</title>-->
  <title>@if(!empty($title)) {{ $title . ' | Log in'  }} @else {{ 'New Avenue | Log in' }} @endif</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ url('style/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('style/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ url('style/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ url('plugins/iCheck/square/blue.css') }}">
  <link rel="stylesheet" href="{{ url('style/style.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Lato:300&amp;subset=latin-ext" rel="stylesheet">


  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<style>
         html{
             position : relative;
         }
        #bodymovin{
            background: url(../images/resources/BG.jpg);
            background-size: cover;
            transform: translate3d(0,0,0);
            display:block;
            opacity: 1;
            width:100%;
            position : fixed;
            top:0;
            bottom:0 ;
        }
        .login-box{
            position:fixed;
            margin-left:calc(50% - 182px);

        }
    </style>
<body class="hold-transition" >
<div id="bodymovin">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ url(adminPath().'/') }}"><img src="{{ url('images/resources/logo.png') }}"></a>

  </div>
  <span class="gold bigsp">WELCOME!</span>
  <span class="gold smsp"> LOGIN</span>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <form action="{{ url(adminPath().'/login_post') }}" method="post" class=" hub-form">
      {!! csrf_field() !!}
      <div class="form-group has-feedback @if($errors->has('email') or session()->has('login_error')) has-error @endif">
        <input type="email" name="email" class="form-control " placeholder="{{ trans('admin.email') }}">
        @if($errors->has('email'))
        <span style="color: red; position: absolute; top: 65px;">{{ $errors->first('email') }}</span>
        @endif
        @if(session()->has('login_error'))
          <span style="color: red; position: absolute; top: 65px;">{{ session()->get('login_error') }}</span>
        @endif
        <span class="email form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback @if($errors->has('password') or session()->has('login_error')) has-error @endif">
        <input type="password" name="password" class="form-control" placeholder="{{ trans('admin.password') }}">
        @if($errors->has('password'))
          <span style="color: red; position: absolute; top: 65px;">{{ $errors->first('password') }}</span>
        @endif
        <span class="password form-control-feedback"></span>
      </div>
      {{--<div class="row">--}}
        {{--<div class="col-xs-12">--}}
          {{--<div class="checkbox icheck">--}}
            {{--<label>--}}
              {{--<input type="checkbox"> {{ trans('admin.remember_me') }}--}}
            {{--</label>--}}
          {{--</div>--}}
        {{--</div>--}}
        <!-- /.col -->
        <div class="col-xs-8 center">
          <button type="submit" class="btn btn-primary btn-block btn-flat hub-btn">{{ trans('admin.sign_in') }}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ url('style/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('style/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ url('plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</div>
</body>
</html>
