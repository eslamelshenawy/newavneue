@extends('admin.index')
@section('content')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default-dark/style.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default/style.min.css">
    <style>
        .tree1, .tree1 ul {
            margin: 0;
            padding: 0;
            list-style: none
        }

        .tree1 ul {
            margin-left: 1em;
            position: relative
        }

        .tree1 ul ul {
            margin-left: .5em
        }

        .tree1 ul:before {
            content: "";
            display: block;
            width: 0;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            border-left: 1px solid
        }

        .tree1 li {
            margin: 0;
            padding: 0 1em;
            line-height: 2em;
            color: #cba42d;
            font-weight: 700;
            position: relative
        }

        .tree1 ul li:before {
            content: "";
            display: block;
            width: 10px;
            height: 0;
            border-top: 1px solid;
            margin-top: -1px;
            position: absolute;
            top: 1em;
            left: 0
        }

        .tree1 ul li:last-child:before {
            background: #fff;
            height: auto;
            top: 1em;
            bottom: 0
        }

        .indicator {
            margin-right: 5px;
        }

        .tree1 li a {
            text-decoration: none;
            color: #cba42d;
        }

        .tree1 li button, .tree1 li button:active, .tree1 li button:focus {
            text-decoration: none;
            color: #cba42d;
            border: none;
            background: transparent;
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
            outline: 0;
        }
    </style>
    <div class="box">
        <div class="box-header with-border">
            <h3>project</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <form action="{{ url(adminPath().'/projects') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group @if($errors->has('en_name')) has-error @endif">
                    <label>{{ trans('admin.en_name') }}</label>
                    <input type="text" name="en_name" class="form-control" value="{{ old('en_name') }}" placeholder="{!! trans('admin.en_name') !!}">
                </div>
                <div class="form-group @if($errors->has('ar_name')) has-error @endif">
                    <label>{{ trans('admin.ar_name') }}</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name') }}" placeholder="{!! trans('admin.ar_name') !!}">
                </div>

                <div class="row"> <div class="phase col-md-10 col-md-push-1"></div></div>
                <div class="form-group">
                   <a class="btn btn-success  add_phase">Add Phase</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>

        $("#jstree").on('changed.jstree', function (e, data) {
            $('#location').text($('#'+data.selected).attr('data-title'));
            var id = data.selected;
            $('#location_id').val(id);
            console.log(id);
        }).jstree({
            'core': {
                "themes": {
                    "dots": false,
                    "icons": false
                }
            },
            'plugins': ['types', 'contextmenu', 'wholerow', 'ui']
        });
        var x = 1;
        var y=1;
        $(document).on('click', '.add_phase', function () {
            $('.phase').append('@include("admin.projects.content.create.phase")');
            x++;
            $('.select2').select2();
        });
        $(document).on('click', '.addproperty', function () {
            var count = $(this).attr('num');
            console.log(count);
                $('#body'+count).append('@include("admin.projects.content.create.property")');
                y++;
            $('.select2').select2();
        });
        $(document).on('click', '.removeprop', function () {
            var count = $(this).attr('num');
            console.log(count);
            $('#prop'+count).remove();
            y--;
            $('.select2').select2();
        });
        $(document).on('click', '.removephase', function () {
            var count = $(this).attr('num');
            console.log(count);
            $('#phase'+count).remove();
            x--;
            $('.select2').select2();
        });

    </script>
@endsection
