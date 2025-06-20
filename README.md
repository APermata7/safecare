# SafeCare

Aplikasi crowdfunding panti asuhan...

## Langkah Instalasi

```bash
git clone https://github.com/APermata7/safecare.git
cd safecare

cp .env.example .env

php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```