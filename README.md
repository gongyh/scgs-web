## Introduction

This website application is based on [Laravel](https://laravel.com) framework.

## Usage

0. Prerequisites
```bash
$ cp .env.example .env
$ vi .env # optional
```

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
$ vi docker-compose.yml # optional
$ mkdir -p pv pv/seq pv/db pv/redis pv/mysql
$ docker-compose up # or docker-compose up -d
```

4. Test using skaffold for k8s

4.1 Create a namespace: skaffold

4.2 Prepare all PVs for the PVC

4.3 test in dev mode

```bash
$ skaffold dev
```

## License

Both the Laravel framework and website application are open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

[Yanhai Gong](mailto:gongyh@qibebt.ac.cn)

[Shiqi Zhou](mailto:zhousq@qibebt.ac.cn)
