<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace core\controllers\admin;

use core\Controller;
use core\models\Job as ModelsJob;
use core\models\File as ModelsFile;
use core\models\Import as ModelsImport;

/**
 * Handles incoming requests and outputs data related to import operations
 */
class Import extends Controller
{

    /**
     * Import model instance
     * @var \core\models\Import $import
     */
    protected $import;

    /**
     * Job model instance
     * @var \core\models\Job $job
     */
    protected $job;

    /**
     * File model instance
     * @var \core\models\File $file
     */
    protected $file;

    /**
     * Constructor
     * @param ModelsJob $job
     * @param ModelsImport $import
     * @param ModelsFile $file
     */
    public function __construct(ModelsJob $job, ModelsImport $import,
            ModelsFile $file)
    {
        parent::__construct();

        $this->job = $job;
        $this->file = $file;
        $this->import = $import;
    }

    /**
     * Displays the import operations overview page
     */
    public function operations()
    {
        $this->data['operations'] = $this->import->getOperations();

        $this->setTitleOperations();
        $this->setBreadcrumbOperations();
        $this->outputOperations();
    }

    /**
     * Displays the import form page
     * @param string $operation_id
     */
    public function import($operation_id)
    {
        $this->controlAccess('file_upload');

        $operation = $this->getOperation($operation_id);

        if ($this->request->get('download_template') && isset($operation['csv']['template'])) {
            $this->response->download($operation['csv']['template']);
        }

        if ($this->request->get('download_errors') && isset($operation['log']['errors'])) {
            $this->response->download($operation['log']['errors']);
        }

        if ($this->request->post('import')) {
            $this->submit($operation);
        }

        $this->data['job'] = $this->getJob();
        $this->data['operation'] = $operation;
        $this->data['limit'] = $this->import->getLimit();

        $this->setTitleImport($operation);
        $this->setBreadcrumbImport();
        $this->outputImport();
    }

    /**
     * Sets titles on the import operations overview page
     */
    protected function setTitleOperations()
    {
        $this->setTitle($this->text('Import'));
    }

    /**
     * Sets breadcrumbs on the import operations overview page
     */
    protected function setBreadcrumbOperations()
    {
        $this->setBreadcrumb(array('text' => $this->text('Dashboard'), 'url' => $this->url('admin')));
    }

    /**
     * Renders the import operations overview page
     */
    protected function outputOperations()
    {
        $this->output('tool/import/list');
    }

    /**
     * Sets titles on the import form page
     * @param array $operation
     */
    protected function setTitleImport(array $operation)
    {
        $this->setTitle($this->text('Import %operation', array('%operation' => $operation['name'])));
    }

    /**
     * Sets breadcrumbs on the import form page
     */
    protected function setBreadcrumbImport()
    {
        $this->setBreadcrumb(array('text' => $this->text('Dashboard'), 'url' => $this->url('admin')));
        $this->setBreadcrumb(array('text' => $this->text('Import'), 'url' => $this->url('admin/tool/import')));
    }

    /**
     * Renders the import page templates
     */
    protected function outputImport()
    {
        $this->output('tool/import/edit');
    }

    /**
     * Returns an operation
     * @param string $operation_id
     * @return array
     */
    protected function getOperation($operation_id)
    {
        $operation = $this->import->getOperation($operation_id);

        if (empty($operation)) {
            $this->outputError(404);
        }

        return $operation;
    }

    /**
     * Starts import
     * @param array $operation
     * @return null
     */
    protected function submit(array $operation)
    {
        $this->submitted = $this->request->post();
        $this->submitted['operation'] = $operation;

        $this->validate();
        $errors = $this->formErrors(false);

        if (!empty($errors)) {
            return;
        }

        $job = array(
            'data' => $this->submitted,
            'id' => $operation['job_id'],
            'total' => $this->submitted['filesize'],
            'redirect_message' => array(
                'finish' => 'Data has been successfully imported. Inserted: %inserted, updated: %updated'
            ),
        );

        if (!empty($operation['log']['errors'])) {
            $job['redirect_message']['errors'] = $this->text('Inserted: %inserted, updated: %updated, errors: %errors. <a href="!url">See error log</a>', array(
                '!url' => $this->url(false, array('download_errors' => 1))));
        }

        $this->job->submit($job);
    }

    /**
     * Validates import data
     */
    protected function validate()
    {
        $this->validateFile();
        $this->validateCsv();
    }

    /**
     * Validates uploaded CSV file
     * @return boolean
     */
    protected function validateFile()
    {
        $file = $this->request->file('file');

        if (empty($file)) {
            $this->data['form_errors']['file'] = $this->text('Required field');
            return false;
        }

        $this->file->setUploadPath('private/import')->setHandler('csv');

        if ($this->file->upload($file) !== true) {
            $this->data['form_errors']['file'] = $this->text('Unable to upload the file');
            return false;
        }

        $this->submitted['filepath'] = $this->file->getUploadedFile();
        $this->submitted['filesize'] = filesize($this->submitted['filepath']);
        return true;
    }

    /**
     * Validates data in the CSV file
     * @return boolean
     */
    protected function validateCsv()
    {
        $header_result = $this->import->validateCsvHeader($this->submitted['filepath'], $this->submitted['operation']);

        if ($header_result !== true) {
            $this->data['form_errors']['file'] = $header_result;
            return false;
        }

        return true;
    }

}
