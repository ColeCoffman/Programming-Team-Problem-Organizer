<?php


// This is the default admin model.
// Since our component only adds site pages, this is unused


namespace ProgrammingTeam\Component\CatalogSystem\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;

class CatalogSystemModel extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_catalogsystem.form', 'catalogsystem', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getMsg()
    {
        return 'Hello Foos: ';
        // return $this->message;
    }
}
