<?php

/*
 * This file is part of Hazelchat for October CMS.
 *
 * (C) 2026 Hazelchat. MIT License; see LICENSE.
 */

namespace Hazelchat\Agent\Classes;

/**
 * The public widget key and the one script tag, as plain functions with no
 * October dependency, so they can be tested with PHP alone. The key is public:
 * it names an agent and grants no access to the Hazelchat account. Written
 * for PHP 8.0, the oldest PHP October 3 runs on.
 */
final class Key
{
    /** The only address this plugin ever prints. */
    const SCRIPT_URL = 'https://app.hazelchat.co/widget.js';

    /** Hazelchat counts installations of this plugin by this id. */
    const SCRIPT_ID = 'hazelchat-october';

    /** Room for a pasted installation code; the key itself is 64 characters. */
    const MAX_INPUT = 1000;

    /** @param mixed $key */
    public static function valid($key): bool
    {
        return \is_string($key) && preg_match('/\A[a-f0-9]{64}\z/', $key) === 1;
    }

    /**
     * The setting takes the whole installation code or just the key. A bare
     * 64-hex value is the key; otherwise the text must hold exactly one
     * data-agent attribute (outside HTML comments) whose value is 64
     * lowercase hex. Anything else gives null, and nothing is printed.
     *
     * @param mixed $value
     */
    public static function fromInput($value): ?string
    {
        if (!\is_string($value)) {
            return null;
        }
        $value = trim($value);
        if ($value === '' || \strlen($value) > self::MAX_INPUT) {
            return null;
        }
        if (self::valid($value)) {
            return $value;
        }

        // A key that is only mentioned in a comment is not the code.
        $value = preg_replace('/<!--.*?-->/s', '', $value);
        if ($value === null || strpos($value, '<!--') !== false) {
            return null;
        }

        // Exactly one data-agent attribute; look-alikes such as
        // xdata-agent or data-agent-x are not it, and two are ambiguous.
        $attribute = '(?<![A-Za-z0-9_:-])data-agent(?![A-Za-z0-9_:-])\s*=';
        if (preg_match_all('/' . $attribute . '/i', $value) !== 1) {
            return null;
        }
        if (preg_match('/' . $attribute . '\s*(?:"([a-f0-9]{64})"|\'([a-f0-9]{64})\'|([a-f0-9]{64})(?=[\s>\/]|\z))/', $value, $match) !== 1) {
            return null;
        }

        $key = ($match[1] ?? '') . ($match[2] ?? '') . ($match[3] ?? '');

        return self::valid($key) ? $key : null;
    }

    /**
     * The script tag, or null when nothing may be printed. No inline code,
     * no data-platform and no address but SCRIPT_URL.
     */
    public static function tag(?string $key, bool $requireConsent): ?string
    {
        if (!self::valid($key)) {
            return null;
        }

        return '<script src="' . self::SCRIPT_URL . '" id="' . self::SCRIPT_ID . '" defer data-agent="' . $key . '"'
            . ($requireConsent ? ' data-require-consent="true"' : '')
            . '></script>';
    }

    /**
     * Puts the tag just before the page's last </body>. A page without
     * </body> (a fragment) or one that already carries the tag stays as it
     * is. Only a real </body> counts: </bodyx> does not.
     */
    public static function insert(string $html, ?string $tag): string
    {
        if ($tag === null || preg_match('/\bid\s*=\s*["\']?' . self::SCRIPT_ID . '["\'\s>]/i', $html) === 1) {
            return $html;
        }
        if (preg_match_all('/<\/body\s*>/i', $html, $found, PREG_OFFSET_CAPTURE) < 1) {
            return $html;
        }
        $at = $found[0][\count($found[0]) - 1][1];

        return substr($html, 0, $at) . $tag . "\n" . substr($html, $at);
    }
}
