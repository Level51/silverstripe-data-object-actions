<?php

namespace Level51\DataObjectActions;

use SilverStripe\Forms\FormField;
use SilverStripe\Forms\LiteralField;

/**
 * LiteralField extension allowing to add simple links to the data object actions section.
 */
class DataObjectLink extends LiteralField
{
    private $link;

    private $newWindow = false;

    public function __construct($name, $title = null, $link = null)
    {
        if ($title === null) {
            $title = FormField::name_to_label($name);
        }

        parent::__construct($name, '');

        $this->title = $title;

        if ($link && is_string($link)) {
            $this->link = $link;
        }
    }

    public function Type()
    {
        return 'custom-link';
    }

    public function FieldHolder($properties = [])
    {
        $title = $this->title;
        $classes = $this->extraClass();

        $classes .= ' btn no-ajax';

        $this->attributes['href'] = $this->link;
        $this->attributes['class'] = $classes;

        if ($this->newWindow) {
            $this->attributes['target'] = '_blank';
        }

        $attrs = '';
        foreach ($this->attributes as $key => $value) {
            $attrs .= ' ' . sprintf('%s="%s"', $key, $value);
        }

        $this->content = sprintf('<a%s>%s</a>', $attrs, $title);

        return parent::FieldHolder();
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function getNewWindow()
    {
        return $this->newWindow;
    }

    public function setNewWindow($newWindow)
    {
        $this->newWindow = $newWindow;
        return $this;
    }

    public function isAlwaysEnabled()
    {
        return false;
    }
}
