<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Document;
use App\WysiwygDocument;
use App\Settings;
use Bitly;
use App\LawCase;
use App\Contact;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;
use App\FirmStripe;
use App\Thread;
use App\Media;
use App\MediaRelationship;

class DocumentController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			$this->user = \Auth::user();
      if(!$this->user){
				return redirect('/login');
			}			
			if(!$this->user->hasPermissionTo('view documents')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}						
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
      $this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
      $this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();
      
			$this->s3 = \Storage::disk('s3');
			return $next($request);
		});
	}


	public function index(Request $request)
	{
		
		if (!$this->user->hasRole('client')) {		
			$documents = Document::where('user_id', $this->user['id'])->with('wysiwyg')->get();
			$my_clients = Contact::where(['user_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id, 'is_client' => '1'])->get();
			$my_contacts = Contact::where(['user_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
			$cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
			$clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '1'])->first();
			$contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
		} else {
			$clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '1'])->get();
			$contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
			$my_contacts = "";
			$my_clients = "";
			$contacts = Contact::where('has_login', $this->user['id'])->first();			
			$documents = Document::where('user_id', $this->user['id'])->get();
			$client_documents = Document::where(['client_id' => $contact->id, 'client_share' => 1])->get();
			$cases = LawCase::where('id', $contact->case_id)->get();
		} 
    
		
		return view('dashboard/documents', [
			'user' => $this->user,
			'documents' => $documents, 
			'client_documents' => isset($client_documents) ? $client_documents: "",
			'firm_id' => $this->user->firm_id,
			'theme' => $this->settings->theme,
			'cases' => $cases,
			'clients' => $clients,
			'contacts' => $contacts,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,  
      'threads' => $this->threads
      
		]);
	}

	public function create(Request $request)
	{

	// Creating the new document...
	$phpWord = new \PhpOffice\PhpWord\PhpWord();

	/* Note: any element you append to a document must reside inside of a Section. */

	// Adding an empty Section to the document...
	$section = $phpWord->addSection();
	// Adding Text element to the Section having font styled by default...
	$section->addText(
	'"Learn from yesterday, live for today, hope for tomorrow. '
	. 'The important thing is not to stop questioning." '
	. '(Albert Einstein)'
	);

	/*
	* Note: it's possible to customize font style of the Text element you add in three ways:
	* - inline;
	* - using named font style (new font style object will be implicitly created);
	* - using explicitly created font style object.
	*/

	// Adding Text element with font customized inline...
	$section->addText(
	'"Great achievement is usually born of great sacrifice, '
	. 'and is never the result of selfishness." '
	. '(Napoleon Hill)',
	array('name' => 'Tahoma', 'size' => 10)
	);

	// Adding Text element with font customized using named font style...
	$fontStyleName = 'oneUserDefinedStyle';
	$phpWord->addFontStyle(
	$fontStyleName,
	array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
	);
	$section->addText(
	'"The greatest accomplishment is not in never falling, '
	. 'but in rising again after you fall." '
	. '(Vince Lombardi)',
	$fontStyleName
	);

	// Adding Text element with font customized using explicitly created font style object...
	$fontStyle = new \PhpOffice\PhpWord\Style\Font();
	$fontStyle->setBold(true);
	$fontStyle->setName('Tahoma');
	$fontStyle->setSize(13);
	$myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
	$myTextElement->setFontStyle($fontStyle);

	// Saving the document as OOXML file...
	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

	try {
	$objWriter->save(storage_path('helloWorld.docx'));
	} catch (Exception $e) {
	}


	return response()->download(storage_path('helloWorld.docx'));
	/*$data = $request->all();

	if(!isset($data['id'])){
	//$data['id'] = DB::table('ck_data')->max('id') + 1;     
	}  

	if(!isset($data['document_id'])){
	$data['document_id'] = \DB::table('document')->max('id') + 1;      
	}        


	WysiwygDocument::updateOrCreate(
	[
	'id' => $data['id'],
	],
	[
	'name' => $data['name'],
	'data' => $data['ckeditor_one'],
	'document_id' => $data['document_id'],
	]);

	Document::updateOrCreate(
	[
	'id' => $data['document_id'],
	],
	[
	'name' => $data['name'],
	'description' => $data['description'],        
	'location' => 'db',
	'firm_id' => $this->user->firm_id,        
	]);

	return redirect('/dashboard/documents')->with('status', 'Document created successfully!');  */    
	}

	public function single(Request $request, $id)
	{
   
		$documents = Document::where(['document_uuid' => $id, 'firm_id' => $this->settings->firm_id])->first();
		if(!$this->user->hasRole('client')){		
			$my_clients = Contact::where(['user_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id, 'is_client' => '1'])->get();
			$my_contacts = Contact::where(['user_id' => $this->user['id'], 'firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
			$cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
			$clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '1'])->get();
			$contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
		}
		
		return view('dashboard/document', [
			'user' => $this->user,
			'document' => $documents, 
			'firm_id' => $this->user->firm_id,
			'theme' => $this->settings->theme,
			'cases' => $cases,
			'user' => $this->user,
			'clients' => $clients,
			'contacts' => $contacts,
			'table_color' => $this->settings->table_color,
			'table_size' => $this->settings->table_size,
      'settings' => $this->settings,
      'fs' => $this->firm_stripe,   
      'threads' => $this->threads,
		]);
	}

	public function upload(Request $request)
	{
		$data = $request->all();

    $uuid = Uuid::generate()->string;
    
    $file_name = 'file_upload';
    if(isset($data['dz'])){
      $file_name = 'file';
      $data['file_description'] = 'uploaded through dropzone';
    }
    
		if(!isset($data['id'])){
			$data['id'] = \DB::table('document')->max('id') + 1; 
			$status = 'added';
			$imageFileName = time() . '.' . $request->file($file_name)->getClientOriginalExtension();
			$filePath = '/f/'.$this->settings->firm_id.'/u/'.$this->user['id'].'/' .$imageFileName;
			$fileMimeType = $request->file($file_name)->getMimeType();
			$this->s3->put($filePath, file_get_contents($request->file($file_name)));
			$this->s3->url($filePath);        
		} 
		else {
			$status = 'updated';
			$filePath = $data['file_path'];
			$imageFileName = $data['file_name'];
			$fileMimeType = Document::where('id', $data['id'])->select('mime_type')->first()->mime_type;
		}
		
		if($this->user->hasRole('client')) {			
			$contact = Contact::where('has_login', $this->user['id'])->first();
			$data['client_id'] = $contact->id;
			$data['case_id'] = $contact->case_id;
		}
    
    if(!isset($data['client_share'])){
      $data['client_share'] = 0;
    }
		
		Document::updateOrCreate(
		[
			'id' => $data['id']
		],
		[
      'document_uuid' => $uuid,
			'name' =>  !isset($data['file_name']) ? $imageFileName : $data['file_name'],
			'description' => $data['file_description'],
			'location' => 's3',
			'path' => $filePath,
			'mime_type' => $fileMimeType,
			'firm_id' => $this->settings->firm_id,
			'contact_id' => isset($data['contact_id']) ? $data['contact_id']: "",
			'client_id' => isset($data['client_id']) ? $data['client_id']: "",
			'case_id' => isset($data['case_id']) ? $data['case_id'] : "",
			'user_id' => $this->user['id'],
			'client_share' => isset($data['client_share']) ? 1: 0,
		]);
		return redirect()->back()->with('status', 'Document '.$status.' successfully!');
	}

	public function uploadFileToS3(Request $request)
	{
		$image = $request->file('image');
	}

	public function delete(Request $request)
	{
		$data = $request->all();
		$media = Media::where('uuid', $data['media_id'])->first();
		if(\File::exists($media->path)) {
			\File::delete($media->path);
		}
		$media->delete();
		$relationship = MediaRelationship::where('media_uuid', $data['media_id'])->delete();
		return redirect()->back()->with('status', $data['media_name'] . ' successfully deleted');
	}

	public function send_email(Request $request)
	{
		$data = $request->all();
		$media = Media::where('uuid', $data['media_id'])->first();


		$relationship = MediaRelationship::where('media_uuid', $data['media_id'])->delete();
		return redirect()->back()->with('status', $data['media_name'] . ' successfully deleted');
	}

	public function relate(Request $request)
	{
		$data = $request->all();
		$case_id = $data['case_id'];
		$contact_id = $data['contact_id'];
		$client_id = $data['client_id'];
		$id = $data['id'];

		$media = Media::where('name', $id)->first();

		if($case_id != "") {
			$case = LawCase::where('id', $case_id)->first();
			$relate = MediaRelationship::updateOrCreate([
				'media_uuid' => $media->uuid,
			], [
				'model' => 'case',
				'model_id' => $case->id,
				'user_id' => \Auth::id(),
			]);
		}

		if($client_id != "") {
			$client = Contact::where('id', $client_id)->first();
			$relate = MediaRelationship::updateOrCreate([
				'media_uuid' => $media->uuid,
			], [
				'model' => 'client',
				'model_id' => $client->id,
				'user_id' => \Auth::id(),
			]);
		}

		if($contact_id != "") {
			$contact = Contact::where('id', $contact_id)->first();
			$relate = MediaRelationship::updateOrCreate([
				'media_uuid' => $media->uuid,
			], [
				'model' => 'contact',
				'model_id' => $contact->id,
				'user_id' => \Auth::id(),
			]);
		}
		return redirect()->back()->with('status', 'Media item relationship created');
	}
  
  public function create_download_link(Request $request, $id)
  {
    $data = $request->all();
    $document = Document::where('id', $id)->first();
    $url = Bitly::getUrl('https://s3.amazonaws.com/legaleeze'.$document->path);
    return redirect('/dashboard/documents/document/'.$document->id)->with('status', 'Your link to send: ' . $url);
  }
}
