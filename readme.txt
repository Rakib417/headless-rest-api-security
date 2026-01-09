=== Headless REST API Security ===
Contributors: rakib417
Tags: headless, rest api, security, authentication
Requires at least: 5.8
Tested up to: 6.9
Stable tag: 2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The #1 Security Solution for Headless WordPress. Lock down your REST API, block scrapers, and secure your Next.js/React backend with one click.

== Description ==

**Headless REST API Security is the "Swiss Army Knife" of API protection for WordPress.**

If you are running a Headless WordPress site (Next.js, Gatsby, Nuxt, or Mobile App), your REST API is **exposed to the public** by default. This leaves your data vulnerable to scrapers, bots, and unauthorized users.

**Headless REST API Security** solves this instantly. It is the **FIRST** and **ONLY** plugin designed specifically to lock down Headless architectures with a "Strict Whitelist" model. We give you the power to disable ALL API routes by default and only allow exactly what your app needs.

🛑 **STOP** unauthorized data scraping.
🔒 **SECURE** your content and user data.
🚀 **BOOST** performance by blocking bad requests.

---

### 🚀 Why Headless REST API Security is the Best Choice?

We didn't just build a security plugin; we built a **Headless Firewall**. Unlike generic security plugins that only look for malware, we control the flow of data itself.

* **🛡️ Strict Security Mode (Whitelist):** The only plugin that blocks 100% of API requests by default. You choose what to unlock.
* **↩️ Smart Headless Redirects:** Automatically redirects visitors who find your backend URL (e.g., `api.yoursite.com`) directly to your frontend (e.g., `www.yoursite.com`).
* **🔑 API Key Authentication:** Secure your mobile apps and frontend fetch requests with a simple, secure `X-API-KEY` header.
* **⚡ Blazing Fast Performance:** Runs before WordPress loads most core files, ensuring blocked requests don't slow down your server.
* **🕵️ Admin Bypass:** Smart detection allows logged-in Administrators to use the WP Dashboard and Gutenberg Block Editor without interruption.

---

### 🔥 Features at a Glance

* **1-Click Lockdown:** Instantly secure your entire REST API.
* **Route-Level Control:** Enable specific endpoints like `/wp/v2/posts` while keeping `/wp/v2/users` hidden.
* **Smart Grouping:** Automatically groups routes (Core vs. Plugins) for easy management.
* **Domain Binding:** Restrict API access to *only* your frontend domain.
* **Plugin Compatibility:** Works perfectly with Rank Math, WooCommerce, Contact Form 7, and ACF.
* **Developer Friendly:** Clean code, native WordPress hooks, and zero bloat.

---

### 💡 Perfect For:

* **Headless Sites:** Next.js, Gatsby, Frontity, Faust.js, Nuxt.js.
* **Mobile Applications:** React Native, Flutter, iOS, Android.
* **Static Sites:** Jamstack architectures needing secure dynamic data.
* **Intranets:** Private internal dashboards.

---

### 🏗️ How It Works

1.  **Activate** the plugin.
2.  **Turn On** the "Master Switch" to block all public access.
3.  **Whitelist** only the routes your frontend needs (e.g., `/wp/v2/posts`).
4.  **Add** your API Key to your frontend environment variables.
5.  **Relax!** Your API is now invisible to the rest of the world.

> "Security is not an option; it's a necessity. Headless REST API Security makes it simple."

---

### ❤️ Love Headless REST API Security?

If this plugin helped you secure your site, please **rate us 5 stars** on WordPress.org! It helps us keep updates coming.

== Installation ==

1. Upload the `headless-rest-api-security` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the **Headless Security** menu in your dashboard sidebar.
4. Enable "Master Switch" to turn on Strict Mode.
5. Set your "Headless Frontend URL" to enable redirects.

== Configuration ==

**1. Headless Redirect (New)**
Enter your frontend URL (e.g., `https://www.mysite.com`) in the "Headless Frontend URL" field.
* Visitors to your API site will now be redirected there.
* `/wp-admin` and `/wp-json` requests are excluded from redirection.

**2. Whitelisting Routes**
Check the "ALLOW" box next to any route you want to make public (to your frontend).
* **Note:** You must enable the "Master Switch" for the blocking to take effect.

**3. Setting up the API Key**
Copy the API Key generated in the settings page. Add it to your frontend requests header:
`X-API-KEY: your_secret_key_here`

== Frequently Asked Questions ==

= Does this plugin replace WordPress authentication? =
No. It adds a security firewall layer *before* WordPress processes the request. It works alongside existing auth methods (like JWT or Cookies).

= Will this break the Block Editor (Gutenberg)? =
No. The plugin includes an "Admin Bypass" feature. If you are logged in as an Administrator or Editor, the API restrictions are skipped so you can work normally.

= Can I use this with Rank Math, WooCommerce, or CF7? =
Yes. The plugin automatically detects routes registered by other plugins. You can see them in the list and whitelist them (e.g., `/wc/v3` or `/contact-form-7/v1`).

= What happens if I lose my API Key? =
You can view or generate a new key anytime from the settings page.

== Screenshots ==

1. **Dashboard:** The main settings interface with the Master Switch and Redirect options.
2. **Whitelist Grid:** The smart list of API routes allowing you to toggle access.

== Changelog ==

= 2.0 =
* New: Added Headless Redirect to main domain function.
* New: Introduced Strict Security (Whitelist) mode.
* New: Added Smart Grouping for cleaner route management.
* Improvement: Added Admin Bypass for logged-in users.

= 1.1.0 =
* Added dynamic REST route detection.
* Added route-level access control.
* Editable API key.
* Domain binding support.

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 2.0 =
Major update introducing Strict Whitelist Mode and Headless Redirects. Please review your allowed routes after upgrading.

== Contact ==
Author: Md. Rakib Ullah
Email: rakib417@gmail.com
Linkedin: https://www.linkedin.com/in/rakib417/

```