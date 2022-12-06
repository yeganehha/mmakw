<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Clients;
use App\ClientsLogs;
use App\Settings;
use Image;
use File;
use Response;
use PDF;
use Hash;
use Auth;

class AdminClientsController extends Controller
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
        $ClientsLists = Clients::orderBy('id', 'DESC')->paginate($settingInfo->item_per_page_back);
        return view('gwc.clients.index',['ClientsLists' => $ClientsLists]);
    }
	
	//logs
	
	 public function getLogs(Request $request) //Request $request
    {
       
	    $settingInfo = Settings::where("keyname","setting")->first();
        //check search queries
        if(!empty($request->get('q'))){
        $q = $request->get('q');
        }else{
        $q = $request->q;
        }
        
       
        //menus records
        if(!empty($q)){
        $ClientsLists = ClientsLogs::where('message','LIKE','%'.$q.'%')
                            ->orderBy('id', 'DESC')
                            ->paginate(50);  
        $ClientsLists->appends(['q' => $q]);
		
        }else{
        $ClientsLists = ClientsLogs::orderBy('id', 'DESC')->paginate($settingInfo->item_per_page_back);
        }
        return view('gwc.clients.logs',['ClientsLists' => $ClientsLists]);
    }
	
	
	//delete logs
	
	public function deleteClientLogs($id){
	//check param ID
	 if(empty($id)){
	 return redirect('/gwc/clients/logs')->with('message-error','Param ID is missing'); 
	 }
	 //get cat info
	 $clients = ClientsLogs::find($id);
	 //check cat id exist or not
	 if(empty($clients->id)){
	 return redirect('/gwc/clients/logs')->with('message-error','No record found'); 
	 }

	 
	    //save logs
		$key_name   = "clientslogs";
		$key_id     = $clients->id;
		$message    = "Client logs is removed : ".$clients->message;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
	 //end deleting parent cat image
	 $clients->delete();
	 return redirect()->back()->with('message-success','Logs is deleted successfully');	
	}
	/**
	Display the Services listings
	**/
	public function create()
    {
	return view('gwc.clients.create');
	}
	

	
	/**
	Store New Services Details
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
		// $image_big_w = 800;
		// $image_big_h = 800;
        $image_big_w = 180;
        $image_big_h = 60;
		}
		//field validation
	    $this->validate($request, [
		    'name'         => 'required|min:3|max:150|string',
			'client_type'  => 'required|max:15|string',
            'email'        => 'required|email|min:3|max:150|string|unique:gwc_clients,email',
			'mobile1'      => 'required|min:3|max:20',
			'username'     => 'nullable|min:3|max:20|string|unique:gwc_clients,username',
			'password'     => 'nullable|min:3|max:150|string',
			'image'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
		
		//upload image
		$imageName="";
		if($request->hasfile('image')){
		$imageName = 'c-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
		$request->image->move(public_path('uploads/clients'), $imageName);
		// open file a image resource
		$imgbig = Image::make(public_path('uploads/clients/'.$imageName));
		//resize image
		$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
		if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
		// insert watermark at bottom-right corner with 10px offset
		$imgbig->insert(public_path('uploads/clients/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
		}
		// save to imgbig thumb
		$imgbig->save(public_path('uploads/clients/'.$imageName));
		
		//create thumb
		// open file a image resource
		$img = Image::make(public_path('uploads/clients/'.$imageName));
		//resize image
		$img->resize($image_thumb_w,$image_thumb_h);//Fixed w,h
		// save to thumb
		$img->save(public_path('uploads/clients/thumb/'.$imageName));
		}

		$clients = new Clients;
        $clients->client_type=$request->input('client_type');
		$clients->name=$request->input('name');
		$clients->email=$request->input('email');
		$clients->mobile1=$request->input('mobile1');
		$clients->mobile2=$request->input('mobile2');
		$clients->mobile3=$request->input('mobile3');
		$clients->username=$request->input('username') ?? $request->input('email');
		$clients->password=bcrypt($request->input('password') ?? $request->input('mobile1'));
		$clients->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
		$clients->image=$imageName;
		$clients->save();
		
		//save logs
		$key_name   = "clients";
		$key_id     = $clients->id;
		$message    = "New client record is added as (".$request->input('name').")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs

        return redirect('/gwc/clients')->with('message-success','Client is added successfully');
	}
	
	 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $editclients = Clients::find($id);
        return view('gwc.clients.edit',compact('editclients'));
    }
	
	/**
     * Show the form for change password the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changepass($id)
    {
	    $editclients = Clients::find($id);
        return view('gwc.clients.changepass',compact('editclients'));
    }
	
	
	
	 /**
     * Show the details of the services.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
		$clientDetails = Clients::find($id);
        return view('gwc.clients.view',compact('clientDetails'));
    }
	
	
	
	public function editchangepass(Request $request, $id){
	
	$v = Validator::make($request->all(), [
        'new_password'      => 'required',
        'confirm_password'  => 'required|same:new_password',
         ]);

		if ($v->fails())
		{
			return redirect()->back()->withErrors($v->errors())->withInput();
		}
		
		$Clients = Clients::where("id",$id)->first();
		
		//save logs
		$key_name   = "clients";
		$key_id     = $Clients->id;
		$message    = "Client Password is changed for ".$Clients['name'];
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
        $Clients->password   = bcrypt($request->new_password);
        $Clients->updated_at = date("Y-m-d H:i:s");
        $Clients->save();
        return redirect()->back()->with('message-success','Password is changed successfully.');;
		
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
        $image_big_w = 180;
        $image_big_h = 60;
		}
		
	 //field validation  
	    $this->validate($request, [
	        'client_type'  => 'required|max:15|string',
		    'name'         => 'required|min:3|max:150|string',
            'email'        => 'required|email|min:3|max:150|string|unique:gwc_clients,email,'.$id,
			'mobile1'      => 'required|min:3|max:20',
			'image'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
		
	$clients = Clients::find($id);
	$imageName='';
	//upload image
	if($request->hasfile('image')){
	//delete image from folder
	if(!empty($clients->image)){
	$web_image_path = "/uploads/clients/".$clients->image;
	$web_image_paththumb = "/uploads/clients/thumb/".$clients->image;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	 }
	}
	//
	$imageName = 'c-'.md5(time()).'.'.$request->image->getClientOriginalExtension();
	
	$request->image->move(public_path('uploads/clients'), $imageName);
	//create thumb
	// open file a image resource
    $imgbig = Image::make(public_path('uploads/clients/'.$imageName));
	//resize image
	$imgbig->resize($image_big_w,$image_big_h);//Fixed w,h
	
	if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
	// insert watermark at bottom-right corner with 10px offset
    $imgbig->insert(public_path('uploads/clients/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
	}
	// save to imgbig thumb
	$imgbig->save(public_path('uploads/clients/'.$imageName));
	
	//create thumb
	// open file a image resource
    $img = Image::make(public_path('uploads/clients/'.$imageName));
	//resize image
	$img->resize($image_thumb_w,$image_thumb_h);//Fixed w,h
	// save to thumb
	$img->save(public_path('uploads/clients/thumb/'.$imageName));
	
	}else{
	$imageName = $clients->image;
	}
	
	$clients->client_type=$request->input('client_type');
	$clients->name=$request->input('name');
	$clients->email=$request->input('email');
	$clients->mobile1=$request->input('mobile1');
	$clients->mobile2=$request->input('mobile2');
	$clients->mobile3=$request->input('mobile3');
	$clients->is_active=!empty($request->input('is_active'))?$request->input('is_active'):'0';
	$clients->image=$imageName;
	$clients->save();
	
	    //save logs
		$key_name   = "clients";
		$key_id     = $clients->id;
		$message    = "Client details are updated for ".$request->input('name');
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		
	return redirect('/gwc/clients')->with('message-success','Information is updated successfully');
	}
	
	/**
     * Delete the Image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	
	public function deleteImage($id){
	$clients = Clients::find($id);
	//delete image from folder
	if(!empty($clients->image)){
	$web_image_path = "/uploads/clients/".$clients->image;
	$web_image_paththumb = "/uploads/clients/thumb/".$clients->image;
	if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	 }
	}
	
	    //save logs
		$key_name   = "clients";
		$key_id     = $clients->id;
		$message    = "Client image is removed for ".$clients->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
	
	$clients->image='';
	$clients->save();
	return redirect()->back()->with('message-success','Image is deleted successfully');	
	}
	
	/**
     * Delete clients along with childs via ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 public function destroy($id){
	 //check param ID
	 if(empty($id)){
	 return redirect('/gwc/clients')->with('message-error','Param ID is missing'); 
	 }
	 //get cat info
	 $clients = Clients::find($id);
	 //check cat id exist or not
	 if(empty($clients->id)){
	 return redirect('/gwc/clients')->with('message-error','No record found'); 
	 }

	 //delete parent cat mage
	 if(!empty($clients->image)){
	 $web_image_path = "/uploads/clients/".$clients->image;
	 $web_image_paththumb = "/uploads/clients/thumb/".$clients->image;
	 if(File::exists(public_path($web_image_path))){
	   File::delete(public_path($web_image_path));
	   File::delete(public_path($web_image_paththumb));
	  }
	 }
	    //save logs
		$key_name   = "clients";
		$key_id     = $clients->id;
		$message    = "Client account is removed for ".$clients->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
	 //end deleting parent cat image
	 $clients->delete();
	 return redirect()->back()->with('message-success','clients is deleted successfully');	
	 }
	 
	 
	 
		//download pdf
	
	public function downloadPDF(){
	  $clients = Clients::get();
      $pdf = PDF::loadview('gwc.clients.pdf', compact('clients'));
      return $pdf->download('clients.pdf');
    }
	
    //update status
	public function updateStatusAjax(Request $request)
    {
		$recDetails = Clients::where('id',$request->id)->first(); 
		if($recDetails['is_active']==1){
			$active=0;
		}else{
			$active=1;
		}
		//save logs
		$key_name   = "clients";
		$key_id     = $recDetails->id;
		$message    = "Client status is changed to ".$active." for ".$recDetails->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		
		$recDetails->is_active=$active;
		$recDetails->save();
		return ['status'=>200,'message'=>'Status is modified successfully'];
	} 
	
}
