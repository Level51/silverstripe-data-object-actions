<?php

namespace Level51\DataObjectActions;

use SilverStripe\Forms\FieldList;

/**
 * Use this as base class for custom extensions instead of the default DataExtension
 * to be able to add custom actions.
 */
class DataExtension extends \SilverStripe\ORM\DataExtension
{
    public function getCustomActions(): FieldList
    {
        return FieldList::create();
    }
}
