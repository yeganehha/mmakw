<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{url('new_assets')}}/images/icon.png" type="image/gif" sizes="16x16">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@if(app()->getLocale()=="en") {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif @hasSection('title')| @yield('title')@endif</title>

    <meta name="description" content="@if(View::hasSection('description')) @yield('description') @else @if(app()->getLocale()=="en") {{$settingInfo->seo_description_en}} @else {{$settingInfo->seo_description_ar}} @endif @endif"/>
    <meta name="abstract" content="@if(View::hasSection('abstract')) @yield('abstract') @else @if(app()->getLocale()=="en") {{$settingInfo->seo_description_en}} @else {{$settingInfo->seo_description_ar}} @endif @endif">
    <meta name="keywords" content="@if(View::hasSection('keywords')) @yield('keywords') @else @if(app()->getLocale()=="en") {{$settingInfo->seo_keywords_en}} @else {{$settingInfo->seo_keywords_ar}} @endif @endif"/>
    <meta name="Copyright" content="{{$settingInfo->name_en}}, Kuwait Copyright 2020 - {{date('Y')}}" />
    <meta name="author" content="Gulfweb Web Design, Kuwait" />
    <META NAME="Designer" CONTENT="Gulfweb Web Design Kuwait">
    <meta name="Country" content="Kuwait" />
    <META NAME="city" CONTENT="Kuwait City">
    <META NAME="Language" CONTENT="English">
    <META NAME="Geography" CONTENT="@if(app()->getLocale()=="en") {{$settingInfo->address_en}} @else {{$settingInfo->address_ar}} @endif">
    <META NAME="Revisit-After" CONTENT="2 days">
    <meta name="robots" CONTENT="all">
    <META NAME="distribution" CONTENT="Global">

    <!-- CSS Files
    ================================================== -->
    <link href="{{url('new_assets')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap" />
    <link href="{{url('new_assets')}}/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" id="bootstrap-grid" />
    <link href="{{url('new_assets')}}/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" id="bootstrap-reboot" />
    <link href="{{url('new_assets')}}/css/plugins.css" rel="stylesheet" type="text/css">
    <link href="{{url('new_assets')}}/css/style.css" rel="stylesheet" type="text/css">
    <link href="{{url('new_assets')}}/css/color.css" rel="stylesheet" type="text/css">

    <!-- custom background -->
    <link rel="stylesheet" href="{{url('new_assets')}}/css/bg.css" type="text/css">

    <!-- color scheme -->
    <link rel="stylesheet" href="{{url('new_assets')}}/css/colors/blue.css" type="text/css" id="colors">

    <!-- revolution slider -->
    <link rel="stylesheet" href="{{url('new_assets')}}/rs-plugin/css/settings.css" type="text/css">
    <link rel="stylesheet" href="{{url('new_assets')}}/css/rev-settings.css" type="text/css">

    <!-- custom font -->
    <link rel="stylesheet" href="{{url('new_assets')}}/css/font-style-2.css" type="text/css">

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
        .g-recaptcha {transform:scale(0.90);transform-origin:0 0;}
        @if(app()->getLocale() == 'ar')
            h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, p, a, span, li, button, div {
                font-family: 'Noto Naskh Arabic' !important;
            }
        @endif
    </style>

</head>


<body id="homepage">

    <div id="wrapper">

        <!-- header begin -->
        @include('website.includes.top_home_header')
        <!-- header close -->

        @yield('content')

        @if(count($clients))
            <section class="{{ app()->getLocale() == 'ar' ? 'ltr' : '' }}">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3 text-center wow fadeInUp">
                        <h1>{{__('webMessage.our_clients')}}</h1>
                        <div class="separator"><span><i class="fa fa-circle"></i></span></div>
                        <div class="spacer-single"></div>
                    </div>
                    <div class="col-md-12">
                        <div id="logo-carousel" class="owl-carousel owl-theme">
                            @foreach($clients as $client)
                                @if ( $client->website )
                                    <a href="{{ $client->website }}" rel="nofollow" target="_blank">
                                @endif
                                <img src="{{url('uploads/clients')}}/{{ $client->image }}" class="img-responsive p-3" alt="{{ $client->name }}">
                                @if ( $client->website )
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <section id="call-to-action" class="bg-color call-to-action text-light padding40" aria-label="cta">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <h3 class="text-dark size-2 no-margin">{{__('webMessage.footercontactusblue')}}</h3>
                    </div>

                    <div class="col-lg-4 col-md-5 text-right">
                        <a href="{{url('/contactus')}}" class="btn-line black wow fadeInUp">{{__('webMessage.footercontactusbutton')}}</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- footer begin -->
        @include('website.includes.home_footer')
        <!-- footer close -->
    </div>
</div>
@include('website.includes.js')
</body>
</html>