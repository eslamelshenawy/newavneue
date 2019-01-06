@extends('admin.index')

@section('content')
{{--@php( $columns = Schema::getColumnListing('banks') )--}}
{{--{{ dd($columns) }}--}}
    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#income" data-toggle="tab"
                                          aria-expanded="false">{{ trans('admin.income') }}</a></li>
                    <li class=""><a href="#outcome" data-toggle="tab"
                                    aria-expanded="true">{{ trans('admin.outcome') }}</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="income">
                    <button class="btn btn-default btn-bitbucket pull-right" data-toggle="modal" data-target="#add_income">{{ trans('admin.new_income') }}</button>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.id')}}</th>
                            <th>{{ trans('admin.method') }}</th>
                            <th>{{ trans('admin.total_price') }}</th>
                            <th>{{ trans('admin.bank_safe') }}</th>
                            <th>{{ trans('admin.date') }}</th>
                            <th>{{ trans('admin.source') }}</th>
                            <th>{{ trans('admin.status') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@App\Income::all() as $income)
                            <tr>
                                <td>#{{ $income->id }}</td>
                                <td>{{ $income->payment_method }}</td>
                                <td>{{ $income->value }} </td>
                                <td>@if($income->safe_id) {{@App\Safe::find($income->safe_id)->{app()->getlocale().'_name'} }} @elseif($income->bank_id) {{@App\Bank::find($income->bank_id)->name }} @endif</td>
                                <td>{{ date('Y-m-d',$income->date) }}</td>
                                <td>{{ $income->source }}</td>
                                <td>
                                    @if($income->status == 'collected')
                                        {{ trans('admin.collected') }}
                                    @else
                                        <a class="btn btn-default btn-bitbucket" href="{{ url(adminPath().'/collect_income/'.$income->id) }}">{{ trans('admin.collect') }}</a>
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="outcome">
                    <button class="btn btn-default btn-bitbucket pull-right" data-toggle="modal" data-target="#add_outcome">{{ trans('admin.new_outcome') }}</button>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.id')}}</th>
                            <th>{{ trans('admin.method') }}</th>
                            <th>{{ trans('admin.total_price') }}</th>
                            <th>{{ trans('admin.bank_safe') }}</th>
                            <th>{{ trans('admin.out_cat') }}</th>
                            <th>{{ trans('admin.out_sub_cat') }}</th>
                            <th>{{ trans('admin.date') }}</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach(@App\Outcome::all() as $outcome)
                            <tr>
                                <td>#{{ $outcome->id }}</td>
                                <td>{{ $outcome->payment_method }}</td>
                                <td>{{ $outcome->value }} </td>
                                <td>
                                    @if($outcome->payment_method == 'cash')
                                        {{ @$outcome->safe->{app()->getLocale() . '_name'} }}
                                    @else
                                        {{ @$outcome->bank->name }}
                                    @endif
                                </td>
                                <td>{{ @$outcome->subCat->name }}</td>
                                <td>{{ @$outcome->subCat->cat->name }}</td>
                                <td>{{ date('Y-m-d', $outcome->date) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div id="add_income" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.add') . ' ' . trans('admin.income') }}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url(adminPath().'/add_income') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                        {!! Form::label(trans('admin.name')) !!}
                        <input name="name" placeholder="{{ __('admin.name') }}" type="text" class="col-md-6 form-control">
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.description')) !!}
                            <textarea name="description" placeholder="{{ __('admin.description') }}" type="text" class="col-md-6 form-control"></textarea>
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.value')) !!}
                            <input name="value" placeholder="{{ __('admin.value') }}" type="number" min="0" class="col-md-6 form-control">
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.currency')) !!}
                            <select class="select2 form-control" name="currency" style="width: 100%" data-placeholder="{{ trans('admin.currency') }}">
                                <option></option>
                                @foreach(@App\currency::all() as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->{app()->getLocale().'_name'} }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.payment_method')) !!}
                            <select class="form-control col-md-12 select2" style="width: 100%" name="payment_method" data-placeholder="{{ trans('admin.payment_method') }}" id="payment_method">
                                <option></option>
                                <option value="cash">{{ trans('admin.cash') }}</option>
                                <option value="cheques">{{ trans('admin.cheques') }}</option>
                                <option value="bank_transfer">{{ trans('admin.bank_transfer') }}</option>
                            </select>
                        </div>
                        <div id="safe_bank" class="form-group">

                        </div>

                        <div class="form-group">
                        {!! Form::label(trans('admin.date')) !!}
                        <input name="date" placeholder="{{ __('admin.date') }}" type="text" class="col-md-6 datepicker form-control" readonly >
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.payment_method')) !!}
                            <select class="form-control col-md-6" name="status">
                                <option value="collected">{{ trans('admin.collected') }}</option>
                                <option value="not_collected">{{ trans('admin.not_collected') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-bitbucket pull-right">{{ trans('admin.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="add_outcome" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.add') . ' ' . trans('admin.outcome') }}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ url(adminPath().'/add_outcome') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            {!! Form::label(trans('admin.out_cat')) !!}
                            <select class="form-control select2" style="width: 100%;" name="out_cat_id" id="outCat" data-placeholder="{{ __('admin.out_cat') }}">
                                <option></option>
                                @foreach(@\App\OutCat::get() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <span id="subCats"></span>

                        <div class="form-group">
                            {!! Form::label(trans('admin.name')) !!}
                            <input name="name" placeholder="{{ __('admin.name') }}" type="text" class="col-md-6 form-control">
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.description')) !!}
                            <textarea name="description" placeholder="{{ __('admin.description') }}" type="text" class="col-md-6 form-control"></textarea>
                        </div>
                        <div class="form-group">
                            {!! Form::label(trans('admin.value')) !!}
                            <input name="value" placeholder="{{ __('admin.value') }}" type="number" min="0" class="col-md-6 form-control">
                        </div>

                        <div class="form-group">
                            {!! Form::label(trans('admin.currency')) !!}
                            <select class="select2 form-control" name="currency" style="width: 100%" data-placeholder="{{ trans('admin.currency') }}">
                                <option></option>
                                @foreach(@App\currency::all() as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->{app()->getLocale().'_name'} }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            {!! Form::label(trans('admin.payment_method')) !!}
                            <select class="form-control col-md-12 select2" style="width: 100%" name="payment_method" data-placeholder="{{ trans('admin.payment_method') }}" id="paymentMethod">
                                <option></option>
                                <option value="cash">{{ trans('admin.cash') }}</option>
                                <option value="cheques">{{ trans('admin.cheques') }}</option>
                                <option value="bank_transfer">{{ trans('admin.bank_transfer') }}</option>
                            </select>
                        </div>
                        <span id="safeBank"></span>

                        <div class="form-group">
                            {!! Form::label(trans('admin.date')) !!}
                            <input name="date" placeholder="{{ __('admin.date') }}" type="text" class="col-md-6 datepicker form-control" readonly >
                        </div>
                        <button type="submit" class="btn btn-default btn-bitbucket pull-right">{{ trans('admin.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#payment_method').on('change',function () {
            $('#safe_bank').empty();
            if($(this).val()== 'cash'){
                $('#safe_bank').append(' {!! Form::label(trans("admin.safe")) !!}');
                $('#safe_bank').append('<select class="form-control col-md-12 select2" style="width: 100%" name="safe">' +
                '@foreach(@App\Safe::all() as $safe)'+
                '<option value="{{ $safe->id }}">{{ $safe->{app()->getLocale()."_name"} }}</option>'+
                '@endforeach'
                );
            }
            else if($(this).val()== 'bank_transfer' || $(this).val()== 'cheques'){
                $('#safe_bank').append(' {!! Form::label(trans("admin.bank")) !!}');
                $('#safe_bank').append('<select class="form-control col-md-12 select2" style="width: 100%" name="bank">' +
                    '@foreach(@App\Bank::all() as $bank)'+
                    '<option value="{{ $bank->id }}">{{ $bank->name }}</option>'+
                    '@endforeach'
                );
            }
        });
    </script>
    <script>
        $(document).on('change', '#outCat', function () {
            var cat = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: '{{ url(adminPath() . '/get_sub_cats') }}',
                type: 'post',
                data: {cat: cat, _token: _token},
                dataType: 'html',
                success: function (data) {
                    $('#subCats').html(data);
                    $('.select2').select2();
                }
            })
        })
    </script>
    <script>
        $(document).on('change', '#paymentMethod', function () {
            var method = $(this).val();
            if (method == 'cash') {
                $('#safeBank').html('<div class="form-group">' +
                    '{!! Form::label(trans("admin.safe")) !!}' +
                    '<select class="select2 form-control" name="safe_id" style="width: 100%" data-placeholder="{{ trans("admin.safe") }}">' +
                    '<option></option>' +
                    '@foreach(@\App\Safe::all() as $safe)' +
                    '<option value="{{ $safe->id }}">{{ $safe->{app()->getLocale()."_name"} }}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</div>');
            } else {
                $('#safeBank').html('<div class="form-group">' +
                    '{!! Form::label(trans("admin.bank")) !!}' +
                    '<select class="select2 form-control" name="bank_id" style="width: 100%" data-placeholder="{{ trans("admin.bank") }}">' +
                    '<option></option>' +
                    '@foreach(@\App\Bank::all() as $bank)' +
                    '<option value="{{ $bank->id }}">{{ $bank->name }}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</div>');
            }
            $('.select2').select2();
        })
    </script>
@endsection