@if($memberslists)
<section id="section-features" class="text-light jarallax">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center wow fadeInUp">
                <h1>{{__('webMessage.membershiplistings')}}</h1>
                <div class="separator"><span><i class="fa fa-circle"></i></span></div>
                <div class="spacer-single"></div>
            </div>
        </div>

        <div class="row1 mt50">
            <div id="members-carousel" class="owl-carousel owl-theme">
                @foreach($memberslists as $memberslist)
                    @if($memberslist->image)
                        <div class="col-lg-41 mt-70 sm-mt0 px-md-1 mt-sm-none fadeInRight" data-wow-delay=".3s">
                            @if($memberslist->website)
                                <a href="{{ $memberslist->website }}" target="_blank" rel="nofollow">
                            @endif
                                    <img class="mw-100" src="{{url('uploads/memberships/'.$memberslist->image)}}" alt="" style="margin-top: 65px;">
                            @if($memberslist->website)
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
