@extends('admin.index')
<style type="text/css">
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
</style>
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="container">
                <h3>{{ $contract->title }}</h3>
                <hr/>
                {!! $contract->contract !!}
            </div>
            @if(!$contract->status)
                <div id="signArea">
                    <h2 class="tag-ingo">{{ __('admin.put_signature_below') }}</h2>
                    <div class="sig sigWrapper" style="height:auto;">
                        <div class="typed"></div>
                        <canvas class="sign-pad" id="sign-pad" width="300" height="100"></canvas>
                    </div>
                </div>
                <div class="form-group text-center">
                    <button id="btnSaveSign" class="btn btn-success btn-flat">
                        {{ __('admin.confirm') }}
                    </button>
                </div>
            @else
                <div class="form-group text-center">
                    <img src="{{ url($contract->signature) }}">
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <link href="http://lisenme.com/demo/sign_src/css/jquery.signaturepad.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://lisenme.com/demo/sign_src/js/numeric-1.2.6.min.js"></script>
    <script src="http://lisenme.com/demo/sign_src/js/bezier.js"></script>
    <script src="http://lisenme.com/demo/sign_src/js/jquery.signaturepad.js"></script>

    <script type='text/javascript'
            src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <script src="http://lisenme.com/demo/sign_src/js/json2.min.js"></script>
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
                        dataType: 'json',
                        success: function (data) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection