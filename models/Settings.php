<?php

/*
 * This file is part of Hazelchat for October CMS.
 *
 * (C) 2026 Hazelchat. MIT License; see LICENSE.
 */

namespace Hazelchat\Agent\Models;

use Hazelchat\Agent\Classes\Key;
use Lang;
use System\Models\SettingModel;
use ValidationException;

/**
 * Settings → Hazelchat: the installation code and one switch, kept in
 * October's own settings table. Only the key is saved, never the pasted
 * code; anything that is not a valid key is refused in one sentence.
 */
class Settings extends SettingModel
{
    public $settingsCode = 'hazelchat_agent_settings';

    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->code = '';
        $this->consent = false;
    }

    public function beforeSave()
    {
        $input = trim((string) $this->code);
        if ($input === '') {
            $this->code = '';
        }
        elseif (($key = Key::fromInput($input)) !== null) {
            $this->code = $key;
        }
        else {
            throw new ValidationException(['code' => Lang::get('hazelchat.agent::lang.settings.refused')]);
        }
        $this->consent = (bool) $this->consent;
    }
}
