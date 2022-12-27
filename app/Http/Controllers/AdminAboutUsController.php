<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\About;
use App\Settings;
use Image;
use File;
use Response;
use App\Services\AboutUsSlug;
use PDF;
use Auth;
class AdminAboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	
	 public function index() //Request $request
    {
       
	    $settingInfo = Settings::where("keyname","setting")->first();

        $AboutLists = About::orderBy('display_order', $settingInfo->default_sort)
            ->paginate($settingInfo->item_per_page_back);
        return view('gwc.About.index',['AboutLists' => $AboutLists]);
    }
	
	
	/**
	Display the About listings
	**/
	public function create()
    {
	
	$lastOrderInfo = About::OrderBy('display_order','desc')->first();
	if(!empty($lastOrderInfo->display_order)){
	$lastOrder=($lastOrderInfo->display_order+1);
	}else{
	$lastOrder=1;
	}
	return view('gwc.About.create')->with(['lastOrder'=>$lastOrder]);
	}
	

	
	/**
	Store New About Details
	**/
	public function store(Request $request)
    {
	    
		$settingInfo = Settings::where("keyname","setting")->first();
		if(!empty($settingInfo->image_thumb_w) && !empty($settingInfo->image_thumb_h)){
		$image_thumb_w = $settingInfo->image_thumb_w;
		$image_thumb_h = $settingInfo->image_thumb_h;
		}else{
		$image_thumb_w = 100;
		$image_thumb_h = 100;
		}
		
		if(!empty($settingInfo->image_big_w) && !empty($settingInfo->image_big_h)){
		$image_big_w = $settingInfo->image_big_w;
		$image_big_h = $settingInfo->image_big_h;
		}else{
		$image_big_w = 800;
		$image_big_h = 800;
		}
		//field validation
	    $this->validate($request, [
            'menu_name_en' => 'required|min:3|max:100|string',
			'menu_name_ar' => 'required|min:3|max:100|string',
			'title_en'     => 'required|min:3|max:190|string',
			'title_ar'     => 'required|min:3|max:190|string',
			'details_en'   => 'required|min:3',
			'details_ar'   => 'required|min:3',
			'image'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'headerimage'  => 'headerimage|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
		
		//upload image
		$imageName="";
		if($request->hasfile('image')){
		$imageName = 'b-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
		$request->image->move(public_path('uploads/About'), $imageName);
		// open file a image resource
		$imgbig = Image::make(public_path('uploads/About/'.$imageName));
		//resize image
		$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
		if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
		// insert watermark at bottom-right corner with 10px offset
		$imgbig->insert(public_path('uploads/About/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
		}
		// save to imgbig thumb
		$imgbig->save(public_path('uploads/About/'.$imageName));
		
		//create thumb
		// open file a image resource
		$img = Image::make(public_path('uploads/About/'.$imageName));
		//resize image
		$img->resize($image_thumb_w,$image_thumb_h);//Fixed w,h
		// save to thumb
		$img->save(public_path('uploads/About/thumb/'.$imageName));
		}
		//upload banner
		$bannerimageName="";
		if($request->hasfile('bannerimage')){
		$bannerimageName = 'ban-'.md5(time()).'.'.$request->bannerimage->getClientOriginalExtension();
		$request->bannerimage->move(public_path('uploads/About'), $bannerimageName);
		
		if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
		// open file a image resource
		$imgbig = Image::make(public_path('uploads/About/'.$bannerimageName));
		// insert watermark at bottom-right corner with 10px offset
		$imgbig->insert(public_path('uploads/About/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
		// save to imgbig thumb
		$imgbig->save(public_path('uploads/About/'.$bannerimageName));
		}
		}

		$About = new About;
		//slug
		$slug = new AboutUsSlug;
		
		$About->slug=$slug->createSlug($request->title_en);
		$About->menu_name_en=$request->input('menu_name_en');
		$About->menu_name_ar=$request->input('menu_name_ar');
		$About->title_en=$request->input('title_en');
		$About->title_ar=$request->input('title_ar');
		$About->details_en=$request->input('details_en');
		$About->details_ar=$request->input('details_ar');
		$About->seo_keywords_en=$request->input('seo_keywords_en');
		$About->seo_keywords_ar=$request->input('seo_keywords_ar');
		$About->seo_description_en=$request->input('seo_description_en');
		$About->seo_description_ar=$request->input('seo_description_ar');
		$About->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
		$About->display_order=!empty($request->input('display_order'))?$request->input('display_order'):'0';
		$About->image=$imageName;
		$About->bannerimage=$bannerimageName;
		$About->save();

       //save logs
		$key_name   = "About";
		$key_id     = $About->id;
		$message    = "A new record is added for About us (".$About->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
        return redirect('/gwc/About')->with('message-success','About is added successfully');
	}
	
	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $editAbout = About::find($id);
        return view('gwc.About.edit',compact('editAbout'));
    }
	
	
	 /**
     * Show the details of the About.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
		$AboutDetails = About::find($id);
		//$countCats = $AboutDetails->childs()->count();
		$countCats = $this->countChildPages($AboutDetails);
        return view('gwc.About.view',compact('AboutDetails','countCats'));
    }
	
	
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	 $settingInfo = Settings::where("keyname","setting")->first();
	 if(!empty($settingInfo->image_thumb_w) && !empty($settingInfo->image_thumb_h)){
		$image_thumb_w = $settingInfo->image_thumb_w;
		$image_thumb_h = $settingInfo->image_thumb_h;
		}else{
		$image_thumb_w = 100;
		$image_thumb_h = 100;
		}
		
		if(!empty($settingInfo->image_big_w) && !empty($settingInfo->image_big_h)){
		$image_big_w = $settingInfo->image_big_w;
		$image_big_h = $settingInfo->image_big_h;
		}else{
		$image_big_w = 800;
		$image_big_h = 800;
		}
		
	 //field validation  
	   $this->validate($request, [
            'menu_name_en' => 'required|min:3|max:100|string',
			'menu_name_ar' => 'required|min:3|max:100|string',
			'title_en'     => 'required|min:3|max:190|string',
			'title_ar'     => 'required|min:3|max:190|string',
			'details_en'   => 'required|min:3',
			'details_ar'   => 'required|min:3',
			'image'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'headerimage'  => 'headerimage|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
		
	$About = About::find($id);
	$imageName='';
	//upload image
	if($request->hasfile('image')){
	//delete image from folder
	if(!empty($About->image)){
	$web_image_path = "/uploads/About/".$About->image;
	$web_image_paththumb = "/uploads/About/thumb/".$About->image;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	 }
	}
	//
	$imageName = 'b-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
	
	$request->image->move(public_path('uploads/About'), $imageName);
	//create thumb
	// open file a image resource
    $imgbig = Image::make(public_path('uploads/About/'.$imageName));
	//resize image
	$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
	
	if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
	// insert watermark at bottom-right corner with 10px offset
    $imgbig->insert(public_path('uploads/About/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
	}
	// save to imgbig thumb
	$imgbig->save(public_path('uploads/About/'.$imageName));
	
	//create thumb
	// open file a image resource
    $img = Image::make(public_path('uploads/About/'.$imageName));
	//resize image
	$img->resize($image_thumb_w,$image_thumb_h);//Fixed w,h
	// save to thumb
	$img->save(public_path('uploads/About/thumb/'.$imageName));
	
	}else{
	$imageName = $About->image;
	}
	
	
	$bannerimageName='';
	//upload image
	if($request->hasfile('bannerimage')){
	//delete image from folder
	if(!empty($About->bannerimage)){
	$web_image_path = "/uploads/About/".$About->bannerimage;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	 }
	}
	//
	$bannerimageName = 'ban-'.md5(time()).'.'.$request->bannerimage->getClientOriginalExtension();
	$request->bannerimage->move(public_path('uploads/About'), $bannerimageName);

	if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
	// open file a image resource
    $imgbig = Image::make(public_path('uploads/About/'.$bannerimageName));
	// insert watermark at bottom-right corner with 10px offset
    $imgbig->insert(public_path('uploads/About/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
	$imgbig->save(public_path('uploads/About/'.$bannerimageName));
	}
	}else{
	$bannerimageName = $About->bannerimage;
	}
	
	
	//slug
		$slug = new AboutUsSlug;
		
		$About->slug=$slug->createSlug($request->title_en,$id);
		$About->menu_name_en=$request->input('menu_name_en');
		$About->menu_name_ar=$request->input('menu_name_ar');
		$About->title_en=$request->input('title_en');
		$About->title_ar=$request->input('title_ar');
		$About->details_en=$request->input('details_en');
		$About->details_ar=$request->input('details_ar');
		$About->seo_keywords_en=$request->input('seo_keywords_en');
		$About->seo_keywords_ar=$request->input('seo_keywords_ar');
		$About->seo_description_en=$request->input('seo_description_en');
		$About->seo_description_ar=$request->input('seo_description_ar');
		$About->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
		$About->display_order=!empty($request->input('display_order'))?$request->input('display_order'):'0';
		$About->image=$imageName;
		$About->bannerimage=$bannerimageName;
		$About->save();
		//save logs
		$key_name   = "About";
		$key_id     = $About->id;
		$message    = "Information is edited for About  (".$About->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
	    return redirect('/gwc/About')->with('message-success','Information is updated successfully');
	}
	
	/**
     * Delete the Image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	
	public function deleteImage($id){
	$About = About::find($id);
	//delete image from folder
	if(!empty($About->image)){
	$web_image_path = "/uploads/About/".$About->image;
	$web_image_paththumb = "/uploads/About/thumb/".$About->image;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	 }
	}
	
	$About->image='';
	$About->save();
	return redirect()->back()->with('message-success','Image is deleted successfully');	
	}
	/////////////////////////////////////////
	public function deletebImage($id){
	$About = About::find($id);
	//delete image from folder
	if(!empty($About->bannerimage)){
	$web_image_path = "/uploads/About/".$About->bannerimage;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	 }
	}
	
	//save logs
		$key_name   = "About";
		$key_id     = $About->id;
		$message    = "Image is removed for About  (".$About->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		
	$About->bannerimage='';
	$About->save();
	return redirect()->back()->with('message-success','Image is deleted successfully');	
	}
	
	/**
     * Delete About along with childs via ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 public function destroy($id){
	 //check param ID
	 if(empty($id)){
	 return redirect('/gwc/About')->with('message-error','Param ID is missing');
	 }
	 //get cat info
	 $About = About::find($id);
	 //check cat id exist or not
	 if(empty($About->id)){
	 return redirect('/gwc/About')->with('message-error','No record found');
	 }

	 //delete parent cat mage
	 if(!empty($About->image)){
	 $web_image_path = "/uploads/About/".$About->image;//image
	 $web_image_paththumb = "/uploads/About/thumb/".$About->image;//thumb
	 $web_bimage_path = "/uploads/About/".$About->bannerimage;//banner
	 if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	   File::delete(public_path($web_bimage_path));
	  }
	 }
	    //save logs
		$key_name   = "About";
		$key_id     = $About->id;
		$message    = "Record is removed for About  (".$About->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
	 //end deleting parent cat image
	 $About->delete();
	 return redirect()->back()->with('message-success','About is deleted successfully');
	 }

	
    //update status
	public function updateStatusAjax(Request $request)
    {
		$recDetails = About::where('id',$request->id)->first();
		if($recDetails['is_active']==1){
			$active=0;
		}else{
			$active=1;
		}
		
		//save logs
		$key_name   = "About";
		$key_id     = $recDetails->id;
		$message    = "Status is changed for About  (".$recDetails->title_en.") to ".$active;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		
		$recDetails->is_active=$active;
		$recDetails->save();
		return ['status'=>200,'message'=>'Status is modified successfully'];
	} 
}
