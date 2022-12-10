<?php
namespace App\Http\Controllers;
use App\Attribute;
use App\Box;
use App\Career;
use App\CareerCategory;
use App\Comment;
use App\NewsCategory;
use App\ServiceCategory;
use App\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Response;
use App\Settings;
use App\Newsletter;
use App\Subjects;
use App\Contactus;
use App\NewsEvents;
use App\Practice;
use App\Services;
use App\Memberships;
use App\Clients;
use App\Teams;
use App\Survey;
use App\Mail\SendGrid;
use Mail;

class webController extends Controller
{
   
     
    public function index()
    {
	  //get setting details
	  $settingInfo      = Settings::where("keyname","setting")->first();
	  //get subject
	  $subjectLists     = Subjects::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get latest news & events
	  $newseventslists  = NewsEvents::where("is_active","1")->orderBy('news_date', 'DESC')->limit(2)->get();
	  //get practice area
	  $practiceareaMenus= Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get services
	  $servicesMenus    = Services::where("is_active","1")->where('image', '!=', '')->whereNotNull('image')->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get members
	  $memberslists     = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  $slides           = Slide::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  $service_categories= ServiceCategory::orderBy('display_order', $settingInfo->default_sort)->with('services')->has('services')->get();
	  $clients           = Clients::where("is_active","1")->whereNotNull('image')->where('image', '!=', '')->take(10)->get();
	  $boxes           = Box::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  $attributes           = Attribute::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();

      return view('website.index',compact('settingInfo','subjectLists','newseventslists','practiceareaMenus','servicesMenus','memberslists','slides','service_categories','clients','boxes','attributes'));
    }
	
	//subscribe newsletter email

    public function contactus()
    {
        $settingInfo    = Settings::where("keyname","setting")->first();
        //get practice area
        $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
        //get services
        $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
        //get members
        $memberslists     = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
        return view('website.contactus',compact('settingInfo','practiceareaMenus','servicesMenus','memberslists'));
    }

	public function subscribe_newsletter(Request $request){
	 //field validation
	 $validator = Validator::make($request->all(),['newsletter_email'=>'required|email|min:3|max:150|string|unique:gwc_newsletter,newsletter_email']);
	 if ($validator->fails())
     {
	 return ["status"=>400,"message"=>trans('webMessage.email_valid_required')];
	 }
	 
	 $newsletter = new Newsletter;
	 $newsletter->newsletter_email=$request->input('newsletter_email');
	 $newsletter->save();
	 
	 //send email notification
	 $data = [
	 'dear' => trans('webMessage.dear_visitor'),
	 'footer' => trans('webMessage.email_footer'),
	 'message' => trans('webMessage.newsletter_body'),
	 'subject' =>'E-mail Subscribed Successfully',
	 'email_from' =>'noreply@mmakw.com',
	 'email_from_name' =>'mmakw.com',
	 'email_cc' =>'info@mmakw.com',
	 'email_cc_name' =>'mmakw.com',
	 'email_bcc' =>'info@mmakw.com',
	 'email_bcc_name' =>'mmakw.com',
	 'email_replyto' =>'info@mmakw.com',
	 'email_replyto_name' =>'mmakw.com'
	 ];
     Mail::to($request->input('newsletter_email'))->send(new SendGrid($data));
	 //end sending email
	 
	 return ["status"=>200,"message"=>trans('webMessage.subscribed_successfully')];	
	}
	
	//store contact us details
	public function contactform(Request $request){
	$settingInfo      = Settings::where("keyname","setting")->first();
	
	      $validator = Validator::make($request->all(),[
            'name'    => 'required',
            'email'   => 'required|email',
			'mobile'   => 'required',
			'subject' => 'required',
			'message' => 'required|string|min:10|max:900',
            ],
            [ 
			'name.required'    => trans('webMessage.name_required'),
			'email.required'   => trans('webMessage.email_required'),
			'mobile.required'  => trans('webMessage.mobile_required'),
			'subject.required' => trans('webMessage.subject_required'),
			'message.required' => trans('webMessage.message_required')
			]
			);
	    if ($validator->fails()) {
            return redirect('/contactus#form')
                        ->withErrors($validator)
                        ->withInput();
        }

	 $grecaptcharesponse = !empty($request->input('g-recaptcha-response'))?$request->input('g-recaptcha-response'):'';
	 if(empty($grecaptcharesponse)){
	 return redirect('/contactus#form')
                        ->with(['message-failed'=>'Invalid captcha'])
                        ->withInput();
	 }

	 $recaptchaValidate = Common::VerifyCaptcha($grecaptcharesponse);
	 if($recaptchaValidate){
	 		
	 $contact = new Contactus;		
	 $contact->name=$request->input('name');
	 $contact->email=$request->input('email');
	 $contact->mobile=$request->input('mobile');
	 $contact->subject=$request->input('subject');
	 $contact->message=$request->input('message');
	 $contact->cip=$_SERVER['REMOTE_ADDR'];
	 $contact->created_at=date("Y-m-d H:i:s");
	 $contact->updated_at=date("Y-m-d H:i:s");
	 $contact->save();	
	 //send email notification
	 if(!empty($request->input('email'))){
	 $data = [
	 'dear' => trans('webMessage.dear').' '.$request->input('name'),
	 'footer' => trans('webMessage.email_footer'),
	 'message' => trans('webMessage.contactus_body'),
	 'subject' =>$this->getSubjectName($request->input('subject')),
	 'email_from' =>'noreply@mmakw.com',
	 'email_from_name' =>'mmakw.com',
	 'email_cc' =>'info@mmakw.com',
	 'email_cc_name' =>'mmakw.com',
	 'email_bcc' =>'info@mmakw.com',
	 'email_bcc_name' =>'mmakw.com',
	 'email_replyto' =>'info@mmakw.com',
	 'email_replyto_name' =>'mmakw.com'
	 ];
     Mail::to($request->input('email'))->send(new SendGrid($data));
	 }
	 //
	 if(!empty($settingInfo->email)){
	 $appendMessage ="";
	 $appendMessage .= "<br><b>".trans('webMessage.name')." : </b>".$request->input('name');
	 $appendMessage .= "<br><b>".trans('webMessage.email')." : </b>".$request->input('email');
	 $appendMessage .= "<br><b>".trans('webMessage.mobile')." : </b>".$request->input('mobile');
	 $appendMessage .= "<br><b>".trans('webMessage.subject')." : </b>".$this->getSubjectName($request->input('subject'));
	 $appendMessage .= "<br><b>".trans('webMessage.message')." : </b>".$request->input('message');
	 $dataadmin = [
	 'dear' => trans('webMessage.dearadmin'),
	 'footer' => trans('webMessage.email_footer'),
	 'message' => trans('webMessage.contactus_admin_body')."<br><br>".$appendMessage,
	 'subject' =>$this->getSubjectName($request->input('subject')),
	 'email_from' =>'noreply@mmakw.com',
	 'email_from_name' =>'mmakw.com',
	 'email_cc' =>'info@mmakw.com',
	 'email_cc_name' =>'mmakw.com',
	 'email_bcc' =>'info@mmakw.com',
	 'email_bcc_name' =>'mmakw.com',
	 'email_replyto' =>'info@mmakw.com',
	 'email_replyto_name' =>'mmakw.com'
	 ];
     Mail::to($settingInfo->email)->send(new SendGrid($dataadmin));
	 }
	 //end sending email	
	 return redirect('/contactus#form')->with('message-success',trans('webMessage.contact_message_sent'));
	 }else{
	 return redirect('/contactus#form')->with('message-failed','Invalid Recaptcha Validation');
	 }
	}
	
	//get news details
	public function newsdetails($slug){
	   //get setting details
	   $settingInfo    = Settings::where("keyname","setting")->first();
	   //news details
	   $newsdetails    = NewsEvents::where('slug',$slug)->first();
	   //get practice area 
	   $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	   //get services
	   $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	   //get members  
	   $memberslists     = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
       $news_categories  = NewsCategory::withCount('news')->orderBy('news_count', 'desc')->has('news')->with('news')->get();
       $comments_count = Comment::where('news_id', $newsdetails->id)->where('is_approved', 1)->orderBy('created_at', $settingInfo->default_sort)->count();
       $comments = Comment::where('news_id', $newsdetails->id)->whereNull('parent_id')->where('is_approved', 1)->orderBy('created_at', $settingInfo->default_sort)->get();

	 return view('website.newsdetails',compact('settingInfo','newsdetails','practiceareaMenus','servicesMenus','memberslists','news_categories','comments','comments_count'));
	}
	
	//get news details
	public function practicearea($slug){
	   //get setting details
	   $settingInfo       = Settings::where("keyname","setting")->first();
	   //news details
	   $practicedetails   = Practice::where('slug',$slug)->first();
	   //get practice area 
	   $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	   //get services  
	   $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	   //get members  
	   $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	 	
	 return view('website.practicearea',compact('settingInfo','practicedetails','practiceareaMenus','servicesMenus','memberslists'));
	}
	//get service details
	public function servicedetails($slug){
	//get setting details
	   $settingInfo       = Settings::where("keyname","setting")->first();
	   //news details
	   $servicedetails    = Services::where('slug',$slug)->first();
	   //get practice area 
	   $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	   //get services  
	   $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	   //get members  
	   $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	 	
	 return view('website.services',compact('settingInfo','servicedetails','practiceareaMenus','servicesMenus','memberslists'));
	
	}
    
	//get mission details
	public function whoweare(){
	     $settingInfo    = Settings::where("keyname","setting")->first();
	     //get practice area 
	     $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	     //get services  
	     $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
		 //get members  
	     $memberslists     = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	 return view('website.whoweare',compact('settingInfo','practiceareaMenus','servicesMenus','memberslists'));
	}

	//get mission details
	public function mission(){
	     $settingInfo    = Settings::where("keyname","setting")->first();
	     //get practice area
	     $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	     //get services
	     $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
		 //get members
	     $memberslists     = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	 return view('website.mission',compact('settingInfo','practiceareaMenus','servicesMenus','memberslists'));
	}
	
	
	//get vision details
	public function vision(){
	  $settingInfo    = Settings::where("keyname","setting")->first();
	   //get practice area 
	  $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get services  
	  $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get members  
	  $memberslists     = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  
	 return view('website.vision',compact('settingInfo','practiceareaMenus','servicesMenus','memberslists'));
	}
	
	//get memberships & listings
	public function memberships(){
	
	  $settingInfo       = Settings::where("keyname","setting")->first();
	   //get practice area 
	  $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get services  
	  $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get members  
	  $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  
	  $memberslistsds    = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->paginate($settingInfo->item_per_page_back);
	  
	  
	  
	 return view('website.members',compact('settingInfo','practiceareaMenus','servicesMenus','memberslists','memberslistsds'));
	 
	}
	
	
	public function teams(){
	
	  $settingInfo       = Settings::where("keyname","setting")->first();
	   //get practice area 
	  $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get services  
	  $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get members  
	  $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  
	  $teamlists         = Teams::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->paginate($settingInfo->item_per_page_back);
	  
	  
	  
	 return view('website.teams',compact('settingInfo','practiceareaMenus','servicesMenus','memberslists','teamlists'));
	 
	}
	
	
	
	
	public function news(){
	
	  $settingInfo       = Settings::where("keyname","setting")->first();
	   //get practice area 
	  $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get services  
	  $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get members  
	  $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  //get members
      if(request()->filled('cat'))
	    $NewsLists         = NewsEvents::where("is_active","1")->where("category_id", NewsCategory::whereSlug(request()->cat)->firstOrFail()->id)->orderBy('news_date', 'desc')->with('category')->paginate($settingInfo->item_per_page_front);
      else
        $NewsLists         = NewsEvents::where("is_active","1")->orderBy('news_date', 'desc')->with('category')->paginate($settingInfo->item_per_page_front);
	  
	 return view('website.news',compact('settingInfo','practiceareaMenus','servicesMenus','NewsLists','memberslists'));
	 
	}

	public function careers() {
	  $settingInfo       = Settings::where("keyname","setting")->first();
	  $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
	  $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
      if(request()->filled('cat'))
	    $careers         = Career::where("category_id", CareerCategory::whereSlug(request()->cat)->where('is_active' , 1)->firstOrFail()->id)->latest()->with('category')->paginate($settingInfo->item_per_page_front);
      else
        $careers         = Career::latest()->where('is_active' , 1)->with('category')->paginate($settingInfo->item_per_page_front);

	 return view('website.careers',compact('settingInfo','practiceareaMenus','servicesMenus','careers','memberslists'));
	}

    public function career($slug){
        $settingInfo       = Settings::where("keyname","setting")->first();
        $career            = Career::where('slug',$slug)->where('is_active' , 1)->with('category')->first();
        if ( $career == null)
            abort(404);
        $practiceareaMenus = Practice::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
        $servicesMenus     = Services::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
        $memberslists      = Memberships::where("is_active","1")->orderBy('display_order', $settingInfo->default_sort)->get();
        $career_categories = CareerCategory::withCount('careers')->orderBy('careers_count', 'desc')->has('careers')->with('careers')->get();

        return view('website.career',compact('settingInfo','career','practiceareaMenus','servicesMenus','memberslists','career_categories'));
    }

	public function submitComment(Request $request){
        // store comment

        $settingInfo      = Settings::where("keyname","setting")->first();

        $validator = Validator::make($request->all(),[
            'name'    => 'required',
            'email'   => 'required|email',
            'text' => 'required|string|min:3|max:900',
        ],
            [
                'name.required'    => trans('webMessage.name_required'),
                'email.required'   => trans('webMessage.email_required'),
                'text.required' => trans('webMessage.message_required')
            ]
        );
        if ($validator->fails()) {
            return redirect()->to(url()->previous() . "#comment-form-wrapper")
                ->withErrors($validator)
                ->withInput();
        }

        $comment = Comment::create($request->all());
        $comment->update(['text' => strip_tags($request->text)]);

        return redirect()->to(url()->previous() . "#comment-form-wrapper")->with('message-success',trans('webMessage.comment_sent'));
	}

   ///	
  public static function getSettings(){
  $settingInfo    = Settings::where("keyname","setting")->first();
  return $settingInfo;
  }	
   //get subject name
   public static function getSubjectName($subjectid){
   $recDetails = Subjects::where('id',$subjectid)->first(); 
   return $recDetails['title_en'];
   }
   
   
   //survey ajax form
   public static function ajaxSurveyForm(Request $request){
   
    $settingInfo    = Settings::where("keyname","setting")->first();
	
	
   $responseJson=[];
   
   if(empty($request->survey_name)){
   $responseJson=["status"=>400,"message"=>"Please enter your name"];
   return json_encode($responseJson);
   }
   if(empty($request->survey_job_title)){
   $responseJson=["status"=>400,"message"=>"Please enter your job title"];
   return json_encode($responseJson);
   }
   if(empty($request->survey_phone)){
   $responseJson=["status"=>400,"message"=>"Please enter your phone number"];
   return json_encode($responseJson);
   }
   if(empty($request->survey_email)){
   $responseJson=["status"=>400,"message"=>"Please enter your email address."];
   return json_encode($responseJson);
   }
   if(empty($request->whats_company)){
   $responseJson=["status"=>400,"message"=>"Please enter the name of your company."];
   return json_encode($responseJson);
   }
   if(empty($request->company_sector)){
   $responseJson=["status"=>400,"message"=>"Which option most closely aligns with your company's sector of activity?"];
   return json_encode($responseJson);
   }
   if(empty($request->company_sale)){
   $responseJson=["status"=>400,"message"=>"Does your company sell products/services to consumers, businesses, or both?"];
   return json_encode($responseJson);
   }
   if(empty($request->company_operating)){
   $responseJson=["status"=>400,"message"=>"How long has your company been operating?"];
   return json_encode($responseJson);
   }
   if(empty($request->Temporarily) && empty($request->Permanently)){
   $responseJson=["status"=>400,"message"=>"How many branches were shut down during the COVID-19 pandemic?"];
   return json_encode($responseJson);
   }
   if(empty($request->shutdown_causes)){
   $responseJson=["status"=>400,"message"=>"Was the decision to shut down branches due to personal or governmental lockdown procedures?"];
   return json_encode($responseJson);
   }
   if(empty($request->company_space)){
   $responseJson=["status"=>400,"message"=>"Do you own or rent your company space?"];
   return json_encode($responseJson);
   }
   if(empty($request->if_selected_rent)){
   $responseJson=["status"=>400,"message"=>"How much is the total monthly rent cost of the company (all branches)?"];
   return json_encode($responseJson);
   }
   if(empty($request->total_rent_paid)){
   $responseJson=["status"=>400,"message"=>"How much total rent was paid for the shutdown branches due to the lockdown?"];
   return json_encode($responseJson);
   }
   if(empty($request->annual_revenue)){
   $responseJson=["status"=>400,"message"=>"How much was the annual revenue for your company in 2019?"];
   return json_encode($responseJson);
   }
   if(empty($request->company_value)){
   $responseJson=["status"=>400,"message"=>"What was the company's valuation according to the last audited financial statement?"];
   return json_encode($responseJson);
   }
   if(empty($request->expected_budget)){
   $responseJson=["status"=>400,"message"=>"What was your company's expected budget this year?"];
   return json_encode($responseJson);
   }
   if(empty($request->before_pandemic_kuwaiti)){
   $responseJson=["status"=>400,"message"=>"How many kuwaiti people were/are employed at your company before pandemic?"];
   return json_encode($responseJson);
   }
   if(empty($request->before_pandemic_nonkuwaiti)){
   $responseJson=["status"=>400,"message"=>"How many non kuwaiti people were/are employed at your company before pandemic?"];
   return json_encode($responseJson);
   }
   if(empty($request->currently_kuwaiti)){
   $responseJson=["status"=>400,"message"=>"How many kuwaiti people are currently employed at your company?"];
   return json_encode($responseJson);
   }
   if(empty($request->currently_nonkuwaiti)){
   $responseJson=["status"=>400,"message"=>"How many non kuwaiti people are currently employed at your company?"];
   return json_encode($responseJson);
   }
   if(empty($request->other_services)){
   $responseJson=["status"=>400,"message"=>"Describe and list the different products and/or services your company provides"];
   return json_encode($responseJson);
   }
   if(empty($request->company_provide_online)){
   $responseJson=["status"=>400,"message"=>"Does your company provide services and/or sell products online?"];
   return json_encode($responseJson);
   }
   if(empty($request->online_platform)){
   $responseJson=["status"=>400,"message"=>"Was the online platform developed before or after the COVID-19 outbreak?"];
   return json_encode($responseJson);
   }
   if(empty($request->affected_your_company)){
   $responseJson=["status"=>400,"message"=>"Over the past few months, how has COVID-19 pandemic affected your company?"];
   return json_encode($responseJson);
   }
   if(empty($request->chance_to_shut)){
   $responseJson=["status"=>400,"message"=>"Do you believe there is a chance that your company will permanently shut down, and if so, when could this occur?"];
   return json_encode($responseJson);
   }
   if(empty($request->additional_cost)){
   $responseJson=["status"=>400,"message"=>"If applicable, please state the company's total additional cost incurred due to the COVID-19 pandemic. Such as utilities, web development, delivery, salaries, material wastage etc."];
   return json_encode($responseJson);
   }
   if(empty($request->additional_comments)){
   $responseJson=["status"=>400,"message"=>"Please write your additional details if there are."];
   return json_encode($responseJson);
   }
   if(empty($request->survey_terms)){
   $responseJson=["status"=>400,"message"=>"Have you read our survey terms & conditions?"];
   return json_encode($responseJson);
   }
   
   
   $existSurvey = Survey::where("email",$request->survey_email)->where("phone",$request->survey_phone)->where("name",$request->survey_name)->first();
   if(empty($existSurvey->id)){
   
   $survey = new Survey;
   $survey->name                       = !empty($request->survey_name)?$request->survey_name:'';
   $survey->job_title                  = !empty($request->survey_job_title)?$request->survey_job_title:'';
   $survey->phone                      = !empty($request->survey_phone)?$request->survey_phone:'';
   $survey->email                      = !empty($request->survey_email)?$request->survey_email:'';
   $survey->whats_company              = !empty($request->whats_company)?$request->whats_company:'';
   $survey->company_sector             = !empty($request->company_sector)?$request->company_sector:'';
   $survey->company_sale               = !empty($request->company_sale)?$request->company_sale:'';
   $survey->company_operating          = !empty($request->company_operating)?$request->company_operating:'';
   $survey->Temporarily                = !empty($request->Temporarily)?$request->Temporarily:'';
   $survey->Permanently                = !empty($request->Permanently)?$request->Permanently:'';
   $survey->shutdown_causes            = !empty($request->shutdown_causes)?$request->shutdown_causes:'';
   $survey->company_space              = !empty($request->company_space)?$request->company_space:'';
   $survey->if_selected_rent           = !empty($request->if_selected_rent)?$request->if_selected_rent:'';
   $survey->total_rent_paid            = !empty($request->total_rent_paid)?$request->total_rent_paid:'';
   $survey->annual_revenue             = !empty($request->annual_revenue)?$request->annual_revenue:'';
   $survey->company_value              = !empty($request->company_value)?$request->company_value:'';
   $survey->expected_budget            = !empty($request->expected_budget)?$request->expected_budget:'';
   $survey->before_pandemic_kuwaiti    = !empty($request->before_pandemic_kuwaiti)?$request->before_pandemic_kuwaiti:'';
   $survey->before_pandemic_nonkuwaiti = !empty($request->before_pandemic_nonkuwaiti)?$request->before_pandemic_nonkuwaiti:'';
   $survey->currently_kuwaiti          = !empty($request->currently_kuwaiti)?$request->currently_kuwaiti:'';
   $survey->currently_nonkuwaiti       = !empty($request->currently_nonkuwaiti)?$request->currently_nonkuwaiti:'';
   $survey->other_services             = !empty($request->other_services)?$request->other_services:'';
   $survey->company_provide_online     = !empty($request->company_provide_online)?$request->company_provide_online:'';
   $survey->online_platform            = !empty($request->online_platform)?$request->online_platform:'';
   $survey->affected_your_company      = !empty($request->affected_your_company)?$request->affected_your_company:'';
   $survey->chance_to_shut             = !empty($request->chance_to_shut)?$request->chance_to_shut:'';
   $survey->additional_cost            = !empty($request->additional_cost)?$request->additional_cost:'';
   $survey->additional_comments        = !empty($request->additional_comments)?$request->additional_comments:'';
   $survey->save();
   
    //send email to admin
     if(!empty($settingInfo->email)){
	 $dataadmin = [
	 'dear' => trans('webMessage.dearadmin'),
	 'footer' => trans('webMessage.email_footer'),
	 'message' => "A new survey details are posted by ".$request->survey_name,
	 'subject' => "New Survey Details #".$survey->id,
	 'email_from' =>'noreply@mmakw.com',
	 'email_from_name' =>'mmakw.com'
	 ];
     Mail::to($settingInfo->email)->send(new SendGrid($dataadmin));	 
	 }
   //send email to client
   if(!empty($request->survey_email)){
	 $dataadmin = [
	 'dear' => trans('webMessage.dear_visitor'),
	 'footer' => trans('webMessage.email_footer'),
	 'message' => "We have received your survey details. Thank you for posting the details to our website. ",
	 'subject' => "Survey Poasted to MMAKW. #".$survey->id,
	 'email_from' =>'noreply@mmakw.com',
	 'email_from_name' =>'mmakw.com'
	 ];
     Mail::to($request->survey_email)->send(new SendGrid($dataadmin));	 
	 }
   $responseJson=["status"=>200,"message"=>"Your survey details are posted successfully"];
   return json_encode($responseJson);
    }else{
	$responseJson=["status"=>400,"message"=>"You have already posted your survey details."];
   return json_encode($responseJson);
	}
   }
			
}
