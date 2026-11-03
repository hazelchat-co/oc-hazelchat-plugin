<?php

/*
 * Hazelchat for October CMS: adds the Hazelchat AI chat agent to every page
 * of an October CMS site from one installation code.
 *
 * (C) 2026 Hazelchat. MIT License; see LICENSE.
 */

namespace Hazelchat\Agent;

use Event;
use Hazelchat\Agent\Classes\Consent;
use Hazelchat\Agent\Classes\Key;
use Hazelchat\Agent\Models\Settings;
use Request;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Throwable;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'hazelchat.agent::lang.plugin.name',
            'description' => 'hazelchat.agent::lang.plugin.description',
            'author' => 'Hazelchat',
            'icon' => 'icon-comments',
            'homepage' => 'https://hazelchat.co/integrations/october-cms/',
        ];
    }

    public function registerPermissions()
    {
        return [
            'hazelchat.agent.manage' => [
                'tab' => 'hazelchat.agent::lang.plugin.name',
                'label' => 'hazelchat.agent::lang.permission',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'hazelchat.agent::lang.plugin.name',
                'description' => 'hazelchat.agent::lang.settings.description',
                'category' => SettingsManager::CATEGORY_CMS,
                'icon' => 'icon-comments',
                'class' => Settings::class,
                'permissions' => ['hazelchat.agent.manage'],
                'keywords' => 'hazelchat chat agent',
                'order' => 500,
            ],
        ];
    }

    /**
     * The tag goes into the finished page. October 4 fires both events, so
     * the second one finds the tag and leaves the page as it is; October 3
     * fires only cms.page.postprocess.
     */
    public function boot()
    {
        Event::listen('cms.page.postProcessContent', function ($controller, $url, $page, &$content) {
            $content = $this->withTag($content);
        });
        Event::listen('cms.page.postprocess', function ($controller, $url, $page, $dataHolder) {
            $dataHolder->content = $this->withTag($dataHolder->content);
        });
    }

    /** @param mixed $html */
    private function withTag($html)
    {
        if (!\is_string($html) || Request::ajax() || Request::header('X-OCTOBER-REQUEST-HANDLER')) {
            return $html;
        }
        $key = Key::fromInput(Settings::get('code'));
        if ($key === null) {
            return $html;
        }
        $wait = (bool) Settings::get('consent', false);
        $decision = Consent::decide($wait, $wait ? self::gdprConsent() : null);

        return $decision['print'] ? Key::insert($html, Key::tag($key, $decision['requireConsent'])) : $html;
    }

    /**
     * OFFLINE.GDPR's answer for the visitor, or null where it does not decide:
     * not installed, or no cookie with the code "hazelchat". Any error leaves
     * the decision to the chat, which then waits.
     */
    public static function gdprConsent(): ?bool
    {
        if (!class_exists(\OFFLINE\GDPR\Classes\Cookies\ConsentCookie::class) || !class_exists(\OFFLINE\GDPR\Models\Cookie::class)) {
            return null;
        }
        try {
            if (!\OFFLINE\GDPR\Models\Cookie::where('code', Consent::GDPR_CODE)->exists()) {
                return null;
            }

            return (bool) (new \OFFLINE\GDPR\Classes\Cookies\ConsentCookie())->isAllowed(Consent::GDPR_CODE);
        } catch (Throwable $e) {
            return null;
        }
    }
}
