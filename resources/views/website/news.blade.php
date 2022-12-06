@extends('website.layout')

@section('title', __('webMessage.newsevents'))

@section('content')

    <!-- subheader -->
    <section id="subheader" data-speed="8" data-type="background" style="background:url({{url('uploads/news.jpg?'.cache('uploadHeader'))}}) no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{__('webMessage.newsevents')}}</h1>
                    <ul class="crumb">
                        <li><a href="{{url('')}}">{{__('webMessage.home')}}</a></li>
                        <li class="sep">/</li>
                        <li>{{__('webMessage.newsevents')}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- subheader close -->

    <!-- content begin -->
    <div id="content">
        <div class="container">
            @if($NewsLists)
                <div class="row">
                    @foreach($NewsLists as $news)
                        <div class="col-lg-4 col-md-6 mb30">
                            <div class="bloglist item">
                                <div class="post-content">
                                    <div class="post-image">
                                        <a class="{{-- image-popup-no-margins --}}" href="{{url('newsdetails/'.$news->slug)}}">
                                            @if($news->image)
                                                <img alt="" src="{{url('uploads/newsevents/'.$news->image)}}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="post-text">
                                        @if($news->category)
                                            <span class="p-tagline @if(app()->getLocale() == "ar" ) p-tagline-rtl @endif">{{ app()->getLocale()=="en" ? optional($news->category)->name_en : optional($news->category)->name_ar }}</span>
                                        @endif
                                        <h4>
                                            <a href="{{url('newsdetails/'.$news->slug)}}">
                                            {{ app()->getLocale()=="en" ? $news->title_en : $news->title_ar }}
                                            </a>
                                        </h4>
                                        <p>{{ \Illuminate\Support\Str::words(strip_tags($news["details_" . app()->getLocale()]), 22, '...') }}</p>
{{--                                        <span class="p-date">{{ $news->created_at->format('F d, Y') }}</span>--}}
                                        <span class="p-date">{{ Carbon\Carbon::parse($news->created_at)->translatedFormat('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="spacer-single"></div>

                    {{ $NewsLists->links('website.includes.news-pagination') }}
                </div>
            @endif

        </div>
    </div>

@endsection