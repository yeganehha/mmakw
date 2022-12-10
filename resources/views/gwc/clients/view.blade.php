<!DOCTYPE html>
<html lang="en">
	<!-- begin::Head -->
	<head>
		<base href="../../">
		<meta charset="utf-8" />
		<title>{{__('adminMessage.websiteName')}}|{{__('adminMessage.clientdetails')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--css files -->
		@include('gwc.css.user')
        <link rel="stylesheet" href="{!! url('admin_assets/assets/plugins/jstree/dist/themes/default/style.min.css') !!}" />
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
									<span class="kt-subheader__separator kt-subheader__separator--v"></span>
									<div class="kt-subheader__breadcrumbs">
										<a href="{{url('home')}}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
										<span class="kt-subheader__breadcrumbs-separator"></span>
										<a href="javascript:;" class="kt-subheader__breadcrumbs-link">{{__('adminMessage.clientdetails')}}</a>
									</div>
								</div>
								<div class="kt-subheader__toolbar"><a href="{{url('gwc/clients')}}" class="btn btn-default btn-bold">{{__('adminMessage.back')}}</a>
								</div>
							</div>
						</div>

						<!-- end:: Subheader -->

						<!-- begin:: Content -->
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                           @if(Session::get('message-success'))
							<div class="alert alert-light alert-success" role="alert">
								<div class="alert-icon"><i class="flaticon-alert kt-font-brand btn-icon-sm"></i></div>
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
							<div class="kt-portlet kt-portlet--mobile">
								<div class="kt-portlet__head kt-portlet__head--lg">
									<div class="kt-portlet__head-label">
										<span class="kt-portlet__head-icon">
											<i class="kt-font-brand flaticon2-line-chart"></i>
										</span>
										<h3 class="kt-portlet__head-title">
											{{__('adminMessage.clientdetails')}}
										</h3>
									</div>
								</div>
                      
								<div class="kt-portlet__body">
                            @if(auth()->guard('admin')->user()->can('clients-view'))
						    <!--Begin:: Portlet-->
							<div class="kt-widget kt-widget--user-profile-3">
										<div class="kt-widget__top">
                                            <div class="kt-widget__media">
                                                @if($clientDetails->image)
												<img src="{{url('uploads/clients/thumb/'.$clientDetails->image)}}" alt="{{$clientDetails->name}}">
                                                @else
                                                <img src="{{url('uploads/clients/no-image.png')}}" alt="{{$clientDetails->name}}">
                                                @endif
											</div>
											<div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-bolder kt-font-light kt-hidden">
												JM
											</div>
                                            
											<div class="kt-widget__content">
												<div class="kt-widget__head">
													<div class="kt-widget__user">
														<h2>{{$clientDetails->name}}</h2>
													</div>
												</div>
												<div class="kt-widget__subhead">
													@if($clientDetails->website)
                                                    <a href="{{ $clientDetails->website }}"><i class="flaticon2-link"></i>{{$clientDetails->website}}</a>
                                                    @endif
													@if($clientDetails->email)
                                                    <a href="javascript:;"><i class="flaticon2-new-email"></i>{{$clientDetails->email}}</a>
                                                    @endif
                                                    
                                                    @if($clientDetails->username)
                                                    <a href="javascript:;"><i class="flaticon-user"></i>{{$clientDetails->username}}</a>
                                                    @endif
                                                    
                                                    
                                                    @if($clientDetails->mobile1)
													<a href="javascript:;"><i class="flaticon2-phone"></i>{{$clientDetails->mobile1}}</a>
                                                    @endif
                                                    @if($clientDetails->mobile2)
													<a href="javascript:;"><i class="flaticon2-phone"></i>{{$clientDetails->mobile2}}</a>
                                                    @endif
                                                    @if($clientDetails->mobile3)
													<a href="javascript:;"><i class="flaticon2-phone"></i>{{$clientDetails->mobile3}}</a>
                                                    @endif
													
												</div>
												<div class="kt-widget__info">
													<div class="kt-widget__desc">
														
													</div>
													
												</div>
											</div>
										</div>
										
									</div>

							<!--End:: Portlet-->	
                            @else
                            <div class="alert alert-light alert-warning" role="alert">
								<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
								<div class="alert-text">{{__('adminMessage.youdonthavepermission')}}</div>
							</div>
                            @endif
									<!--end: Datatable -->
								</div>
							</div>
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

		<!-- begin::Quick Panel -->
		

		<!-- end::Quick Panel -->

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

		<!-- end::Scrolltop -->

		<!-- js files -->
		@include('gwc.js.user')
         
    
	</body>
	<!-- end::Body -->
</html>