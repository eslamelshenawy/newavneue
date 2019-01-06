@extends('admin.index')

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
            <button class="btn btn-primary btn-flat pull-right" data-toggle="modal"
                    data-target="#add_bank">{{ trans('admin.add_bank') }}</button>
            <button class="btn btn-primary btn-flat pull-right" data-toggle="modal"
                    data-target="#add_currency">{{ trans('admin.add') }} {{ trans('admin.currency') }}</button>
            <button class="btn btn-primary btn-flat pull-right" data-toggle="modal"
                    data-target="#add_safe">{{ trans('admin.add') }} {{ trans('admin.safe') }}</button>

            <table class="table table-hover table-striped datatable">
                <thead>
                <tr>
                    <th>{{ trans('admin.name') }}</th>
                    <th>{{ trans('admin.account_number') }}</th>
                    <th>{{ trans('admin.open_value') }}</th>
                    <th>{{ trans('admin.currency') }}</th>
                    <th>{{ trans('admin.action') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                @foreach($banks as $bank)
                    <tr>
                        <form action="{{ url(adminPath().'/edit_bank/'.$bank->id) }}" method="post">
                            {{ csrf_field() }}
                            <td><input class="form-control" name="name" value="{{ $bank->name }}"></td>
                            <td><input class="form-control" name="account_number" value="{{ $bank->account_number }}">
                            </td>
                            <td><input class="form-control" name="open_value" value="{{ $bank->open_value }}"></td>
                            <td>
                                <select name="currency" class="select2" style="width: 200px;"
                                        data-placeholder="{{ trans('admin.currency') }}">
                                    <option></option>
                                    @foreach(@App\currency::all() as $currency)
                                        <option value="{{ $currency->id }}" style="width: 150px"
                                                @if($currency->id == $bank->currency) selected @endif>{{ $currency->{app()->getLocale().'_name'} }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-flat btn-warning">{{ trans('admin.edit') }}</button>
                        </form>
                        <a href="{{ url(adminPath().'/delete_bank/'.$bank->id) }}"
                           class="btn btn-flat btn-danger">{{ trans('admin.delete') }}</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    </div>
    <div id="add_bank" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.add') . ' ' . trans('admin.bank') }}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url(adminPath().'/add_bank') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            {!! Form::label(trans('admin.name')) !!}
                            <input name="name" type="text" class="col-md-6 form-control">
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.account_number')) !!}
                            <input name="account_number" type="text" class="col-md-6 form-control">
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.open_value')) !!}
                            <input name="open_value" type="number" min="0" class="col-md-6 form-control">
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.currency')) !!}
                            <select class="select2" name="currency" style="width: 100%"
                                    data-placeholder="{{ trans('admin.currency') }}">
                                <option></option>
                                @foreach(@App\currency::all() as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->{app()->getLocale().'_name'} }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit">{{ trans('admin.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="add_currency" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.add') . ' ' . trans('admin.currency') }}</h4>
                </div>
                <div class="modal-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.ar_name') }}</th>
                            <th>{{ trans('admin.en_name') }}</th>
                            <th>{{ trans('admin.action') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(@App\currency::all() as $currency)
                            <tr>
                                <form action="{{ url(adminPath().'/edit_currency/'.$currency->id) }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="put">
                                    <td><input name="ar_name" value="{{ $currency->ar_name }}"></td>
                                    <td><input name="en_name" value="{{ $currency->en_name }}"></td>
                                    <td>
                                        <button class="btn btn-default btn-warning"
                                                id="edit{{ $currency->id }}">{{ trans('admin.edit') }}</button>
                                </form>
                                <a href="{{ url(adminPath().'/delete_currency/'.$currency->id) }}"
                                   class="btn btn-default btn-danger">{{ trans('admin.delete') }}</a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <h4 class="modal-title">{{ trans('admin.new') . ' ' . trans('admin.currency') }}</h4>
                    <form action="{{ url(adminPath().'/add_currency') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group col-xs-6">
                            {!! Form::label(trans('admin.ar_name')) !!}
                            <input name="ar_name" type="text" class="col-xs-6 form-control">
                        </div>
                        <div class="form-group col-xs-6">
                            {!! Form::label(trans('admin.en_name')) !!}
                            <input name="en_name" type="text" class="col-xs-6 form-control">
                        </div>
                        <button type="submit"
                                class="btn btn-default btn-bitbucket pull-right">{{ trans('admin.submit') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="add_safe" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.add') . ' ' . trans('admin.safe') }}</h4>
                </div>
                <div class="modal-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.ar_name') }}</th>
                            <th>{{ trans('admin.en_name') }}</th>
                            <th>{{ trans('admin.action') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(@App\Safe::all() as $safe)
                            <tr>
                                <form action="{{ url(adminPath().'/edit_safe/'.$safe->id) }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="put">
                                    <td><input name="ar_name" value="{{ $safe->ar_name }}"></td>
                                    <td><input name="en_name" value="{{ $safe->en_name }}"></td>
                                    <td>
                                        <button class="btn btn-flat btn-warning"
                                                id="edit{{ $safe->id }}">{{ trans('admin.edit') }}</button>
                                </form>
                                <a href="{{ url(adminPath().'/delete_safe/'.$safe->id) }}"
                                   class="btn btn-falt btn-danger">{{ trans('admin.delete') }}</a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <h4 class="modal-title">{{ trans('admin.new') . ' ' . trans('admin.safe') }}</h4>
                    <form action="{{ url(adminPath().'/add_safe') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group col-xs-6">
                            {!! Form::label(trans('admin.ar_name')) !!}
                            <input name="ar_name" type="text" class="col-xs-6 form-control">
                        </div>
                        <div class="form-group col-xs-6">
                            {!! Form::label(trans('admin.en_name')) !!}
                            <input name="en_name" type="text" class="col-xs-6 form-control">
                        </div>
                        <button type="submit"
                                class="btn btn-default btn-bitbucket pull-right">{{ trans('admin.submit') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection