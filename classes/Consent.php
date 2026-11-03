<?php

/*
 * This file is part of Hazelchat for October CMS.
 *
 * (C) 2026 Hazelchat. MIT License; see LICENSE.
 */

namespace Hazelchat\Agent\Classes;

/**
 * What the page gets when the owner asks the chat to wait for consent, as a
 * plain function so it can be tested with PHP alone.
 *
 * Most consent managers run in the browser, where the chat itself waits for
 * them (data-require-consent). OFFLINE.GDPR's cookie banner decides on the
 * server instead, so with it the plugin prints the chat only for a visitor
 * who accepted the cookie the owner named "Hazelchat" there.
 */
final class Consent
{
    /** The code OFFLINE.GDPR gives a cookie named "Hazelchat". */
    const GDPR_CODE = 'hazelchat';

    /**
     * @param bool $wait the owner's "Wait for my consent manager" switch
     * @param bool|null $gdpr OFFLINE.GDPR's answer for the Hazelchat cookie,
     *                        or null where it does not decide (not installed,
     *                        or no cookie with that code)
     * @return array{print: bool, requireConsent: bool}
     */
    public static function decide(bool $wait, ?bool $gdpr): array
    {
        if (!$wait) {
            return ['print' => true, 'requireConsent' => false];
        }
        if ($gdpr === null) {
            return ['print' => true, 'requireConsent' => true];
        }

        return ['print' => $gdpr, 'requireConsent' => false];
    }
}
