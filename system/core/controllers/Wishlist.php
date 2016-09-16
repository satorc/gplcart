<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace core\controllers;

use core\controllers\Controller as FrontendController;

class Wishlist extends FrontendController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Displays the wishlist page
     */
    public function indexWishlist()
    {
        $this->setDataWishlist();

        $this->setTitleIndexWishlist();
        $this->setBreadcrumbIndexWishlist();
        $this->outputIndexWishlist();
    }

    /**
     * Sets titles on the wishlist page
     */
    protected function setTitleIndexWishlist()
    {
        $this->setTitle($this->text('My wishlist'));
    }

    /**
     * Sets breadcrumbs on the wishlist page
     */
    protected function setBreadcrumbIndexWishlist()
    {
        $breadcrumb = array(
            'text' => $this->text('Home'),
            'url' => $this->url('/'));

        $this->setBreadcrumb($breadcrumb);
    }

    /**
     * Renders the wishlist page templates
     */
    protected function outputIndexWishlist()
    {
        $this->output('wishlist');
    }

    /**
     * Returns an array of wishlist items for the current user
     * @return array
     */
    protected function getListProductWishlist()
    {
        $user_id = $this->cart->uid();
        $results = $this->wishlist->getList(array('user_id' => $user_id));

        // Reindex array
        $products = array();
        foreach ($results as $result) {
            $products[$result['product_id']] = $result;
        }

        return $products;
    }

    /**
     * Sets rendered product list
     */
    protected function setDataWishlist()
    {
        $products = $this->getListProductWishlist();
        $prepared = $this->prepareProducts($products);

        $html = $this->render("product/list", array('products' => $prepared));
        $this->setData('products', $html);
    }

}
