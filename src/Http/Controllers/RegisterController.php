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
            'name.*' => '用户名最少4位.',
            'email.unique' => '邮箱重复.',
            'email.*' => '请填写正确Email.',
            'password.*' => '密码最少6位.',
            'c_password.*' => '两次密码不一致.'
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        User::create($input);

        return redirect()->route('admin.login');
    }
}
