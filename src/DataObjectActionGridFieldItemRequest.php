<?php

namespace Level51\DataObjectActions;

use CE\ServicePlatform\Pigment;
use CE\ServicePlatform\PigmentItemRequest;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Extension;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\GridField\GridFieldDetailForm_ItemRequest;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Versioned\RecursivePublishable;
use SilverStripe\Versioned\Versioned;

/**
 * GridField item request extension to add custom action on a per DataObject base.
 *
 * @property GridFieldDetailForm_ItemRequest owner
 *
 * @package Level51\DataObjectActions
 */
class DataObjectActionGridFieldItemRequest extends Extension {

    const CUSTOM_ACTION_NAME = 'customDataObjectAction';

    private static $allowed_actions = [
        'edit',
        'view',
        'ItemEditForm',
        'customDataObjectAction'
    ];

    /**
     * Handler for all custom actions.
     *
     * Each custom action will have "customDataObjectAction" as name including the
     * nested action as array param, so for example "customDataObjectAction[myActionName]".
     *
     * This method will cover the basic action handling including calling the nested action,
     * saving the record and do the redirect handling.
     *
     * Custom stuff can be done in "myActionName" defined on the record class.
     *
     * @param array $data
     * @param Form  $form
     */
    public function customDataObjectAction($data, $form) {
        $action = array_keys($data['action_customDataObjectAction']);
        $action = array_shift($action);

        /** @var Versioned|DataObject|DataObjectActionProvider $record */
        $record = $this->owner->getRecord();
        $isNewRecord = $record->ID == 0;

        if (!$record->canEdit() || !$record->hasMethod($action))
            return $this->owner->httpError(403);

        $message = $record->{$action}($data, $form);

        // Save from form data
        $this->owner->saveFormIntoRecord($data, $form);

        if ($message)
            $form->sessionMessage($message, 'good', ValidationResult::CAST_HTML);

        // Redirect after save
        return $this->redirectAfterSave($isNewRecord);
    }

    /**
     * Hook into the getFormActions method to add our custom form actions.
     *
     * @param FieldList $actions
     */
    public function updateFormActions(FieldList $actions) {
        /** @var Versioned|DataObject|DataObjectActionProvider $record */
        $record = $this->owner->getRecord();

        if ($record instanceof DataObjectActionProvider) {
            /** @var FieldList||null $customActions */
            $customActions = $record->getCustomActions();

            if ($customActions && $customActions->count() > 0) {
                /** @var DataObjectAction $formAction */
                foreach ($customActions as $formAction) {
                    $actions->insertAfter('MajorActions', $formAction);
                }
            }
        }
    }

    /**
     * Same as in {@see GridFieldDetailForm_ItemRequest}
     *
     * We have to copy the function as it's not callable by $this->owner due to the protected state.
     *
     * @param $isNewRecord
     *
     * @return mixed|\SilverStripe\Control\HTTPResponse
     */
    protected function redirectAfterSave($isNewRecord) {
        $controller = $this->getToplevelController();
        if ($isNewRecord) {
            return $controller->redirect($this->owner->Link());
        } elseif ($this->owner->getGridField()->getList()->byID($this->owner->getRecord()->ID)) {
            // Return new view, as we can't do a "virtual redirect" via the CMS Ajax
            // to the same URL (it assumes that its content is already current, and doesn't reload)
            return $this->owner->edit($controller->getRequest());
        } else {
            // Changes to the record properties might've excluded the record from
            // a filtered list, so return back to the main view if it can't be found
            $url = $controller->getRequest()->getURL();
            $noActionURL = $controller->removeAction($url);
            $controller->getRequest()->addHeader('X-Pjax', 'Content');

            return $controller->redirect($noActionURL, 302);
        }
    }

    /**
     * Same as in {@see GridFieldDetailForm_ItemRequest}
     *
     * We have to copy the function as it's not callable by $this->owner due to the protected state.
     *
     * @return \SilverStripe\Control\Controller|\SilverStripe\Control\RequestHandler
     */
    protected function getToplevelController() {
        $c = $this->owner->popupController;
        while ($c && $c instanceof GridFieldDetailForm_ItemRequest) {
            $c = $c->getController();
        }

        return $c;
    }
}
