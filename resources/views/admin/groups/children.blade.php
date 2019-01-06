<ul class="">
    @foreach($childs as $child)
        <li style="cursor: pointer" class="child" id="{{ $child->id }}" child="{{ $child->id }}">
            <span class="fa fa-users"></span> {{ $child->name }}
            @if(count($child->children))
                @include('admin.groups.children',['childs' => $child->children])
            @endif
        </li>
    @endforeach
</ul>