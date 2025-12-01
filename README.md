# SilverStripe DataObject Actions
Module for Silverstripe 5 allowing to add custom actions for DataObjects to the GridFieldDetailForm. See `0.X` releases for Silverstripe 4 support.

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

  // Return a field list containing all custom actions, each one of type DataObjectAction or DataObjectLink
  public function getCustomActions() 
  {
    return FieldList::create(
      [
        DataObjectAction::create('myCustomAction', 'My Custom Action')
          ->addExtraClass('btn-outline-primary font-icon-rocket')
          ->setUseButtonTag(true),
        DataObjectLink::create('externalLink', 'External Link', 'https://lvl51.de')
        	->addExtraClass('btn-outline-dark font-icon-external-link')
          ->setNewWindow(true)
      ]
    );
  }
	
  // Implement the handler method(s)
  public function myCustomAction($data, $form) 
  {
    // Do stuff, e.g. set a property
    // Do NOT call $this->write(), this will be done automatically
    // use `setWriteBeforeAction` when creating the action if the record 
    // should be written before the action is executed
		
    // throw a new \SilverStripe\ORM\ValidationResult in case something failed
		
    // Optionally return a success message
    return 'Success message';
  }

}
```

### Usage with DataExtensions
You can update the custom actions of a parent class implementing the DataObjectActionProvider interface 
using the `updateCustomActions` extension hook.

```php
public function updateCustomActions(FieldList $fields)
{
    $fields->push(
        DataObjectAction::create(...)
    );
}
```

To be able to add custom actions to an owner class which is out of your control (e.g. core Member class)
you have to use the Level51\DataObjectActions\DataExtension class as base class of your 
extension. With that you are able to use the getCustomActions method as shown above.

## Action Options
- use `setIsAlwaysEnabled` to always enable the action, even if the whole edit form is read-only
- use `setWriteBeforeAction` if the record should be written before the action is executed

## Requirements
- SilverStripe ^5.0
- PHP >= 8.0

## Maintainer
- Level51 <hallo@lvl51.de>
