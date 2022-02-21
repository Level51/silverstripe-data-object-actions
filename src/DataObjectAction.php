<?php

namespace Level51\DataObjectActions;

use SilverStripe\Forms\FormAction;

/**
 * Simple FormAction extension for data object actions.
 *
 * Ensures that all custom actions map to the CustomDataObjectActionGridFieldItemRequest::CUSTOM_ACTION_NAME.
 *
 * As the result is a default FormAction you can use all of it's methods to add classes, styles etc.
 *
 * @package Level51\DataObjectActions
 */
class DataObjectAction extends FormAction {
    /** @var bool Set to true, if the action should be enabled even if the whole edit form is read-only */
    protected $alwaysEnabled = false;

    public function __construct($action, $title = "", $form = null) {
        $action = sprintf('%s[%s]', DataObjectActionGridFieldItemRequest::CUSTOM_ACTION_NAME, $action);

        parent::__construct($action, $title, $form);
    }

    public function setIsAlwaysEnabled($alwaysEnabled) {
        $this->alwaysEnabled = $alwaysEnabled;

        return $this;
    }

    public function isAlwaysEnabled() {
        return $this->alwaysEnabled;
    }
}
