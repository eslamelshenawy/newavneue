@extends('website.index')
@section('content')
    <!-- News Details Start -->
    <section id="news-section-1" class="news-section-details property-details padding_top">
        <div class="container">

            <div class="row heading_space">

                <div class="col-md-8">
                    @foreach($types as $type)
                        <div class="col-sm-6 not-filter">
                            <div class="property_item" style="margin-bottom:10px; ">
                                <div class="image">
                                    <a href="{{ url($route.'/'.$type->en_name.'-'.$type->id) }}"><img src="{{ url('uploads/'.$type->image) }}" alt="{{ $type->{app()->getLocale().'_name'} }}" class="img-responsive"></a>

                                    {{--<span class="tag_t">{{ __('admin.resale') }}</span>--}}
                                </div>
                                <div class="proerty_content">
                                    <span>{{ $type->{app()->getLocale().'_name'} }}</span>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
                <aside class="col-md-4 col-xs-12 ">
                    <div class="property-query-area clearfix">
                        @include('website/search')
                    </div>
                    @include('website.featured_projects',['featured',$featured])
                </aside>

            </div>

        </div>
    </section>
    <!-- News Details End -->
@endsection