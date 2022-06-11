<!DOCTYPE html>
<html class="loading" data-textdirection="ltr" lang="@yield('locale')">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="copyright" content="@yield('name')"/>
    <meta name="author" content="@yield('author')">
    <meta name="robots" content="all" />

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@@yield('twitter')"/>
    <meta name="twitter:creator" content="@@yield('twitter')"/>

    <meta property="og:image" content="https://acikkuran.com/static/logo2.png"/>
    <meta property="og:image:alt" content="@yield('keywords'){{ $meta['name'] }}"/>
    <meta property="og:image:width" content="200"/>
    <meta property="og:image:height" content="200"/>
    <meta property="og:locale" content="@yield('locale')"/>
    <meta property="og:site_name" content="@yield('name')"/>
    <meta property="og:title" content="@yield('name')"/>
    <meta property="og:url" content="@yield('url')"/>
    <meta property="og:description" content="@yield('description')"/>

    <meta itemprop="name" content="@yield('name')"/>
    <meta itemprop="image" content="{{ asset('images/logo.png') }}"/>
    <meta itemprop="description" content="@yield('description')">

    <title>@yield('title')</title>

    <link rel="apple-touch-icon" href="{{ asset('images/favicon/apple-touch-icon-152x152.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/chartist-js/chartist.min.css') }}"> --}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('') }}asset/vendors/chartist-js/chartist-plugin-tooltip.css">--}}
<!-- END: VENDOR CSS-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/materialize.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style-horizontal.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard-modern.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/pages/intro.min.css') }}"> --}}
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    <!-- END: Custom CSS-->

    @yield('head')


</head>
<!-- END: Head-->
<body class="horizontal-layout page-header-light horizontal-menu preload-transitions 2-columns" data-open="click" data-menu="horizontal-menu" data-col="2-columns">

<!-- BEGIN: Header-->
<header class="page-topbar" id="header">
    <div class="navbar">
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-purple-deep-orange">
            <div class="nav-wrapper">
                <ul class="left">
                    <li>
                        <h1 class="logo-wrapper">
                            <a class="brand-logo darken-1" href="{{config('app.url')}}">
                                <img src="{{ asset('images/logo.png') }}" alt="materialize logo">
                                <span class="logo-text hide-on-med-and-down">{{ $meta['name'] }}</span>
                            </a>
                        </h1>
                    </li>
                </ul>
{{--                <div class="header-search-wrapper hide-on-med-and-down"><i class="material-icons">search</i>--}}
{{--                    <input class="header-search-input z-depth-2" type="text" name="Search" placeholder="Explore Materialize" data-search="template-list">--}}
{{--                    <ul class="search-list collection display-none"></ul>--}}
{{--                </div>--}}

            @yield('search-large')

                <ul class="navbar-list right">
                    <li class="dropdown-language">
                        <a class="waves-effect waves-block waves-light translation-button" href="javascript:void(0);" data-target="translation-dropdown">
                            <span class="flag-icon flag-icon-{{$locale}}"></span>
                        </a>
                    </li>
                    <li class="hide-on-med-and-down">
                        <a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);">
                            <i class="material-icons">settings_overscan</i>
                        </a>
                    </li>
                    <li class="hide-on-large-only">
                        <a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);">
                            <i class="material-icons">search</i>
                        </a>
                    </li>
                    @yield('right-button')
{{--                    <li>--}}
{{--                        <a class="waves-effect waves-block waves-light sidenav-trigger" href="#" data-target="slide-out-right">--}}
{{--                            <i class="material-icons">format_indent_increase</i>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                </ul>

                <!-- translation-button-->
                <ul class="dropdown-content select-languages" id="translation-dropdown">
                    @foreach ($languageList as $language)
                        <li class="dropdown-item">
                            <a class="grey-text text-darken-1" data-locale="{{$language['code']}}" href="{{$language['url']}}">
                                <span class="flag-icon flag-icon-{{$language['code']}}"></span> {{$language['name']}}</a>
                        </li>
                    @endforeach
                </ul>

            </div>


            @yield('search-small')

{{--            <nav class="display-none search-sm">--}}
{{--                <div class="nav-wrapper">--}}
{{--                    <form id="navbarForm">--}}
{{--                        <div class="input-field search-input-sm">--}}
{{--                            <input class="search-box-sm" type="search" required="" id="search" placeholder="Explore Materialize" data-search="template-list">--}}
{{--                            <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>--}}
{{--                            <ul class="search-list collection search-list-sm display-none"></ul>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </nav>--}}
        </nav>
        <!-- BEGIN: Horizontal nav start-->
        <nav class="white hide-on-med-and-down" id="horizontal-nav">
            <div class="nav-wrapper">
                <ul class="left hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation">
                    @foreach ($menuList as $menu)
                        <li>
                            <a @class(['tab-menu', 'active' => $activeMenuSlug === $menu['slug']]) href="{{$url.$menu['slug']}}" >
                                <i class="material-icons">{{$menu['icon']}}</i>
                                <span class="tab-title">{{$menu['label']}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- END: Horizontal nav start-->
        </nav>
    </div>
</header>
<!-- END: Header-->
<ul class="display-none" id="default-search-main">
    <li class="auto-suggestion-title"></li>

</ul>
<ul class="display-none" id="page-search-title">

</ul>
<ul class="display-none" id="search-not-found">
    <li class="auto-suggestion"><a class="collection-item display-flex align-items-center" href="#"><span class="material-icons">error_outline</span><span class="member-info">No results found.</span></a></li>
</ul>



<!-- BEGIN: SideNav-->
<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-fixed hide-on-large-only">
    <div class="brand-sidebar sidenav-light"></div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed hide-on-large-only menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
        @foreach ($menuList as $menu)
            <li class="bold">
                <a class="waves-effect waves-cyan " href="{{$url.$menu['slug']}}" style="padding-top:1em" >
                    <i class="material-icons">{{$menu['icon']}}</i>
                    <span class="menu-title">{{$menu['label']}}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="navigation-background"></div><a class="sidenav-trigger btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
<!-- END: SideNav-->

<!-- BEGIN: Page Main-->
<div id="main">
    <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
            <div class="container">
                @yield('content')
            </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>
<!-- END: Page Main-->


<!-- BEGIN: Footer-->

<footer
    class="page-footer footer footer-static footer-dark gradient-45deg-purple-deep-purple gradient-shadow navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container">
            <span class="right ">&copy; 2021  <a href="{{$url}}" target="_blank">{{__('app.index_of_quran')}}</a> {{__('app.rights_reserved')}}</span>
        </div>
    </div>
</footer>

<!-- END: Footer-->
<!-- BEGIN VENDOR JS-->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/prism.min.js') }}"></script>
<script src="{{ asset('js/materialize.min.js') }}"></script>
<script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
<!-- <script src="{{ asset('') }}asset/js/vendors.min.js"></script> -->
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- {{--<script src="{{ asset('') }}asset/vendors/chartjs/chart.min.js"></script>--}}
{{--<script src="{{ asset('') }}asset/vendors/chartist-js/chartist.min.js"></script>--}}
{{--<script src="{{ asset('') }}asset/vendors/chartist-js/chartist-plugin-tooltip.js"></script>--}}
{{--<script src="{{ asset('') }}asset/vendors/chartist-js/chartist-plugin-fill-donut.min.js"></script>--}} -->
<!-- END PAGE VENDOR JS-->
<!-- BEGIN THEME  JS-->
<script src="{{ asset('js/plugins.min.js') }}"></script>
<script src="{{ asset('js/search.min.js') }}"></script>
<!-- <script src="{{ asset('') }}asset/js/headroom.min.js"></script> -->
<script src="{{ asset('js/custom-script.min.js') }}"></script>
<script src="{{ asset('js/customizer.min.js') }}"></script>
<!-- END THEME  JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="{{ asset('js/dashboard-modern.js') }}"></script>
<!-- <script src="{{ asset('') }}asset/js/scripts/intro.min.js"></script> -->
<!-- END PAGE LEVEL JS-->

@yield('footer')

</body>
</html>
