@if(!empty($boxes))
    <section id="section-services" class="no-top mt150">
        <div class="container">
            <!--<div class="row">-->  <!-- Before scrolle active -->
            <div id="box-carousel" class="owl-carousel owl-theme"> <!-- after scrolle active -->

                {{--            <span>{{__('webMessage.ourbestservices')}}</span>--}}
                {{--            <h2>{{__('webMessage.ourservices')}}</h2>--}}
                @foreach($boxes as $box)
                    <!--<div class="pt-4 col-lg-3 mt-70 sm-mt0 text-middle text-light wow fadeInRight" data-wow-delay="0">-->  <!-- Before scrolle active -->
                    <div class="pt-4 mt-70 sm-mt0 text-middle text-light wow fadeInRight" data-wow-delay="0"> <!-- after scrolle active -->
                        <div class="shadow-soft h-100" data-bgimage="url({{ $box->image ? url('uploads/boxes/'.$box->image) : url('uploads/no-image.png')}})">
                            <div class="padding40 overlay60">
                                <h3>@if(app()->getLocale()=="en") {{$box->title_en}} @else {{$box->title_ar}} @endif</h3>
                                <p>
                                    {!! app()->getLocale() == "en" ? $box->description_en : $box->description_ar !!}
                                </p>
                                @if(!empty($box->link))
                                    <a href="{{$box->link}}" class="btn-line btn-fullwidth">
                                        {{ (app()->getLocale()=="en" ? $box->link_title_en : $box->link_title_ar) }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endif
