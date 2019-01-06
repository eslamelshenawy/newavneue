@extends('admin.index')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default-dark/style.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/themes/default/style.min.css">
    <style>
        .tree1, .tree1 ul {
            margin:0;
            padding:0;
            list-style:none
        }
        .tree1 ul {
            margin-left:1em;
            position:relative
        }
        .tree1 ul ul {
            margin-left:.5em
        }
        .tree1 ul:before {
            content:"";
            display:block;
            width:0;
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            border-left:1px solid
        }
        .tree1 li {
            margin:0;
            padding:0 1em;
            line-height:2em;
            color:#cba42d;
            font-weight:700;
            position:relative
        }
        .tree1 ul li:before {
            content:"";
            display:block;
            width:10px;
            height:0;
            border-top:1px solid;
            margin-top:-1px;
            position:absolute;
            top:1em;
            left:0
        }
        .tree1 ul li:last-child:before {
            background:#fff;
            height:auto;
            top:1em;
            bottom:0
        }
        .indicator {
            margin-right:5px;
        }
        .tree1 li a {
            text-decoration: none;
            color:#cba42d;
        }
        .tree1 li button, .tree1 li button:active, .tree1 li button:focus {
            text-decoration: none;
            color:#cba42d;
            border:none;
            background:transparent;
            margin:0px 0px 0px 0px;
            padding:0px 0px 0px 0px;
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
            <div class="container">
                <h3>{{ trans('admin.all_groups') }}</h3>
                <div class="col-md-6" id="jstree">
                    <ul id="" class="">
                        @foreach($categories as $category)
                            <li style="cursor: pointer" id="{{ $category->id }}" class="child" child="{{ $category->id }}">
                                <span class="fa fa-users"></span> {{ $category->name }}
                                @if(count($category->children))
                                    @include('admin.groups.children',['childs' => $category->children])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6 well">
                    <h3>{{ trans('admin.add_group') }}</h3>

                    {!! Form::open(['url'=>adminPath().'/groups']) !!}

                    <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                        {!! Form::label(trans('admin.parent')) !!}
                        <select class="select2 form-control" id="parent_id" name="parent_id" data-placeholder="{{ trans('admin.parent') }}">
                            <option value="0">{{ trans('admin.main') }}</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        {!! Form::label(trans('admin.name')) !!}
                        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>trans('admin.name')]) !!}
                    </div>

                    <div class="form-group {{ $errors->has('team_leader_id') ? 'has-error' : '' }}">
                        {!! Form::label(trans('admin.team_leader')) !!}
                        <select class="select2 form-control" name="team_leader_id" data-placeholder="{{ trans('admin.team_leader') }}">
                            <option></option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group {{ $errors->has('members') ? 'has-error' : '' }}">
                        {!! Form::label(trans('admin.members')) !!}
                        <select class="select2 form-control" multiple name="members[]" data-placeholder="{{ trans('admin.members') }}">
                            <option></option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group {{ $errors->has('notes') ? 'has-error' : '' }}">
                        {!! Form::label(trans('admin.notes')) !!}
                        <textarea class="form-control" name="notes" placeholder="{{ trans('admin.notes') }}"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.4/jstree.min.js"></script>
    <script>

        $("#jstree").on('changed.jstree', function (e, data) {
            $('#parent_id').select2('val',data.selected)
        }).jstree({
            'core' : {
                "themes": {
                    "dots": false,
                    "icons": false
                }
            },
            'plugins' : ['types','wholerow','ui']
        });

        $('#parent_id').on('change', function () {
            var id = $(this).val();
            var old = $(this).attr('old');
            $('#jstree').jstree(true).deselect_node(old);
            $('#jstree').jstree(true).select_node(id);
            $(this).attr('old',id);
        })


    </script>
@endsection
