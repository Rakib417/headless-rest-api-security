=== Headless REST API Security ===
Contributors: rakib417
Tags: security, headless, rest api, json, authentication, protection
Requires at least: 5.8
Tested up to: 6.4
Stable tag: 2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The best REST API protection to secure Headless WordPress and block unauthorized data access. The essential security solution for Next.js, React, and mobile app backends.

== Description ==

The best REST API protection to secure Headless WordPress and block unauthorized data access. The essential security solution for Next.js, React, and mobile app backends.

**Headless REST API Security** locks down your WordPress REST API against scrapers and bots by adopting a strict "whitelist" model to prevent your site from exposing sensitive data. You can manage access controls and monitor your API security directly from your dashboard.

Major features include:

* **Strict Security Mode:** Automatically blocks all REST API endpoints by default to prevent unauthorized access.
* **Headless Redirect:** Redirects visitors who land on your backend API URL directly to your main frontend domain (e.g., `api.site.com` -> `mysite.com`).
* **Smart Whitelisting:** Easily select exactly which API routes to expose (e.g., `/wp/v2/posts`) while keeping users and settings hidden.
* **API Key Authentication:** Requires a secure `X-API-KEY` header for external applications to access data.
* **Admin Bypass:** Automatically detects logged-in administrators so you can continue using the WP Admin and Block Editor without interruption.
* **Domain Binding:** Restricts API access to specific origins, ensuring only your frontend can fetch data.

PS: You'll be able to generate your secure API Key instantly upon activation.

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

= Can I use this with RankMath, WooCommerce, or CF7? =
Yes. The plugin automatically detects routes registered by other plugins. You can see them in the list and whitelist them (e.g., `/wc/v3` or `/contact-form-7/v1`).

= What happens if I lose my API Key? =
You can view or generate a new key anytime from the settings page.

== Screenshots ==

1. **Dashboard:** The main settings interface with the Master Switch and Redirect options.
2. **Whitelist Grid:** The smart list of API routes allowing you to toggle access.

== Changelog ==

= 2.0 =
* **New:** Added Headless Redirect to main domain function.
* **New:** Introduced Strict Security (Whitelist) mode.
* **New:** Added Smart Grouping for cleaner route management.
* **Improvement:** Added Admin Bypass for logged-in users.

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