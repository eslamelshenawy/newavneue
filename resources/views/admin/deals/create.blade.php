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
            {!! Form::open(['url' => adminPath().'/deals']) !!}
            <div class="form-group @if($errors->has('proposal_id')) has-error @endif col-md-12">
                <label>{{ trans('admin.proposal') }}</label>
                <select class="form-control select2" id="proposal_id" name="proposal_id"
                        data-placeholder="{{ trans('admin.proposal') }}">
                    <option></option>
                    @foreach(@\App\Proposal::where('status','confirmed')->get() as $proposal)
                        <option value="{{ $proposal->id }}" type="{{ $proposal->unit_type }}">
                            #{{ $proposal->id }}</option>
                    @endforeach
                </select>
            </div>

            <span id="unit"></span>

            <div class="form-group @if($errors->has('price')) has-error @endif col-md-12">
                <label>{{ trans('admin.price') }}</label>
                {!! Form::number('price','',['class' => 'form-control', 'placeholder' => trans('admin.price'), 'id' => 'price', 'min' => 0]) !!}
            </div>

            <div class="form-group @if($errors->has('agent_id')) has-error @endif col-md-6">
                <label>{{ trans('admin.agent') }}</label>
                <input class="form-control" id="agent_name" placeholder="{{ trans('admin.agent') }}" readonly>
                <input type="hidden" name="agent_id" id="agent_id" placeholder="{{ trans('admin.agent') }}">
            </div>

            <div class="form-group @if($errors->has('agent_commission')) has-error @endif col-md-6">
                <label>{{ trans('admin.agent_commission') }}</label>
                <div class="input-group">
                    {!! Form::number('agent_commission','',['class' => 'form-control', 'placeholder' => trans('admin.agent_commission'),'readonly'=>'','id'=> 'agent_commission']) !!}
                    <span style="cursor: pointer" class="input-group-addon" id="addAgent"><i
                                class="fa fa-plus"></i></span>
                </div>
            </div>

            <span id="agents"></span>

            <div class="form-group @if($errors->has('company_commission')) has-error @endif col-md-12">
                <label>{{ trans('admin.company_commission') }}</label>
                {!! Form::number('company_commission','',['class' => 'form-control', 'placeholder' => trans('admin.company_commission'),'id'=> 'company_commission']) !!}
            </div>

            <div class="form-group @if($errors->has('broker_commission')) has-error @endif col-md-12 hidden"
                 id="broker">
                <label>{{ trans('admin.broker_commission') }}</label>
                {!! Form::number('broker_commission','',['class' => 'form-control', 'placeholder' => trans('admin.broker_commission'),'id'=> 'broker_commission']) !!}
            </div>

            <div class="form-group @if($errors->has('description')) has-error @endif col-md-12">
                <label>{{ trans('admin.description') }}</label>
                {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
            </div>
            <input type="hidden" name="seller_id" id="seller_id">
            <input type="hidden" name="buyer_id" id="buyer_id">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('change', '#proposal_id', function () {
            var proposal = $(this).val();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_proposal') }}",
                method: 'post',
                dataType: 'json',
                data: {proposal: proposal, _token: _token},
                success: function (data) {
                    $('#price').val(data.price);
                    $('#price').attr('readonly', true);
                    $('#buyer_id').val(data.buyer);
                    $('#seller_id').val(data.seller);
                    $('#agent_id').val(data.agent_id);
                    $('#agent_name').val(data.agent_name);
                    var agentCommission = data.price * data.agent_commission / 100;
                    $('#agent_commission').val(agentCommission);
                    if (data.broker > 0) {
                        $('#broker').removeClass('hidden');
                    } else {
                        $('#broker').addClass('hidden');
                    }
                    if (data.type == 'new_home') {
                        var commission = data.price * data.commission / 100;
                        $('#company_commission').val(commission);
                        $('#company_commission').attr('commission', data.commission);
                        $('#company_commission').attr('readonly', true);
                    } else {
                        $('#company_commission').val(0);
                        $('#company_commission').attr('commission', 0);
                        $('#company_commission').attr('readonly', false);
                    }
                }
            });
            $.ajax({
                url: "{{ url(adminPath().'/get_proposal_html') }}",
                method: 'post',
                dataType: 'html',
                data: {proposal: proposal, _token: _token},
                success: function (data) {
                    $('#unit').html(data);
                }
            });
        });
        var i = 1;
        $(document).on('click', '#addAgent', function () {
            $('#agents').append('<div id="agent' + i + '">' +
                '<div class="form-group @if($errors->has("agent_id")) has-error @endif col-md-6">' +
                '<label>{{ trans("admin.agent") }}</label>' +
                '<select class="form-control select2 otherAgent" count="' + i + '" name="other_agent[]" data-placeholder="{{ trans("admin.agent") }}">' +
                '<option></option>' +
                '@foreach(@\App\User::all() as $agent)' +
                '<option value="{{ $agent->id }}" commission="{{ $agent->commission }}">{{ $agent->name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="form-group @if($errors->has("agent_commission")) has-error @endif col-md-6">' +
                '<label>{{ trans("admin.agent_commission") }}</label>' +
                '<div class="input-group">' +
                '<input name="other_agent_commission[]" class="form-control" placeholder="{{ trans("admin.agent_commission") }}" id="agentCommission' + i + '" readonly>' +
                '<span style="cursor: pointer" class="input-group-addon removeAgent" count="' + i + '"><i' +
                ' class="fa fa-minus"></i></span>' +
                '</div>' +
                '</div>' +
                '</div>');
            $('.select2').select2();
            i++;
        });

        $(document).on('click', '.removeAgent', function () {
            var count = $(this).attr('count');
            $('#agent'+count).remove();
        });

        $(document).on('change', '.otherAgent', function () {
            var agentPercentage = parseInt($('option:selected', this).attr('commission'));
            var count = $(this).attr('count');
            var price = parseInt($('#price').val());
            var agentCommission = price * agentPercentage / 100;
            $('#agentCommission' + count).val(agentCommission);
            $('#agentCommission' + count).attr('commission', agentPercentage);
        });

        $(document).on('keyup', '#price', function () {
            var price = $(this).val();
            var agentCom = price * $('#agent_commission').attr('commission') / 100;
            var companyCom = price * $('#company_commission').attr('commission') / 100;

            $('#agent_commission').val(agentCom);
            $('#company_commission').val(companyCom);

        })
    </script>
@endsection