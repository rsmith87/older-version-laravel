<?php

namespace App\Http\Controllers\Media;


/**
 * Class DownloadController.
 */
class DownloadController extends LfmController
{
    /**
     * Download a file.
     *
     * @return mixed
     */
    public function getDownload()
    {
        return response()->download(parent::getCurrentPath(request('file')));
    }
}
