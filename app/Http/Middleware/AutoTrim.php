<?php 

namespace MMC\Http\Middleware;

use Closure;

class AutoTrim {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$input = $request->all();

        if ($input) {
            array_walk_recursive($input, function (&$item) {
                $item = trim($item);
                $item = ($item == "") ? null : $item;
            });

            $request->merge($input);
        }

        return $next($request);
	}
}