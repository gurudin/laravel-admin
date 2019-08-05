<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * __construct
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Ajax response
     *
     * @param bool   $status 
     * @param string $msg 
     * @param array  $data 
     * @param int    $header_code 
     *
     * @return Json
     */
    protected function response(bool $status, string $msg = 'success', array $data = [], int $header_code = 200)
    {
        return response()->json(
            [
                'status' => $status,
                'msg'    => $msg,
                'data'   => $data
            ], $header_code
        );
    }
}
