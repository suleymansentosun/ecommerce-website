<ul>
    @foreach($items as $menu_item)
        @if($menu_item->title === 'Follow me:')        
            <li>{{ $menu_item->title }}</li>
        @endif

        <li><a href="{{ $menu_item->link() }}"><i class="{{ $menu_item->title }}"></i></a></li>
    @endforeach
</ul>