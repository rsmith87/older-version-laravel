<?php

namespace App\Http\Middleware;

use Closure;

class CreateDefaultFolder
{
    use \App\Http\Controllers\Traits\LfmHelpers;

    public function handle($request, Closure $next)
    {
        $this->checkDefaultFolderExists('user');
        $this->checkDefaultFolderExists('share');
        $this->checkDefaultFolderExists('firm');

        return $next($request);
    }

    private function checkDefaultFolderExists($type)
    {
        if ($type === 'user' && ! $this->allowMultiUser()) {
            return;
        }

        if ($type === 'share' && ! $this->allowShareFolder()) {
            return;
        }


        $path = $this->getRootFolderPath($type);

        $this->createFolderByPath($path);
    }
}
