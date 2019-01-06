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
            <div class="col-md-6">
                <strong>{{ trans('admin.buyer') }}
                    : </strong>{{ @\App\Lead::find($deal->buyer_id)->first_name . ' ' . @\App\Lead::find($deal->buyer_id)->last_name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.seller') }}
                    : </strong>{{ @\App\Lead::find($deal->seller_id)->first_name . ' ' . @\App\Lead::find($deal->seller_id)->last_name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.price') }}
                    : </strong>{{ $deal->price }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.proposal') }}
                    : </strong> <a href="{{ url(adminPath().'/proposals/'.$deal->proposal_id) }}">
                    #{{ $deal->proposal_id }} </a>
                <br>
                <hr>
            </div>
            @php
                $proposal = @\App\Proposal::find($deal->proposal_id)
            @endphp
            <div class="col-md-6">
                <strong>{{ trans('admin.unit_type') }}
                    : </strong> {{ trans('admin.'.$proposal->unit_type) }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.unit') }}
                    : </strong>
                @if($proposal->unit_type == 'new_home')
                    {{ @\App\Property::find($proposal->unit_id)->{app()->getLocale().'_name'} }}
                @elseif($proposal->unit_type == 'resale')
                    {{ @\App\ResaleUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                @elseif($proposal->unit_type == 'rental')
                    {{ @\App\RentalUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                @endif
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.agent') }}
                    : </strong> {{ @\App\User::find($deal->agent_id)->name }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.agent_commission') }}
                    : </strong> {{ $deal->agent_commission }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.company_commission') }}
                    : </strong> {{ $deal->company_commission }}
                <br>
                <hr>
            </div>
            <div class="col-md-6">
                <strong>{{ trans('admin.description') }}
                    : </strong>{{ $deal->description }}
                <br>
                <hr>
            </div>
        </div>
    </div>
@endsection