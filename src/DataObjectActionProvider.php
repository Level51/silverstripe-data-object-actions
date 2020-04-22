<?php

namespace Level51\DataObjectActions;

use SilverStripe\Forms\FieldList;

/**
 * Interface for DataObjects which would like to provide custom actions.
 *
 * @package Level51\DataObjectActions
 */
interface DataObjectActionProvider {

    /**
     * FieldList containing all DataObjectActions this DO should have.
     *
     * @return FieldList
     *
     * @see DataObjectAction
     */
    public function getCustomActions();
}
