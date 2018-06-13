<?php

namespace App\Listeners;


use App\Media;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;
use Webpatser\Uuid\Uuid;

class HasUploadedImageListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(ImageWasUploaded $event)
    {
      
        $media_uuid = Uuid::generate()->string;
        $publicFilePath = str_replace(public_path(), "", $event->path());
        $file = pathinfo($publicFilePath);
        $filename = $file['filename'];
        $extension = $file['extension'];
        $basename = $file['basename']
        
        /*Media::create(
          [
            'path' => $publicFilePath,
            'uuid' => $media_uuid,
            'model' => '',
            'model_id' => '',
            'name' => '',
            'file_name' => '',
            'mime_type' => '',
            'disk' => 'public',
            'user_id' = \Auth::id(),
            'size' => '',
          ]
        );*/
    }
}
