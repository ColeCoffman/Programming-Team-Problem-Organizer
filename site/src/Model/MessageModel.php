<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Text;

class MessageModel extends ItemModel
{
    // This is Joomla's default site model. It is never used.
    public function getItem($pk= null): object
    {
        $item = new \stdClass();
        $item->message = Text::_('COM_CATALOGSYSTEM_GREETING');
        return $item;
    }
}
