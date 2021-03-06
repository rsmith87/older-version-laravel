<?php

namespace App\Listeners;


use App\Media;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
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
        
        Media::create(
          [
            'uuid' => $media_uuid,
            'name' => $basename,
            'file_name' => $filename,
            'path' => $publicFilePath,
            'mime_type' => $extension,
            'disk' => 'public',
            'user_id' => \Auth::id(),
            'size' => $size.'B',
          ]
        );
    }
}
