<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Gurudin\LaravelAdmin\Models\User;

class RegisterController extends BaseController
{
    use ValidatesRequests;

    /**
     * Register view.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerFrom(Request $request)
    {
        return view('admin::auth.register');
    }

    /**
     * (post) Register
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|min:2',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'c_password' => 'required|same:password',
        ], [
            'name.*' => __("admin::messages.register.minimum-4-digits-for-user-name"),
            'email.unique' => __("admin::messages.register.email-to-repeat"),
            'email.*' => __("admin::messages.register.please-fill-in-the-email-correctly"),
            'password.*' => __("admin::messages.register.minimum-password-6-digits"),
            'c_password.*' => __("admin::messages.register.the-passwords-don't-match-twice")
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        User::create($input);

        return redirect()->route('admin.login');
    }
}
