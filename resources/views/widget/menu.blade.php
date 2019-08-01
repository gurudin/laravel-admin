<ul class="lay-menu">
  @foreach ($menus as $menu)
  <li class="lay-menu-item">
    <a href="{{$menu['route'] ? explode('{', $menu['route'])[0] : 'javascript:;'}}"
      class="{{ isset($menu['children']) ? 'uri-to' : '' }}">
      <i class="{{$menu['data']['icon'] ? $menu['data']['icon'] : 'fa'}}"></i>
      <cite>{{$menu['title']}}</cite>
      <span class="{{ isset($menu['children']) ? 'fa fa-caret-down' : 'fa' }}"></span>
    </a>

    @if (isset($menu['children']))
    <dl class="lay-nav-child">
      @foreach ($menu['children'] as $child1)
        <dd>
          <a href="{{ $child1['route'] ? explode('{', $child1['route'])[0] : 'javascript:;' }}"
            class="{{ isset($child1['children']) ? 'uri-to' : '' }}">
            <i class="{{$child1['data']['icon'] ? $child1['data']['icon'] : 'fa'}}"></i>
            <cite>{{$child1['title']}}</cite>
            <span class="{{ isset($child1['children']) ? 'fa fa-caret-down' : 'fa' }}"></span>
          </a>

          @if (isset($child1['children']))
          <dl class="lay-nav-child">
            @foreach ($child1['children'] as $children)
              <dd>
                <a href="{{ $children['route'] ? explode('{', $children['route'])[0] : 'javascript:;' }}">
                  <i class="{{$children['data']['icon'] ? $children['data']['icon'] : 'fa'}}"></i>
                  <cite>{{$children['title']}}</cite>
                </a>
              </dd>
            @endforeach
          </dl>
          @endif
        </dd>
      @endforeach
      </dl>
    @endif
    
  </li>
  @endforeach
</ul>
