<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace gplcart\core\controllers\frontend;

use gplcart\core\models\Page as PageModel;
use gplcart\core\controllers\frontend\Controller as FrontendController;

/**
 * Handles incoming requests and outputs data related to blog
 */
class Blog extends FrontendController
{

    /**
     * Page model instance
     * @var \gplcart\core\models\Page $page
     */
    protected $page;

    /**
     * Pager limit
     * @var array
     */
    protected $data_limit;

    /**
     * Total number of blog posts
     * @var int
     */
    protected $data_total;

    /**
     * @param PageModel $page
     */
    public function __construct(PageModel $page)
    {
        parent::__construct();

        $this->page = $page;
    }

    /**
     * Displays the blog page
     */
    public function listBlog()
    {
        $this->setTitleListBlog();
        $this->setBreadcrumbListBlog();

        $this->setTotalListBlog();
        $this->setPagerListBlog();

        $this->setData('pages', $this->getPagesBlog());
        $this->outputListBlog();
    }

    /**
     * Sets a total number of posts found
     * @return int
     */
    protected function setTotalListBlog()
    {
        $options = $this->query_filter;

        $options['status'] = 1;
        $options['count'] = true;
        $options['store_id'] = $this->store_id;
        $options['category_group_id'] = $this->store->config('blog_category_group_id');

        return $this->data_total = (int) $this->page->getList($options);
    }

    /**
     * Returns an array of blog posts
     * @return array
     */
    protected function getPagesBlog()
    {
        $options = $this->query_filter;

        $options['status'] = 1;
        $options['limit'] = $this->data_limit;
        $options['store_id'] = $this->store_id;
        $options['category_group_id'] = $this->store->config('blog_category_group_id');

        $pages = (array) $this->page->getList($options);

        return $this->preparePagesBlog($pages);
    }

    /**
     * Prepares an array of pages
     */
    protected function preparePagesBlog(array $pages)
    {
        foreach ($pages as &$page) {

            list($teaser, $body) = $this->explodeText($page['description']);

            if ($body !== '') {
                $page['teaser'] = strip_tags($teaser);
            }

            $this->setItemUrl($page, array('id_key' => 'page_id'));
        }

        return $pages;
    }

    /**
     * Sets pager
     * @return array
     */
    protected function setPagerListBlog()
    {
        $pager = array(
            'total' => $this->data_total,
            'query' => $this->query_filter,
            'limit' => $this->configTheme('blog_limit', 20)
        );

        return $this->data_limit = $this->setPager($pager);
    }

    /**
     * Sets titles on the blog page
     */
    protected function setTitleListBlog()
    {
        $this->setTitle($this->text('Blog'));
    }

    /**
     * Sets bread crumbs on the blog page
     */
    protected function setBreadcrumbListBlog()
    {
        $this->setBreadcrumbHome();
    }

    /**
     * Renders and outputs the blog page templates
     */
    protected function outputListBlog()
    {
        $this->output('blog/list');
    }

}