<!DOCTYPE html>
<html lang="en">
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>{{__('adminMessage.websiteName')}}|{{__('adminMessage.createnewclients')}}</title>
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
									<h3 class="kt-subheader__title">{{__('adminMessage.clients')}}</h3>
									<span class="kt-subheader__separator kt-hidden"></span>
									<div class="kt-subheader__breadcrumbs">
										<a href="{{url('gwc/home')}}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
										<span class="kt-subheader__breadcrumbs-separator"></span>
										<a href="javascript:;" class="kt-subheader__breadcrumbs-link">{{__('adminMessage.createnewclients')}}</a>
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
							<div class="alert alert-light alert-warning" role="alert">
								<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
								<div class="alert-text">
									{{ Session::get('message-error') }}
								</div>
							</div>
                           @endif
                      
							<!--begin::Portlet-->
									<div class="kt-portlet">
						<div class="kt-portlet__head kt-portlet__head--lg">
									<div class="kt-portlet__head-label">
										<span class="kt-portlet__head-icon">
											<i class="kt-font-brand flaticon2-line-chart"></i>
										</span>
										<h3 class="kt-portlet__head-title">{{__('adminMessage.createnewclients')}}</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<div class="kt-portlet__head-wrapper">
											<div class="kt-portlet__head-actions">
												
												@if(auth()->guard('admin')->user()->can('clients-list'))
												<a href="{{url('gwc/clients')}}" class="btn btn-brand btn-elevate btn-icon-sm"><i class="la la-list-ul"></i>{{__('adminMessage.listclients')}}</a> @endif
											</div>
										</div>
									</div>
								</div>				
										<!--begin::Form-->
					@if(auth()->guard('admin')->user()->can('clients-create'))
                    
                         <form name="tFrm"  id="form_validation"  method="post"
                          class="kt-form" enctype="multipart/form-data" action="{{route('clients.store')}}">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
											<div class="kt-portlet__body">
										<!--parent categories dropdown -->	
                                           <div class="form-group row">
                                             <div class="col-lg-6">
                                                <div class="form-group row">
													<label class="col-3 col-form-label">{{__('adminMessage.client_type')}}</label>
													<div class="col-9">
														<div class="kt-radio-inline @if($errors->has('client_type')) is-invalid @endif">
														<label class="kt-radio">
															<input type="radio" name="client_type" value="individual"> {{__('adminMessage.individual')}}
															<span></span>
														</label>
														<label class="kt-radio">
															<input type="radio" name="client_type" value="corporate"> {{__('adminMessage.corporate')}}
															<span></span>
														</label>
														
													</div>
                                                               @if($errors->has('client_type'))
                                                               <div class="invalid-feedback">{{ $errors->first('client_type') }}</div>
                                                               @endif
													</div>
												   </div>
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                <div class="form-group row">
													<label class="col-3 col-form-label">{{__('adminMessage.isactive')}}</label>
													<div class="col-3">
														<span class="kt-switch">
															<label>
																<input type="checkbox" checked="checked" name="is_active"  id="is_active" value="1"/>
																<span></span>
															</label>
														</span>
													</div>
												   </div>
                                                </div>
                                            </div>
                                            													
                                            <div class="form-group"><h5>{{__('adminMessage.basicinformation')}}</h5></div>
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                <label>{{__('adminMessage.name')}}</label>
                                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" name="name"
                                                               value="{{old('name')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_name')}}*" />
                                                               @if($errors->has('name'))
                                                               <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                               @endif
                                                </div>
                                                <div class="col-lg-6">
                                                <label>{{__('adminMessage.email')}}</label>
                                                <input type="text" class="form-control @if($errors->has('email')) is-invalid @endif" name="email"
                                                               value="{{old('email')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_email')}}*" />
                                                               @if($errors->has('email'))
                                                               <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                               @endif
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                <label>{{__('adminMessage.mobile')}}-1</label>
                                                <input type="text" class="form-control @if($errors->has('mobile1')) is-invalid @endif" name="mobile1"
                                                               value="{{old('mobile1')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_mobile')}}*" />
                                                               @if($errors->has('mobile1'))
                                                               <div class="invalid-feedback">{{ $errors->first('mobile1') }}</div>
                                                               @endif
                                                </div>
                                                <div class="col-lg-4">
                                                <label>{{__('adminMessage.mobile')}}-2</label>
                                                <input type="text" class="form-control @if($errors->has('mobile2')) is-invalid @endif" name="mobile2"
                                                               value="{{old('mobile2')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_mobile')}}" />
                                                               @if($errors->has('mobile2'))
                                                               <div class="invalid-feedback">{{ $errors->first('mobile2') }}</div>
                                                               @endif
                                                </div>
                                                <div class="col-lg-4">
                                                <label>{{__('adminMessage.mobile')}}-3</label>
                                                <input type="text" class="form-control @if($errors->has('mobile3')) is-invalid @endif" name="mobile3"
                                                               value="{{old('mobile3')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_mobile')}}" />
                                                               @if($errors->has('mobile3'))
                                                               <div class="invalid-feedback">{{ $errors->first('mobile3') }}</div>
                                                               @endif
                                                </div>
                                            </div>
                                            
                                   
                                          
                                         <div class="form-group row">
                                                <div class="col-lg-4">
                                                <label>{{__('adminMessage.image')}}</label>
                                                        <div class="custom-file @if($errors->has('image')) is-invalid @endif">
														<input type="file" class="custom-file-input @if($errors->has('image')) is-invalid @endif"  id="image" name="image">
														<label class="custom-file-label" for="image">{{__('adminMessage.chooseImage')}}</label>
													    </div>
                                                               @if($errors->has('image'))
                                                               <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                                               @endif
                                                </div>
                                                <div class="col-lg-4">
                                                
                                                </div>
                                                <div class="col-lg-4">
                                               
                                                </div>
                                            </div>
                                         <div class="form-group"><h5>{{__('adminMessage.logininformation')}}</h5></div>   
                                          <div class="form-group row">
                                             
                                                <div class="col-lg-4">
                                                <label>{{__('adminMessage.username')}}</label>
                                                <input type="text" class="form-control @if($errors->has('username')) is-invalid @endif" name="username"
                                                               value="{{old('username')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_username')}}" />
													<small> If you leave it blank, the email will be inserted automatically.</small>
													@if($errors->has('username'))
														<div class="invalid-feedback">{{ $errors->first('username') }}</div>
													@endif
												</div>
											  <div class="col-lg-4">
												  <label>{{__('adminMessage.password')}}</label>
												  <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" name="password"
														 value="{{old('password')}}" autocomplete="off" placeholder="{{__('adminMessage.enter_password')}}" />
												  <small> If you leave it blank, the Mobile-1 will be inserted automatically.</small>
                                                               @if($errors->has('password'))
                                                               <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                                               @endif
                                                </div>
                                            </div>           
                                                     
											</div>
											<div class="kt-portlet__foot">
												<div class="kt-form__actions">
													<button type="submit" class="btn btn-success">{{__('adminMessage.save')}}</button>
													<button type="button" onClick="Javascript:window.location.href='{{url('gwc/clients')}}'"  class="btn btn-secondary cancelbtn">{{__('adminMessage.cancel')}}</button>
												</div>
											</div>
										</form>
                                  
                            @else
                            <div class="alert alert-light alert-warning" role="alert">
								<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
								<div class="alert-text">{{__('adminMessage.youdonthavepermission')}}</div>
							</div>
                            @endif
										<!--end::Form-->
									</div>

									<!--end::Portlet-->
                                    
                                    
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
        
         <!--begin::Page Vendors(used by this page) -->
		<script src="{{url('admin_assets/assets/plugins/custom/tinymce/tinymce.bundle.js')}}" type="text/javascript"></script>
		<!--end::Page Vendors -->

		<script>
        jQuery(document).ready(function() {
		tinymce.init({
		selector: '.kt-tinymce-4',
		menubar: false,
		toolbar: [
		'styleselect fontselect fontsizeselect',
		'undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify',
		'bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code'],
		 plugins : 'advlist autolink link image lists charmap print preview code'
		 }); 
		});
       </script>
	</body>

	<!-- end::Body -->
</html>