# laravel-admin

### 添加用户认证
```php artisan make:auth```

### 配置
修改```config/auth.php``` 文件 ```providers->users->model```配置

```'model' => Gurudin\LaravelAdmin\Models\User::class,```
