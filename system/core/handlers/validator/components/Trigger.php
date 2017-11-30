<?php

/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */

namespace gplcart\core\handlers\validator\components;

// Parent
use gplcart\core\Config;
use gplcart\core\models\File as FileModel,
    gplcart\core\models\User as UserModel,
    gplcart\core\models\Store as StoreModel,
    gplcart\core\models\Alias as AliasModel,
    gplcart\core\helpers\Request as RequestHelper,
    gplcart\core\models\Language as LanguageModel;
// New
use gplcart\core\models\Trigger as TriggerModel,
    gplcart\core\models\Condition as ConditionModel;
use gplcart\core\handlers\validator\Component as ComponentValidator;

/**
 * Provides methods to validate trigger data
 */
class Trigger extends ComponentValidator
{

    /**
     * Condition model instance
     * @var \gplcart\core\models\Condition $condition
     */
    protected $condition;

    /**
     * Trigger model instance
     * @var \gplcart\core\models\Trigger $trigger
     */
    protected $trigger;

    /**
     * @param Config $config
     * @param LanguageModel $language
     * @param FileModel $file
     * @param UserModel $user
     * @param StoreModel $store
     * @param AliasModel $alias
     * @param RequestHelper $request
     * @param ConditionModel $condition
     * @param TriggerModel $trigger
     */
    public function __construct(Config $config, LanguageModel $language, FileModel $file,
            UserModel $user, StoreModel $store, AliasModel $alias, RequestHelper $request,
            ConditionModel $condition, TriggerModel $trigger)
    {
        parent::__construct($config, $language, $file, $user, $store, $alias, $request);

        $this->trigger = $trigger;
        $this->condition = $condition;
    }

    /**
     * Performs full trigger data validation
     * @param array $submitted
     * @param array $options
     * @return array|boolean
     */
    public function trigger(array &$submitted, array $options)
    {
        $this->options = $options;
        $this->submitted = &$submitted;

        $this->validateTrigger();
        $this->validateStatus();
        $this->validateName();
        $this->validateStoreId();
        $this->validateWeight();
        $this->validateConditionsTrigger();

        return $this->getResult();
    }

    /**
     * Validates a trigger to be updated
     * @return boolean
     */
    protected function validateTrigger()
    {
        $trigger_id = $this->getUpdatingId();

        if ($trigger_id === false) {
            return null;
        }

        $data = $this->trigger->get($trigger_id);

        if (empty($data)) {
            $this->setErrorUnavailable('update', $this->language->text('Trigger'));
            return false;
        }

        $this->setUpdating($data);
        return true;
    }

    /**
     * Validates and modifies trigger conditions
     * @return boolean|null
     */
    public function validateConditionsTrigger()
    {
        $field = 'data.conditions';
        $value = $this->getSubmitted($field);

        if ($this->isUpdating() && !isset($value)) {
            return null;
        }

        if (empty($value)) {
            $this->setErrorRequired($field, $this->language->text('Conditions'));
            return false;
        }

        $errors = $modified = array();
        $submitted = $this->getSubmitted();
        $operators = $this->condition->getOperators();
        $prepared_operators = array_map('htmlspecialchars', array_keys($operators));

        foreach ($value as $line => $condition) {

            $line++;
            list($condition_id, $operator, $parameters) = $this->getParameters($condition);

            if (empty($parameters)) {
                $errors[] = $this->language->text('Error on line @num: !error', array(
                    '@num' => $line,
                    '!error' => $this->language->text('No parameters')));
                continue;
            }

            if (!in_array(htmlspecialchars($operator), $prepared_operators)) {
                $errors[] = $this->language->text('Error on line @num: !error', array(
                    '@num' => $line,
                    '!error' => $this->language->text('Invalid operator')));
                continue;
            }

            $result = $this->callValidator($condition_id, array($parameters, $operator, $condition_id, $submitted));

            if ($result !== true) {
                $errors[] = $this->language->text('Error on line @num: !error', array('@num' => $line, '!error' => $result));
                continue;
            }

            $modified[] = array(
                'weight' => $line,
                'id' => $condition_id,
                'value' => $parameters,
                'operator' => $operator,
                'original' => "$condition_id $operator " . implode(',', $parameters),
            );
        }

        if (!empty($errors)) {
            $this->setError($field, implode('<br>', $errors));
        }

        if (!$this->isError()) {
            $this->setSubmitted($field, $modified);
            return true;
        }

        return false;
    }

    /**
     * Returns exploded and prepared condition parameters
     * @param string $string
     * @return array
     */
    protected function getParameters($string)
    {
        $parts = gplcart_string_explode_whitespace($string, 3);

        $condition_id = array_shift($parts);
        $operator = array_shift($parts);

        $parameters = array_filter(explode(',', implode('', $parts)), function ($value) {
            return ($value !== "");
        });

        return array($condition_id, $operator, array_unique($parameters));
    }

    /**
     * Call a validator handler
     * @param string $condition_id
     * @param array $args
     * @return mixed
     */
    protected function callValidator($condition_id, array $args)
    {
        try {
            $handlers = $this->condition->getHandlers();
            return static::call($handlers, $condition_id, 'validate', $args);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

}
