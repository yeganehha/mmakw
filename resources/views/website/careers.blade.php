@extends('website.layout')

@section('title', __('webMessage.careers'))

@section('content')

    <!-- subheader -->
    <section id="subheader" data-speed="8" data-type="background" style="background:url({{url('uploads/careers.jpg?'.cache('uploadHeader'))}}) no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{__('webMessage.careers')}}</h1>
                    <ul class="crumb">
                        <li><a href="{{url('')}}">{{__('webMessage.home')}}</a></li>
                        <li class="sep">/</li>
                        <li>{{__('webMessage.careers')}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- subheader close -->

    <!-- content begin -->
    <div id="content">
        <div class="container">
            @if($careers)
                <div class="row">
                    @foreach($careers as $career)
                        <div class="col-lg-4 col-md-6 mb30">
                            <div class="bloglist item">
                                <div class="post-content">
                                    <div class="post-image">
                                        <a class="{{-- image-popup-no-margins --}}" href="{{url('careers/'.$career->slug)}}">
                                            @if($career->image)
                                                <img alt="" src="{{url('uploads/careers/'.$career->image)}}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="post-text">
                                        @if($career->category)
                                            <span class="p-tagline @if(app()->getLocale() == "ar" ) p-tagline-rtl @endif">{{ app()->getLocale()=="en" ? optional($career->category)->name_en : optional($career->category)->name_ar }}</span>
                                        @endif
                                        <h4>
                                            <a href="{{url('careers/'.$career->slug)}}">
                                            {{ app()->getLocale()=="en" ? $career->title_en : $career->title_ar }}
                                            </a>
                                        </h4>
                                        <p>{{ \Illuminate\Support\Str::words(strip_tags($career["description_" . app()->getLocale()]), 22, '...') }}</p>
{{--                                        <span class="p-date">{{ $career->created_at->format('F d, Y') }}</span>--}}
                                        <span class="p-date">{{ Carbon\Carbon::parse($career->created_at)->translatedFormat('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="spacer-single"></div>

                    {{ $careers->links('website.includes.news-pagination') }}
                </div>
            @endif

        </div>
    </div>

@endsection