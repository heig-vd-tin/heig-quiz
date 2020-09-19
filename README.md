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
php artisan migrate:freah --seed
```
