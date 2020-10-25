<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helpers as Helpers;

class ApiAuthorization
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if($request->header('token')) {

			if($request->header('token') == env('JWT_SECRET')) {
				return $next($request);
			} else {
				return response()->json([
					'success' => false,
					'error_code' => '401',
					'message' => 'Unauthorized.',
				],401);
			}

		} else {

			return response()->json([
				'success' => false,
				'error_code' => '401',
				'message' => 'Unauthorized.',
			],401);
		}

		return $next($request);
	}
}
