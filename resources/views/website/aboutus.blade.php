@extends('website.layout')

@section('title', app()->getLocale()=="en" ? $aboutusDetails->title_en : $aboutusDetails->title_ar)

@section('description', app()->getLocale()=="en" ? $aboutusDetails->seo_description_en : $aboutusDetails->seo_description_ar)
@section('abstract', app()->getLocale()=="en" ? $aboutusDetails->seo_description_en : $aboutusDetails->seo_description_ar)
@section('keywords', app()->getLocale()=="en" ? $aboutusDetails->seo_keywords_en : $aboutusDetails->seo_keywords_ar)

@section('content')
    <!-- subheader -->
    <section id="subheader" data-speed="8" data-type="background" style="background:url({{url("uploads/About/$aboutusDetails->bannerimage")}}) no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@if(app()->getLocale()=="en") {{$aboutusDetails->title_en}} @else  {{$aboutusDetails->title_ar}} @endif</h1>
                    <ul class="crumb">
                        <li><a href="{{url('/#home')}}">{{__('webMessage.home')}}</a></li>
                        <li class="sep">/</li>
                        <li><a href="#">{{__('webMessage.aboutus')}}</a></li>
                        <li class="sep">/</li>
                        <li>@if(app()->getLocale()=="en") {{$aboutusDetails->title_en}} @else  {{$aboutusDetails->title_ar}} @endif</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- subheader close -->

    <!-- content begin -->
    <div id="content">
        <div class="container">
            <div class="row">
                <div id="sidebar" class="col-md-3 wow fadeInUp">
                    <ul id="services-list">
                        @if(count($aboutusMenus))
                            @foreach($aboutusMenus as $aboutusMenu)
                                <li class="{{ Request::is("about-us/$aboutusMenu->slug") ? 'active' : '' }}"><a href="{{url('/about-us/'.$aboutusMenu->slug)}}">{{ app()->getLocale()=="en" ? $aboutusMenu->menu_name_en : $aboutusMenu->menu_name_ar }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 wow fadeInUp" data-wow-delay=".3s">
                            @if(app()->getLocale()=="en") {!!$aboutusDetails->details_en!!} @else  {!!$aboutusDetails->details_ar!!} @endif
                        </div>
                        <div class="col-md-6 pic-services wow fadeInUp" data-wow-delay=".6s">
                            <img src="{{url("uploads/About/$aboutusDetails->image")}}" class="img-responsive" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection