<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Unisharp\Laravelfilemanager\Events\ImageIsDeleting;
use Unisharp\Laravelfilemanager\Events\ImageIsRenaming;
use Unisharp\Laravelfilemanager\Events\ImageIsUploading;
use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;

use App\Listeners\DeleteImageListener;
use App\Listeners\RenameImageListener;
use App\Listeners\IsUploadingImageListener;
use App\Listeners\HasUploadedImageListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        ImageIsDeleting::class => [
            DeleteImageListener::class
        ],
        ImageIsRenaming::class => [
            RenameImageListener::class
        ],
        ImageIsUploading::class => [
            IsUploadingImageListener::class
        ],
        ImageWasUploaded::class => [
            HasUploadedImageListener::class
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    /**
     * "ctf0/package-changelog".
     */
    public static function postAutoloadDump(\Composer\Script\Event $event)
    {
        if (class_exists('ctf0\PackageChangeLog\Ops')) {
            return \ctf0\PackageChangeLog\Ops::postAutoloadDump($event);
        }
    }
}
