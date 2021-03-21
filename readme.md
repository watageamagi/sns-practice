
# Laravel Starter

## set up
```
composer install

php artisan migrate
```

暗号キーを作成
```
php artisan passport:install
```

生成した``Client ID: 2``の``client secret``を``.env``に追加する
```env
CLIENT_SECRET=XXXXXXXX
```


```
npm install

npm run dev
```


## Route Guard

```javascript
...authGuard([
    {
        path: '/',
        name: 'top',
        component: page('app/top.vue'),
    },
]),
...guestGuard([
    {
        path: '/login',
        name: 'login',
        component: page('login.vue'),
    },
])
```

## In-Component Guards
```vue
<script>
    export default {
        middleware: ['auth'],
    }
</script>
```
