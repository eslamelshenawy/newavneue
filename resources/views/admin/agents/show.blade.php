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
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="@if(!session()->has('redirect_back')) active @endif tab-button"><a href="#info" data-toggle="tab" class="padding-tap"
                                                     aria-expanded="true">{{ trans('admin.main_info') }}</a></li>
                    <li class="@if(session()->has('redirect_back')) active @endif"><a href="#finance" data-toggle="tab" class="padding-tap"
                                    aria-expanded="true">{{ trans('admin.finance') }}</a></li>
                    <li class=""><a href="#resale_units" data-toggle="tab" class="padding-tap"
                                    aria-expanded="true">{{ trans('admin.resale_units') }}</a></li>
                    <li class=""><a href="#rental_units" data-toggle="tab" class="padding-tap"
                                    aria-expanded="true">{{ trans('admin.rental_units') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane @if(!session()->has('redirect_back')) active @endif" id="info">
                        <strong>{{ trans('admin.id') }} : </strong>{{ $show->id }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.name') }} : </strong>{{ $show->name }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.email') }} : </strong>{{ $show->email }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.phone') }} : </strong>{{ $show->phone }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.agent_type') }} : </strong>{{ $show->source }}
                        <br>
                        <hr>
                        <strong>{{ trans('admin.image') }} : </strong><br>
                        <img src="/uploads/{{ $show->image }}" class="img-thumbnail" alt="Cinque Terre" width="304"
                             height="236">
                    </div>
                    <div class="tab-pane @if(session()->has('redirect_back')) active @endif" id="finance">
                        @php
                            $main = \App\ClosedDeal::where('agent_id',$show->id)->sum('agent_commission');
                            $mainPending = \App\ClosedDeal::where('agent_id',$show->id)->where('agent_payment_status','pending')->sum('agent_commission');
                            $mainPayed = \App\ClosedDeal::where('agent_id',$show->id)->where('agent_payment_status','payed')->sum('agent_commission');
                            $sub = \App\DealAgents::where('agent_id',$show->id)->sum('agent_commission');
                            $subPending = \App\DealAgents::where('agent_id',$show->id)->where('agent_payment_status','pending')->sum('agent_commission');
                            $subPayed = \App\DealAgents::where('agent_id',$show->id)->where('agent_payment_status','payed')->sum('agent_commission');
                            $total = $main + $sub;
                            $totalPending = $mainPending + $subPending;
                            $totalPayed = $mainPayed + $subPayed;
                        @endphp
                        <table class="table table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 25%; text-align: center"><i class="fa fa-money"></i></th>
                                <th style="width: 25%; text-align: center">{{ __('admin.main') }}</th>
                                <th style="width: 25%; text-align: center">{{ __('admin.sub') }}</th>
                                <th style="width: 25%; text-align: center">{{ __('admin.total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th style="text-align: center">{{ __('admin.pending') }}</th>
                                <td style="text-align: center">{{ $mainPending }}</td>
                                <td style="text-align: center">{{ $subPending }}</td>
                                <th style="text-align: center">{{ $totalPending }}</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">{{ __('admin.payed') }}</th>
                                <td style="text-align: center">{{ $mainPayed }}</td>
                                <td style="text-align: center">{{ $subPayed }}</td>
                                <th style="text-align: center">{{ $totalPayed }}</th>
                            </tr>
                            <tr>
                                <th style="text-align: center">{{ __('admin.total') }}</th>
                                <th style="text-align: center">{{ $main }}</th>
                                <th style="text-align: center">{{ $sub }}</th>
                                <th style="text-align: center">{{ $total }}</th>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('admin.seller') }}</th>
                                <th>{{ __('admin.buyer') }}</th>
                                <th>{{ __('admin.commission') }}</th>
                                <th>{{ __('admin.type') }}</th>
                                <th>{{ __('admin.personal_commercial') }}</th>
                                <th>{{ __('admin.unit') }}</th>
                                <th>{{ __('admin.show') }}</th>
                                <th>{{ __('admin.status') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\ClosedDeal::where('agent_id',$show->id)->get() as $deal)
                                @php($proposal = \App\Proposal::find($deal->proposal_id))
                                <tr>
                                    <td>{{ @\App\Lead::find($deal->seller_id)->first_name . ' ' . @\App\Lead::find($deal->seller_id)->last_name }}</td>
                                    <td>{{ @\App\Lead::find($deal->buyer_id)->first_name . ' ' . @\App\Lead::find($deal->buyer_id)->last_name }}</td>
                                    <td>{{ $deal->agent_commission }}</td>
                                    <td>{{ __('admin.main') }}</td>
                                    <td>{{ __('admin.'.$proposal->personal_commercial) }}</td>
                                    <td>
                                        @if($proposal->unit_type == 'resale')
                                            <a href="{{ url(adminPath().'/resale_units/'.$proposal->unit_id) }}">
                                                {{ @\App\ResaleUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                                            </a>
                                        @elseif($proposal->unit_type == 'rental')
                                            <a href="{{ url(adminPath().'rental_units'.$proposal->unit_id) }}">
                                                {{ @\App\RentalUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                                            </a>
                                        @elseif($proposal->unit_type == 'new_home')
                                            <a href="#">
                                                {{ @\App\Property::find($proposal->unit_id)->{app()->getLocale().'_name'} }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url(adminPath().'/deals/'.$deal->id) }}"
                                           class="btn btn-flat btn-primary">{{ __('admin.show') }}</a>
                                    </td>
                                    <td>
                                        @if($deal->agent_payment_status != 'payed')
                                            <a data-toggle="modal" data-target="#main{{ $deal->id }}"
                                               class="btn btn-flat btn-success">{{ __('admin.pay') }}</a>
                                        @else
                                            {{ __('admin.payed') }}
                                        @endif
                                    </td>
                                </tr>
                                <div id="main{{ $deal->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">{{ trans('admin.payed') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('admin.payed') . ' #' . $deal->id }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-flat"
                                                        data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                <a class="btn btn-success btn-flat"
                                                   href="{{ url(adminPath().'/main_payed/'.$deal->id) }}">{{ trans('admin.pay') }}</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                            @foreach(\App\DealAgents::where('agent_id',$show->id)->get() as $sub_deal)
                                @php($deal = \App\ClosedDeal::find($sub_deal->deal_id))
                                @php($proposal = \App\Proposal::find($deal->proposal_id))
                                <tr>
                                    <td>{{ @\App\Lead::find($deal->seller_id)->first_name . ' ' . @\App\Lead::find($deal->seller_id)->last_name }}</td>
                                    <td>{{ @\App\Lead::find($deal->buyer_id)->first_name . ' ' . @\App\Lead::find($deal->buyer_id)->last_name }}</td>
                                    <td>{{ $sub_deal->agent_commission }}</td>
                                    <td>{{ __('admin.sub') }}</td>
                                    <td>{{ __('admin.'.$proposal->personal_commercial) }}</td>
                                    <td>
                                        @if($proposal->unit_type == 'resale')
                                            <a href="{{ url(adminPath().'/resale_units/'.$proposal->unit_id) }}">
                                                {{ @\App\ResaleUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                                            </a>
                                        @elseif($proposal->unit_type == 'rental')
                                            <a href="{{ url(adminPath().'rental_units'.$proposal->unit_id) }}">
                                                {{ @\App\RentalUnit::find($proposal->unit_id)->{app()->getLocale().'_title'} }}
                                            </a>
                                        @elseif($proposal->unit_type == 'new_home')
                                            <a href="#">
                                                {{ @\App\Property::find($proposal->unit_id)->{app()->getLocale().'_name'} }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url(adminPath().'/deals/'.$sub_deal->deal_id) }}"
                                           class="btn btn-flat btn-primary">{{ __('admin.show') }}</a>
                                    </td>
                                    <td>
                                        @if($sub_deal->agent_payment_status != 'payed')
                                            <a data-toggle="modal" data-target="#sub{{ $sub_deal->id }}"
                                               class="btn btn-flat btn-success">{{ __('admin.pay') }}</a>
                                        @else
                                            {{ __('admin.payed') }}
                                        @endif
                                    </td>
                                </tr>
                                <div id="sub{{ $sub_deal->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">{{ trans('admin.payed') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('admin.payed') . ' #' . $deal->id }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-flat"
                                                        data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                <a class="btn btn-success btn-flat"
                                                   href="{{ url(adminPath().'/sub_payed/'.$sub_deal->id) }}">{{ trans('admin.pay') }}</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="resale_units">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('admin.image') }}</th>
                                <th>{{ __('admin.title') }}</th>
                                <th>{{ __('admin.lead') }}</th>
                                <th>{{ __('admin.unit_type') }}</th>
                                <th>{{ __('admin.personal_commercial') }}</th>
                                <th>{{ __('admin.price') }}</th>
                                <th>{{ __('admin.availability') }}</th>
                                <th>{{ __('admin.show') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($resale as $resale_unit)
                                <tr>
                                    <td><img src="{{ url('uploads/'.$resale_unit->image) }}" height="50px"></td>
                                    <td>{{ $resale_unit->title }}</td>
                                    <td><a href="{{ url(adminPath().'/leads/'.$resale_unit->lead_id) }}">{{ $resale_unit->first_name . ' ' . $resale_unit->last_name }}</a></td>
                                    <td>{{ $resale_unit->unit_type }}</td>
                                    <td>{{ __('admin.'.$resale_unit->type) }}</td>
                                    <td>{{ $resale_unit->price }}</td>
                                    <td>{{ $resale_unit->availability }}</td>
                                    <td><a href="{{ url(adminPath().'/resale_units/'.$resale_unit->id) }}" class="btn btn-flat btn-primary">{{ __('admin.show') }}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="rental_units">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('admin.image') }}</th>
                                <th>{{ __('admin.title') }}</th>
                                <th>{{ __('admin.lead') }}</th>
                                <th>{{ __('admin.unit_type') }}</th>
                                <th>{{ __('admin.personal_commercial') }}</th>
                                <th>{{ __('admin.price') }}</th>
                                <th>{{ __('admin.availability') }}</th>
                                <th>{{ __('admin.show') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rental as $rental_unit)
                                <tr>
                                    <td><img src="{{ url('uploads/'.$rental_unit->image) }}" height="50px"></td>
                                    <td>{{ $rental_unit->title }}</td>
                                    <td><a href="{{ url(adminPath().'/leads/'.$rental_unit->lead_id) }}">{{ $rental_unit->first_name . ' ' . $rental_unit->last_name }}</a></td>
                                    <td>{{ $rental_unit->unit_type }}</td>
                                    <td>{{ __('admin.'.$rental_unit->type) }}</td>
                                    <td>{{ $rental_unit->price }}</td>
                                    <td>{{ $rental_unit->availability }}</td>
                                    <td><a href="{{ url(adminPath().'/rental_units/'.$rental_unit->id) }}" class="btn btn-flat btn-primary">{{ __('admin.show') }}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection