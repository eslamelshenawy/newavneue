@extends('website.index')
@section('content')
    <!-- Listing Start -->
    <section id="listing1" class="listing1 about-background" style="min-height: 350px">

        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                    <div class="col-sm-12 about-text">
                        <div class="col-xs-12">
                        <h2 class="arabic-text about-title">{{ __('admin.our_story') }}</h2>
                        </div>
                        @if(app()->getLocale() == 'en')
                            <p class="" style="color: #000">{{ @App\Setting::first()->about_hub }}</p>
                        @else
                            <p class="arabic-text" style="color: #000">{{ @App\Setting::first()->ar_about_hub }}</p>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection