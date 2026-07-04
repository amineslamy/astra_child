# Sahab Front-end Research Workstation (قالب اختصاصی سامانه سحاب)

An optimized, secure, and fully customized WordPress child theme designed for offline academic, OSINT, and institutional research tracking. This workstation allows researchers to search, filter, and insert data directly from a high-performance frontend interface without interacting with the core WordPress dashboard.

یک پوسته فرزند (Child Theme) بهینه‌سازی شده، امن و کاملاً سفارشی برای وردپرس که جهت مستندسازی، فیش‌برداری پژوهشی و رصد اطلاعاتی در محیط‌های آفلاین توسعه یافته است. این سیستم به پژوهشگر اجازه می‌دهد بدون نیاز به ورود به پیشخوان پیش‌فرض وردپرس، اسناد را از ظاهر سایت مدیریت، جستجو و پایدارسازی کند.

---

## 🇮🇷 راهنمای فارسی (Persian Guide)

### چرا این پروژه توسعه یافت؟
محیط پیش‌فرض مدیریت وردپرس (`/wp-admin`) برای سناریوهای فیش‌برداری سریع و کپی-پیست‌های متوالی از شبکه‌های اجتماعی بسیار سنگین، شلوغ و غیرمتمرکز است. سحاب با حذف کامل بک‌اند برای کاربر نهایی، یک ایستگاه کاری (Workstation) تک‌صفحه‌ای هوشمند فراهم می‌کند که تمرکز آن روی **سرعت پردازش داده**، **امنیت محلی** و **سادگی مطلق** است.

### ویژگی‌های کلیدی سیستم
* **تفکیک کامل فرانت‌اند از بک‌اند:** کاربر هرگز منوهای پیچیده وردپرس را نمی‌بیند.
* **فرم ورود اطلاعات یکپارچه (Inline Form):** ثبت فیش، دسته‌بندی موضوعی و برچسب‌گذاری مستقیم از صفحه اول.
* **مدیریت رسانه دوگانه:** قابلیت آپلود همزمان سند جدید یا انتخاب از آرشیو تصاویر و مستندات قبلی دیتابیس با پاپ‌آپ محلی.
* **هوشمندسازی عنوان:** پردازش خودکار خط اول متون کپی‌شده برای پیشنهاد عنوان فیش جهت افزایش سرعت اپراتور.
* **امنیت درون‌برنامه‌ای:** فرم لاگین اختصاصی بدون خروج از لایه فرانت‌اند جهت حفظ امنیت دیتابیس محلی.

### نحوه راه‌اندازی و استفاده (لوکال / لاراگون)
۱. پوشه `astra-sahab` را در مسیر پوسته‌های وردپرس خود قرار دهید:
   `wp-content/themes/astra-sahab`
۲. مطمئن شوید پوسته مادر یعنی `Astra` نیز نصب شده است.
۳. از پیشخوان وردپرس، پوسته **«قالب اختصاصی سامانه سحاب»** را فعال کنید.
۴. یک برگه جدید بسازید و قالب آن را روی **`Home Custom Page v5`** تنظیم کنید.
۵. در تنظیمات خواندن وردپرس، این برگه را به عنوان صفحه اصلی سایت ست کنید.

---

## 🇬🇧 English Guide

### Why This Project Was Developed?
The default WordPress dashboard (`/wp-admin`) is heavily bloated and counter-intuitive for rapid data entry, OSINT intelligence gathering, and continuous copy-pasting from social networks. Sahab bypasses the entire backend for the end-user, providing a clean, single-page UI optimized for **maximum data input speed**, **local security**, and **prose-focused stability**.

### Key Features
* **Complete Backend Decoupling:** Users interact only with a clean dashboard tailored for textual analytics.
* **Inline Post Submission:** Seamlessly write/paste research inputs, assign categories, and append analytical tags without loading dashboard nodes.
* **Dual Media Management:** Native integration with the WP Media library allowing both direct file dropping and picking from historical database archives via an isolated modal.
* **Smart Title Extractor:** Automatic listening to the editor canvas to capture the first line of pasted content and propose it as the post title, drastically reducing click overhead.
* **Inline Authentication Buffer:** A local, private authentication portal embedded into the frontend card layout to guard database integrity.

### Installation & Deployment (Local / Laragon)
1. Clone or copy the `astra-sahab` directory into your local WordPress theme infrastructure:
   `wp-content/themes/astra-sahab`
2. Ensure the parent theme `Astra` is installed in the adjacent folder.
3. Activate the **Sahab Custom Research Theme** via the WordPress Appearance panel.
4. Create a new page and select **`Home Custom Page v5`** as its Page Template.
5. Navigate to WordPress Settings > Reading, and bind this page as the Static Homepage.

---

## 🛠️ Stack & Architecture (معماری فنی)
* **Core Engine:** WordPress Custom Child Theme Framework
* **Parent Theme Dependencies:** Astra (Lightweight Object Model)
* **Frontend Controller:** Pure Vanilla JavaScript (DOM Mutation Listeners & Inline WP Media Handlers)
* **Styling Paradigm:** Responsive Utility CSS with Glassmorphism/Flat design accents
* **Recommended Search Engine Plugin:** `Relevanssi` (For deep keyword tokenization across the frontend search query container)

## 👤 Developer
* **Name:** Amin Eslamy
* **GitHub:** [@mineslamy](https://github.com/mineslamy)
* **Repository:** [astra_child](https://github.com/amineslamy/astra_child)