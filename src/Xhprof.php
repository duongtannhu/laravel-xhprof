<?php
namespace NhuDuong\Xhprof;

use Closure;
use Illuminate\Http\Request;

/**
 * Class XhprofMiddleware.
 *
 * XHProf is a useful profiling tool built by Facebook. This middleware allows
 * developers to profile specific requests by appending `xhprof=true` to any
 * query.
 *
 * Results will be stored on `/tmp` and can be visualized using the XHProf UI.
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package App\Http\Middleware
 */
class Xhprof
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // We will only profile requests if the proper flag is set on the query
        // of the request. You may further customize this to be disabled on 
        // production releases of your application.
        if ($request->query->get('xhprof') !== 'true') {
            return $next($request);
        }

        tideways_enable(TIDEWAYS_FLAGS_NO_SPANS);

        $result = $next($request);

        $data = tideways_disable();
        $uniqueId = uniqid();
        $link = "/tmp/" . $uniqueId . ".yourapp.xhprof";
        file_put_contents(
            $link,
            serialize($data)
        );
        $viewLink = "<a href='http://egli.dev/xhprof/xhprof_html/index.php?run={$uniqueId}&source=yourapp' target='_blank'>XhProfile Detail</a>";
        \Debugbar::info($viewLink);
        return $result;
    }
}
