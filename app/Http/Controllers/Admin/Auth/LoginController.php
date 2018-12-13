<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    use AuthenticatesUsers;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/system/index/index';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    
    public function showLoginForm()
    {
        return view('admin/auth/login');//对应视图
    }
    
    public function username()
    {
        return 'username';//默认是email
    }
    
    protected function guard()
    {
        return \Auth::guard('admin');//默认是web
    }
    
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        
        return $this->loggedOut($request) ?:
            ($request->ajax() ? response()->json([
                                                     'status_code' => 200,
                                                     'message' => '退出成功',
                                                     //ajax请求返回数据
                                                     'data' => ['url' => '/admin/login'],
                                                 ]) :
                redirect('/admin/login'));//退出后跳回登录页
    }
    
    
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 'password' => 'required',
        ], [
                            $this->username() . '.required' => '账号必须填写',
                            'password.required' => '密码必须填写',
                        ]);//加验证中文提示
    }
    
    
    
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        return $this->authenticated($request, $this->guard()->user()) ?:
            ($request->ajax() ? response()->json([
                                                     'status_code' => 200,
                                                     'message' => '登录成功',
                                                     'data' => ['url' => $this->redirectPath()]
                                                 ]) :
                redirect()->intended($this->redirectPath()));
    }
    

}
