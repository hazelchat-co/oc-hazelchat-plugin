# Hazelchat for October CMS

Adds your [Hazelchat](https://hazelchat.co/?utm_source=october&utm_medium=plugin&utm_campaign=readme) AI chat agent to every page of an October CMS site from one pasted installation code. The agent answers visitors from the pages you approved, shows its sources and passes on their enquiries.

- October CMS 3 and 4, on every theme and layout. No layout editing, and nothing lost when the theme changes or updates.
- Needs a Hazelchat account. The Free plan covers one website and stays free.

## Install

1. In Hazelchat, add your website and copy its installation code ([create a free account](https://app.hazelchat.co/signup?utm_source=october&utm_medium=plugin&utm_campaign=readme-signup)).
2. Install the plugin: in the back end under **Settings → Updates & Plugins**, or run `php artisan plugin:install Hazelchat.Agent`, or `composer require hazelchat/agent-plugin`.
3. Open **Settings → Hazelchat**, paste the installation code and save.
4. Back in Hazelchat, click **Publish and switch on**. Hazelchat finds the chat on your live page and verifies the website.

## What it does

- Every page October renders gets one tag just before `</body>`:
  `<script src="https://app.hazelchat.co/widget.js" id="hazelchat-october" defer data-agent="<key>"></script>`
- The installation code may be the pasted code or just its 64-character key. Only a valid key is saved; anything else, such as an API key, is refused. The script address is fixed and cannot be changed in the settings.
- **Wait for my consent manager** (off) adds `data-require-consent="true"`: the chat starts only once your consent manager allows it. The script file itself still loads straight away, except with OFFLINE.GDPR's Hazelchat cookie; see [Data and third parties](#data-and-third-parties) and [Visitor consent](https://hazelchat.co/integrations/october-cms/#consent).
- The back end never loads the chat, and AJAX responses and pages without `</body>` stay as they are.
- Only administrators with the **Change the Hazelchat settings** permission see the settings (super users have every permission).
- Back-end texts in English and German.

### With OFFLINE.GDPR's cookie banner

[OFFLINE.GDPR](https://octobercms.com/plugin/offline-gdpr) decides about cookies on the server, before the page reaches the browser. With **Wait for my consent manager** on:

1. In its cookie settings, add a cookie named **Hazelchat** (code `hazelchat`) to the group you choose.
2. The plugin then prints the chat only for visitors who accepted that cookie, and nothing for everyone else.

Until that cookie exists, the settings page reminds you in one sentence, and the chat waits as it does with any other consent manager. A full-page cache would store the page as the first visitor saw it; this applies to every script OFFLINE.GDPR controls.

## Data and third parties

- Visitors' browsers load the chat from `app.hazelchat.co` (Hazelchat, Germany). Hazelchat processes the questions they type and, if they leave an enquiry, their name, email address and message ([privacy notice](https://hazelchat.co/privacy/), [terms](https://hazelchat.co/terms/)).
- With **Wait for my consent manager** on, only the chat waits: browsers still download `widget.js` from `app.hazelchat.co` straight away, so the visitor's IP address reaches Hazelchat before they agree. This is by design. If your site must prevent that too, use OFFLINE.GDPR's Hazelchat cookie (above), which prints nothing until the visitor accepts, or a consent tool that can block the script itself.
- The script tag carries the id `hazelchat-october`. When Hazelchat checks your website, it reads this id to record that the chat was installed with this plugin, and it counts the plugin's installations that way.
- The chat sets no cookies. It keeps a reference to the conversation in session storage until the tab is closed (details on the [cookie banner page](https://hazelchat.co/integrations/cookie-banners/)).
- The plugin itself makes no requests from October, adds no tables, routes or files, and stores only the key and the switch in October's settings table.

## Remove

Empty the installation code in **Settings → Hazelchat**, or uninstall the plugin (`php artisan plugin:remove Hazelchat.Agent`). Your Hazelchat account and plan are not affected.

## Deutsch

Hazelchat fügt Ihren KI-Chat-Agenten mit einem einzigen Installationscode in jede Seite Ihrer October-CMS-Site ein (October 3 und 4, jedes Theme). Installieren Sie das Plugin im Backend oder mit `php artisan plugin:install Hazelchat.Agent`, öffnen Sie **Einstellungen → Hazelchat**, fügen Sie den Code aus Hazelchat ein und speichern Sie. Die Browser Ihrer Besucher laden den Chat von `app.hazelchat.co`; deren Fragen und Anfragedaten verarbeitet Hazelchat. Wartet der Chat auf Ihren Consent-Manager, lädt der Browser `widget.js` trotzdem sofort, und die IP-Adresse des Besuchers erreicht Hazelchat schon vor der Einwilligung; nur der Chat wartet. Mit dem Hazelchat-Cookie von OFFLINE.GDPR lädt vor der Zustimmung nichts. Über die ID `hazelchat-october` im Skript-Tag zählt Hazelchat die Installationen dieses Plugins. Sie brauchen ein Hazelchat-Konto; der Free-Tarif gilt für eine Website und bleibt kostenlos.

## Licence

MIT. October CMS is a trademark of its makers; Hazelchat is not affiliated with or endorsed by them.
