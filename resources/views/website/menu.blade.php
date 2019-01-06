<div class="collapse col-xs-12 navbar-collapse pull-right" id="navbar-menu">
    <ul class="nav navbar-nav col-xs-12 pull-right" data-in="fadeIn" data-out="fadeOut">
        <li>
            <a href="{{ url('/') }}" >{{ __('admin.home_page') }}</a>
        </li>
        <li>
            <a href="{{ url('about') }}">{{ __('admin.about_page') }}</a>
        </li>
        <li><a href="{{ url('new-home-properties') }}">{{ __('admin.projects') }}</a></li>
        <li><a href="{{ url('new-home-commercial') }}">{{ __('admin.commercial') }}</a></li>
        <!--<li class="dropdown">-->
        <!--    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ __('admin.projects') }}</a>-->
        <!--    <ul class="dropdown-menu">-->
                
                
        <!--    </ul>-->
        <!--</li>-->
        <li class="dropdown">
            <a href="#"  class="dropdown-toggle" data-toggle="dropdown">{{ __('admin.resale') }}</a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('resale-properties') }}">{{ __('admin.personal') }}</a></li>
                <li><a href="{{ url('resale-commercial') }}">{{ __('admin.commercial') }}</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#"  class="dropdown-toggle" data-toggle="dropdown">{{ __('admin.rental') }}</a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('rental-properties') }}">{{__('admin.personal') }}</a></li>
                <li><a href="{{ url('rental-commercial') }}">{{__('admin.commercial') }}</a></li>
            </ul>
        </li>
        <li>
            <a href="{{ url('locations') }}">{{ __('admin.locations') }}</a>
        </li>
        <li>
            <a href="{{ url('news') }}">{{ __('admin.news') }}</a>
        </li>
        <li><a href="{{ url('contact') }}">{{ __('admin.contact_page') }}</a></li>
    </ul>
</div>