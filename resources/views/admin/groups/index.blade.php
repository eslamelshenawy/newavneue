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
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <a class="btn btn-success btn-flat @if(app()->getLocale() == 'en') pull-right @else pull-left @endif"
               href="{{ url(adminPath().'/groups/create') }}">{{ trans('admin.add') }}</a>
            <div class="container">
                <h3>{{ trans('admin.all_groups') }}</h3>
                <div class="col-md-6" id="jstree">
                    <ul id="" class="">
                        @foreach($categories as $category)
                            <li style="cursor: pointer" id="{{ $category->id }}" class="child"
                                child="{{ $category->id }}">
                                <span class="fa fa-users"></span> {{ $category->name }}
                                @if(count($category->children))
                                    @include('admin.groups.children',['childs' => $category->children])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <span class="beforeSend hidden" style="z-index: 500"><i class="fa fa-circle-o-notch fa-spin" style="font-size: 2em"></i></span>
                <div class="col-md-6 well hidden" id="group">
                    <h3>{{ trans('admin.group') }}</h3>
                    <div id="get_group"></div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>

        $("#jstree").on('changed.jstree', function (e, data) {
            var id = data.selected;
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_group')}}",
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                beforeSend: function () {
                    $('.beforeSend').removeClass('hidden');
                }, success: function (data) {
                    $('.beforeSend').addClass('hidden');
                    $('#group').removeClass('hidden');
                    $('#get_group').html(data);
                }
            })
        }).jstree({
            'core': {
                "themes": {
                    "dots": false,
                    "icons": false
                }
            },
            'plugins': ['types', 'contextmenu', 'wholerow', 'ui']
        });


    </script>
@endsection
