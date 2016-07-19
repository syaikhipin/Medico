<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }
          else {

                return redirect()->guest('user/login');
            }
        }

        if(\Auth::check() && \Auth::user()->group_id==3 && \Auth::user()->payment_method=='0'){
            $trial= date('Y-m-d H:i:s', strtotime('+5 day',strtotime(\Auth::user()->created_at)));

            if(strtotime($trial) <= strtotime(date('Y-m-d H:i:s'))) {
                return redirect('user/subscribe')->with('message', \SiteHelpers::alert('error', 'Your trial or subscription expired.'));
            }
        }
        return $next($request);
    }
}
