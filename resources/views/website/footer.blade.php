<!--Footer-->
<footer class="footer_third" >
    <div class="container padding_top about-background2" >
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="footer_panel bottom30">
                    <a style="width: 100%;" href="{{ url('/') }}" class="logo bottom30"><img src="{{ url('website_style/images/logo.png')}}" width="200px" alt="logo"></a>
                    @if(app()->getLocale() == 'en')
                        <p class="bottom15">{{ @App\Setting::first()->about_hub }}
                        </p>
                    @else
                        <p class="bottom15">{{ @App\Setting::first()->ar_about_hub }}
                        </p>
                    @endif

                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="footer_panel bottom30">
                    <h4 class="bottom30 heading">{{ __('admin.latest_news') }}</h4>
                    @foreach(@App\Event::limit(3)->get() as $news)
                        <div class="media bottom30">
                            <div class="media-body">
                                <a href="{{ url('event/'.$news->id) }}">{{ $news->{app()->getLocale().'_title'} }}</a>
                                <span><i class="icon-clock5"></i>{{ date('M d Y',strtotime($news->created_at)) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="footer_panel bottom30">
                    <h4 class="bottom30 heading">{{ __('admin.Subscribe') }}</h4>
                    <p>{{ __('admin.subscribe') }}</p>
                    <form class="top30" method="post" action="{{ url('newsletter') }}">
                        {{ csrf_field() }}
                        <input class="search" name="email" placeholder="{{ __('admin.email_enter') }}" type="text">
                        <button class="button_s" href="#">
                            <i class="icon-mail-envelope-open"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!--CopyRight-->
        <div class="copyright_simple">
            <div class="row">
                <div class="col-md-6 col-sm-5 top20 bottom20">
                    <p>Copyright &copy; 2018 <span>PropertzCrm</span>. All rights reserved.</p>
                </div>
                <div class="col-md-6 col-sm-7 text-right top15 bottom10">
                    <ul class="social_share">
                        @foreach(@App\HubSocial::all() as $social)
                            <li>
                            <a href="{{ $social->link }}" class="facebook">
                                <img src="{{ url('uploads/'.$social->web_icon) }}" style="height: 30px">
                            </a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--CopyRight-->

<script src="{{  asset('website_style/js/jquery-2.1.4.js')}}"></script>
<script src="{{  asset('website_style/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website_style/css/slick/slick.js')}}"></script>
<script src="{{  asset('website_style/js/jquery.appear.js')}}"></script>
<script src="{{  asset('website_style/js/jquery-countTo.js') }}"></script>
<script src="{{  asset('website_style/js/bootsnav.js') }}"></script>
<script src="{{  asset('website_style/js/masonry.pkgd.min.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.parallax-1.1.3.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.cubeportfolio.min.js') }}"></script>
<script src="{{  asset('website_style/js/owl.carousel.min.js') }}"></script>
<script src="{{  asset('website_style/js/selectbox-0.2.min.js') }}"></script>
<script src="{{  asset('website_style/js/zelect.js') }}"></script>
<script src="{{  asset('website_style/js/editor.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.fancybox.js') }}"></script>
<script src="{{  asset('website_style/js/dropzone.min.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{  asset('website_style/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.actions.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.layeranimation.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.navigation.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.parallax.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.slideanims.min.js') }}"></script>
<script src="{{  asset('website_style/js/revolution.extension.video.min.js') }}"></script>
<script src="{{  asset('website_style/js/functions.js') }}"></script>
<script src="{{  asset('website_style/js/custom-file-input.js') }}"></script>
<script src="{{  asset('website_style/js/range-Slider.min.js') }}"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{  asset('style/select2/dist/js/select2.full.min.js') }}"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>

<script>
    $('.select2').select2();
</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=5a4a2a6c9d192f00137436a8&product=inline-share-buttons"></script>

@yield('js')

</body>
</html>

