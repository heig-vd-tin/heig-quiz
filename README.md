# HEIG Quiz

Portail de Quiz pour la HEIG-VD.

![quiz](resources/img/quiz.svg)

## Development

### Get the code

```
git clone git@github.com:yves-chevallier/chevallier.io.git
cd chevallier.io
cp .env.example .env
composer install
npm install
```

### Initialize

```
docker-compose up

php artisan key:generate
php artisan migrate:fresh --seed
```

### Configure Environment

In your `.env`  make sure that `APP_DEBUG` is set to `true`. To login with any user use the route `/debug/login/{id}`.

Then you should configure your MySQL `DB_CONNECTION=mysql` and your Pusher settings. You can get them by creating an account on pusher.com:

```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
```

### Configure Cron

The cron allows task scheduling such as emitting an event when an activity is about to end. Artisan command will be called every minute.

```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Serve

You need more than one terminal...

First to start the Laravel development server:

```bash
php artisan serve
```

Then start the pusher replacement websocket server:

```bash
php artisan websockets:serve
```

Eventually the Vue development server:

```bash
npm run watch
```

### Debug

To debug websocket messages, you can go to :

http://127.0.0.1:8000/laravel-websockets

### Reset all the migrations

```bash
php artisan migrate:fresh --seed
```

## Production

### Deployment

Still have some issues on `npm` on the server side...

```bash
npm run production
tar cvzf dist.tar.gz public/css/app.css public/css/app.css.map public/js/app.js public/js/app.js.LICENSE.txt public/js/app.js.map
scp dist.tar.gz ...
```

### PHP

```bash
sudo apt install php-yaml php-zip
```

### Shibboleth

https://www.switch.ch/aai/guides/sp/installation/index.html?os=ubuntu20
