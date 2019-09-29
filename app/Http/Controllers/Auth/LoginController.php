<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 將用戶導向 GitHub 的授權頁面
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * 從 Github 取得用戶資訊
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $this->createOrFindUser($user = Socialite::driver('github')->user());
            \Auth::login($user, true);
            return redirect('/');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect('/login');
        }
    }

    public function createOrFindUser($user)
    {
        if($loginUser = User::where('third_party_id', $user->id)->first()) {
            return $loginUser;
        }

        return User::create([
            'name' => $user->name,
            'third_party_id' => $user->third_party_id,
            'nick_name' => $user->nickname,
            'email' => $user->email,
            'avatar' => $user->avatar
        ]);
    }
}
