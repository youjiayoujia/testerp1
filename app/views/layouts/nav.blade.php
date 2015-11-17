<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> NJKY</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="#"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> 常用</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span> 产品 <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('product.index') }}">列表</a></li>
                        <li><a href="#">图库</a></li>
                    </ul>
                </li>
                <li><a href="#"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> 订单</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> 仓储</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-plane" aria-hidden="true"></span> 物流</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> 采购</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> 渠道</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span> 财务</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 客户</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">换色</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">系统</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">修改密码</a></li>
                        <li><a href="#">注销</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>