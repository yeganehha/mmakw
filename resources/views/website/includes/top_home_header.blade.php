<header class="transparent new-header">
    <div class="info">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="column">Our Address <span class="id-color"><strong>{{$settingInfo['address_' . app()->getLocale()]}}</strong></span></div>
                    <div class="column">Call Us <span class="id-color"><strong>{{$settingInfo->phone}}</strong></span></div>
                    <!-- social icons -->
                    <div class="column social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-rss"></i></a>
                        <a href="#"><i class="fa fa-google-plus"></i></a>
                        <a href="#"><i class="fa fa-envelope-o"></i></a>
                    </div>
                    <!-- social icons close -->
                </div>
            </div>
        </div>
    </div>

    <div class="container header-wide">
        <div class="row">
            <div class="col-md-12">
                <div class="md-flex">
                    <!-- logo begin -->
                    <div id="logo">
                        <a href="index.html">
{{--                            <img class="logo" src="{{url('new_assets')}}/images/logo.png" alt="">--}}
                            @if($settingInfo->logo)<a class="navbar-brand" href="{{url('/#home')}}"><img src="{{url('uploads/logo/'.$settingInfo->logo)}}" alt="@if(app()->getLocale()=='en') {{$settingInfo->name_en}} @else {{$settingInfo->name_ar}} @endif"></a>@endif
                        </a>
                    </div>
                    <!-- logo close -->

                    <!-- small button begin -->
                    <span id="menu-btn"></span>
                    <!-- small button close -->

                    <!-- mainmenu begin -->
                    <div class="md-flex-col">
                        <nav class="md-flex">
                            <ul id="mainmenu">
                                <li ><a  href="{{url('/')}}">{{__('webMessage.home')}}</a></li>
                                <li class="menu-item-has-children"><a href="{{url('/whoweare')}}">{{__('webMessage.whoweare')}}</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{url('/mission')}}">{{__('webMessage.mission')}}</a></li>
                                        <li><a href="{{url('/vision')}}">{{__('webMessage.vision')}}</a></li>
                                        <li><a href="{{url('/team')}}">{{__('webMessage.team')}}</a></li>
                                    </ul>
                                </li>

                                @if(count($practiceareaMenus))
                                    <li class="menu-item-has-children">
                                        <a href="{{url('/#home')}}">{{__('webMessage.practicearea')}}</a>
                                        <ul class="sub-menu" style="width:290px;">
                                            @foreach($practiceareaMenus as $practiceareaMenu)
                                                <li><a href="{{url('/practice/'.$practiceareaMenu->slug)}}" class="w-100">
                                                        @if(app()->getLocale()=='en') {{$practiceareaMenu->menu_name_en}} @else {{$practiceareaMenu->menu_name_ar}} @endif
                                                </a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif

                                @if(count($servicesMenus))
                                <li><a href="{{url('/#services')}}">{{__('webMessage.services')}}</a>
                                    <ul>
                                        @foreach($servicesMenus as $servicesMenu)
                                        <li><a href="{{url('/services/'.$servicesMenu->slug)}}">@if(app()->getLocale()=='en') {{$servicesMenu->menu_name_ar}} @else {{$servicesMenu->menu_name_ar}} @endif</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endif

                                <li><a href="{{url('/members')}}">{{__('webMessage.membershiplistings')}}</a></li>
                                <li><a href="{{url('/news')}}">{{__('webMessage.newsevents')}}</a></li>
                                <li class="menu-item-has-children">
                                    <a href="{{url('/contactus')}}">{{__('webMessage.contactus')}}</a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="{{url('/careers')}}">{{__('webMessage.careers')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url('/contactus')}}">{{__('webMessage.contactus')}}</a>
                                        </li>
                                    </ul>
                                </li>

{{--                                @if(empty(Auth::guard('webs')->user()->id))--}}
{{--                                    @if(app()->getLocale()=='en')--}}
{{--                                        <li><a href="{{ url('/login') }}" >{{__('webMessage.login_menu_txt')}}</a></li>--}}
{{--                                        @if($settingInfo->is_lang==1)--}}
{{--                                            <li class="arabic"><a href="{{ url('locale/ar') }}" >العربية</a></li>--}}
{{--                                        @endif--}}
{{--                                    @else--}}
{{--                                        <li><a href="{{ url('/login') }}" >{{__('webMessage.login_menu_txt')}}</a></li>--}}
{{--                                        @if($settingInfo->is_lang==1)--}}
{{--                                            <li><a href="{{ url('locale/en') }}" >English</a></li>--}}
{{--                                        @endif--}}
{{--                                    @endif--}}
{{--                                @else--}}

                                    @if($settingInfo->is_login)
                                        <li><a href="{{ $settingInfo->login_url }}">{{__('webMessage.login')}}</a></li>
                                    @endif

                                    @if(app()->getLocale()=='en')
{{--                                        <li><a href="{{ url('/account') }}" >{{__('webMessage.myaccount_txt')}}</a></li>--}}
                                        @if($settingInfo->is_lang==1)
                                            <li class="arabic"><a href="{{ url('locale/ar') }}" >العربية</a></li>
                                        @endif
                                    @else
{{--                                        <li><a href="{{ url('/account') }}" >{{__('webMessage.myaccount_txt')}}</a></li>--}}
                                        @if($settingInfo->is_lang==1)
                                            <li><a href="{{ url('locale/en') }}" >English</a></li>
                                        @endif
                                    @endif
{{--                                @endif--}}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


