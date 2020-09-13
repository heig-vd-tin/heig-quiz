# Chevallier.io

Base site for Teaching at HEIG-VD. This website features:

- Links to teaching materials
- Login for students and teachers
- Quiz system
- List of exercises
- Personnalized links for students

## Development

Get the code:

```
git clone git@github.com:yves-chevallier/chevallier.io.git
cd chevallier.io
cp .env.example .env
composer install
npm install
```

Initialize:

```
docker-compose up

./artisan key:generate
./artisan migrate
./artisan db:seed
```

Serve:

```
./artisan serve
```