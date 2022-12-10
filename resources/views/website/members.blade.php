@extends('website.layout')

@section('title', __('webMessage.membershiplistings'))

@section('content')
            <!-- subheader -->
            <section id="subheader" style="background:url({{url('uploads/members.jpg?'.cache('uploadHeader'))}}) no-repeat;" data-speed="8" data-type="background">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1>{{__('webMessage.membershiplistings')}}</h1>
                            <ul class="crumb">
                                <li><a href="{{url('/#home')}}">{{__('webMessage.home')}}</a></li>
                                <li class="sep">/</li>
                                <li>{{__('webMessage.membershiplistings')}}</li>
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
                        <div class="col-12">
                        @if(count($memberslistsds))
                            @foreach($memberslistsds as $memberslist)
                                <img src="{{url('uploads/memberships/'.$memberslist->image)}}" alt="" class=" {{ app()->getLocale() == "en" ? 'pic_left' : 'pic_right' }} w-100" style="max-width: 290px;">
                                {!! app()->getLocale()=="en" ? $memberslist->details_en : $memberslist->details_ar !!}
                                @if($memberslist->website)
                                    <br>
                                    <a href="{{ $memberslist->website }}" target="_blank" rel="nofollow">{{ $memberslist->website }}</a>
                                @endif
                                <div class="clear30x"></div>
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>

@endsection