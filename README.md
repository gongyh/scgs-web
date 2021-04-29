## Introduction

This website application is based on [Laravel](https://laravel.com) framework.

## Usage

0. Prerequisites
```bash
$ cp .env.example .env
vi .env # configration
```
Setup your database, and manually modify the configration file `.env`.

1. Autoloader Optimization
```bash
$ rm -f composer.lock package-lock.json # optional, slow
$ composer install --optimize-autoloader --no-dev
```

2. Compiling Assets
```bash
$ npm install && npm run production
```

3. Test using docker compose
```bash
$ docker-compose up # or docker-compose up -d
```

## License

Both the Laravel framework and website application are open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

[Yanhai Gong](mailto:gongyh@qibebt.ac.cn)
[Shiqi Zhou](mailto:zhousq@qibebt.ac.cn)
