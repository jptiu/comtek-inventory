## ✨ Inventory Management System

Inventory Management System with Laravel 10 and MySql.

## 💀 Design Database
![Diagram Class](https://github.com/jptiu/comtek-inventory/assets/71541409/0c7d4163-96f5-4724-8741-4615e52ecf98)

## 😎 Features
- POS
- Orders
  - Pending Orders
  - Complete Orders
  - Pending Due
- Purchases
  - All Purchases
  - Approval Purchases
  - Purchase Report
- Products
- Customers
- Suppliers

## 🚀 How to Use

1. Clone Repository

```bash
git clone https://github.com/jptiu/comtek-inventory
```

2. Go into the repository 

```bash
cd comtek
```

3. Install Packages 

```bash
composer install
```


4. Copy `.env` file 

```bash

cp .env.example .env

```

5. Generate app key 

```bash
php artisan key:generate
```

6. Setting up your database credentials in your `.env` file.
7. Seed Database: 

```bash

php artisan migrate:fresh --seed

```
8. Create Storage Link

```bash
php artisan storage:link
```

9. Install NPM dependencies 

```bash

npm install && npm run dev

```
10. Run 

```bash

php artisan serve

```
11. Try login with email: 

```bash

admin@gmail.com

```
and password: 

```bash

password

```

## 🚀 Config

1. **Config Chart**

    Open file `./config/cart.php`. You can set a tax, format number, etc.
    > For More details, visit this link [hardevine/shoppingcart](https://packagist.org/packages/hardevine/shoppingcart).
