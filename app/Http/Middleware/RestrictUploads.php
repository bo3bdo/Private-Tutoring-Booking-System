<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictUploads
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request has file uploads
        if ($request->hasFile('file') || $request->hasFile('attachments') || $request->hasFile('thumbnail')) {
            notify()->error()
                ->title(__('common.Demo Mode'))
                ->message(__('common.You cannot upload files. This is a demo version.'))
                ->send();

            return back();
        }

        return $next($request);
    }
}
