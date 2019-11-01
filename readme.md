<p align="center"><img src="https://raw.githubusercontent.com/hackedon/blog_images/master/hackedon-ctf-manager.png" width="600"></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About HackedON CTF Manager

The HackedON CTF Manager was developed for the express need of SLIIT annual Hack Me If You Can Capture the Flag event. It was hosted on HackedON servers and the participatings teams were allowed to login to submit their hard earned flags and track their progress. To that end, the CTF Manager app has the following functionality:

- Register Boxes and add Flags
- Register teams
- 2 User modes (ADMIN and USER)
- View complete summary of the CTF progress visually

The application was developed with security and reliability as a priority. The following features complements that goal.

- Flags are securely hashed using bcrypt before persisting to the database.
- Flag submission endpoint is rate-limited.
- Report upload endpoint is rate-limited and accepts only .doc or .docx filetypes.
- Redis is used to cache individual user profiles and CTF summary details greatly reducing load on the origin server. The caches are invalidated in a timely manner to ensure that content stays up-to-date as and when flags are submitted to the system.



## Setup Process

The following are requirements for HackedON CTF Manager.

- All Laravel 5.8 requirements (PHP 7.2)
- Apache / Nginx (Nginx preferred)
- Redis / Memcached Server
- predis/predis composer package (if Redis is used)
- MySQL or Laravel compliant database server

The setup process is as follows.

- Clone the repository to your server’s document root. (eg: /var/www/html/ctf-manager)
- Provide proper file permissions for the www-data user
- Run `composer install` inside the ctf-manager directory.
- Make a copy of the environment file `cp .env.example .env`
- Generate application encryption key `php artisan key:generate`
- Update environment configuration.
  - APP_ENV, APP_DEBUG, APP_URL
  - Database details
  - CACHE_DRIVER=redis
  - REDIS_HOST, REDIS_PASSWORD
- Create new entry at `/etc/nginx/sites-available` to reflect the ctf-manager app and then `sudo service nginx restart`

## License

The HackedON CTF Manager is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT). The copyright notice below and this permission notice shall be included in all copies or substantial portions of the Software.

<p align="center" style=“text-align:center”>Copyright (c) 2019 HackedON </p>