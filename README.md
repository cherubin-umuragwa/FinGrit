# FinGrit 💰

![License](https://img.shields.io/github/license/cherubin-umuragwa/FinGrit)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.x-orange)
![Contributions](https://img.shields.io/badge/contributions-welcome-brightgreen)

---

## 📖 Project Overview

**FinGrit** is a personal finance management platform designed to help users **track expenses, set savings goals, and analyze financial data**.  
It is built with **PHP, MySQL, JavaScript, and CSS**, providing a lightweight yet powerful solution for individuals and small teams.  

The system allows users to:  

- Register and authenticate securely  
- Track transactions (income & expenses)  
- Define and monitor financial goals  
- View analytics and visual insights (ApexCharts)  
- Manage user profiles and account settings  

---

## 🧩 Libraries & Frontend Dependencies

FinGrit includes several frontend libraries loaded via CDN. These are already referenced in the HTML/PHP templates (so you don't need to install them locally unless you prefer to). Detected libraries and CDNs used in the project:

- **Google Fonts**: Inter (via Google Fonts) - <https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap>
- **Bootstrap**: Bootstrap 5.3.3 (CSS & Bundle JS) - <https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/>
- **Bootstrap Icons**: Bootstrap Icons 1.11.3 - <https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css>
- **Swiper**: Swiper 11 - <https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js>
- **AOS**: AOS (Animate On Scroll) 2.3.4 - <https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/>
- **GSAP**: GSAP 3.13.0 - <https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/>
- **imagesLoaded**: imagesLoaded 5.0.0 - <https://cdn.jsdelivr.net/npm/imagesloaded@5.0.0/imagesloaded.pkgd.min.js>
- **Isotope**: Isotope 3.0.6 - <https://cdn.jsdelivr.net/npm/isotope-layout@3.0.6/dist/isotope.pkgd.min.js>
- **GLightbox**: GLightbox 3.2.0 - <https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/>
- **PureCounter.js**: PureCounter.js - <https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js>
- **ApexCharts**: ApexCharts - <https://cdn.jsdelivr.net/npm/apexcharts>

---

## ⚙️ PHP Requirements & Extensions

The application is built with plain PHP files. Recommended runtime environment:

- **PHP**: 8.0+ (the code is compatible with PHP 7.4+, but PHP 8+ is recommended)
- **MySQL**: 8.x (or compatible MariaDB)
- **Required PHP extensions**:
  - `pdo` / `pdo_mysql` (used via PDO in `php/db.php`)
  - `json` (used for API responses)
  - `session` (built-in, used for auth sessions)
- Optional but recommended extensions:
  - `mbstring`
  - `openssl` (if you add encryption features or use external services)

The project uses a small custom `.env` loader (`env-loader.php`) instead of `vlucas/phpdotenv` or Composer-based libraries.

---

## 🌍 Live Demo & Video

- 🔗 **Live Demo**: [Live Demo](https://fingrit.free.nf/)  
- 🎥 **Video Walkthrough**: [YouTube Demo](https://youtu.be/SfFS-omNevo?si=0Au4MH4u3aGnw5rI)

---

## ✨ Features

- 🔐 **Authentication**: Register, login, password reset, logout  
- 💸 **Transactions**: Record, view, and categorize income & expenses  
- 🎯 **Goals**: Define savings goals and track progress  
- 📊 **Analytics Dashboard**: Visual insights (ApexCharts) & summaries  
- 👤 **User Profile Management**: Update details, delete account  
- 📂 **Export Data**: Download financial records  

---

## 🛠️ Tech Stack

- **Backend**: PHP (plain PHP files, no Composer)  
- **Database**: MySQL (PDO)  
- **Frontend**: HTML5, CSS3, JavaScript (vanilla)  
- **Styling**: Bootstrap 5 + custom CSS  
- **Environment**: `.env` configuration file (loaded with `env-loader.php`)  

---

## 📸 Screenshots

_To be added._  

---

## ⚡ Installation & Setup

Follow these steps to set up **FinGrit** locally:  

### 1. Clone the repository

```bash
git clone https://github.com/cherubin-umuragwa/FinGrit.git
cd FinGrit
```

### 2. Install dependencies

There are no Composer or npm dependencies detected. Frontend libraries are loaded via CDN.  
Make sure you have **PHP** and **MySQL** installed.

### 3. Configure environment

Copy the `.env.example` file to `.env` and update values accordingly:  

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=yourpassword
DB_NAME=fin_grit
```

### 4. Import database

Create a new MySQL database and import the provided schema:  

```bash
mysql -u root -p fin_grit < db.sql
```

### 5. Run the project locally

Start your local PHP server (or use XAMPP/MAMP/WAMP):  

```bash
php -S localhost:8000
```

Now visit: [http://localhost:8000](http://localhost:8000) 🎉  

---

## 📂 File Structure

```
FinGrit/
│── api/
│   ├── auth.php
│   ├── goals.php
│   └── transactions.php
│
│── assets/
│   └── images/
│
│── css/
│   ├── main.css
│   ├── root_variables.css
│   └── specific_styles.css
│
│── js/
│   ├── analytics.js
│   ├── hamburger.js
│   ├── main.js
│   └── transactions.js
│
│── partials/
│   └── sidebar.php
│
│── php/
│   ├── auth.php
│   ├── db.php
│   ├── functions.php
│   ├── goals.php
│   ├── helpers.php
│   ├── middleware.php
│   └── transactions.php
│
│── env-loader.php
│── export-data.php
│── forgot-password.php
│── goals.php
│── index.php
│── LICENSE
│── login.php
│── logout.php
│── profile.php
│── register.php
│── reset-password.php
│── transactions.php
│── analytics.php
│── dashboard.php
│── db.sql
│── delete-account.php
│── .env.example
```

---

## 🚀 Usage

1. **Register** a new account or login with existing credentials  
2. **Add Transactions**: Income or Expense  
3. **Set Goals**: Define savings targets and track progress  
4. **View Dashboard**: Analytics & financial summaries (ApexCharts)  
5. **Export Data**: Download for offline use  

---

## 🤝 Contributing

Contributions are always welcome!  

1. Fork the repository  
2. Create a feature branch: `git checkout -b feature-name`  
3. Commit changes: `git commit -m 'Add feature'`  
4. Push to branch: `git push origin feature-name`  
5. Open a Pull Request  

---

## 📜 Code of Conduct

This project follows the [Contributor Covenant Code of Conduct](https://www.contributor-covenant.org/).  
By participating, you agree to uphold this standard.  

---

## 📄 License

This project is licensed under the terms of the **MIT License**.  
See the [LICENSE](LICENSE) file for details.  

---

## 📬 Contact

- 💬 **[Discord](https://discord.com/users/1267949776071299092)**  
- 🐙 **[GitHub](https://github.com/cherubin-umuragwa)**  
- 📧 **[Email](mailto:"cherubinamani09@gmail.com")**

---

_If you find this project useful, don’t forget to ⭐ star the repository!_
