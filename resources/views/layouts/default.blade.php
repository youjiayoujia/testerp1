@extends('layouts.base')
@section('title') 南京快悦ERP @stop

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @stop

    @section('head_css')
            <!-- bootstrap - css -->
    <link href="{{ asset('BJUI/themes/css/bootstrap.css') }}" rel="stylesheet">
    <!-- core - css -->
    <link href="{{ asset('BJUI/themes/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('BJUI/themes/blue/core.css') }}" id="bjui-link-theme" rel="stylesheet">
    <!-- plug - css -->
    <link href="{{ asset('BJUI/plugins/kindeditor_4.1.10/themes/default/default.css') }}" rel="stylesheet">
    <link href="{{ asset('BJUI/plugins/colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('BJUI/plugins/niceValidator/jquery.validator.css') }}" rel="stylesheet">
    <link href="{{ asset('BJUI/plugins/bootstrapSelect/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('BJUI/themes/css/FA/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('BJUI/plugins/uploadify/css/uploadify.css') }}" rel="stylesheet">
    <!--[if lte IE 7]>
    <link href="{{ asset('BJUI/themes/css/ie7.css') }}" rel="stylesheet">
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    @stop

@section('head_js')
<!--[if lte IE 9]>
    <script src="{{ asset('BJUI/other/html5shiv.min.js') }}"></script>
    <script src="{{ asset('BJUI/other/respond.min.js') }}"></script>
    <![endif]-->
    <!-- jquery -->
    <script src="{{ asset('BJUI/js/jquery-1.7.2.min.js') }}"></script>
    <script src="{{ asset('BJUI/js/jquery.cookie.js') }}"></script>
    <!--[if lte IE 9]>
    <script src="{{ asset('BJUI/other/jquery.iframe-transport.js') }}"></script>
    <![endif]-->
    <!-- BJUI.all 分模块压缩版 -->
    <script src="{{ asset('BJUI/js/bjui-all.js') }}"></script>
    <!-- 以下是B-JUI的分模块未压缩版，建议开发调试阶段使用下面的版本 -->
    <!--
<script src="{{ asset('BJUI/js/bjui-core.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-regional.zh-CN.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-frag.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-extends.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-basedrag.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-slidebar.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-contextmenu.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-navtab.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-dialog.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-taskbar.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-ajax.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-alertmsg.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-pagination.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-util.date.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-datepicker.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-ajaxtab.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-datagrid.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-tablefixed.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-tabledit.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-spinner.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-lookup.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-tags.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-upload.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-theme.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-initui.js') }}"></script>
<script src="{{ asset('BJUI/js/bjui-plugins.js') }}"></script>
-->
    <!-- plugins -->
    <!-- swfupload for uploadify && kindeditor -->
    <script src="{{ asset('BJUI/plugins/swfupload/swfupload.js') }}"></script>
    <!-- kindeditor -->
    <script src="{{ asset('BJUI/plugins/kindeditor_4.1.10/kindeditor-all.min.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/kindeditor_4.1.10/lang/zh_CN.js') }}"></script>
    <!-- colorpicker -->
    <script src="{{ asset('BJUI/plugins/colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- ztree -->
    <script src="{{ asset('BJUI/plugins/ztree/jquery.ztree.all-3.5.js') }}"></script>
    <!-- nice validate -->
    <script src="{{ asset('BJUI/plugins/niceValidator/jquery.validator.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/niceValidator/jquery.validator.themes.js') }}"></script>
    <!-- bootstrap plugins -->
    <script src="{{ asset('BJUI/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/bootstrapSelect/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/bootstrapSelect/defaults-zh_CN.min.js') }}"></script>
    <!-- icheck -->
    <script src="{{ asset('BJUI/plugins/icheck/icheck.min.js') }}"></script>
    <!-- dragsort -->
    <script src="{{ asset('BJUI/plugins/dragsort/jquery.dragsort-0.5.1.min.js') }}"></script>
    <!-- HighCharts -->
    <script src="{{ asset('BJUI/plugins/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/highcharts/themes/gray.js') }}"></script>
    <!-- ECharts -->
    <script src="{{ asset('BJUI/plugins/echarts/echarts.js') }}"></script>
    <!-- other plugins -->
    <script src="{{ asset('BJUI/plugins/other/jquery.autosize.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/uploadify/scripts/jquery.uploadify.min.js') }}"></script>
    <script src="{{ asset('BJUI/plugins/download/jquery.fileDownload.js') }}"></script>
    @stop

    @section('init')
            <!-- init -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            BJUI.init({
                JSPATH: 'BJUI/', //[可选]框架路径
                PLUGINPATH: 'BJUI/plugins/', //[可选]插件路径
                loginInfo: {url: '#', title: '登录', width: 400, height: 200}, // 会话超时后弹出登录对话框
                statusCode: {ok: 200, error: 300, timeout: 301}, //[可选]
                ajaxTimeout: 50000, //[可选]全局Ajax请求超时时间(毫秒)
                pageInfo: {
                    total: 'total',
                    pageCurrent: 'page',
                    pageSize: 'pageSize',
                    orderField: 'orderField',
                    orderDirection: 'orderDirection'
                }, //[可选]分页参数
                alertMsg: {displayPosition: 'topcenter', displayMode: 'slide', alertTimeout: 3000}, //[可选]信息提示的显示位置，显隐方式，及[info/correct]方式时自动关闭延时(毫秒)
                keys: {statusCode: 'statusCode', message: 'message'}, //[可选]
                ui: {
                    windowWidth: 0, //框架可视宽度，0=100%宽，> 600为则居中显示
                    showSlidebar: true, //[可选]左侧导航栏锁定/隐藏
                    clientPaging: true, //[可选]是否在客户端响应分页及排序参数
                    overwriteHomeTab: false //[可选]当打开一个未定义id的navtab时，是否可以覆盖主navtab(我的主页)
                },
                debug: true, // [可选]调试模式 [true|false，默认false]
                theme: 'sky' // 若有Cookie['bjui_theme'],优先选择Cookie['bjui_theme']。皮肤[五种皮肤:default, orange, purple, blue, red, green]
            })

            // main - menu
            $('#bjui-accordionmenu')
                    .collapse()
                    .on('hidden.bs.collapse', function (e) {
                        $(this).find('> .panel > .panel-heading').each(function () {
                            var $heading = $(this), $a = $heading.find('> h4 > a')

                            if ($a.hasClass('collapsed'))
                                $heading.removeClass('active')
                        })
                    })
                    .on('shown.bs.collapse', function (e) {
                        $(this).find('> .panel > .panel-heading').each(function () {
                            var $heading = $(this), $a = $heading.find('> h4 > a')

                            if (!$a.hasClass('collapsed'))
                                $heading.addClass('active')
                        })
                    })

            $(document).on('click', 'ul.menu-items > li > a', function (e) {
                var $a = $(this), $li = $a.parent(), options = $a.data('options').toObj()
                var onClose = function () {
                    $li.removeClass('active')
                }
                var onSwitch = function () {
                    $('#bjui-accordionmenu').find('ul.menu-items > li').removeClass('switch')
                    $li.addClass('switch')
                }

                $li.addClass('active')
                if (options) {
                    options.url = $a.attr('href')
                    options.onClose = onClose
                    options.onSwitch = onSwitch
                    if (!options.title)
                        options.title = $a.text()

                    if (!options.target)
                        $a.navtab(options)
                    else
                        $a.dialog(options)
                }

                e.preventDefault()
            })

            //时钟
            var today = new Date(), time = today.getTime()
            $('#bjui-date').html(today.formatDate('yyyy/MM/dd'))
            setInterval(function () {
                today = new Date(today.setSeconds(today.getSeconds() + 1))
                $('#bjui-clock').html(today.formatDate('HH:mm:ss'))
            }, 1000)
        })

        //菜单-事件
        function MainMenuClick(event, treeId, treeNode) {
            event.preventDefault()

            if (treeNode.isParent) {
                var zTree = $.fn.zTree.getZTreeObj(treeId)

                zTree.expandNode(treeNode, !treeNode.open, false, true, true)
                return
            }

            if (treeNode.target && treeNode.target == 'dialog')
                $(event.target).dialog({id: treeNode.tabid, url: treeNode.url, title: treeNode.name})
            else
                $(event.target).navtab({
                    id: treeNode.tabid,
                    url: treeNode.url,
                    title: treeNode.name,
                    fresh: treeNode.fresh,
                    external: treeNode.external
                })
        }
    </script>
    @stop

    @section('doc_begin')
            <!-- for doc begin -->
    <link type="text/css" rel="stylesheet" href="{{ asset('/js/syntaxhighlighter-2.1.382/styles/shCore.css') }}"/>
    <link type="text/css" rel="stylesheet"
          href="{{ asset('/js/syntaxhighlighter-2.1.382/styles/shThemeEclipse.css') }}"/>
    <script type="text/javascript" src="{{ asset('/js/syntaxhighlighter-2.1.382/scripts/brush.js') }}"></script>
    <link href="{{ asset('doc/doc.css') }}" rel="stylesheet">
    <script type="text/javascript">
        $(function () {
            SyntaxHighlighter.config.clipboardSwf = '{{ asset(' / js / syntaxhighlighter - 2.1.382 / scripts / clipboard.swf') }}'
            $(document).on(BJUI.eventType.initUI, function (e) {
                SyntaxHighlighter.highlight();
            })
        })
    </script>
    <!-- for doc end -->
@stop

@section('body_attr')
    id="bjui-window"
@stop

@section('body')
    @extends('layouts.header')
    <div id="bjui-container">
        <div id="bjui-leftside">
            <div id="bjui-sidebar-s">
                <div class="collapse"></div>
            </div>
            <div id="bjui-sidebar">
                <div class="toggleCollapse"><h2><i class="fa fa-bars"></i> 导航栏 <i class="fa fa-bars"></i></h2><a
                            href="javascript:;" class="lock"><i class="fa fa-lock"></i></a></div>
                <div class="panel-group panel-main" data-toggle="accordion" id="bjui-accordionmenu"
                     data-heightbox="#bjui-sidebar" data-offsety="26">
                </div>
            </div>
        </div>
        <div id="bjui-navtab" class="tabsPage">
            <div class="tabsPageHeader">
                <div class="tabsPageHeaderContent">
                    <ul class="navtab-tab nav nav-tabs">
                        <li data-url="{{ route('dashboard.index') }}">
                            <a href="javascript:;"><span><i class="fa fa-home"></i> #maintab#</span></a>
                        </li>
                    </ul>
                </div>
                <div class="tabsLeft"><i class="fa fa-angle-double-left"></i></div>
                <div class="tabsRight"><i class="fa fa-angle-double-right"></i></div>
                <div class="tabsMore"><i class="fa fa-angle-double-down"></i></div>
            </div>
            <ul class="tabsMoreList">
                <li><a href="javascript:;">#maintab#</a></li>
            </ul>
            <div class="navtab-panel tabsPageContent">
                <div class="navtabPage unitBox">
                    <div class="bjui-pageContent" style="background:#FFF;">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
    @extends('layouts.footer')
@stop