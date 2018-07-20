<?php

namespace App\Http\Controllers\Media;

use Illuminate\Support\Facades\File;
use App\Settings;
use App\FirmStripe;
use App\Thread;

/**
 * Class FolderController.
 */
class FolderController extends LfmController
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
			if(!$this->user->hasPermissionTo('view contacts')){
				return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
			}
			$this->settings = Settings::where('user_id', $this->user['id'])->first();
			$this->firm_stripe = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
			$this->threads = Thread::forUser(\Auth::id())->where('firm_id', $this->settings->firm_id)->latest('updated_at')->get();

			return $next($request);
		});
	}

    /**
     * Get list of folders as json to populate treeview.
     *
     * @return mixed
     */
    public function getFolders()
    {
        $folder_types = [];
        $root_folders = [];

        if (parent::allowMultiUser()) {
            $folder_types['user'] = 'user';
        }

        if (parent::allowShareFolder()) {
            $folder_types['share'] = 'shares';
        }

        $folder_types['firm'] = 'firm';

        foreach ($folder_types as $folder_type => $lang_key) {
            
            $root_folder_path = parent::getRootFolderPath($folder_type);

            $children = parent::getDirectories($root_folder_path);
            usort($children, function ($a, $b) {
                return strcmp($a->name, $b->name);
            });

            array_push($root_folders, (object) [
                'name' => trans('lfm.title-' . $lang_key),
                'path' => parent::getInternalPath($root_folder_path),
                'children' => $children,
                'has_next' => ! ($lang_key == end($folder_types)),
            ]);
        }

        return view('laravel-filemanager::tree')
            ->with(compact('root_folders'));
    }

    /**
     * Add a new folder.
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = parent::translateFromUtf8(trim(request('name')));
        $path = parent::getCurrentPath($folder_name);

        if (empty($folder_name)) {
            return parent::error('folder-name');
        }

        if (File::exists($path)) {
            return parent::error('folder-exist');
        }

        if (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
            return parent::error('folder-alnum');
        }

        parent::createFolderByPath($path);
        return parent::$success_response;
    }
}
