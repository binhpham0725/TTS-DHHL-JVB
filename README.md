# 🏘️ TTS_JVB - MVC Framework Demo

Minimalistische Custom MVC framework demonstration với routing, controllers, models, và views.

---

## 📁 Project Structure

```
mvc/
├── 📄 index.php              # Web entry point
├── 📄 bootstrap.php          # App initialization
├── 📄 .htaccess              # URL rewriting
│
├── 📂 app/
│   ├── 📂 controllers/
│   │   └── Home.php
│   ├── 📂 models/
│   │   └── HomeModel.php
│   ├── 📂 views/
│   │   ├── 📂 blocks/
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── 📂 layouts/
│   │   │   └── client_layout.php
│   │   └── 📂 products/
│   │       ├── list.php
│   │       └── detail.php
│   └── 📂 errors/
│       └── 404.php
│
├── 📂 configs/
│   ├── app.php               # App configuration
│   ├── database.php          # Database config
│   └── routes.php            # Route definitions
│
├── 📂 core/
│   ├── Controller.php        # Base controller
│   └── Route.php             # Router class
│
└── 📂 public/
    └── assets/
        ├── 📂 admin/
        ├── 📂 client/
        │   ├── css/
        │   └── js/
```

---

## 🚀 Quick Start

### 1. Setup Database

```bash
# Create database (if needed)
mysql -u root -p
CREATE DATABASE tts_jvb;
```

### 2. Configure

Edit `configs/database.php`:

```php
'host' => 'localhost',
'database' => 'tts_jvb',
'user' => 'root',
'password' => ''
```

### 3. Access

```
http://localhost/TTS_JVB/mvc/
```

---

## 🔗 Routes

```
GET    /                       Home page
GET    /products               Product list
GET    /products/{id}          Product detail
```

---

## 🏗️ Architecture

### Router (core/Route.php)

- URL matching
- Parameter extraction
- Controller dispatch

### Controller (core/Controller.php)

- Base class for all controllers
- View rendering
- Data passing

### Model (app/models/)

- Database interaction
- Business logic

### View (app/views/)

- UI templates
- Layout system

---

## 📝 Creating New Route

1. **Add route** in `configs/routes.php`:

```php
$routes = [
    '/new-page' => 'Home@newAction',
];
```

2. **Add method** in controller:

```php
public function newAction() {
    view('path/to/view', $data);
}
```

3. **Create view** file:

```php
<!-- app/views/path/to/view.php -->
<h1><?= $title ?></h1>
```

---

## 🎯 Features

- ✅ Simple routing
- ✅ MVC architecture
- ✅ Template view system
- ✅ Layout support
- ✅ Error handling (404)
- ✅ Modular structure

---

## ⚠️ Setup Tips

- Ensure `.htaccess` is enabled in Apache
- Check `AllowOverride All` in Apache config
- Verify PHP is 7.4+
- Restart Apache after changes

---

**Version:** 1.0  
**Type:** Framework Demo  
**Status:** ✅ Functional
