# SafeCare

Aplikasi crowdfunding panti asuhan...

## Langkah Instalasi

```bash
git clone https://github.com/APermata7/safecare.git
cd safecare

cp .env.example .env

composer install
npm install
npm run build

php artisan migrate:fresh --seed
php artisan storage:link
php artisan key:generate
php artisan serve
```