# SilverStripe DataObject Actions
Module for SilverStripe 4 allowing to add custom actions for DataObjects to the GridFieldDetailForm.

## Installation
`composer require level51/silverstripe-data-object-actions`

## Usage
```php
namespace My\Awesome\Project;

use Level51\DataObjectActions\DataObjectActionProvider;
use Level51\DataObjectActions\DataObjectAction;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;

// Implement DataObjectActionProvider interface on your DataObject
class MyDataObject extends DataObject implements DataObjectActionProvider {

  // Return a field list containing all custom actions, each one of type DataObjectAction
  public function getCustomActions() {
    return FieldList::create([
      DataObjectAction::create('myCustomAction', 'My Custom Action')
        ->addExtraClass('btn-outline-primary font-icon-rocket')
        ->setUseButtonTag(true)
      ]);
  }
	
  // Implement the handler method(s)
  public function myCustomAction($data, $form) {
    // Do stuff, e.g. set a property
    // Do NOT call $this->write(), this will be done automatically
		
    // throw a new \SilverStripe\ORM\ValidationResult in case something failed
		
    // Optionally return a success message
    return 'Success message';
  }

}
```

## Requirements
- SilverStripe ^4.0
- PHP >= 7.0

## Maintainer
- Level51 <hallo@lvl51.de>
