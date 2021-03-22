{{-- For submenu --}}
<ul class="menu-content">
    @php
    $subMenuPermissionsJson = file_get_contents(base_path('resources/json/submenuPermissions.json'));
    $subMenuPermissionsData = json_decode($subMenuPermissionsJson);
    @endphp
    @foreach($menu as $submenu)
    @if(in_array($subMenuPermissionsData->permissions->{$submenu->name}, session('permissions')))
    <?php
            $submenuTranslation = "";
            if(isset($menu->i18n)){
                $submenuTranslation = $menu->i18n;
            }
        ?>
    <li class="{{ (request()->is($submenu->url)) ? 'active' : '' }}">
        <a href="{{ url($submenu->url) }}">
            <i class="{{ isset($submenu->icon) ? $submenu->icon : "" }}"></i>
            <span class="menu-title" data-i18n="{{ $submenuTranslation }}">{{ $submenu->name }}</span>
        </a>
        @if (isset($submenu->submenu))
        @include('panels/submenu', ['menu' => $submenu->submenu])
        @endif
    </li>
    @endif
    @endforeach
</ul>
