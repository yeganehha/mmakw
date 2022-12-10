<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Memberships;
use App\Settings;
use Image;
use File;
use Response;
use PDF;
use Hash;
use Auth;
class AdminMembershipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	
	 public function index() //Request $request
    {
       
	    $settingInfo = Settings::where("keyname","setting")->first();
        //check search queries
        $MembershipsLists = Memberships::orderBy('display_order', $settingInfo->default_sort)->paginate($settingInfo->item_per_page_back);
        return view('gwc.memberships.index',['MembershipsLists' => $MembershipsLists]);
    }
	
	
	/**
	Display the Services listings
	**/
	public function create()
    {
	$lastOrderInfo = Memberships::OrderBy('display_order','desc')->first();
	if(!empty($lastOrderInfo->display_order)){
	$lastOrder=($lastOrderInfo->display_order+1);
	}else{
	$lastOrder=1;
	}
	return view('gwc.memberships.create',compact('lastOrder'));
	}
	

	
	/**
	Store New Services Details
	**/
	public function store(Request $request)
    {
	    
		
		$settingInfo = Settings::where("keyname","setting")->first();
		//if(!empty($settingInfo->image_thumb_w) && !empty($settingInfo->image_thumb_h)){
		//$image_thumb_w = $settingInfo->image_thumb_w;
		//$image_thumb_h = $settingInfo->image_thumb_h;
		//}else{
		$image_thumb_w = 370;
		$image_thumb_h = 186;
		//}
		
		//if(!empty($settingInfo->image_big_w) && !empty($settingInfo->image_big_h)){
		//$image_big_w = $settingInfo->image_big_w;
		//$image_big_h = $settingInfo->image_big_h;
		//}else{
		// $image_big_w = 570;
		// $image_big_h = 386;
        $image_big_w = 370;
        $image_big_h = 186;
		//}
		//field validation
	    $this->validate($request, [
		    'title_en'     => 'required|min:3|max:192|string|unique:gwc_memberships,title_en',
			'title_ar'     => 'required|min:3|max:192|string|unique:gwc_memberships,title_ar',
            'website'      => 'nullable|url|string',
			'image'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
		
		//upload image
		$imageName="";
		if($request->hasfile('image')){
		$imageName = 'c-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
		$request->image->move(public_path('uploads/memberships'), $imageName);
		// open file a image resource
		$imgbig = Image::make(public_path('uploads/memberships/'.$imageName));
		//resize image
		$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
		if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
		// insert watermark at bottom-right corner with 10px offset
		$imgbig->insert(public_path('uploads/memberships/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
		}
		// save to imgbig thumb
		$imgbig->save(public_path('uploads/memberships/'.$imageName));
		
		//create thumb
		// open file a image resource
		$img = Image::make(public_path('uploads/memberships/'.$imageName));
		//resize image
		$img->resize($image_thumb_w,$image_thumb_h);//Fixed w,h
		// save to thumb
		$img->save(public_path('uploads/memberships/thumb/'.$imageName));
		}

		$memberships = new Memberships;
		$memberships->title_en=$request->input('title_en');
		$memberships->title_ar=$request->input('title_ar');
		$memberships->details_en=$request->input('details_en');
		$memberships->details_ar=$request->input('details_ar');
		$memberships->website=$request->input('website');
		$memberships->display_order=!empty($request->input('display_order'))?$request->input('display_order'):'0';
		$memberships->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
		$memberships->image=$imageName;
		$memberships->save();
		
		//save logs
		$key_name   = "memberships";
		$key_id     = $memberships->id;
		$message    = "A new Membership & listings is added (".$memberships->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs

        return redirect('/gwc/memberships')->with('message-success','Information is added successfully');
	}
	
	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $editmemberships = Memberships::find($id);
        return view('gwc.memberships.edit',compact('editmemberships'));
    }
	
	
	 /**
     * Show the details of the services.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
		$clientDetails = Memberships::find($id);
        return view('gwc.memberships.view',compact('clientDetails'));
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
	    //if(!empty($settingInfo->image_thumb_w) && !empty($settingInfo->image_thumb_h)){
		//$image_thumb_w = $settingInfo->image_thumb_w;
		//$image_thumb_h = $settingInfo->image_thumb_h;
		//}else{
		$image_thumb_w = 370;
		$image_thumb_h = 186;
		//}
		
		//if(!empty($settingInfo->image_big_w) && !empty($settingInfo->image_big_h)){
		//$image_big_w = $settingInfo->image_big_w;
		//$image_big_h = $settingInfo->image_big_h;
		//}else{
		// $image_big_w = 570;
		// $image_big_h = 386;
		$image_big_w = 370;
		$image_big_h = 186;
		//}
	 //field validation  
	    $this->validate($request, [
            'title_en'        => 'required|min:3|max:192|string|unique:gwc_memberships,title_en,'.$id,
			'title_ar'        => 'required|min:3|max:192|string|unique:gwc_memberships,title_ar,'.$id,
            'website'      => 'nullable|url|string',
			'image'           => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
		
	$memberships = Memberships::find($id);
	$imageName='';
	//upload image
	if($request->hasfile('image')){
	//delete image from folder
	if(!empty($memberships->image)){
	$web_image_path = "/uploads/memberships/".$memberships->image;
	$web_image_paththumb = "/uploads/memberships/thumb/".$memberships->image;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	 }
	}
	//
	$imageName = 'm-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
	
	$request->image->move(public_path('uploads/memberships'), $imageName);
	//create thumb
	// open file a image resource
    $imgbig = Image::make(public_path('uploads/memberships/'.$imageName));
	//resize image
	$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
	
	if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
	// insert watermark at bottom-right corner with 10px offset
    $imgbig->insert(public_path('uploads/memberships/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
	}
	// save to imgbig thumb
	$imgbig->save(public_path('uploads/memberships/'.$imageName));
	
	//create thumb
	// open file a image resource
    $img = Image::make(public_path('uploads/memberships/'.$imageName));
	//resize image
	$img->resize($image_thumb_w,$image_thumb_h);//Fixed w,h
	// save to thumb
	$img->save(public_path('uploads/memberships/thumb/'.$imageName));
	
	}else{
	$imageName = $memberships->image;
	}
	
	$memberships->title_en=$request->input('title_en');
	$memberships->title_ar=$request->input('title_ar');
	$memberships->details_en=$request->input('details_en');
	$memberships->details_ar=$request->input('details_ar');
	$memberships->website=$request->input('website');
	$memberships->display_order=!empty($request->input('display_order'))?$request->input('display_order'):'0';
	$memberships->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
	$memberships->image=$imageName;
	$memberships->save();
	//save logs
		$key_name   = "memberships";
		$key_id     = $memberships->id;
		$message    = "Membership & listings is edited (".$memberships->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
	return redirect('/gwc/memberships')->with('message-success','Information is updated successfully');
	}
	
	/**
     * Delete the Image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	
	public function deleteImage($id){
	$memberships = Memberships::find($id);
	//delete image from folder
	if(!empty($memberships->image)){
	$web_image_path = "/uploads/memberships/".$memberships->image;
	$web_image_paththumb = "/uploads/memberships/thumb/".$memberships->image;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	 }
	}
	    //save logs
		$key_name   = "memberships";
		$key_id     = $memberships->id;
		$message    = "Image is removed for Membership & listings (".$memberships->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		
	$memberships->image='';
	$memberships->save();
	
	
	return redirect()->back()->with('message-success','Image is deleted successfully');	
	}
	
	/**
     * Delete memberships along with childs via ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 public function destroy($id){
	 //check param ID
	 if(empty($id)){
	 return redirect('/gwc/memberships')->with('message-error','Param ID is missing'); 
	 }
	 //get cat info
	 $memberships = Memberships::find($id);
	 //check cat id exist or not
	 if(empty($memberships->id)){
	 return redirect('/gwc/memberships')->with('message-error','No record found'); 
	 }

	 //delete parent cat mage
	 if(!empty($memberships->image)){
	 $web_image_path = "/uploads/memberships/".$memberships->image;
	 $web_image_paththumb = "/uploads/memberships/thumb/".$memberships->image;
	 if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	  }
	 }
	 //save logs
		$key_name   = "memberships";
		$key_id     = $memberships->id;
		$message    = "A record is removed for Membership & listings (".$memberships->title_en.")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		
	 //end deleting parent cat image
	 $memberships->delete();
	 return redirect()->back()->with('message-success','memberships is deleted successfully');	
	 }
	 
	 
	 
		//download pdf
	
	public function downloadPDF(){
	  $memberships = Memberships::get();
      $pdf = PDF::loadview('gwc.memberships.pdf', compact('memberships'));
      return $pdf->download('memberships.pdf');
    }
	
    //update status
	public function updateStatusAjax(Request $request)
    {
		$recDetails = Memberships::where('id',$request->id)->first(); 
		if($recDetails['is_active']==1){
			$active=0;
		}else{
			$active=1;
		}
		
		//save logs
		$key_name   = "memberships";
		$key_id     = $recDetails->id;
		$message    = "Status is changed for Membership & listings (".$recDetails->title_en.") to ".$active;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		
		$recDetails->is_active=$active;
		$recDetails->save();
		return ['status'=>200,'message'=>'Status is modified successfully'];
	} 
	
}
