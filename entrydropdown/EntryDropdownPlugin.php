<?php

namespace Craft;

class EntryDropdownPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Entry Dropdown');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Rob Sanchez';
    }

    public function getDeveloperUrl()
    {
        return 'https://github.com/rsanchez';
    }
}
