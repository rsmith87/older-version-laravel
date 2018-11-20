<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Document;
use App\Settings;
use Bitly;
use App\LawCase;
use App\Contact;
use App\Http\Controllers\Controller;
use Webpatser\Uuid\Uuid;
use App\Media;
use App\MediaRelationship;

class MediaController extends Controller
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
            if (!$this->user) {
                return redirect('/login');
            }
            if (!$this->user->hasPermissionTo('view documents')) {
                return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
            }
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
            return $next($request);
        });
    }


    public function index(Request $request)
    {

        if (!$this->user->hasRole('client')) {
            $cases = LawCase::where('firm_id', $this->settings->firm_id)->get();
            $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '1'])->first();
            $contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
        } else {
            $clients = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '1'])->get();
            $contacts = Contact::where(['firm_id' => $this->settings->firm_id, 'is_client' => '0'])->get();
            $contacts = Contact::where('has_login', $this->user['id'])->first();
        }


        return view('dashboard/media', [
            'user' => $this->user,
            'firm_id' => $this->user->firm_id,
            'theme' => $this->settings->theme,
            'cases' => $cases,
            'clients' => $clients,
            'contacts' => $contacts,
            'settings' => $this->settings,

        ]);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        $media = Media::where('uuid', $data['media_id'])->first();
        if (\File::exists($media->path)) {
            \File::delete($media->path);
        }
        $media->delete();
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

        if ($case_id != "") {
            $case = LawCase::where('id', $case_id)->first();
            $relate = MediaRelationship::updateOrCreate([
                'media_uuid' => $media->uuid,
            ], [
                'model' => 'case',
                'model_id' => $case->id,
                'user_id' => \Auth::id(),
            ]);
        }

        if ($client_id != "") {
            $client = Contact::where('id', $client_id)->first();
            $relate = MediaRelationship::updateOrCreate([
                'media_uuid' => $media->uuid,
            ], [
                'model' => 'client',
                'model_id' => $client->id,
                'user_id' => \Auth::id(),
            ]);
        }

        if ($contact_id != "") {
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

    /*public function create_download_link(Request $request, $id)
    {
      $data = $request->all();
      $document = Document::where('id', $id)->first();
      $url = Bitly::getUrl('https://s3.amazonaws.com/legaleeze'.$document->path);
      return redirect('/dashboard/documents/document/'.$document->id)->with('status', 'Your link to send: ' . $url);
    }*/
}
