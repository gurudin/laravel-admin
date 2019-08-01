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
        $source = $request->source;

        if (Auth::check()) {
            return $source
                ? redirect()->route($source)
                : redirect()->route(config('login_successful_redirect'));
        }

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
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email' => __("admin::messages.login.please-fill-in-the-email-correctly"),
        ]);
        
        if (Auth::attempt(['email' => $request->post('email'), 'password' => $request->post('password')])) {
            if (is_null($request->source)) {
                return redirect()->route(config('admin.login_successful_redirect'));
            } else {
                return redirect($request->source);
            }
        } else {
            $uri = 'admin/login';
            if (!empty($request->source)) {
                $uri .= '?source=' . urlencode($request->source);
            }

            return redirect($uri)
                ->withErrors(['password' => __("admin::messages.login.wrong-user-name-or-password"),])
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