<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;//model
use Image;
use File;
use Response;
use Auth;
class AdminSettingsController extends Controller
{
    //view the page
	public function index()
    {
	 //currency
	 $currencies=["KD","AED","USD"];
	 //sorting
	 $sortings=["ASC","DESC"];
	 //social link
	 $sociallinks=["facebook","twitter","instagram","linkedin","youtube"];
	 
	 $settingDetails = Settings::where('keyname','setting')->first();
	 return view('gwc.adminSettingsForm',compact('settingDetails','currencies','sortings','sociallinks'));
	}
	
	/**
	Store setting details
	**/
	public function update(Request $request,$keyname)
    {

        if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/general-settings')->with('message-error','Internal error found. Please reload the page and try again.');
		}
	    //field validation
	    $this->validate($request,[
            'name_en' => 'required|min:3|max:150|string',
			'name_ar' => 'required|min:3|max:150|string',
			'seo_description_en' => 'required|min:3|max:600|string',
			'seo_description_ar' => 'required|min:3|max:600|string',
			'seo_keywords_en' => 'required|min:3|max:300|string',
			'seo_keywords_ar' => 'required|min:3|max:300|string',
			'owner_name' => 'required|min:3|max:150|string',
			'address_en' => 'required|min:3|max:500|string',
			'address_ar' => 'required|min:3|max:500|string',
			'email' => 'required|email|min:3|max:150|string',
			'mobile' => 'nullable|string|max:192',
			'phone' => 'nullable|string|max:192',
			'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'emaillogo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'favicon' => 'image|mimes:jpg,jpeg,gif,png,ico,x-icon|max:2048',
			'item_per_page_front' => 'numeric',
			'item_per_page_back' => 'numeric',
			'bestseller_items' => 'numeric',
			'featured_items' => 'numeric',
			'latest_items' => 'numeric',
			'special_items' => 'numeric',
			'items_of_the_day' => 'numeric',
			'base_currency' => 'string',
			'default_sort' => 'string',
			'image_thumb_w' => 'required',
			'image_thumb_h' => 'required',
			'image_big_w' => 'required',
			'image_big_h' => 'required',
			'office_hours_en' => 'min:3|max:192|string',
			'office_hours_ar' => 'min:3|max:192|string',
			'footer_about_en' => 'max:500|string',
			'footer_about_ar' => 'max:500|string',
			'fax' => 'max:100|string',
            'loginurl' => 'nullable|max:100|string',
        ]);



        if($request->hasfile('membership_header')){
            $request->membership_header->move( public_path('uploads') , 'members.jpg');
            cache()->rememberForever('uploadHeader' , fn() => time() );
        }
        if($request->hasfile('news_header')){
            $request->news_header->move( public_path('uploads') , 'news.jpg');
            cache()->rememberForever('uploadHeader' , fn() => time() );
        }
        if($request->hasfile('careers_header')){
            $request->careers_header->move( public_path('uploads') , 'careers.jpg');
            cache()->rememberForever('uploadHeader' , fn() => time() );
        }

		 //check if water status is on , image required
		 $is_watermark = !empty($request->input('is_watermark'))?$request->input('is_watermark'):'0';
		 if($request->hasfile('watermark_img') && $is_watermark==0){
		 return redirect()->back()->with("message-error","Please choose watermark status");
		 }
		
		
		$setting = Settings::where("keyname","setting")->first();
		//upload logo
		
		if($request->hasfile('logo')){
		//delete image from folder
		if(!empty($setting->logo)){
		$web_logo_path = "/uploads/logo/".$setting->logo;
		if(File::exists(public_path($web_logo_path))){
		   File::delete(public_path($web_logo_path));
		 }
		}
		$logoName = 'logo-'.md5(time()).'.'.$request->logo->getClientOriginalExtension();
		$request->logo->move(public_path('uploads/logo'), $logoName);
		}else{
		$logoName=$setting->logo;
		}
		//upload email logo
		$emaillogoName="";
		if($request->hasfile('emaillogo')){
		//delete image from folder
		if(!empty($setting->emaillogo)){
		$web_elogo_path = "/uploads/logo/".$setting->emaillogo;
		if(File::exists(public_path($web_elogo_path))){
		   File::delete(public_path($web_elogo_path));
		 }
		}
		$emaillogoName = 'elogo-'.md5(time()).'.'.$request->emaillogo->getClientOriginalExtension();
		$request->emaillogo->move(public_path('uploads/logo'), $emaillogoName);
		}else{
		$emaillogoName=$setting->emaillogo;
		}
		//upload favicon
		$faviconName="";
		if($request->hasfile('favicon')){
		//delete image from folder
		if(!empty($setting->favicon)){
		$web_fav_path = "/uploads/logo/".$setting->favicon;
		if(File::exists(public_path($web_fav_path))){
		   File::delete(public_path($web_fav_path));
		 }
		}
		$faviconName = 'favicon-'.md5(time()).'.'.$request->favicon->getClientOriginalExtension();
		$request->favicon->move(public_path('uploads/logo'), $faviconName);
		}else{
		$faviconName=$setting->favicon;
		}
		
		//upload watermark
		$watermarkName="";
		if($request->hasfile('watermark_img')){
		//delete image from folder
		if(!empty($setting->watermark_img)){
		$web_wm_path = "/uploads/logo/".$setting->watermark_img;
		if(File::exists(public_path($web_wm_path))){
		   File::delete(public_path($web_wm_path));
		 }
		}
		$watermarkName = 'watermark-'.md5(time()).'.'.$request->watermark_img->getClientOriginalExtension();
		$request->watermark_img->move(public_path('uploads/logo'), $watermarkName);
		}else{
		$watermarkName=$setting->watermark_img;
		}
		//check social links
		
		$setting->social_facebook=$request->input('social_facebook');
		$setting->social_twitter=$request->input('social_twitter');
		$setting->social_instagram=$request->input('social_instagram');
		$setting->social_linkedin=$request->input('social_linkedin');
		$setting->social_youtube=$request->input('social_youtube');
		 

		$setting->office_hours_en=$request->input('office_hours_en');
		$setting->office_hours_ar=$request->input('office_hours_ar');
		
		$setting->name_en=$request->input('name_en');
		$setting->name_ar=$request->input('name_ar');
		$setting->seo_description_en=$request->input('seo_description_en');
		$setting->seo_description_ar=$request->input('seo_description_ar');
		$setting->seo_keywords_en=$request->input('seo_keywords_en');
		$setting->seo_keywords_ar=$request->input('seo_keywords_ar');
		$setting->footer_about_en=$request->input('footer_about_en');
		$setting->footer_about_ar=$request->input('footer_about_ar');
		$setting->owner_name=$request->input('owner_name');
		$setting->address_en=$request->input('address_en');
		$setting->address_ar=$request->input('address_ar');
		$setting->email=$request->input('email');
		$setting->mobile=$request->input('mobile');
		$setting->phone=$request->input('phone');
		$setting->fax=$request->input('fax');
		$setting->login_url= empty($request->input('login_url')) ? null : $request->input('login_url');
		//$setting->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
		$setting->logo=$logoName;
		$setting->emaillogo=$emaillogoName;
		$setting->favicon=$faviconName;
		$setting->watermark_img=$watermarkName;
		$setting->is_watermark=!empty($request->input('is_watermark'))?$request->input('is_watermark'):'0';
		
		$setting->item_per_page_front=!empty($request->input('item_per_page_front'))?$request->input('item_per_page_front'):'50';
		$setting->item_per_page_back=!empty($request->input('item_per_page_back'))?$request->input('item_per_page_back'):'50';

		$setting->default_sort=!empty($request->input('default_sort'))?$request->input('default_sort'):'0';
		//category image
		$setting->image_thumb_w=!empty($request->input('image_thumb_w'))?$request->input('image_thumb_w'):'100';
		$setting->image_thumb_h=!empty($request->input('image_thumb_h'))?$request->input('image_thumb_h'):'100';
		$setting->image_big_w=!empty($request->input('image_big_w'))?$request->input('image_big_w'):'500';
		$setting->image_big_h=!empty($request->input('image_big_h'))?$request->input('image_big_h'):'500';
	
	
	    $setting->google_analytics = $request->input('google_analytics');
		
		$setting->is_lang=!empty($request->input('is_lang'))?$request->input('is_lang'):'0';
		
		$setting->case_updates_notification=$request->input('case_updates_notification');
		
		$setting->save();
		
		
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Website settings are updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		return redirect('/gwc/general-settings')->with('message-success','Information is updated successfully');
	}
	
		
	//delete favicon
	public function deleteFavicon(){
	$setting = Settings::where("keyname","setting")->first();
	   if(!empty($setting->favicon)){
		$web_fav_path = "/uploads/logo/".$setting->favicon;
		if(File::exists(public_path($web_fav_path))){
		   File::delete(public_path($web_fav_path));
		 }
		}
	$setting->favicon='';
	$setting->save();
	    //save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Favicon is removed";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
			
	return redirect('/gwc/general-settings')->with('message-success','Favicon is removed successfully');	
	}
	
	//delete logo
	public function deleteLogo(){
	$setting = Settings::where("keyname","setting")->first();
	   if(!empty($setting->logo)){
		$web_fav_path = "/uploads/logo/".$setting->logo;
		if(File::exists(public_path($web_fav_path))){
		   File::delete(public_path($web_fav_path));
		 }
		}
	$setting->logo='';
	$setting->save();	
	//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Logo is removed";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
	return redirect('/gwc/general-settings')->with('message-success','Logo is removed successfully');	
	}
	
	//delete emai llogo
	public function deleteEmailLogo(){
	$setting = Settings::where("keyname","setting")->first();
	   if(!empty($setting->emaillogo)){
		$web_fav_path = "/uploads/logo/".$setting->emaillogo;
		if(File::exists(public_path($web_fav_path))){
		   File::delete(public_path($web_fav_path));
		 }
		}
	$setting->emaillogo='';
	$setting->save();
	//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Email logo is removed";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs	
	return redirect('/gwc/general-settings')->with('message-success','E-mail Logo is removed successfully');	
	}
	
	
	//delete watermark
	public function deletewatermark(){
	$setting = Settings::where("keyname","setting")->first();
	   if(!empty($setting->watermark_img)){
		$web_wtm_path = "/uploads/logo/".$setting->watermark_img;
		if(File::exists(public_path($web_wtm_path))){
		   File::delete(public_path($web_wtm_path));
		 }
		}
	$setting->watermark_img='';
	$setting->is_watermark='0';
	$setting->save();	
	//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Watermark image is removed";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
	return redirect('/gwc/general-settings')->with('message-success','Watermark image is removed successfully');	
	}
	
	
	///about us
	
	
	public function aboutus(){
	$settingDetails = Settings::where('keyname','setting')->first();
	return view('gwc.adminaboutusForm',compact('settingDetails'));
	}
	
	public function aboutuspost(Request $request){
	    $keyname = "setting";
	    if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/aboutus')->with('message-error','Internal error found. Please reload the page and try again.');
		}
		
	    //field validation
	    $this->validate($request,[
            'about_title_1_en' => 'required|min:3|max:150|string',
			'about_title_1_ar' => 'required|min:3|max:150|string',
			'about_title_2_en' => 'required|min:3|max:600|string',
			'about_title_2_en' => 'required|min:3|max:600|string',
			'about_details_en' => 'required|min:3|string',
			'about_details_ar' => 'required|min:3|string',
			'image'            => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
		
		$setting = Settings::where("keyname","setting")->first();
		
		if(!empty($setting->image_thumb_w) && !empty($setting->image_thumb_h)){
		$image_thumb_w = $setting->image_thumb_w;
		$image_thumb_h = $setting->image_thumb_h;
		}else{
		$image_thumb_w = 100;
		$image_thumb_h = 100;
		}
		
		if(!empty($setting->image_big_w) && !empty($setting->image_big_h)){
		$image_big_w = $setting->image_big_w;
		$image_big_h = $setting->image_big_h;
		}else{
		$image_big_w = 600;
		$image_big_h = 600;
		}
		
		if($request->hasfile('image')){
		//delete image from folder
		if(!empty($setting->image)){
		$web_image_path = "/uploads/aboutus/".$setting->image;
		if(File::exists(public_path($web_image_path))){
		   File::delete(public_path($web_image_path));
		 }
		}
		$imageName = 'image-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
		
		$request->image->move(public_path('uploads/aboutus'), $imageName);
		// open file a image resource
		$imgbig = Image::make(public_path('uploads/aboutus/'.$imageName));
		//resize image
		$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
		
		if($setting->is_watermark==1 && !empty($setting->watermark_img)){
		// insert watermark at bottom-right corner with 10px offset
		$imgbig->insert(public_path('uploads/logo/'.$setting->watermark_img), 'bottom-right', 10, 10);
		}
		// save to imgbig thumb
		$imgbig->save(public_path('uploads/aboutus/'.$imageName));
		
		}else{
		$imageName=$setting->image;
		}
	
		
		$setting->about_title_1_en=$request->input('about_title_1_en');
		$setting->about_title_1_ar=$request->input('about_title_1_ar');
		$setting->about_title_2_en=$request->input('about_title_2_en');
		$setting->about_title_2_ar=$request->input('about_title_2_ar');
		$setting->about_details_en=$request->input('about_details_en');
		$setting->about_details_ar=$request->input('about_details_ar');
		$setting->image=$imageName;
		$setting->save();
		
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "About us information is updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		return redirect('/gwc/aboutus')->with('message-success','Information is updated successfully');
		
	}
	
	//survey terms
	public function survey_terms(){
	$settingDetails = Settings::where('keyname','setting')->first();
	return view('gwc.admintermsForm',compact('settingDetails'));
	}
	
	public function survey_termspost(Request $request){
	    $keyname = "setting";
	    if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/survey_terms')->with('message-error','Internal error found. Please reload the page and try again.');
		}
		
	    //field validation
	    $this->validate($request,[
            'survey_terms_title_en'   => 'required|min:3|max:150|string',
			'survey_terms_title_ar'   => 'required|min:3|max:150|string',
			'survey_terms_details_en' => 'required|min:3|string',
			'survey_terms_details_ar' => 'required|min:3|string'
        ]);
		
		$setting = Settings::where("keyname","setting")->first();
		
		$setting->survey_terms_title_en=$request->input('survey_terms_title_en');
		$setting->survey_terms_title_ar=$request->input('survey_terms_title_ar');
		$setting->survey_terms_details_en=$request->input('survey_terms_details_en');
		$setting->survey_terms_details_ar=$request->input('survey_terms_details_ar');
		$setting->is_active_survey=!empty($request->input('is_active_survey'))?$request->input('is_active_survey'):'0';
		
		$setting->save();
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Survey Terms information is updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		return redirect('/gwc/survey_terms')->with('message-success','Information is updated successfully');
		
	}
	//mission
	public function mission(){
	$settingDetails = Settings::where('keyname','setting')->first();
	return view('gwc.adminmissionForm',compact('settingDetails'));
	}
	
	public function missionpost(Request $request){
	    $keyname = "setting";
	    if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/mission')->with('message-error','Internal error found. Please reload the page and try again.');
		}
		
	    //field validation
	    $this->validate($request,[
            'mission_title_en'   => 'required|min:3|max:150|string',
			'mission_title_ar'   => 'required|min:3|max:150|string',
			'mission_details_en' => 'required|min:3|string',
			'mission_details_ar' => 'required|min:3|string'
        ]);
		
		$setting = Settings::where("keyname","setting")->first();
		
		$setting->mission_title_en=$request->input('mission_title_en');
		$setting->mission_title_ar=$request->input('mission_title_ar');
		$setting->mission_details_en=$request->input('mission_details_en');
		$setting->mission_details_ar=$request->input('mission_details_ar');
		$setting->save();
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Mission information is updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		return redirect('/gwc/mission')->with('message-success','Information is updated successfully');
		
	}

	//who
	public function who(){
	$settingDetails = Settings::where('keyname','setting')->first();
	return view('gwc.adminwhoForm',compact('settingDetails'));
	}

	public function whopost(Request $request){
	    $keyname = "setting";
	    if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/who')->with('message-error','Internal error found. Please reload the page and try again.');
		}

	    //field validation
	    $this->validate($request,[
            'who_title_en'   => 'min:3|max:150|string',
			'who_title_ar'   => 'min:3|max:150|string',
			'who_details_en' => 'required|min:3|string',
			'who_details_ar' => 'required|min:3|string'
        ]);

		$setting = Settings::where("keyname","setting")->first();

		$setting->who_title_en=$request->input('who_title_en');
		$setting->who_title_ar=$request->input('who_title_ar');
		$setting->who_details_en=$request->input('who_details_en');
		$setting->who_details_ar=$request->input('who_details_ar');
		$setting->save();
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Who We Are information is updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		return redirect('/gwc/who')->with('message-success','Information is updated successfully');
	}

	//vision
	public function vision(){
	$settingDetails = Settings::where('keyname','setting')->first();
	return view('gwc.adminvisionForm',compact('settingDetails'));
	}
	
	public function visionpost(Request $request){
	    $keyname = "setting";
	    if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/vision')->with('message-error','Internal error found. Please reload the page and try again.');
		}
		
	    //field validation
	    $this->validate($request,[
            'vision_title_en'   => 'required|min:3|max:150|string',
			'vision_title_ar'   => 'required|min:3|max:150|string',
			'vision_details_en' => 'required|min:3|string',
			'vision_details_ar' => 'required|min:3|string'
        ]);
		
		$setting = Settings::where("keyname","setting")->first();
		
		$setting->vision_title_en=$request->input('vision_title_en');
		$setting->vision_title_ar=$request->input('vision_title_ar');
		$setting->vision_details_en=$request->input('vision_details_en');
		$setting->vision_details_ar=$request->input('vision_details_ar');
		$setting->save();
		
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Vision information is updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		return redirect('/gwc/vision')->with('message-success','Information is updated successfully');
		
	}
	
	//team content 
	public function teamcontent(){
	$settingDetails = Settings::where('keyname','setting')->first();
	return view('gwc.adminTeamTextForm',compact('settingDetails'));
	}
	
	public function teamcontentpost(Request $request){
	    $keyname = "setting";
	    if(empty($keyname) || $keyname<>"setting"){
		return redirect('/gwc/mission')->with('message-error','Internal error found. Please reload the page and try again.');
		}
		
	    //field validation
	    $this->validate($request,[
			'team_content_en' => 'required|min:3|string',
			'team_content_ar' => 'required|min:3|string'
        ]);
		
		$setting = Settings::where("keyname","setting")->first();
		
		$setting->team_content_en=$request->input('team_content_en');
		$setting->team_content_ar=$request->input('team_content_ar');
		$setting->save();
		//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "Team context information is updated";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		return redirect('/gwc/teamcontent')->with('message-success','Information is updated successfully');
		
	}
	
	//delete watermark
	public function deleteimage(){
	$setting = Settings::where("keyname","setting")->first();
	   if(!empty($setting->image)){
		$web_wtm_path = "/uploads/aboutus/".$setting->image;
		$web_wtm_path_thumb = "/uploads/aboutus/thumb/".$setting->image;
		if(File::exists(public_path($web_wtm_path))){
		   File::delete(public_path($web_wtm_path));
		 }
		 if(File::exists(public_path($web_wtm_path_thumb))){
		   File::delete(public_path($web_wtm_path_thumb));
		 }
		}
	$setting->image='';
	$setting->save();	
	
	//save logs
		$key_name   = "setting";
		$key_id     = $setting->id;
		$message    = "About us image is removed";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
	return redirect('/gwc/aboutus')->with('message-success','Image is removed successfully');	
	}
	
	
}
