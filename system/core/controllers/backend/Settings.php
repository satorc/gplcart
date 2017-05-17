<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace gplcart\core\controllers\backend;

use gplcart\core\controllers\backend\Controller as BackendController;

/**
 * Handles incoming requests and outputs data related to common store settings
 */
class Settings extends BackendController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Displays the edit settings page
     */
    public function editSettings()
    {
        $this->setTitleEditSettings();
        $this->setBreadcrumbEditSettings();

        $this->setData('settings', $this->getSettings());
        $this->setData('timezones', gplcart_timezones());

        $this->submitEditSettings();
        $this->outputEditSettings();
    }

    /**
     * Returns an array of default settings
     * @return array
     */
    protected function getDefaultSettings()
    {
        return array(
            'cron_key' => '',
            'error_level' => 2,
            'error_live_report' => 0,
            'timezone' => 'Europe/London'
        );
    }

    /**
     * Returns an array of settings
     * @return array
     */
    protected function getSettings()
    {
        $default = $this->getDefaultSettings();
        $saved = $this->config();
        return gplcart_array_merge($default, $saved);
    }

    /**
     * Saves submitted settings
     */
    protected function submitEditSettings()
    {
        if ($this->isPosted('delete_cached_assets')) {
            $this->clearCacheAssetsSettings();
        } else if ($this->isPosted('save') && $this->validateEditSettings()) {
            $this->updateSettings();
        }
    }

    /**
     * Validates submitted settings
     * @return bool
     */
    protected function validateEditSettings()
    {
        $this->setSubmitted('settings');

        if (!$this->getSubmitted('cron_key')) {
            $this->setSubmitted('cron_key', gplcart_string_random());
        }

        return !$this->hasErrors();
    }

    /**
     * Deletes all aggregated assets
     */
    protected function clearCacheAssetsSettings()
    {
        $deleted = gplcart_file_delete_recursive(GC_COMPRESSED_ASSET_DIR);

        if ($deleted) {
            $this->redirect('', $this->text('Cache has been cleared'), 'success');
        }

        $this->redirect('');
    }

    /**
     * Updates settings
     */
    protected function updateSettings()
    {
        $this->controlAccess('settings_edit');

        foreach ($this->getSubmitted() as $key => $value) {
            $this->config->set($key, $value);
        }

        $message = $this->text('Settings have been updated');
        $this->redirect('', $message, 'success');
    }

    /**
     * Sets title on the edit settings page
     */
    protected function setTitleEditSettings()
    {
        $this->setTitle($this->text('Settings'));
    }

    /**
     * Sets breadcrumbs on the edit settings page
     */
    protected function setBreadcrumbEditSettings()
    {
        $breadcrumb = array(
            'url' => $this->url('admin'),
            'text' => $this->text('Dashboard')
        );

        $this->setBreadcrumb($breadcrumb);
    }

    /**
     * Render and output the edit settings page
     */
    protected function outputEditSettings()
    {
        $this->output('settings/common');
    }

}
