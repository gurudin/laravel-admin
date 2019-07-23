<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    use ValidatesRequests;

    /**
     * Login view.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginFrom(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('admin.welcome');
        }

        $source = $request->source;

        return view('admin::auth.login', compact('source'));
    }

    /**
     * (post) Login
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return mixed
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email' => '请正确填写Email.',
        ]);
        
        if (Auth::attempt(['email' => $request->post('email'), 'password' => $request->post('password')])) {
            return redirect()->route('admin.welcome');
        } else {
            $uri = 'admin/login';
            if (!empty($request->source)) {
                $uri .= '?source=' . urlencode($request->source);
            }

            return redirect($uri)
                ->withErrors(['password' => '用户名或者密码错误.'])
                ->withInput();
        }
    }

    /**
     * Login out
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return mixed
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('admin.login');
    }
}