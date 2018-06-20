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
        //$var = public_path() . '/files/'. \Auth::id() .'/' . $file['basename'];
        $size = filesize($event->path());
        $filename = $file['filename'];
        $extension = $file['extension'];
        $basename = $file['basename'];
        
        //NEED TO GET SIZE OF DOCUMENT OR IMAGE
        //ALSO VERIFY $filename AND $basename AND $extension AND $file
        
        Media::create(
          [
            'uuid' => $media_uuid,
            'name' => $basename,
            'file_name' => $filename,
            'mime_type' => $extension,
            'disk' => 'public',
            'user_id' => \Auth::id(),
            'size' => $size.'B',
          ]
        );
    }
}
