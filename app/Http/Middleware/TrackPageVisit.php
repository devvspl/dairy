<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageVisit;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;

class TrackPageVisit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes, API routes, and AJAX requests
        if (
            $request->is('admin/*') ||
            $request->is('api/*') ||
            $request->ajax() ||
            $request->wantsJson()
        ) {
            return $next($request);
        }

        // Skip tracking for asset requests
        if ($request->is('css/*') || $request->is('js/*') || $request->is('images/*')) {
            return $next($request);
        }

        try {
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            // Determine device type
            $deviceType = 'Desktop';
            if ($agent->isMobile()) {
                $deviceType = 'Mobile';
            } elseif ($agent->isTablet()) {
                $deviceType = 'Tablet';
            } elseif ($agent->isRobot()) {
                $deviceType = 'Bot';
            }

            PageVisit::create([
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'device_type' => $deviceType,
                'browser' => $agent->browser(),
                'platform' => $agent->platform(),
                'user_id' => auth()->id(),
                'visited_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the application if tracking fails
            \Log::error('Page visit tracking failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
