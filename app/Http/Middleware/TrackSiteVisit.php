<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin*') || $request->is('login') || $request->is('livewire/*')) {
            return $next($request);
        }

        if ($request->method() === 'GET' && ! $request->ajax() && Schema::hasTable('site_settings')) {
            if (! $request->session()->has('visit_counted')) {
                SiteSetting::incrementVisitors();
                $request->session()->put('visit_counted', true);
            }
        }

        return $next($request);
    }
}
