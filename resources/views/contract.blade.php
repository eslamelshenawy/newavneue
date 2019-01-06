<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ url('signature/css.css') }}" rel="stylesheet">
    <script src="{{ url('signature/jquery.js') }}"></script>
    <script src="{{ url('signature/js1.js') }}"></script>
    <script src="{{ url('signature/js2.js') }}"></script>
    <script src="{{ url('signature/js3.js') }}"></script>

    <script type='text/javascript'
    src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <script src="{{ url('signature/js4.js') }}"></script>
    <style>
        .bg {
            background: url('{{ url('uploads/' . $contract->background) }}');
            min-height: 100%;
            width: 100%;
            background-size: cover;
            position: absolute;
            background-attachment: fixed;
        }

        .form-group {
            padding: 30px 30px 0px 30px;
        }

        label {
            color: #fff;
            text-shadow: 0 0 10px #000;
        }

        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {

        }


        #signArea {
            width: 304px;
            margin: 50px auto;
        }

        .sign-container {
            width: 60%;
            margin: auto;
        }

        .sign-preview {
            width: 150px;
            height: 50px;
            border: solid 1px #CFCFCF;
            margin: 10px 5px;
        }

        .tag-ingo {
            font-family: cursive;
            font-size: 12px;
            text-align: left;
            font-style: oblique;
        }

        .btn-flat {
            border-radius: 0px !important;
        }

        .page {
            background: whitesmoke;
            border-radius: 3px;
        }

        .title {
            color: #fff;
            text-shadow: 0 0 10px #000;
        }
    </style>
</head>
<body>
<div class="bg">
    <div class="container">
        <div class="row" style="margin: 25px 0px 25px 0px">
            <div class="pull-left">
                <img src="{{ url('uploads/logo.png') }}" height="100" class="">
            </div>
            <div class="clearfix"></div>
            <h1 class="text-center title">
                {{ $contract->title }}
            </h1>
        </div>
        <div class="row page">
            <div class="container" style="padding: 20px">
                {!! $contract->contract !!}
            </div>
        </div>
        @if(!$contract->status)
            <form method="post" id="contractForm" action="{{ url('contract-form') }}" enctype="multipart/form-data">
                <div class="pull-left">
                    <div id="signArea">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $contract->id }}">
                        <label>{{ __('admin.lead_name') }}</label>
                        <input type="text" name="lead_name" class="form-control" readonly value="{{ $contract->lead->first_name . ' ' . $contract->lead->last_name }}"
                               placeholder="{{ __('admin.lead_name') }}">

                        <br/>

                        <label>{{ __('admin.signature_name') }}</label>
                        <input type="text" name="signature_name" class="form-control"
                               placeholder="{{ __('admin.signature_name') }}">

                        <br/>

                        <label>{{ __('admin.files') }}</label>
                        <input type="file" id="files" multiple name="docs[]" class="form-control"
                               placeholder="{{ __('admin.files') }}">

                        <br/>

                        <label>{{ __('admin.put_signature_below') }}</label>
                        <div class="sig sigWrapper" style="height:auto;">
                            <div class="typed"></div>
                            <canvas class="sign-pad" id="sign-pad" width="300" height="120"></canvas>
                        </div>

                        <br/>
                        <div class="text-center">
                            <button id="btnSaveSign" class="btn btn-success btn-flat">
                                {{ __('admin.submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="form-group pull-left">
                <img src="{{ url($contract->signature) }}">
            </div>
        @endif
    </div>
</div>
</body>
<footer>
    <script>
        $(document).ready(function () {
            $('#signArea').signaturePad({drawOnly: true, drawBezierCurves: true, lineTop: 90});
        });

        $("#btnSaveSign").click(function (e) {
            html2canvas([document.getElementById('sign-pad')], {
                onrendered: function (canvas) {
                    var _token = '{{ csrf_token() }}';
                    var id = '{{ $contract->id }}';
                    var canvas_img_data = canvas.toDataURL('image/png');
                    var img = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    //ajax call to save image inside folder
                    $.ajax({
                        url: '{{ url('confirm-contract') }}',
                        data: {img: img, _token: _token, id: id},
                        type: 'post',
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 1) {
                                $('#contractForm').submit();
                            } else {
                                alert('{{ __('admin.error') }}')
                            }
                        }
                    });
                }
            });
        });
    </script>
</footer>
</html>