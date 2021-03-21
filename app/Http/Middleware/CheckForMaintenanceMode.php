<?php

/*
 * CheckForMaintenanceMode.php
 * オリジナル：\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode
 */
namespace App\Http\Middleware;

use App\Models\MaintenanceAccess;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;
    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // アクセス許可したいIPアドレス
        $addresses = explode(',', env('MAINTENANCE_IP'));

        $maintenanceAccesses = MaintenanceAccess::query()->get()->pluck('ip')->toArray();

        $addresses = array_merge($maintenanceAccesses, $addresses);

        if ($this->app->isDownForMaintenance()) {
            if (!is_array($addresses) || !in_array($request->getClientIp(), $addresses)) {
                throw new HttpException(503);
            }
        }

        return $next($request);
    }
}
