<?php

namespace App\Listeners;

use App\Media;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;

class MediaUploaded
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ImageWasUploaded  $event
     * @return void
     */
    public function handle(ImageWasUploaded $event)
    {
        // this is where we get data to save to the DB
        $publicFilePath = str_replace(public_path(), "", $event->path());
        FilePath::create(['path' => $publicFilePath]);
    }
}
