=== Headless REST API Security ===
Contributors: rakib
Tags: rest-api, headless, security, api-key, nextjs, react, backend, protection
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Secure your WordPress REST APIs for headless and decoupled frontends using API keys, domain binding, and route-level control.

== Description ==

**Headless REST API Security** is a lightweight yet powerful security plugin designed specifically for **headless WordPress architectures**.

When WordPress is used as a backend for frameworks like **Next.js, React, Nuxt, Vue, or mobile apps**, the REST API becomes publicly accessible. This plugin gives you **full control over who can access which API routes**, without modifying core WordPress files.

---

### 🔐 What This Plugin Does

- Protect WordPress REST APIs using an **API key**
- Bind API access to a **specific domain**
- Automatically **detect all REST API routes**
- Allow administrators to **enable/disable protection per route**
- Enable or disable security globally with one click
- Works with **GET, POST, PUT, DELETE** requests
- Compatible with WordPress core APIs and popular plugins

---

### ❓ Why You Need This Plugin

By default, WordPress REST APIs are **public**.

This can lead to:
- Unauthorized data scraping
- Exposure of unpublished or sensitive data
- Abuse of custom APIs
- Security risks in headless projects

This plugin solves those problems by adding a **controlled authentication layer** that is:
- Simple
- Fast
- Developer-friendly
- WordPress-native

---

### ⚙️ How It Works

1. The plugin automatically reads all registered REST API routes.
2. You select which routes should be protected from the admin dashboard.
3. The frontend sends an API key via request headers.
4. The plugin validates:
   - API key
   - Request origin domain
   - Requested route
5. Unauthorized requests are blocked with a **403 error**.

All checks happen **before WordPress processes the request**, ensuring minimal performance impact.

---

### 🧪 Real-World Use Cases (Case Studies)

#### Case 1: Headless Blog with Next.js
A news website uses WordPress as backend and Next.js as frontend.
Only `/wp/v2/posts` and `/wp/v2/categories` APIs are exposed.
All other APIs remain protected.

✅ Prevents data scraping  
✅ Secures backend APIs  
✅ Clean separation of frontend & backend  

---

#### Case 2: Mobile App Backend
A mobile application consumes WordPress REST APIs.
API access is restricted to the mobile app’s domain and API key.

✅ Prevents unauthorized third-party usage  
✅ Protects write APIs  
✅ Safer mobile data access  

---

#### Case 3: Internal Admin Tools
Custom dashboards built using React access WordPress APIs.
Only internal APIs are allowed, others are blocked.

✅ Internal data safety  
✅ Fine-grained API access  
✅ No custom authentication code needed  

---

### 📌 Scenarios Covered

- Headless WordPress websites
- Decoupled frontends (React / Next.js / Vue)
- Mobile applications
- Private REST APIs
- Multi-frontend environments
- API abuse prevention

---

### 🧩 Key Features

- Editable API key
- Domain-based access control
- Auto-discovered REST routes
- Route-level access toggle
- Global enable/disable switch
- No core file modification
- Clean and lightweight

---

### 🔒 Security Philosophy

This plugin:
- Uses WordPress native hooks
- Avoids custom database tables
- Follows WordPress coding standards
- Respects plugin review guidelines

---

### 📦 Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin from the WordPress admin panel
3. Go to **API Security** in the dashboard
4. Configure API key, domain, and allowed routes

---

### 🛠️ Frequently Asked Questions

**Does this plugin replace WordPress authentication?**  
No. It adds an extra security layer for REST APIs.

**Will this slow down my site?**  
No. The checks are lightweight and run before request processing.

**Can I use this with custom REST APIs?**  
Yes. All registered REST routes are automatically detected.

**Is it compatible with Rank Math or other plugins?**  
Yes, any plugin that registers REST APIs is supported.

---

### 🧾 Changelog

**1.1.0**
- Added dynamic REST route detection
- Added route-level access control
- Editable API key
- Domain binding support

---

### 👨‍💻 Developer Notes

This plugin is designed for developers building **secure headless WordPress backends** without rewriting authentication logic.

---

### ❤️ Credits

Developed by Rakib  
Email: rakib417@gmail.com

---

== Screenshots ==

1. API Security settings dashboard
2. REST API route list with toggle options
3. API key and domain binding configuration

---

== Upgrade Notice ==

**1.1.0**
Improved REST API control and enhanced security options.
