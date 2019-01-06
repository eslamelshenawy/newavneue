<ul>
    @foreach($childs as $child)
        <li style="cursor: pointer" class="child" arabic="{{ $child->ar_name }}" english="{{ $child->en_name }}" id="{{ $child->id }}" data-title="{{ $child->{app()->getLocale().'_name'} }} " lat="{{ $child->lat }}" lng="{{ $child->lng }}" zoom="{{ $child->zoom }}" data-id="{{ $child->title }}">
            <span class="fa fa-thumb-tack"></span> {{ $child->{app()->getLocale().'_name'}  }}
            @if(count($child->childs))
                @include('admin.locations.manageChild',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>