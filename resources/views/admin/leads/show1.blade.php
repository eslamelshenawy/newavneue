@extends('admin.index')

@section('content')
    <div class="col-md-7">
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
                        <li class="active"><a href="#main_info" data-toggle="tab"
                                              aria-expanded="false">{{ trans('admin.main_info') }}</a></li>
                        <li class=""><a href="#contacts" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.contacts') }}</a></li>
                        <li class=""><a href="#suggestion" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.suggestion') }}</a></li>
                        <li class=""><a href="#new_home" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.new') }} {{ trans('admin.home') }}</a></li>
                        <li class=""><a href="#rental" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.rental') }}</a></li>
                        <li class=""><a href="#resale" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.resale') }}</a></li>
                        <li class="pull-right"><a href="#" data-toggle="modal" data-target="#sendCIL">
                                {{ trans('admin.send_cil') }}</a></li>
                        <div id="sendCIL" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.lead_source') }}</h4>
                                    </div>
                                    {!! Form::open(['method'=>'post','url'=>adminPath().'/send_cil']) !!}
                                    <div class="modal-body">
                                        <select required class="form-control select2" style="width: 100%"
                                                name="developer_id" data-placeholder="{{ trans('admin.developer') }}">
                                            <option></option>
                                            @foreach(@\App\Developer::all() as $developer)
                                                <option value="{{ $developer->id }}">{{ $developer->{app()->getLocale().'_name'} }}</option>
                                            @endforeach
                                            <input name="lead_id" type="hidden" value="{{ $show->id }}">
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-flat"
                                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                                        <button type="submit"
                                                class="btn btn-success btn-flat">{{ trans('admin.send') }}</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                            </div>
                        </div>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" style="min-height: 650px;" id="main_info">
                            <div class="col-md-6">
                                <strong>{{ trans('admin.first_name') }}
                                    : </strong>{{ trans('admin.'.$show->prefix_name).' '.$show->first_name }}
                                <br>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <strong>{{ trans('admin.last_name') }} : </strong>{{ $show->last_name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ trans('admin.ar_first_name') }} : </strong>{{ $show->ar_first_name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ trans('admin.ar_last_name') }} : </strong>{{ $show->ar_last_name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.job_title') }} : </strong><a
                                        href="{{ url(adminPath().'/titles/'.$show->title_id) }}">{{ @\App\Title::find($show->title_id) }}</a>
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.email') }} : </strong><a
                                        href="mailto:{{ $show->email }}">{{ $show->email }}</a>
                                <br>
                                <hr>
                            </div>
                            @php
                                $otherEmails = json_decode($show->other_emails);
                            @endphp
                            @if($otherEmails != null)
                                @foreach(@$otherEmails as $email)
                                    <div class="col-md-12">
                                        <strong>{{ trans('admin.other_emails') }} : </strong><a
                                                href="mailto:{{ $email }}">{{ $email }}</a>
                                        <br>
                                        <hr>
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-md-4">
                                <strong>{{ trans('admin.phone') }} : </strong>{{ $show->phone }}
                                <div class="pull-right">
                                    @php
                                        if($show->social) {
                                            $socials = json_decode($show->social);
                                        }else{
                                            $socials=(object)['whatsapp'=>'','viber'=>'','sms'=>''];

                                        }
                                    @endphp
                                    @if($socials->whatsapp== 1)
                                        <i class="fa fa-whatsapp" style="color: #34af23;"></i>
                                    @endif
                                    @if($socials->viber == 1)
                                        <img src="{{ url('viber.png') }}" height="18px">
                                    @endif
                                    @if($socials->sms == 1)
                                        <i class="fa fa-comments" style="color: #3b5998;"></i>
                                    @endif
                                </div>
                                <br>
                                <hr>
                            </div>
                            @php
                                $otherPhones = json_decode($show->other_phones);
                            @endphp
                            @if($otherPhones != null)
                                @foreach($otherPhones as $phones)
                                    @foreach($phones as $phone => $social)
                                        <div class="col-md-4">
                                            <strong>{{ trans('admin.other_phones') }} : </strong>{{ $phone }}
                                            <div class="pull-right">
                                                @if($social->whatsapp == 1)
                                                    <i class="fa fa-whatsapp" style="color: #34af23;"></i>
                                                @endif
                                                @if($social->viber == 1)
                                                    <img src="{{ url('viber.png') }}" height="18px">
                                                @endif
                                                @if($social->sms == 1)
                                                    <i class="fa fa-comments" style="color: #3b5998;"></i>
                                                @endif
                                            </div>
                                            <br>
                                            <hr>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endif
                            <div class="col-md-4">
                                <strong>{{ trans('admin.nationality') }}
                                    : </strong>{{ @\App\Country::find($show->nationality)->name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.country') }}
                                    : </strong>{{ @\App\Country::find($show->country_id)->name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.city') }}: </strong>{{ @\App\City::find($show->city_id)->name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.address') }} : </strong>{{ $show->address }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.religion') }} : </strong>{{ trans('admin.'.$show->religion) }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.birth_date') }} : </strong>{{ date('Y-m-d',$show->birth_date) }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.industry') }}
                                    : </strong>{{ @\App\Industry::find($show->industry_id)->name }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.company') }} : </strong>{{ $show->company }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.school') }} : </strong>{{ $show->school }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.club') }} : </strong>{{ $show->club }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ trans('admin.facebook') }} : </strong><a href="{{ $show->facebook }}"
                                                                                    class="fa fa-facebook"
                                                                                    target="_blank"></a>
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <strong>{{ trans('admin.notes') }} : </strong>{{ $show->notes }}
                                <br>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <strong>{{ trans('admin.image') }} : </strong><br>
                                <img src="{{ url('/uploads/'.$show->image) }}" class="img-thumbnail" alt="Cinque Terre"
                                     width="304"
                                     height="236">
                            </div>
                        </div>
                        <div class="tab-pane" id="contacts">
                            <a data-toggle="modal" data-target="#addContact"
                               class="btn btn-primary btn-flat fa fa-user hidden"> {{ trans('admin.add_contact') }} </a>
                            <div id="addContact" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.contact') }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            {!! Form::open(['url' => adminPath().'/contacts']) !!}
                                            <div class="form-group @if($errors->has('name')) has-error @endif">
                                                <label>{{ trans('admin.name') }}</label>
                                                {!! Form::text('name','',['class' => 'form-control', 'placeholder' => trans('admin.name')]) !!}
                                            </div>
                                            <div class="form-group @if($errors->has('description')) has-error @endif">
                                                <label>{{ trans('admin.description') }}</label>
                                                {!! Form::textarea('description','',['class' => 'form-control', 'placeholder' => trans('admin.description'),'rows'=>5]) !!}
                                            </div>
                                            <button type="submit"
                                                    class="btn btn-primary btn-flat">{{ trans('admin.submit') }}</button>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default btn-flat"
                                                    data-dismiss="modal">{{ trans('admin.close') }}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.id') }}</th>
                                    <th>{{ trans('admin.name') }}</th>
                                    <th>{{ trans('admin.relation') }}</th>
                                    <th>{{ trans('admin.email') }}</th>
                                    <th>{{ trans('admin.phone') }}</th>
                                    <th>{{ trans('admin.show') }}</th>
                                    <th>{{ trans('admin.delete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(@\App\Contact::where('lead_id',$show->id)->get() as $contact)
                                    <tr>
                                        <td>{{ $contact->id }}</td>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->relation }}</td>
                                        <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                        <td>{{ $contact->phone }}</td>
                                        <td><a data-toggle="modal" data-target="#show{{ $contact->id }}"
                                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                                        <td><a data-toggle="modal" data-target="#delete{{ $contact->id }}"
                                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                                    </tr>
                                </tbody>
                                <div id="show{{ $contact->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">{{ trans('admin.show') . ' ' . trans('admin.contact') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-6">
                                                    <strong>{{ trans('admin.name') }} : </strong>{{ $contact->name }}
                                                    <br>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ trans('admin.relation') }}
                                                        : </strong>{{ $contact->relation }}
                                                    <br>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ trans('admin.phone') }} : </strong>{{ $contact->phone }}
                                                    @php
                                                        $contacSocials = json_decode($contact->social);
                                                    @endphp
                                                    @if(@$contacSocials->whatsapp == 1)
                                                        <i class="fa fa-whatsapp" style="color: #34af23;"></i>
                                                    @endif
                                                    @if(@$contacSocials->viber == 1)
                                                        <img src="{{ url('viber.png') }}" height="18px">
                                                    @endif
                                                    @if(@$contacSocials->sms == 1)
                                                        <i class="fa fa-comments" style="color: #3b5998;"></i>
                                                    @endif
                                                    <br>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ trans('admin.email') }} : </strong><a
                                                            href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                                    <br>
                                                    <hr>
                                                </div>
                                                @php
                                                    $contactEmails = json_decode($contact->other_emails)
                                                @endphp
                                                @if($contactEmails != null)
                                                    @foreach($contactEmails as $emails)
                                                        <div class="col-md-12">
                                                            <strong>{{ trans('admin.other_emails') }} : </strong><a
                                                                    href="mailto:{{ $emails }}">{{ $emails }}</a>
                                                            <br>
                                                            <hr>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                @php
                                                    $contactPhones = json_decode($contact->other_phones);
                                                @endphp
                                                @if($contactPhones != null)
                                                    @foreach($contactPhones as $phones)
                                                        @foreach($phones as $phone => $social)
                                                            <div class="col-md-12">
                                                                <strong>{{ trans('admin.other_phones') }}
                                                                    : </strong>{{ $phone }}
                                                                <div class="pull-right">
                                                                    @if($social->whatsapp == 1)
                                                                        <i class="fa fa-whatsapp"
                                                                           style="color: #34af23;"></i>
                                                                    @endif
                                                                    @if($social->viber == 1)
                                                                        <img src="{{ url('viber.png') }}" height="18px">
                                                                    @endif
                                                                    @if($social->sms == 1)
                                                                        <i class="fa fa-comments"
                                                                           style="color: #3b5998;"></i>
                                                                    @endif
                                                                </div>
                                                                <br>
                                                                <hr>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                                <div class="col-md-12">
                                                    <strong>{{ trans('admin.job_title') }}
                                                        : </strong>{{ @\App\Title::find($contact->title_id)->name }}
                                                    <br>
                                                    <hr>
                                                </div>
                                                <div class="col-md-12">
                                                    <strong>{{ trans('admin.nationality') }}
                                                        : </strong>{{ @\App\Country::find($contact->nationality)->name }}
                                                    <br>
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div id="delete{{ $contact->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.contact') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('admin.delete') . ' ' . $contact->name }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                {!! Form::open(['method'=>'DELETE','route'=>['contacts.destroy',$contact->id]]) !!}
                                                <button type="button" class="btn btn-default btn-flat"
                                                        data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                <button type="submit"
                                                        class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </table>
                        </div>
                        <div class="tab-pane" id="suggestion">
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.id') }}</th>
                                    <th>{{ trans('admin.lead') }}</th>
                                    <th>{{ trans('admin.unit_type') }}</th>
                                    <th>{{ trans('admin.price').' '.trans('admin.from') }}</th>
                                    <th>{{ trans('admin.price').' '.trans('admin.to') }}</th>
                                    <th>{{ trans('admin.delivery_date') }}</th>
                                    <th>{{ trans('admin.show') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(App\Request::where('lead_id',$show->id)->get() as $req)
                                    <tr>
                                        <td>{{ $req->id }}</td>
                                        <td>{{ @\App\Lead::find($req->lead_id)->first_name }}</td>
                                        <td>{{ @\App\UnitType::find($req->unit_type_id)->{app()->getLocale().'_name'} }}</td>
                                        <td>{{ $req->price_from }}</td>
                                        <td>{{ $req->price_to }}</td>
                                        <td>{{ $req->date }}</td>
                                        <td><a href="{{ url(adminPath().'/requests/'.$req->id) }}"
                                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                        <div class="tab-pane active" style="min-height: 650px;" id="new_home">
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.title') }}</th>
                                    <th>{{ trans('admin.start_price') }}</th>
                                    <th>{{ trans('admin.area') }}</th>
                                    <th>{{ trans('admin.project') }}</th>
                                    <th>{{ trans('admin.phase') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($newHomes = @App\Property::where('lead_id',$show->id)->get())
                                @foreach($newHomes as $newHome)
                                    @php($phase = @\App\Phase::find($newHome->phase_id))
                                    @php($project = @\App\Project::find($phase->project_id))
                                    <tr>
                                        <td>{{ $newHome->{app()->getLocale().'_name'} }}</td>
                                        <td>{{ $newHome->start_price }}</td>
                                        <td>{{ $newHome->area_from }} <i
                                                    class="fa fa-arrows-h"></i> {{ $newHome->area_to }} </td>
                                        <td>{{ $project->{app()->getLocale().'_name'} }}</td>
                                        <td>{{ $phase->{app()->getLocale().'_name'} }}</td>
                                        @php($phase = @App\Phase::find($newHome->phase_id))
                                        @php($project = @App\Project::find($phase->project_id))
                                        <td><a class="btn btn-primary btn-flat "
                                               href="{{ url(adminPath().'/resale_units/create') }}?lead_id={{$show->id}}&ar_name={{ $newHome->ar_name }}&en_name={{ $newHome->en_name }}&ar_description{{ $newHome->ar_description }}&en_description{{ $newHome->en_description }}&unit_price={{ $newHome->start_price  }}&broker={{ $newHome->user_id }}&project={{ $project->id  }}&area={{ $newHome->area_from}}&type={{ $newHome->type}}&type_id={{ $newHome->unit_id}}&lng={{ $project->lng}}&lat={{ $project->lat}}&zoom={{ $project->zoom}}&location={{ $newHome->location_id }}}&ar_address={{ $newHome->ar_address }}&en_address={{ $newHome->en_address }}">
                                                {{ trans('admin.convert_to') }} {{ trans('admin.resale') }}</a></td>
                                        <td><a class="btn btn-primary btn-flat "
                                               href="{{ url(adminPath().'/rental_units/create') }}?lead_id={{$show->id}}&ar_name={{ $newHome->ar_name }}&en_name={{ $newHome->en_name }}&ar_description{{ $newHome->ar_description }}&en_description{{ $newHome->en_description }}&unit_price={{ $newHome->start_price  }}&broker={{ $newHome->user_id }}&project={{ $project->id  }}&area={{ $newHome->area_from}}&type={{ $newHome->type}}&type_id={{ $newHome->unit_id}}&lng={{ $project->lng}}&lat={{ $project->lat}}&zoom={{ $project->zoom}}&location={{ $newHome->location_id }}&ar_address={{ $newHome->ar_address }}&en_address={{ $newHome->en_address }}">
                                                {{ trans('admin.convert_to') }} {{ trans('admin.rental') }}</a></td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>

                        </div>
                        <div class="tab-pane active" style="min-height: 650px;" id="rental">

                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.property') }}</th>
                                    <th>{{ trans('admin.title') }}</th>
                                    <th>{{ trans('admin.status') }}</th>
                                    <th>{{ trans('admin.location') }}</th>
                                    <th>{{ trans('admin.rent') }}</th>
                                    <th>{{ trans('admin.rooms') }}</th>
                                    <th>{{ trans('admin.bathrooms') }}</th>
                                    <th>{{ trans('admin.area') }}</th>
                                    <th>{{ trans('admin.delivery_date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($rental = @App\RentalUnit::where('lead_id',$show->id)->get())
                                @foreach($rental as $resaleUnit)
                                    <tr>
                                        <td><img src="{{ url('/uploads/'.$resaleUnit->image) }}" width="50px"></td>
                                        <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                        <td>{{ $resaleUnit->availability }}</td>
                                        <td>{{ @App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                        <td>{{ $resaleUnit->rent }}</td>
                                        <td>{{ $resaleUnit->rooms }}</td>
                                        <td>{{ $resaleUnit->bathrooms }}</td>
                                        <td>{{ $resaleUnit->area }}</td>
                                        <td>{{ $resaleUnit->delivery_date }}</td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                        <div class="tab-pane active" style="min-height: 650px;" id="resale">
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.property') }}</th>
                                    <th>{{ trans('admin.title') }}</th>
                                    <th>{{ trans('admin.status') }}</th>
                                    <th>{{ trans('admin.location') }}</th>
                                    <th>{{ trans('admin.price') }}</th>
                                    <th>{{ trans('admin.rooms') }}</th>
                                    <th>{{ trans('admin.bathrooms') }}</th>
                                    <th>{{ trans('admin.area') }}</th>
                                    <th>{{ trans('admin.delivery_date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($resale = @App\ResaleUnit::where('lead_id',$show->id)->get())
                                @foreach($resale as $resaleUnit)
                                    <tr>
                                        <td><img src="{{ url('uploads/'.$resaleUnit->image) }}" width="75 px"></td>
                                        <td>{{ $resaleUnit->{app()->getLocale().'_title'} }}</td>
                                        <td>{{ trans('admin.'.$resaleUnit->availability) }}</td>
                                        <td>{{ @\App\Location::find($resaleUnit->location)->{app()->getLocale().'_name'} }}</td>
                                        <td>{{ $resaleUnit->total }}</td>
                                        <td>{{ $resaleUnit->rooms }}</td>
                                        <td>{{ $resaleUnit->bathrooms }}</td>
                                        <td>{{ $resaleUnit->area }}</td>
                                        <td>{{ $resaleUnit->delivery_date }}</td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box">

            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('admin.activity') }}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">

                        <li class="active"><a href="#calls" data-toggle="tab"
                                              aria-expanded="true">{{ trans('admin.calls') }}</a></li>
                        <li class=""><a href="#meetings" data-toggle="tab"
                                        aria-expanded="true">{{ trans('admin.meetings') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="calls">
                            <a href="{{ url(adminPath().'/calls/create?lead='.$show->id) }}"
                               class="btn btn-primary btn-flat fa fa-phone" target="_blank"
                               data-target="#myModal"> {{ trans('admin.add_call') }} </a>

                        </div>

                        <div class="tab-pane" id="meetings">
                            <a href="{{ url(adminPath().'/meetings/create?lead='.$show->id) }}"
                               class="btn btn-primary btn-flat fa fa-handshake-o"
                               target="_blank"> {{ trans('admin.add_meeting') }} </a>
                            <table class="table table-hover table-striped datatable">
                                <thead>
                                <tr>
                                    <th>{{ trans('admin.id') }}</th>
                                    <th>{{ trans('admin.contact') }}</th>
                                    <th>{{ trans('admin.duration') }}</th>
                                    <th>{{ trans('admin.probability') }}</th>
                                    <th>{{ trans('admin.show') }}</th>
                                    <th>{{ trans('admin.delete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(@\App\Meeting::where('lead_id',$show->id)->get() as $meeting)
                                    <tr>
                                        <td>{{ $meeting->id }}</td>
                                        <td>
                                            @if($meeting->contact_id == 0)
                                                {{ $show->first_name . ' ' . $show->last_name }}
                                            @else
                                                {{ @\App\Contact::find($meeting->contact_id)->name }}
                                            @endif
                                        </td>
                                        <td>{{ $meeting->duration }}</td>
                                        <td>{{ $meeting->probability }}%</td>
                                        <td><a href="{{ url(adminPath().'/meetings/'.$meeting->id) }}"
                                               class="btn btn-primary btn-flat">{{ trans('admin.show') }}</a></td>
                                        <td><a data-toggle="modal" data-target="#delete{{ $meeting->id }}"
                                               class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</a></td>
                                    </tr>
                                </tbody>
                                <div id="delete{{ $meeting->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">{{ trans('admin.delete') . ' ' . trans('admin.call') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ trans('admin.delete') . ' #' . $meeting->id }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                {!! Form::open(['method'=>'DELETE','route'=>['meetings.destroy',$meeting->id]]) !!}
                                                <button type="button" class="btn btn-default btn-flat"
                                                        data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                <button type="submit"
                                                        class="btn btn-danger btn-flat">{{ trans('admin.delete') }}</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.datatable').dataTable({
            'paging': true,
            'lengthChange': false,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true
        })
    </script>
@stop