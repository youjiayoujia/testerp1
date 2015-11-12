<header id="bjui-header">
    <div class="bjui-navbar-header">
        <button type="button" class="bjui-navbar-toggle btn-default" data-toggle="collapse" data-target="#bjui-navbar-collapse">
            <i class="fa fa-bars"></i>
        </button>
        <a class="bjui-navbar-logo" href="#">南京快悦ERP</a>
    </div>
    <nav id="bjui-navbar-collapse">
        <ul class="bjui-navbar-right">
            <li class="datetime"><div><span id="bjui-date"></span> <span id="bjui-clock"></span></div></li>
            <li><a href="#">消息 <span class="badge">4</span></a></li>
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">我的账户 <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="changepwd.html" data-toggle="dialog" data-id="changepwd_page" data-mask="true" data-width="400" data-height="260">&nbsp;<span class="glyphicon glyphicon-lock"></span> 修改密码&nbsp;</a></li>
                    <li><a href="#">&nbsp;<span class="glyphicon glyphicon-user"></span> 我的资料</a></li>
                    <li class="divider"></li>
                    <li><a href="login.html" class="red">&nbsp;<span class="glyphicon glyphicon-off"></span> 注销登陆</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="bjui-hnav">
        <div id="bjui-hnav-navbar-box">
            <ul id="bjui-hnav-navbar">
                @foreach($navigations as $key => $navigation)
                <li class="{{ $navigation['active'] }}"><a href="javascript:;" data-toggle="slidebar"><i class="{{ $navigation['icon'] }}"></i> {{ $navigation['name'] }}</a>
                    <div class="items hide" data-noinit="true">
                        <ul id="bjui-hnav-tree{{ $key + 1 }}" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="{{ $navigation['icon'] }}">
                            <li data-id="{{ $key + 1 }}" data-pid="0" data-faicon="folder-open-o" data-faicon-close="folder-o">{{ $navigation['name'] }}</li>
                            @foreach($navigation['subnavigations'] as $subkey => $subnavigation)
                            <li data-id="{{ ($key + 1).$subkey }}" data-pid="{{ $key + 1 }}" data-url="{{ route($subnavigation['url']) }}" data-tabid="{{ $subnavigation['tabid'] }}" data-faicon="{{ $subnavigation['icon'] }}">{{ $subnavigation['name'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-default bjui-hnav-more-right" title="导航菜单右移"><i class="fa fa-angle-double-right"></i></button>
    </div>
</header>