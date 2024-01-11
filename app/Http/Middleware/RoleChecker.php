<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class RoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $super_admin, $user =null, $customer_support = null, $store_manager = null, $product_quantites_sold_view = null)
    {
        $roles[]=Auth::guard('admin')->user()->role;
        if (in_array($super_admin, $roles)) {
            return $next($request);
        } else if (in_array($user, $roles)) {
            return $next($request);
        } else if (in_array($customer_support, $roles)) {
            return $next($request);
        } else if (in_array($store_manager, $roles)) {
            return $next($request);
        }else if (in_array($product_quantites_sold_view, $roles)) {
            return $next($request);
        }
        return redirect()->back()->withErrors(['error'=>'You Cant Do this Action Permission Denied']);
    }
}
