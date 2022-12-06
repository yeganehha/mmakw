@extends('website.layout')

@section('title', app()->getLocale()=="en" ? $career->title_en : $career->title_ar)

@section('content')

<!-- subheader -->
<section id="subheader" data-speed="8" data-type="background" style="background:url({{url('uploads/careers.jpg?'.cache('uploadHeader'))}}) no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($career->category)
                    <h1>{{ $career->category['name_' . app()->getLocale()] }}</h1>
                    <ul class="crumb">
                        <li><a href="{{url('')}}">{{__('webMessage.home')}}</a></li>
                        <li class="sep">/</li>
                        <li>{{ $career->category['name_' . app()->getLocale()] }}</li>
                    </ul>
                @else
                    <ul class="crumb">
                        <li><a href="{{url('')}}">{{__('webMessage.home')}}</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- subheader close -->

<!-- content begin -->
<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="blog-read">
                    <div class="post-content">
                        <div class="post-image">
                            @if($career->image)
                                <img alt="" src="{{url('uploads/careers/'.$career->image)}}">
                            @endif
                        </div>

                        <div class="date-box">
                            <div class="day">{{ $career->created_at->format('d') }}</div>
                            <div class="month" {!! app()->getLocale() == 'en' ?: 'style="letter-spacing: normal;"' !!}>{{ strtoupper($career->created_at->translatedFormat('M')) }}</div>
                        </div>

                        <div class="post-text" {!! app()->getLocale() == 'ar' ? 'style="padding-left: 0; padding-right: 100px"' : '' !!}>
                            <h2><a href="#">{{ app()->getLocale()=="en" ? $career->title_en : $career->title_ar }}</a></h2>

                            {!! app()->getLocale()=="en" ? $career->description_en : $career->description_ar !!}

                        </div>
                    </div>

                    <div class="post-meta">
{{--                        <span><i class="fa fa-user id-color"></i>By: <a href="#">Lynda Wu</a></span>--}}
                        @if($career->category)
                            <span><i class="fa fa-tag id-color"></i><a href="{{url('/careers')}}?cat={{ $career->category->slug }}">{{ $career->category["name_" . app()->getLocale()] }}</a></span>
                        @endif
{{--                        <span><i class="fa fa-comment id-color"></i><a href="#">{{ $comments_count }} {{ __('webMessage.comments') }}</a></span>--}}
                    </div>

                    <div class="spacer-single"></div>

                    <div id="comment-form-wrapper">
                        <h3>{{__('webMessage.apply_for_position')}}</h3>
                        @if ($message = Session::get('message-success'))
                            <div id="success_message" class='success d-block'>
                                {{ $message }}
                            </div>
                        @endif
                        <div class="comment_form_holder">
                            <form id="contact_form" method="post" action="{{ url('apply') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="career_id" value="{{ $career->id }}"/>

                                <label>{{__('webMessage.name')}}</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}"/>
                                @if($errors->has('name'))
                                    <div id='name_error' class='error d-block'>{{ $errors->first('name') }}</div>
                                @endif

                                <label>{{__('webMessage.email')}} <span class="req">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}"/>
                                @if($errors->has('email'))
                                    <div id='email_error' class='error d-block'>{{ $errors->first('email') }}</div>
                                @endif

                                <label>{{__('webMessage.mobile')}}</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" value="{{old('mobile')}}"/>
                                @if($errors->has('mobile'))
                                    <div id='mobile_error' class='error d-block'>{{ $errors->first('mobile') }}</div>
                                @endif

                                <label>{{__('webMessage.uploadcv')}}</label>
                                <input type="file" name="file" id="file" class="form-control mb30"/>
                                @if($errors->has('file'))
                                    <div id='file_error' class='error d-block'>{{ $errors->first('file') }}</div>
                                @endif

                                <label>{{__('webMessage.message')}} <span class="req">*</span></label>
                                <textarea cols="10" rows="10" name="message" id="message" class="form-control">{{old('message')}}</textarea>
                                @if($errors->has('message'))
                                    <div id='message_error' class='error d-block'>{{ $errors->first('message') }}</div>
                                @endif

                                <p id="btnsubmit">
                                    <input type="submit" id="send" value="{{__('webMessage.applynow')}}" class="btn btn-line" /></p>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="sidebar" class="col-md-4">
{{--                <div class="widget widget-post">--}}
{{--                    <h4>{{__('webMessage.recent_posts')}}</h4>--}}
{{--                    <div class="small-border"></div>--}}
{{--                    <ul>--}}
{{--                        @foreach(\App\NewsEvents::where("is_active","1")->orderBy('news_date', 'desc')->take(5)->get() as $post)--}}
{{--                            <li><a href="{{url('newsdetails/'.$post->slug)}}">{{ app()->getLocale()=="en" ? $post->title_en : $post->title_ar }}</a></li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}

{{--                <div class="widget widget-text">--}}
{{--                    <h4>{{__('webMessage.aboutus')}}</h4>--}}
{{--                    <div class="small-border"></div>--}}
{{--                    {{app()->getLocale() == 'en' ? $settingInfo->footer_about_en : $settingInfo->footer_about_ar}}--}}
{{--                </div>--}}
{{--                @if(count($news_categories))--}}
{{--                    <div class="widget widget_tags">--}}
{{--                        <h4>{{__('webMessage.tags')}}</h4>--}}
{{--                        <div class="small-border"></div>--}}
{{--                        <ul>--}}
{{--                            @foreach($news_categories as $cat)--}}
{{--                                <li><a href="{{url('/careers')}}?cat={{ $cat->slug }}">{{ $cat["name_" . app()->getLocale()] }}</a></li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                @endif--}}

            </div>
        </div>
    </div>
</div>
@endsection