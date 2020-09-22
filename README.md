# Chevallier.io

Base site for Teaching at HEIG-VD. This website features:

- Links to teaching materials
- Login for students and teachers
- Quiz system
- List of exercises
- Personnalized links for students

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
php artisan migrate:freah --seed
```

### Configure

In your `.env`  make sure that `APP_DEBUG` is set to `true` and give a default login with:

```
DEVEL_AUTOLOGIN="yves.chevallier@heig-vd.ch"
```

Then you should configure your MySQL `DB_CONNECTION=mysql` and your Pusher settings. You can get them by creating an account on pusher.com:

```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
```

### Serve

You need more than one terminal...

```
php artisan serve
```

```
npm run watch
```

### Reset all the migrations

```
php artisan migrate:fresh --seed
```
