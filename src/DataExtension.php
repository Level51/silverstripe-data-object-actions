<?php

namespace Level51\DataObjectActions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;

/**
 * Use this as base class for custom extensions instead of the default Extension
 * to be able to add custom actions.
 */
class DataExtension extends Extension
{
    public function getCustomActions(): FieldList
    {
        return FieldList::create();
    }
}
