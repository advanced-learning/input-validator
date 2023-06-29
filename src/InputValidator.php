<?php

namespace AdvancedLearning\InputValidator;

use AdvancedLearning\InputValidator\Exceptions\InputValidationException;
use AdvancedLearning\InputValidator\Interfaces\MappableModel;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;

/**
 * An interface for creating a validator for a specific model.
 */
abstract class InputValidator
{
    /**
     * Customise the messages returned.
     *
     * @var array
     */
    protected $messages = [
    ];

    /**
     * Returns array of rules of the format field => rule.
     *
     * @return array
     */
    abstract public function getRules();

    /**
     * Validate an array of data.
     *
     * @param array $data The array of data to validate.
     * @return boolean
     * @throws InputValidationException If validation fails.
     */
    public function valid(array $data)
    {
        $rules = $this->getRules();
        $messages = [];

        foreach ($rules as $field => $rule) {
            try {
                $rule->assert(isset($data[$field]) ? $data[$field] : null);
            } catch (NestedValidationException $e) {
                // get individual error messages
                foreach ($e as $validationException) {
                    $this->formatMessage($validationException);
                    $messages[$field][] = $validationException->getMessage();
                }
            }
        }

        if (!empty($messages)) {
            throw new InputValidationException($messages);
        }

        return true;
    }

    /**
     * Validate an instance of a model.
     *
     * @param MappableModel $model The model to validate.
     * @return mixed
     */
    public function validateModel(MappableModel $model)
    {
        return $this->valid($model->toMap());
    }

    /**
     * Set custom templates for messages.
     *
     * @param array $messages
     *
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Sets the message template on the exception if it has been configured.
     *
     * @param ValidationException $e The exception to add message to.
     *
     * @return static
     */
    protected function formatMessage(ValidationException $e)
    {
        if (preg_match("/\\\([^\\\]+)$/", get_class($e), $matches)) {
            $shortClassName = $matches[1];

            if (preg_match("/^(.+?)Exception$/", $shortClassName, $matches)) {
                $type = lcfirst($matches[1]);

                if (!empty($this->messages[$type])) {
                    $e->updateTemplate($this->messages[$type]);
                }
            }
        }

        return $this;
    }
}
