<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Field;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;

FormHelper::loadFieldClass('list');

class CategoryField extends ListField
{
    protected $type = 'category';

    public function getOptions()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select("DISTINCT name")->from($db->quoteName('com_catalogsystem_category'))->order('name'); // Add Order
        $categories = $db->loadObjectList();
        $options = array();

        foreach ($categories as $category) {
            $options[] = HTMLHelper::_('select.option', $category->name);
        }

        if ($this->form) { // If we are in a form merge additional xml data
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }
}
