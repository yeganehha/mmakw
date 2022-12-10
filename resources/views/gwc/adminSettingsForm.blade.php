<!DOCTYPE html>
<html lang="en">
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>{{__('adminMessage.websiteName')}} | {{__('adminMessage.generalsettings')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--css files -->
		@include('gwc.css.user')
		<!-- token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading">

		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				@php
                $settingDetailsMenu = App\Http\Controllers\AdminDashboardController::getSettingsDetails();
                @endphp
                <a href="{{url('/gwc/home')}}">
                @if($settingDetailsMenu['logo'])
				<img alt="{{__('adminMessage.websiteName')}}" src="{!! url('uploads/logo/'.$settingDetailsMenu['logo']) !!}" height="40" />
                @endif
			   </a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				@include('gwc.includes.leftmenu')

				<!-- end:: Aside -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					@include('gwc.includes.header')

					<!-- end:: Header -->
					<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

						<!-- begin:: Subheader -->
						<div class="kt-subheader   kt-grid__item" id="kt_subheader">
							<div class="kt-container  kt-container--fluid ">
								<div class="kt-subheader__main">
									<h3 class="kt-subheader__title">{{__('adminMessage.systems')}}</h3>
									<span class="kt-subheader__separator kt-hidden"></span>
									<div class="kt-subheader__breadcrumbs">
										<a href="{{url('gwc/home')}}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
										<span class="kt-subheader__breadcrumbs-separator"></span>
										<a href="javascript:;" class="kt-subheader__breadcrumbs-link">{{__('adminMessage.generalsettings')}}</a>
									</div>
								</div>
								
							</div>
						</div>
                        

						<!-- end:: Subheader -->

						<!-- begin:: Content -->
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                        
                          @if(Session::get('message-success'))
							<div class="alert alert-light alert-success" role="alert">
								<div class="alert-icon"><i class="flaticon-alert kt-font-brand"></i></div>
								<div class="alert-text">
									{{ Session::get('message-success') }}
								</div>
							</div>
                           @endif
                           
                           @if(Session::get('message-error'))
							<div class="alert alert-light alert-danger" role="alert">
								<div class="alert-icon"><i class="flaticon-alert kt-font-brand"></i></div>
								<div class="alert-text">
									{{ Session::get('message-error') }}
								</div>
							</div>
                           @endif
                        @if(auth()->guard('admin')->user()->can('general-settings-edit'))      
                           <form name="tFrm"  id="form_validation"  method="post"
                          class="kt-form" enctype="multipart/form-data" action="{{route('general-settings.update',$settingDetails->keyname)}}">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="row">
								<div class="col-md-6">
                          
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													{{__('adminMessage.storedetails')}}
												</h3>
											</div>
										</div>

										<!--begin::Form-->
                                       
											<div class="kt-portlet__body">
												
												<div class="form-group">
                                                <div class="row">
								                    <div class="col-md-6">
													<label>{{__('adminMessage.websitename_en')}}</label>
													<input type="text"  class="form-control @if($errors->has('name_en')) is-invalid @endif" name="name_en" placeholder="{{__('adminMessage.enter_websitename_en')}}" value="@if($settingDetails->name_en) {{$settingDetails->name_en}} @endif">
                                                    @if($errors->has('name_en'))
                                                    <div class="invalid-feedback">{{ $errors->first('name_en') }}</div>
                                                    @endif
                                                    </div>
                                                    <div class="col-md-6">
													<label>{{__('adminMessage.websitename_ar')}}</label>
													<input type="text"  class="form-control @if($errors->has('name_ar')) is-invalid @endif" name="name_ar" placeholder="{{__('adminMessage.enter_websitename_ar')}}" value="@if($settingDetails->name_ar) {{$settingDetails->name_ar}} @endif">
                                                    @if($errors->has('name_ar'))
                                                    <div class="invalid-feedback">{{ $errors->first('name_ar') }}</div>
                                                    @endif
                                                    </div>
                                                    </div>
												</div>
                                                
                                                <div class="form-group">
													<label>{{__('adminMessage.seodescription_en')}}</label>
													<input type="text" class="form-control  @if($errors->has('seo_description_en')) is-invalid @endif" name="seo_description_en" placeholder="{{__('adminMessage.enterseodescription_en')}}" value="@if($settingDetails->seo_description_en) {{$settingDetails->seo_description_en}} @endif">
                                                    @if($errors->has('seo_description_en'))
                                                    <div class="invalid-feedback">{{ $errors->first('seo_description_en') }}</div>
                                                    @endif
												</div>
                                                <div class="form-group">
													<label>{{__('adminMessage.seodescription_ar')}}</label>
													<input type="text" class="form-control  @if($errors->has('seo_description_ar')) is-invalid @endif" name="seo_description_ar" placeholder="{{__('adminMessage.enterseodescription_ar')}}" value="@if($settingDetails->seo_description_ar) {{$settingDetails->seo_description_ar}} @endif">
                                                    @if($errors->has('seo_description_ar'))
                                                    <div class="invalid-feedback">{{ $errors->first('seo_description_ar') }}</div>
                                                    @endif
												</div>
                                                
                                                <div class="form-group">
													<label>{{__('adminMessage.seokeywords_en')}}</label>
													<input type="text" class="form-control  @if($errors->has('seo_keywords_en')) is-invalid @endif" name="seo_keywords_en" placeholder="{{__('adminMessage.enterseokeywords_en')}}" value="@if($settingDetails->seo_keywords_en) {{$settingDetails->seo_keywords_en}} @endif">
                                                    @if($errors->has('seo_keywords_en'))
                                                    <div class="invalid-feedback">{{ $errors->first('seo_keywords_en') }}</div>
                                                    @endif
												</div>
                                                <div class="form-group">
													<label>{{__('adminMessage.seokeywords_ar')}}</label>
													<input type="text" class="form-control  @if($errors->has('seo_keywords_ar')) is-invalid @endif" name="seo_keywords_ar" placeholder="{{__('adminMessage.enterseokeywords_ar')}}" value="@if($settingDetails->seo_keywords_ar) {{$settingDetails->seo_keywords_ar}} @endif">
                                                    @if($errors->has('seo_keywords_ar'))
                                                    <div class="invalid-feedback">{{ $errors->first('seo_keywords_ar') }}</div>
                                                    @endif
												</div>

                                                <div class="form-group">
													<label>{{__('adminMessage.footer_about_en')}}</label>
													<input type="text" class="form-control  @if($errors->has('footer_about_en')) is-invalid @endif" name="footer_about_en" placeholder="{{__('adminMessage.enter_footer_about_en')}}" value="@if($settingDetails->footer_about_en){{$settingDetails->footer_about_en}}@endif">
                                                    @if($errors->has('footer_about_en'))
                                                    <div class="invalid-feedback">{{ $errors->first('footer_about_en') }}</div>
                                                    @endif
												</div>

                                                <div class="form-group">
													<label>{{__('adminMessage.footer_about_ar')}}</label>
													<input type="text" class="form-control  @if($errors->has('footer_about_ar')) is-invalid @endif" name="footer_about_ar" placeholder="{{__('adminMessage.enter_footer_about_ar')}}" value="@if($settingDetails->footer_about_ar){{$settingDetails->footer_about_ar}}@endif">
                                                    @if($errors->has('footer_about_ar'))
                                                    <div class="invalid-feedback">{{ $errors->first('footer_about_ar') }}</div>
                                                    @endif
												</div>
                                                
                                                <div class="form-group">
													<label>{{__('adminMessage.owner_name')}}</label>
													<input type="text" class="form-control  @if($errors->has('owner_name')) is-invalid @endif" name="owner_name" placeholder="{{__('adminMessage.enterowner_name')}}" value="@if($settingDetails->owner_name) {{$settingDetails->owner_name}} @endif">
                                                    @if($errors->has('owner_name'))
                                                    <div class="invalid-feedback">{{ $errors->first('owner_name') }}</div>
                                                    @endif
												</div>
                                                
                                                
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-6">
													<label>{{__('adminMessage.address_en')}}</label>
													<textarea class="form-control  @if($errors->has('address_en')) is-invalid @endif" rows="3" name="address_en" placeholder="{{__('adminMessage.enter_address_en')}}">@if($settingDetails->address_en) {!!$settingDetails->address_en!!} @endif</textarea>
                                                    @if($errors->has('address_en'))
                                                    <div class="invalid-feedback">{{ $errors->first('address_en') }}</div>
                                                    @endif
                                                    </div>
                                                    <div class="col-md-6">
													<label>{{__('adminMessage.address_ar')}}</label>
													<textarea class="form-control  @if($errors->has('address_ar')) is-invalid @endif" rows="3" name="address_ar" placeholder="{{__('adminMessage.enter_address_ar')}}">@if($settingDetails->address_ar) {!!$settingDetails->address_ar!!} @endif</textarea>
                                                    @if($errors->has('address_ar'))
                                                    <div class="invalid-feedback">{{ $errors->first('address_ar') }}</div>
                                                    @endif
                                                    </div>
                                                    </div>
												</div>
                                                
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-6">
													<label>{{__('adminMessage.email')}}</label>
													<input type="text" class="form-control  @if($errors->has('email')) is-invalid @endif" name="email" placeholder="{{__('adminMessage.enter_email')}}" value="@if($settingDetails->email){{$settingDetails->email}}@endif">
                                                    @if($errors->has('email'))
                                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                    @endif
                                                    </div>
                                                    <div class="col-md-6">
													<label>{{__('adminMessage.mobile')}}</label>
													<input type="text" class="form-control  @if($errors->has('mobile')) is-invalid @endif" name="mobile" placeholder="{{__('adminMessage.enter_mobile')}}" value="@if($settingDetails->mobile){{$settingDetails->mobile}}@endif">
                                                    @if($errors->has('mobile'))
                                                    <div class="invalid-feedback">{{ $errors->first('mobile') }}</div>
                                                    @endif
                                                    </div>
                                                    </div>
												</div>
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-12">
													<label>{{__('adminMessage.phone')}}</label>
													<input type="text" class="form-control  @if($errors->has('phone')) is-invalid @endif" name="phone" placeholder="{{__('adminMessage.enter_phone')}}" value="@if($settingDetails->phone){{$settingDetails->phone}}@endif">
                                                    @if($errors->has('phone'))
                                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                    @endif
                                                    </div>
												</div>
												<div class="row">
								                    <div class="col-md-12">
													<label>{{__('adminMessage.fax')}}</label>
													<input type="text" class="form-control  @if($errors->has('fax')) is-invalid @endif" name="fax" placeholder="{{__('adminMessage.enter_fax')}}" value="@if($settingDetails->fax){{$settingDetails->fax}}@endif">
                                                    @if($errors->has('fax'))
                                                    <div class="invalid-feedback">{{ $errors->first('fax') }}</div>
                                                    @endif
                                                    </div>
                                                  
                                                    </div>
												</div>
                                                <div class="form-group">
                                                <div class="row">
								                    
                                                    <div class="col-md-6">
													<label>{{__('adminMessage.office_hours_en')}}</label>
													<input type="text" class="form-control  @if($errors->has('office_hours_en')) is-invalid @endif" name="office_hours_en" placeholder="{{__('adminMessage.enter_office_hours_en')}}" value="@if($settingDetails->office_hours_en) {{$settingDetails->office_hours_en}} @endif">
                                                    @if($errors->has('office_hours_en'))
                                                    <div class="invalid-feedback">{{ $errors->first('office_hours_en') }}</div>
                                                    @endif
                                                    </div>
                                                    <div class="col-md-6">
													<label>{{__('adminMessage.office_hours_ar')}}</label>
													<input type="text" class="form-control  @if($errors->has('office_hours_ar')) is-invalid @endif" name="office_hours_ar" placeholder="{{__('adminMessage.enter_office_hours_ar')}}" value="@if($settingDetails->office_hours_ar) {{$settingDetails->office_hours_ar}} @endif">
                                                    @if($errors->has('office_hours_ar'))
                                                    <div class="invalid-feedback">{{ $errors->first('office_hours_ar') }}</div>
                                                    @endif
                                                    </div>
                                                    </div>
												</div>
                                                <!--website logo -->
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-8">
                                                    <label>{{__('adminMessage.logo')}}</label>
                                                        <div class="custom-file @if($errors->has('logo')) is-invalid @endif">
														<input type="file" class="custom-file-input @if($errors->has('logo')) is-invalid @endif"  id="logo" name="logo">
														<label class="custom-file-label" for="logo">{{__('adminMessage.chooseImage')}}</label>
													    </div>
                                                        @if($errors->has('logo'))
                                                        <div class="invalid-feedback">{{ $errors->first('logo') }}</div>
                                                        @endif
                                                </div>
                                                <div class="col-md-4">
                                                @if($settingDetails->logo)
                                                <img src="{!! url('uploads/logo/'.$settingDetails->logo) !!}" width="40">
                                                <a href="javascript:;" data-toggle="kt-popover" data-trigger="focus" title="{{__('adminMessage.alert')}}" data-html="true" data-content="{{__('adminMessage.areyousuretodelete')}}<br><br><a href='{{url('gwc/settings/deleteLogo/')}}' class='btn btn-brand btn-danger btn-icon-sm btn-sm'>{{__('adminMessage.yes')}}</a>" class="btn btn-brand btn-danger btn-icon-sm btn-sm"><i class="la la-trash"></i>{{__('adminMessage.delete')}}</a>
                                                @endif
                                                </div>
                                                    </div>
												</div>
                                                <!--email logo-->
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-8">
                                                    <label>{{__('adminMessage.emaillogo')}}</label>
                                                        <div class="custom-file @if($errors->has('emaillogo')) is-invalid @endif">
														<input type="file" class="custom-file-input @if($errors->has('emaillogo')) is-invalid @endif"  id="emaillogo" name="emaillogo">
														<label class="custom-file-label" for="emaillogo">{{__('adminMessage.chooseImage')}}</label>
													    </div>
                                                        @if($errors->has('emaillogo'))
                                                        <div class="invalid-feedback">{{ $errors->first('emaillogo') }}</div>
                                                        @endif
                                                </div>
                                                <div class="col-md-4">
                                                @if($settingDetails->emaillogo)
                                                <img src="{!! url('uploads/logo/'.$settingDetails->emaillogo) !!}" width="40">
                                                <a href="javascript:;" data-toggle="kt-popover" data-trigger="focus" title="{{__('adminMessage.alert')}}" data-html="true" data-content="{{__('adminMessage.areyousuretodelete')}}<br><br><a href='{{url('gwc/settings/deleteEmailLogo/')}}' class='btn btn-brand btn-danger btn-icon-sm btn-sm'>{{__('adminMessage.yes')}}</a>" class="btn btn-brand btn-danger btn-icon-sm btn-sm"><i class="la la-trash"></i>{{__('adminMessage.delete')}}</a>
                                                @endif
                                                </div>
                                                    </div>
												</div>
                                                <!--favicon -->
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-8">
                                                    <label>{{__('adminMessage.favicon')}}</label>
                                                        <div class="custom-file @if($errors->has('favicon')) is-invalid @endif">
														<input type="file" class="custom-file-input @if($errors->has('favicon')) is-invalid @endif"  id="favicon" name="favicon">
														<label class="custom-file-label" for="image">{{__('adminMessage.chooseImageFavicon')}}</label>
													    </div>
                                                        @if($errors->has('favicon'))
                                                        <div class="invalid-feedback">{{ $errors->first('favicon') }}</div>
                                                        @endif
                                                </div>
                                                <div class="col-md-4">
                                                @if($settingDetails->favicon)
                                                <img src="{!! url('uploads/logo/'.$settingDetails->favicon) !!}" width="40">
                                                <a href="javascript:;" data-toggle="kt-popover" data-trigger="focus" title="{{__('adminMessage.alert')}}" data-html="true" data-content="{{__('adminMessage.areyousuretodelete')}}<br><br><a href='{{url('gwc/settings/deletefavicon/')}}' class='btn btn-brand btn-danger btn-icon-sm btn-sm'>{{__('adminMessage.yes')}}</a>" class="btn btn-brand btn-danger btn-icon-sm btn-sm"><i class="la la-trash"></i>{{__('adminMessage.delete')}}</a>
                                                @endif
                                                </div>
                                                    </div>
												</div>
                                                <!--watermark image -->
                                                <div class="form-group">
                                                <div class="input-group row">
												 <label class="col-3">{{__('adminMessage.is_watermark')}}</label>
													<div class="col-2">
														<span class="kt-switch"><label>
												<input value="1" {{!empty($settingDetails->is_watermark)?'checked':''}} type="checkbox"  id="is_watermark" name="is_watermark"><span></span>
															</label>
														</span>
													</div>
                                                   </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                <div class="row">
                                                <div class="col-md-8">
                                                <label>{{__('adminMessage.watermarkimage')}}</label>
                                                    <div class="custom-file @if($errors->has('watermark_img')) is-invalid @endif">
                                                    <input type="file" class="custom-file-input @if($errors->has('watermark_img')) is-invalid @endif"  id="watermark_img" name="watermark_img">               <label class="custom-file-label" for="watermark_img">{{__('adminMessage.chooseImagewatermark')}}</label>
                                                    </div>
                                                    @if($errors->has('watermark_img'))
                                                    <div class="invalid-feedback">{{ $errors->first('watermark_img') }}</div>
                                                    @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                    @if($settingDetails->watermark_img)
                                                    <img src="{!! url('uploads/logo/'.$settingDetails->watermark_img) !!}" width="40">
                                                    <a href="javascript:;" data-toggle="kt-popover" data-trigger="focus" title="{{__('adminMessage.alert')}}" data-html="true" data-content="{{__('adminMessage.areyousuretodelete')}}<br><br><a href='{{url('gwc/settings/deletewatermark/')}}' class='btn btn-brand btn-danger btn-icon-sm btn-sm'>{{__('adminMessage.yes')}}</a>" class="btn btn-brand btn-danger btn-icon-sm btn-sm"><i class="la la-trash"></i>{{__('adminMessage.delete')}}</a>
                                                    @endif
                                                    </div>
                                                </div>
                                                </div>
                                          	
											</div>
											<div class="kt-portlet__foot">
												<div class="kt-form__actions">
													<button type="submit" class="btn btn-success">{{__('adminMessage.save')}}</button>
												</div>
											</div>
										

										<!--end::Form-->
									</div>

									<!--end::Portlet-->

								</div>
								<div class="col-md-6">

									<!--begin::Portlet-->
									   <div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													{{__('adminMessage.basicsettings')}}
												</h3>
											</div>
										</div>

										<!--begin::Form-->
		
											<div class="kt-portlet__body">
												<div class="form-group">
													<div class="input-group row">
													 <label class="col-6">{{__('adminMessage.is_language_active')}}</label>
														<div class="col-6">
															<span class="kt-switch">
																<label>
																	<input value="1" {{!empty($settingDetails->is_lang)?'checked':''}} type="checkbox"  id="is_lang" name="is_lang"><span></span>
																</label>
															</span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="input-group row">
													 <label class="col-6">IS Login active</label>
														<div class="col-6">
															<span class="kt-switch">
																<label>
																	<input value="1" {{!empty($settingDetails->is_login)?'checked':''}} type="checkbox"  id="is_login" name="is_login"><span></span>
																</label>
															</span>
														</div>
													</div>
												</div>

												<div class="form-group ">
													<div class="input-group">
														<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.login_url')}}</span></div>
														<input type="text" class="form-control @if($errors->has('login_url')) is-invalid @endif" name="login_url" value="@if($settingDetails->login_url){{$settingDetails->login_url}}@endif">
														@if($errors->has('login_url'))
															<div class="invalid-feedback">{{ $errors->first('login_url') }}</div>
														@endif
													</div>
												</div>
                                                
                                                <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-12">
													<label>{{__('adminMessage.case_updates_notification')}}</label>
													<textarea class="form-control  @if($errors->has('case_updates_notification')) is-invalid @endif" rows="3" name="case_updates_notification" placeholder="{{__('adminMessage.enter_case_updates_notification')}}">@if($settingDetails->case_updates_notification) {!!$settingDetails->case_updates_notification!!} @endif</textarea>
                                                    @if($errors->has('case_updates_notification'))
                                                    <div class="invalid-feedback">{{ $errors->first('case_updates_notification') }}</div>
                                                    @endif
                                                    </div>
                                                    </div>
												</div>
                                            
												<div class="form-group ">
												<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.item_per_page_front')}}</span></div>
												<input type="number" class="form-control @if($errors->has('item_per_page_front')) is-invalid @endif" name="item_per_page_front" value="@if($settingDetails->item_per_page_front){{$settingDetails->item_per_page_front}}@endif">
                                                @if($errors->has('item_per_page_front'))
                                                <div class="invalid-feedback">{{$errors->first('item_per_page_front')}}</div>
                                                @endif
												</div>
                                                </div>
                                                
                                                <div class="form-group ">
												<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.item_per_page_back')}}</span></div>
												<input type="number" class="form-control @if($errors->has('item_per_page_back')) is-invalid @endif" name="item_per_page_back" value="@if($settingDetails->item_per_page_back){{$settingDetails->item_per_page_back}}@endif">
                                                @if($errors->has('item_per_page_back'))
                                                <div class="invalid-feedback">{{ $errors->first('item_per_page_back') }}</div>
                                                @endif
												</div>
                                                </div>
                                                
                                                <div class="form-group ">
												<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.default_sort')}}</span></div>
												
                                                <select name="default_sort" class="form-control @if($errors->has('default_sort')) is-invalid @endif">
                                                @foreach($sortings as $sorting)
                                                <option value="{{$sorting}}" {{$settingDetails->default_sort==$sorting?'selected':''}}>{{$sorting}}</option>
                                                @endforeach
                                                </select>
                                                
                                                @if($errors->has('default_sort'))
                                                <div class="invalid-feedback">{{ $errors->first('default_sort') }}</div>
                                                @endif
												</div>
                                                </div>
                                                <!--category image management-->
                                                <div class="form-group "><h5>{{__('adminMessage.manageimage')}}</h5></div>
                                                
                                                <div class="form-group ">
												<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.image_thumb_wh')}}</span></div>
												<input type="number" class="form-control @if($errors->has('image_thumb_w')) is-invalid @endif" name="image_thumb_w" value="@if($settingDetails->image_thumb_w){{$settingDetails->image_thumb_w}}@endif">
                                                <input type="number" class="form-control @if($errors->has('image_thumb_h')) is-invalid @endif" name="image_thumb_h" value="@if($settingDetails->image_thumb_h){{$settingDetails->image_thumb_h}}@endif">
                                                
                                                @if($errors->has('image_thumb_w'))
                                                <div class="invalid-feedback">{{ $errors->first('image_thumb_w') }}</div>
                                                @endif
                                                @if($errors->has('image_thumb_h'))
                                                <div class="invalid-feedback">{{ $errors->first('image_thumb_h') }}</div>
                                                @endif
                                                
												</div>
                                                </div>
                                                
                                                <div class="form-group ">
												<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.image_big_wh')}}</span></div>
												<input type="number" class="form-control @if($errors->has('image_big_w')) is-invalid @endif" name="image_big_w" value="@if($settingDetails->image_big_w){{$settingDetails->image_big_w}}@endif">
                                                <input type="number" class="form-control @if($errors->has('image_big_h')) is-invalid @endif" name="image_big_h" value="@if($settingDetails->image_big_h){{$settingDetails->image_big_h}}@endif">
                                                
                                                @if($errors->has('image_big_w'))
                                                <div class="invalid-feedback">{{ $errors->first('image_big_w') }}</div>
                                                @endif
                                                @if($errors->has('image_big_h'))
                                                <div class="invalid-feedback">{{ $errors->first('image_big_h') }}</div>
                                                @endif
												</div>
                                                </div>
                                                @if(count($sociallinks))
                                                <input type="hidden" name="socialsfields" value="{{implode(',',$sociallinks)}}">
                                                <div class="form-group "><h5>{{__('adminMessage.sociallinks')}}</h5></div>
                                                @foreach($sociallinks as $sociallinks)
                                                <div class="form-group ">
												<div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">{{__('adminMessage.'.$sociallinks)}}</span></div>
												
                                                <input placeholder="{{__('adminMessage.'.$sociallinks.'_place')}}" type="text" class="form-control" name="social_{{$sociallinks}}" value=" @if($settingDetails->social_facebook && $sociallinks=='facebook'){{$settingDetails->social_facebook}}@elseif($settingDetails->social_twitter && $sociallinks=='twitter'){{$settingDetails->social_twitter}}@elseif($settingDetails->social_instagram && $sociallinks=='instagram'){{$settingDetails->social_instagram}}@elseif($settingDetails->social_linkedin && $sociallinks=='linkedin'){{$settingDetails->social_linkedin}}@elseif($settingDetails->social_youtube && $sociallinks=='youtube'){{$settingDetails->social_youtube}}@endif">
                                   
												</div>
                                                </div>
                                                @endforeach
                                                @endif
                                               
                                               
                                               <div class="form-group">
                                                <div class="row">
								                    <div class="col-md-12">
													<label>{{__('adminMessage.google_analytics')}}</label>
													<textarea class="form-control  @if($errors->has('address_en')) is-invalid @endif" rows="3" name="google_analytics" placeholder="{{__('adminMessage.enter_google_analytics')}}">@if($settingDetails->google_analytics) {!!$settingDetails->google_analytics!!} @endif</textarea>
                                                    @if($errors->has('google_analytics'))
                                                    <div class="invalid-feedback">{{ $errors->first('google_analytics') }}</div>
                                                    @endif
                                                    </div>
                                                    
                                                    </div>
												</div>



												<!--Membership Header Image -->
												<div class="form-group">
													<div class="row">
														<div class="col-md-8">
															<label>Membership Header Image</label>
															<div class="custom-file @if($errors->has('membership_header')) is-invalid @endif">
																<input type="file" class="custom-file-input @if($errors->has('membership_header')) is-invalid @endif"  id="membership_header" name="membership_header">
																<label class="custom-file-label" for="membership_header">Membership Header Image</label>
															</div>
															@if($errors->has('membership_header'))
																<div class="invalid-feedback">{{ $errors->first('membership_header') }}</div>
															@endif
														</div>
														<div class="col-md-4">
															<img src="{{url('uploads/members.jpg?'.cache('uploadHeader'))}}" style="max-width: 100%;">
														</div>
													</div>
												</div>

												<!--News Header Image -->
												<div class="form-group">
													<div class="row">
														<div class="col-md-8">
															<label>News Header Image</label>
															<div class="custom-file @if($errors->has('news_header')) is-invalid @endif">
																<input type="file" class="custom-file-input @if($errors->has('news_header')) is-invalid @endif"  id="news_header" name="news_header">
																<label class="custom-file-label" for="news_header">News Header Image</label>
															</div>
															@if($errors->has('news_header'))
																<div class="invalid-feedback">{{ $errors->first('news_header') }}</div>
															@endif
														</div>
														<div class="col-md-4">
															<img src="{{url('uploads/news.jpg?'.cache('uploadHeader'))}}" style="max-width: 100%;">
														</div>
													</div>
												</div>

												<!--Careers Header Image -->
												<div class="form-group">
													<div class="row">
														<div class="col-md-8">
															<label>Careers Header Image</label>
															<div class="custom-file @if($errors->has('careers_header')) is-invalid @endif">
																<input type="file" class="custom-file-input @if($errors->has('news_header')) is-invalid @endif"  id="careers_header" name="careers_header">
																<label class="custom-file-label" for="careers_header">Careers Header Image</label>
															</div>
															@if($errors->has('careers_header'))
																<div class="invalid-feedback">{{ $errors->first('careers_header') }}</div>
															@endif
														</div>
														<div class="col-md-4">
															<img src="{{url('uploads/careers.jpg?'.cache('uploadHeader'))}}" style="max-width: 100%;">
														</div>
													</div>
												</div>
                                                
                                                
                                            </div>
										

										<!--end::Form-->
									</div>
                               
									<!--end::Portlet-->

								</div>
                                
							</div>
                            </form>
                            
                            @else
                            <div class="alert alert-light alert-warning" role="alert">
								<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
								<div class="alert-text">{{__('adminMessage.youdonthavepermission')}}</div>
							</div>
                            @endif
						</div>


						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					@include('gwc.includes.footer');

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- end:: Page -->


		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

	
		<!-- js files -->
		@include('gwc.js.user')
	</body>

	<!-- end::Body -->
</html>