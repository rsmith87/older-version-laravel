<?php

namespace App\Http\Controllers\Media;

use App\LawCase;
use App\Contact;

/**
 * Class LfmController.
 */
class LfmController extends Controller
{
    use \App\Http\Controllers\Traits\LfmHelpers;

    protected static $success_response = 'OK';

    public function __construct()
    {
        $this->applyIniOverrides();
    }

    /**
     * Show the filemanager.
     *
     * @return mixed
     */
    public function show()
    {
        $cases = LawCase::where('u_id', \Auth::id())->get();
        $contacts = Contact::where(['user_id' => \Auth::id(), 'is_client' => 0])->get();
        $clients = Contact::where(['user_id' => \Auth::id(), 'is_client' => 1])->get();
        //print_r($cases);
      
        return view('laravel-filemanager::index', [
            'cases' => $cases,
            'contacts' => $contacts,
            'clients' => $clients,  
        ]);
    }

    public function getErrors()
    {
        $arr_errors = [];

        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            array_push($arr_errors, trans('laravel-filemanager::lfm.message-extension_not_found'));
        }

        $type_key = $this->currentLfmType();
        $mine_config = 'lfm.valid_' . $type_key . '_mimetypes';
        $config_error = null;

        if (! is_array(config($mine_config))) {
            array_push($arr_errors, 'Config : ' . $mine_config . ' is not a valid array.');
        }

        return $arr_errors;
    }
}
