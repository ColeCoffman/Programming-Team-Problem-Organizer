<?php

namespace ProgrammingTeam\Component\CatalogSystem\Site\View\Catalog;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * @package     Joomla.Site
 * @subpackage  com_catalogsystem
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * View for the user identity validation form
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Display the view
     *
     * @param   string  $template  The name of the layout file to parse.
     * @return  void
     */
    protected $items;
    protected $pagination;
    protected $form;

    public function display($template = null)
    {
        // Call the parent display to display the layout file
        $this->items = $this->get('Items', 'Catalog');
        $this->pagination = $this->get('Pagination', 'Catalog');
        $this->form = $this->get('form', 'CatalogSearch');
        $this->categories = $this->get('CategoryTags');
        parent::display($template);
    }
}
