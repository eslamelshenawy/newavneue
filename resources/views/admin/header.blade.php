<!DOCTYPE html>
<html>
<head>
    <style>
        .bootstrap-tagsinput {
            width: 100%;
            border-radius: 0 !important;
        }
        .navbar-nav > li > .dropdown-menu{
            background: rgba(0,0,0,0.8);
            border: 0;
        }
        .dropdown-menu > li > a {
             color: #fff !important;
        }
        .dropdown-menu > li > a:hover {
            background-color: transparent !important;
            color: #828080 !important;
        }
    </style>
     <style>

        .sidenav {
            height: 100%;
            /*width: 0;*/
            position: fixed;
            border: 1px solid #ccc;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: white;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px !important;
            margin-top: 30px !important;
            width: 0;
            margin-left: 0;
            {{--@if(trans('hr')!='HR') direction:rtl; @endif--}}

/*border: 1px solid #ccc;*/
            /*background-color: white;*/
            /*margin-top: 30px;*/
        }

        .sidenav a {
            padding: 30px 0px 2px 7px;
            text-decoration: none;
            font-size: 22px;
            color: #818181;
            display: block;
            transition: 0.3s;
            @if(trans('hr')!='HR') direction:rtl; @endif

        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 50px;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            @if(trans('hr')!='HR') direction:rtl; @endif

        }

        /*.navside_2 {*/
            /*width: 250px;*/
            /*border: 1px solid #ccc;*/
            /*background-color: white;*/
            /*margin-top: 30px;*/
        /*}*/

        #main {
            transition: margin-left .5s;
            padding: 16px;
        }
        </style>

    <style>
        #piechart{
            position: absolute;
            width: 400px;
            height: 147px;
            margin-top: -62px;
        }
    </style>
        
        <style>
            
            /** card */

        .card-wrapper {
            /**background-color: black;*/
            color: white;
            display: flex;
            flex-flow: row nowrap;
            min-height: 100%;
            width: 100%;
        }

        .card-icon {
            align-self: center;
            /**background-color: yellow;*/
            /**color: black;*/
            flex: 0 0 auto;
            /**font-size: 32px;*/
            padding: 20px;
        }

        .card-icon-chars {
            align-self: center;
            /**background-color: yellow;*/
            /**color: black;*/
            flex: 0 0 auto;
            /**font-size: 32px;*/
            padding-left: 0;
            padding-top: 10px;
            padding-right: 20px;
            padding-bottom: 20px;
        }

        .card-content {
            color: black;
            flex: 12 1 auto;
            padding-top: 20px;
        }

        .card-content-left {
            color: black;
            padding-top: 10px;
        }

        .card-image{
            border: 1px solid #ccc;
            border-radius: 50% ;
            width: 75px;
            height: 75px;
        }

        .card-image-right {
            border: 1px solid #ccc;
            border-radius: 50% ;
            width: 75px;
            height: 75px;
            margin-right: 10px;
        }

        .card-image-right.characters-image {
            background-color: grey;
            font-size: 20px;
            text-align: center;
            vertical-align: middle;
            line-height: 70px;
            font-weight: 600;
            padding: 2px;
        }

        .fa.card-font-icon {
            margin-right: 5px;
            margin-left: 0 !important;
            margin-top: 8px;
            font-size: 25px;
            cursor: pointer;
        }

        #notesUl li {
            list-style-type: none;
        }
        [data-notify="progressbar"] {
        	margin-bottom: 0px;
        	position: absolute;
        	bottom: 0px;
        	left: 0px;
        	width: 100%;
        	height: 5px;
        }
        </style>
<!-- rating stars style -->
        <style>
            .stars-outer {
  display: inline-block;
  position: relative;
  font-family: FontAwesome ! important ;
}
 
.stars-outer::before {
  content: "\f006 \f006 \f006 \f006 \f006";
}
 
.stars-inner {
  position: absolute;
  top: 0;
  left: 0;
  white-space: nowrap;
  overflow: hidden;
  width: 50%;
  font-family: FontAwesome ! important ;
}
 
.stars-inner::before {
  content: "\f005 \f005 \f005 \f005 \f005";
  color: #f8ce0b;
}
 </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@if(!empty($title)) {{ trans('admin.website_title') . ' | ' . $title }} @else {{ trans('admin.website_title') }} @endif</title>
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('style/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('style/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('style/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('css/image-picker.css') }}" >
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ url('dist/css/skins/_all-skins.min.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ url('style/morris.js/morris.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ url('style/jvectormap/jquery-jvectormap.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ url('style/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ url('plugins/timepicker/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ url('style/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="{{ url('style/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/file_upload/file_upload.css') }}">
    <link rel="stylesheet" href="{{ url('dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ url('css/bootstrap-tagsinput.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link href="{{ url('css/gallery.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/SocialIcons/1.0.1/soc.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:300&amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/style/style.css') }}">
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-flipped.css">
        <link rel="stylesheet"
              href="{{ url('style/style_ar.css') }}">
    @endif
    <link rel="stylesheet" href="{{ url('css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{ url('css/animate.css') }}">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/earlyaccess/notokufiarabic.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" />
    <link rel="icon" href="{{ url('website_style/images/icon.png')}}">
    @yield('styles')
</head>

<body class="hold-transition fixed sidebar-mini {{ getInfo()->theme }}" theme="{{ getInfo()->theme }}" id="themeColor">
<div class="wrapper">
<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">
	<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>
	<span data-notify="icon"></span>
	<span data-notify="title">{1}</span>
	<span data-notify="message">{2}</span>
	<div class="progress" data-notify="progressbar">
		<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
	</div>
	<a href="{3}" target="{4}" data-notify="url"></a>
</div>