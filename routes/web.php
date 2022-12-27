<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Roles
Route::group(['middleware' => ['admin']], function() {
    Route::resource('gwc/roles','RoleController');
	Route::post('/gwc/roles/{id}','RoleController@update');
	Route::get('/gwc/roles/destroy/{id}','RoleController@destroy');
});

//practices
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/practice/{id}','AdminPracticeController@update');
	Route::get('/gwc/practice/deletePracticeImage/{id}','AdminPracticeController@deleteImage');
	Route::get('/gwc/practice/deletePracticebImage/{id}','AdminPracticeController@deletebImage');
	Route::get('/gwc/practice/delete/{id}','AdminPracticeController@destroy');
	Route::get('/admin/practice/ajax/{id}','AdminPracticeController@updateStatusAjax');
	Route::get('/gwc/practice/csv','AdminPracticeController@downloadCSV');
	Route::get('/gwc/practice/pdf','AdminPracticeController@downloadPDF');
	Route::resource('gwc/practice', 'AdminPracticeController');
});
//about us
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/About/{id}','AdminAboutUsController@update');
	Route::get('/gwc/About/deletePracticeImage/{id}','AdminAboutUsController@deleteImage');
	Route::get('/gwc/About/deletePracticebImage/{id}','AdminAboutUsController@deletebImage');
	Route::get('/gwc/About/delete/{id}','AdminAboutUsController@destroy');
	Route::get('/admin/About/ajax/{id}','AdminAboutUsController@updateStatusAjax');
	Route::resource('gwc/About', 'AdminAboutUsController');
});
//services
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/services/{id}','AdminServicesController@update');
	Route::get('/gwc/services/deleteservicesImage/{id}','AdminServicesController@deleteImage');
	Route::get('/gwc/services/delete/{id}','AdminServicesController@destroy');
	Route::get('/admin/services/ajax/{id}','AdminServicesController@updateStatusAjax');
	Route::get('/gwc/services/pdf','AdminServicesController@downloadPDF');
	Route::resource('gwc/services', 'AdminServicesController');
});

//news & events
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/newsevents/{id}','AdminNewsEventsController@update');
	Route::get('/gwc/newsevents/deletenewseventsImage/{id}','AdminNewsEventsController@deleteImage');
	Route::get('/gwc/newsevents/delete/{id}','AdminNewsEventsController@destroy');
	Route::get('/admin/newsevents/ajax/{id}','AdminNewsEventsController@updateStatusAjax');
	Route::get('/gwc/newsevents/pdf','AdminNewsEventsController@downloadPDF');
	Route::get('/gwc/survey','AdminUserController@survey');
	Route::get('/gwc/survey/delete/{id}','AdminUserController@deleteSurvey');
	Route::get('/gwc/survey/details/{id}','AdminUserController@detailsSurvey');
	Route::resource('gwc/newsevents', 'AdminNewsEventsController');
});

//slides
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/slides/deleteslideImage/{id}','AdminSlidesController@deleteImage');
    Route::get('/gwc/slides/deleteslideVideo/{id}','AdminSlidesController@deleteVideo');
    Route::get('/gwc/slides/delete/{id}','AdminSlidesController@destroy');
    Route::get('/admin/slides/ajax/{id}','AdminSlidesController@updateStatusAjax');
    Route::resource('gwc/slides', 'AdminSlidesController', ['except' => 'destroy']);
});

//boxes
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/boxes/delete/{id}','AdminBoxesController@destroy');
    Route::get('/admin/boxes/ajax/{id}','AdminBoxesController@updateStatusAjax');
    Route::resource('gwc/boxes', 'AdminBoxesController', ['except' => 'destroy']);
});

//news-categories
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/news-categories/delete/{id}','AdminNewsCategoriesController@destroy');
    Route::resource('gwc/news-categories', 'AdminNewsCategoriesController', ['except' => 'destroy']);
});

//attributes
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/attributes/delete/{id}','AdminAttributesController@destroy');
    Route::get('/admin/attributes/ajax/{id}','AdminAttributesController@updateStatusAjax');
    Route::resource('gwc/attributes', 'AdminAttributesController', ['except' => 'destroy']);
});

//comments
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/comments/{id}/approve','AdminCommentsController@approve');
    Route::get('/gwc/comments/{id}/reject','AdminCommentsController@reject');
    Route::resource('gwc/comments', 'AdminCommentsController', ['only' => 'index']);
});

//service-categories
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/service-categories/delete/{id}','AdminServiceCategoriesController@destroy');
    Route::resource('gwc/service-categories', 'AdminServiceCategoriesController', ['except' => 'destroy']);
});

//footer-links
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/footer-links/delete/{id}','AdminFooterLinksController@destroy');
    Route::get('/admin/footer-links/ajax/{id}','AdminFooterLinksController@updateStatusAjax');
    Route::resource('gwc/footer-links', 'AdminFooterLinksController', ['except' => 'destroy']);
});

//resumes
Route::group(['middleware' => ['admin']], function() {
    Route::resource('gwc/resumes', 'AdminResumesController', ['only' => ['index', 'show']]);
});
Route::post('/apply','AdminResumesController@apply');

//careers
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/careers/deletecareersImage/{id}','AdminCareersController@deleteImage');
    Route::get('/gwc/careers/delete/{id}','AdminCareersController@destroy');
    Route::get('/admin/careers/ajax/{id}','AdminCareersController@updateStatusAjax');
    Route::resource('gwc/careers', 'AdminCareersController', ['except' => 'destroy']);
});

//career-categories
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/career-categories/delete/{id}','AdminCareerCategoriesController@destroy');
    Route::resource('gwc/career-categories', 'AdminCareerCategoriesController', ['except' => 'destroy']);
});

//contact us
Route::group(['middleware' => ['admin']], function(){
	Route::get('/gwc/contactus/subjects', 'AdminInboxController@showSubjects');
	Route::post('/gwc/contactus/saveSubject', 'AdminInboxController@saveSubject')->name('saveSubject');
	Route::get('/gwc/contactus/subjects/delete/{id}','AdminInboxController@destroySubjects');
	Route::get('/gwc/contactus/{id}/view','AdminInboxController@view');
	Route::get('/gwc/contactus/inbox/delete/{id}','AdminInboxController@destroy');
	Route::get('/admin/subjects/ajax/{id}','AdminInboxController@updateStatusAjax');
	Route::resource('gwc/contactus/inbox', 'AdminInboxController');
});
//clients
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/clients/{id}','AdminClientsController@update');
	Route::get('/gwc/clients/deleteclientsImage/{id}','AdminClientsController@deleteImage');
	Route::get('/gwc/clients/delete/{id}','AdminClientsController@destroy');
	Route::get('/admin/clients/ajax/{id}','AdminClientsController@updateStatusAjax');
	Route::get('/gwc/clients/{id}/view','AdminClientsController@view');
	Route::get('/gwc/clients/pdf','AdminClientsController@downloadPDF');
	Route::get('/gwc/clients/changepass/{id}','AdminClientsController@changepass');
	Route::post('/gwc/clients/changepass/{id}','AdminClientsController@editchangepass')->name('clients.changepass');
	Route::get('/gwc/clients/logs','AdminClientsController@getLogs');
	Route::get('/gwc/clients/logs/delete/{id}','AdminClientsController@deleteClientLogs');
	
	Route::resource('gwc/clients', 'AdminClientsController');
});

//memberships
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/memberships/{id}','AdminMembershipsController@update');
	Route::get('/gwc/memberships/deletemembershipsImage/{id}','AdminMembershipsController@deleteImage');
	Route::get('/gwc/memberships/delete/{id}','AdminMembershipsController@destroy');
	Route::get('/admin/memberships/ajax/{id}','AdminMembershipsController@updateStatusAjax');
	Route::get('/gwc/memberships/{id}/view','AdminMembershipsController@view');
	Route::resource('gwc/memberships', 'AdminMembershipsController');
});

//case type
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/casetype/{id}','AdminCaseTypeController@update');
	Route::get('/gwc/casetype/delete/{id}','AdminCaseTypeController@destroy');
	Route::get('/admin/casetype/ajax/{id}','AdminCaseTypeController@updateStatusAjax');
	Route::resource('gwc/casetype', 'AdminCaseTypeController');
});
//cases
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/clients_cases/{id}/view','AdminCasesController@view');
    Route::get('/gwc/clients_cases/{id}/edit','AdminCasesController@edit')->name('editCase');
	Route::post('/gwc/clients_cases/{id}/edit','AdminCasesController@update');
	Route::get('/gwc/clients_cases/DateFilterAjax','AdminCasesController@DateFilterAjax');
	Route::get('/gwc/clients_cases/DateFilterAjaxReset','AdminCasesController@DateFilterAjaxReset');
	Route::get('/gwc/caselogs/ajax','AdminCasesController@updateLogs');
	
	
	
	Route::get('/gwc/clients_cases/{client_id}/delete/{id}','AdminCasesController@destroy');
	Route::get('/gwc/clients_cases/{case_id}/deleteattach/{id}','AdminCasesController@destroyAttach');
	Route::get('/admin/clients_cases/ajax/{id}','AdminCasesController@updateStatusAjax');
	Route::get('/gwc/clients_cases/{client_id}/create', 'AdminCasesController@create')->name('createCase');
	Route::post('/gwc/clients_cases/{client_id}/create', 'AdminCasesController@store')->name('createCase');
	Route::get('/gwc/clients_cases/{client_id}','AdminCasesController@index');
	Route::post('/gwc/clients_cases/{client_id}', 'AdminCasesController@index')->name('searchCases');
	Route::get('/gwc/caseAttachUpdates/{id}/{title_en}/{title_ar}/{doc_date}','AdminCasesController@updateCaseAttachAjax');
});

//clients
Route::group(['middleware' => ['admin']], function() {
    Route::get('/gwc/clients_cases_updates/{id}/view','AdminCasesUpdatesController@view');
    Route::get('/gwc/clients_cases_updates/{id}/edit','AdminCasesUpdatesController@edit')->name('editCaseUpdate');
	
	Route::get('/gwc/clients_cases_updates/DateFilterAjax','AdminCasesUpdatesController@DateFilterAjax');
	Route::get('/gwc/clients_cases_updates/DateFilterAjaxReset','AdminCasesUpdatesController@DateFilterAjaxReset');
	
	Route::post('/gwc/clients_cases_updates/{id}/edit','AdminCasesUpdatesController@update');
	Route::get('/gwc/clients_cases_updates/{case_id}/delete/{id}','AdminCasesUpdatesController@destroy');
	Route::get('/gwc/clients_cases_updates/{case_id}/deleteattach/{id}','AdminCasesUpdatesController@destroyAttach');
	Route::get('/admin/clients_cases_updates/ajax/{id}','AdminCasesUpdatesController@updateStatusAjax');
	Route::get('/gwc/clients_cases_updates/{case_id}/create', 'AdminCasesUpdatesController@create')->name('createCaseUpdate');
	Route::post('/gwc/clients_cases_updates/{case_id}/create', 'AdminCasesUpdatesController@store')->name('createCaseUpdate');
	Route::get('/gwc/clients_cases_updates/{case_id}','AdminCasesUpdatesController@index');
	Route::get('/gwc/caseAttachUpdatesDetails/{id}/{title_en}/{title_ar}/{doc_date}','AdminCasesUpdatesController@updateCaseAttachAjax');
	
});


//setting
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/general-settings/{keyname}','AdminSettingsController@update');
	Route::get('/gwc/settings/deletefavicon/','AdminSettingsController@deleteFavicon');
	Route::get('/gwc/settings/deleteEmailLogo/','AdminSettingsController@deleteEmailLogo');
	Route::get('/gwc/settings/deleteLogo/','AdminSettingsController@deleteLogo');
	Route::get('/gwc/settings/deletewatermark/','AdminSettingsController@deletewatermark');
	Route::get('/gwc/aboutus','AdminSettingsController@aboutus');
	Route::post('/gwc/aboutuspost','AdminSettingsController@aboutuspost')->name('aboutuspost');
	Route::get('/gwc/aboutus/deleteimage/','AdminSettingsController@deleteimage');
	Route::resource('gwc/general-settings', 'AdminSettingsController');
	Route::get('/gwc/mission','AdminSettingsController@mission');
	Route::post('/gwc/missionpost','AdminSettingsController@missionpost')->name('missionpost');
	Route::get('/gwc/who','AdminSettingsController@who');
	Route::post('/gwc/whopost','AdminSettingsController@whopost')->name('whopost');
	Route::get('/gwc/vision','AdminSettingsController@vision');
	Route::post('/gwc/visionpost','AdminSettingsController@visionpost')->name('visionpost');
	Route::get('/gwc/teamcontent','AdminSettingsController@teamcontent');
	Route::get('/gwc/survey_terms','AdminSettingsController@survey_terms');
	Route::post('/gwc/survey_termspost','AdminSettingsController@survey_termspost')->name('survey_termspost');
	Route::post('/gwc/teamcontentpost','AdminSettingsController@teamcontentpost')->name('teamcontentpost');
});
//teams
Route::group(['middleware' => ['admin']], function() {
	Route::post('/gwc/teams/{id}','AdminTeamsController@update');
	Route::get('/gwc/teams/deleteteamsImage/{id}','AdminTeamsController@deleteImage');
	Route::get('/gwc/teams/delete/{id}','AdminTeamsController@destroy');
	Route::get('/admin/teams/ajax/{id}','AdminTeamsController@updateStatusAjax');
	Route::get('/gwc/teams/{id}/view','AdminTeamsController@view');
	Route::get('/gwc/teams/pdf','AdminTeamsController@downloadPDF');
	Route::resource('gwc/teams', 'AdminTeamsController');
});

//gwc sections
    Route::get('/gwc/forgot','AdminIndexController@forgotview');
    Route::post('gwc/email', 'AdminIndexController@sendResetLinkEmail')->name('gwc.email');
    Route::get('gwc/forgot/{token}', 'AdminIndexController@showResetForm')->name('gwc.reset');
    Route::post('gwc/forgot/{token}', 'AdminIndexController@resets')->name('gwc.token');
    	
	Route::get('/gwc/','AdminIndexController@index');
	Route::post('/gwc/login','AdminIndexController@login')->name('adminlogin');
	Route::get('/gwc/home','AdminDashboardController@index')->middleware('admin');
	Route::post('/gwc/logout', 'AdminDashboardController@logout'); //logout from admin panel
	Route::get('/gwc/logs','AdminUserController@logs')->middleware('admin');
	Route::get('/gwc/logs/delete/{id}', 'AdminUserController@deleteLogs')->middleware('admin');
	Route::get('/gwc/subscribers','AdminUserController@subscribers')->middleware('admin');
	Route::get('/gwc/subscribers/delete/{id}', 'AdminUserController@deleteSubscriber')->middleware('admin');
	Route::get('/gwc/subscribers/csv', 'AdminUserController@exportSubscriber')->middleware('admin');
	Route::post('/gwc/subscribers', 'AdminUserController@subscribers')->name('searchSubscribers');
	//gwc menus
	Route::get('/gwc/menus', 'AdminMenuController@index')->middleware('admin');
	Route::post('/gwc/menus', 'AdminMenuController@index')->name('menusearch');
	Route::get('/gwc/menus/new', 'AdminMenuController@adminMenusForm')->middleware('admin');
	Route::post('/gwc/menus/new', 'AdminMenuController@AddRecord')->name('newmenu');
	Route::get('/gwc/menus/edit/{id}', 'AdminMenuController@adminMenusForm')->middleware('admin');
	Route::get('/gwc/menus/delete/{id}', 'AdminMenuController@deleteMenus')->middleware('admin');
	Route::get('/admin/menus/ajax/{id}', 'AdminMenuController@updateStatusAjax')->middleware('admin');
	//users
	Route::get('/gwc/users', 'AdminUserController@index')->middleware('admin');;
	Route::post('/gwc/users', 'AdminUserController@index')->name('usersearch');
	Route::get('/gwc/users/new', 'AdminUserController@adminUserForm')->middleware('admin');
	Route::post('/gwc/users/new', 'AdminUserController@AddRecord')->name('newuser');
	Route::get('/gwc/users/edit/{id}', 'AdminUserController@adminUserForm')->middleware('admin');
	Route::get('/gwc/users/changepass/{id}', 'AdminUserController@adminUserForm')->middleware('admin');
	Route::get('/gwc/users/settings/{id}', 'AdminUserController@adminUserForm')->middleware('admin');
	Route::post('/gwc/users/save', 'AdminUserController@adminSaveProfile')->name('adminSaveProfile');
	Route::post('/gwc/users/change/pass', 'AdminUserController@adminChangePass')->name('adminChangePass');
	Route::get('/gwc/users/delete/{id}', 'AdminUserController@deleteUser')->middleware('admin');
	Route::get('/admin/users/ajax/{id}', 'AdminUserController@updateStatusAjax')->middleware('admin');
	Route::get('/gwc/editprofile', 'AdminUserController@editprofile')->middleware('admin');
	
	Route::post('/gwc/editprofile/save', 'AdminUserController@adminSaveEditProfile')->name('adminSaveEditProfile');
	
	Route::get('/gwc/changepassword', 'AdminUserController@changepassword')->middleware('admin');

//Front end sections
//change language
    Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
    });
	
	Route::get('/','webController@index');
	Route::post('/subscribe_newsletter','webController@subscribe_newsletter');
	Route::get('/contactus','webController@contactus');
	Route::post('/contactform','webController@contactform')->name('contactform');
	Route::get('/newsdetails/{slug}','webController@newsdetails');
	Route::post('/submitComment','webController@submitComment');
	Route::get('/whoweare','webController@whoweare');
	Route::get('/mission','webController@mission');
	Route::get('/vision','webController@vision');
	Route::get('/practice/{slug}','webController@practicearea');
	Route::get('/about-us/{slug}','webController@aboutus');
	Route::get('/services/{slug}','webController@servicedetails');
	Route::get('/members','webController@memberships');
	Route::get('/news','webController@news');
	Route::get('/careers','webController@careers');
    Route::get('/careers/{slug}','webController@career');
	Route::get('/team','webController@teams');
	Route::post('/ajax_survey','webController@ajaxSurveyForm');
	//user
	Route::get('/login','userController@loginForm');
	Route::post('/login','userController@loginAuthenticate')->name('loginform');
	
	// Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ForgotPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset/{token}', 'ForgotPasswordController@resets')->name('password.token');
	
	//loggedin user routes
	Route::group(['middleware' => ['webs']], function() {
	Route::get('/account','accountController@index');
	
	Route::get('/editprofile','accountController@editprofileForm');
	Route::post('/editprofile','accountController@editprofileSave')->name('editprofileSave');
	Route::post('/changepass','accountController@changepass')->name('changepass');
	Route::get('/changepass','accountController@changepassForm');
	Route::get('/casesupdates','accountController@viewcaseupdates');
	Route::post('/casesupdates','accountController@viewcaseupdates')->name('search');
	Route::get('/casesupdates-details/{id}','accountController@viewcaseupdatesdetails');
	
	Route::post('/logout', 'accountController@logout')->name('logout');
	});
	
  //image clients	
   Route::get('/uploads/clients/thumb/{file}', [ function ($file) {
	 $path = base_path('public/uploads/clients/thumb/'.$file);
     if (file_exists($path)) {
      return response()->file($path, array('Content-Type' =>'image/jpeg'));
     }
       abort(404);
	}]);
	
	//
  Route::get('/uploads/clients/{file}', [ function ($file) {
  $path = base_path('public/uploads/clients/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  
  //about us
  Route::get('/uploads/aboutus/thumb/{file}', [ function ($file) {
	 $path = base_path('public/uploads/aboutus/thumb/'.$file);
     if (file_exists($path)) {
      return response()->file($path, array('Content-Type' =>'image/jpeg'));
     }
       abort(404);
	}]);
	
	//
  Route::get('/uploads/aboutus/{file}', [ function ($file) {
  $path = base_path('public/uploads/aboutus/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  
  //logo
  Route::get('/uploads/logo/{file}', [ function ($file) {
	 $path = base_path('public/uploads/logo/'.$file);
     if (file_exists($path)) {
      return response()->file($path, array('Content-Type' =>'image/jpeg'));
     }
       abort(404);
	}]);
	
  //membership
  Route::get('/uploads/memberships/{file}', [ function ($file) {
  $path = base_path('public/uploads/memberships/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  Route::get('/uploads/memberships/thumb/{file}', [ function ($file) {
  $path = base_path('public/uploads/memberships/thumb/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  
  //newsevents
  Route::get('/uploads/newsevents/{file}', [ function ($file) {
  $path = base_path('public/uploads/newsevents/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  Route::get('/uploads/newsevents/thumb/{file}', [ function ($file) {
  $path = base_path('public/uploads/newsevents/thumb/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  
  //practice
  Route::get('/uploads/practice/{file}', [ function ($file) {
  $path = base_path('public/uploads/practice/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  Route::get('/uploads/practice/thumb/{file}', [ function ($file) {
  $path = base_path('public/uploads/practice/thumb/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  
  //services
  Route::get('/uploads/services/{file}', [ function ($file) {
  $path = base_path('public/uploads/services/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  Route::get('/uploads/services/thumb/{file}', [ function ($file) {
  $path = base_path('public/uploads/services/thumb/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);


//teams
  Route::get('/uploads/teams/{file}', [ function ($file) {
  $path = base_path('public/uploads/teams/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);
  
  Route::get('/uploads/teams/thumb/{file}', [ function ($file) {
  $path = base_path('public/uploads/teams/thumb/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);

//users
 Route::get('/uploads/users/{file}', [ function ($file) {
  $path = base_path('public/uploads/users/'.$file);
  if (file_exists($path)) {
    return response()->file($path, array('Content-Type' =>'image/jpeg'));
  }
  abort(404);
  }]);

  Route::get('/uploads/attach/{file}', [ function ($file) {
  $path = base_path('public/uploads/attach/'.$file);
  if (file_exists($path)) {
    return response()->download($path);
  }
  abort(404);
  }]);
