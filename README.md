# SocialiteHomTest
第三方登入串接

---
Socialite 是 Laravel 提供的第三方登入的擴充, 對 OAuth providers 進行身份驗證

## 安裝
composer require laravel/socialite

## 配置
使用 socialite 前, 需要對 OAuth 服務的供應商設置憑證.
設置檔案為: `config/service.php`
設置的內容取決於使用的供應商.

```
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => 'https://接收認證後訊息的網址',
    ],
```

GitHub 提供的 OAuth

![](https://i.imgur.com/e9mQHmA.png)


## 路由

設定完憑證後. 需要設定二組路由
1. 跳轉至 OAuth 服務的頁面
2. 接收回應訊息的頁面

```
class LoginController extends Controller
{
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
        $user = Socialite::driver('github')->user();
        // $user->id
    }
}
```

web.php 路由設定
```
Route::get('/login/github', 'Auth\LoginController@redirectToProvider');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
```

## 參考
[LearnKu Socialite 社会化登录](https://learnku.com/docs/laravel/6.x/socialite/5192)

[OSChina Laravel 第三方登陆之 Socialite Providers](https://my.oschina.net/dingdayu/blog/3001705)

[願い星 Laravel Socialite 簡單的代價](https://medium.com/@Negaihoshi/laravel-socialite-簡單的代價-ff4ab3f406c1)