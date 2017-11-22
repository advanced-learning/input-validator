<?php

namespace AdvancedLearning\InputValidator\Exceptions;
/**
 * An exception thrown when a model fails validation
 */
class InputValidationException extends \InvalidArgumentException
{
    /**
     * Array of messages, should be key value pair of fieldname => message
     *
     * @var array
     */
    protected $messages;

    /**
     * FormValidationException constructor.
     *
     * @param array          $messages Key/value pairs of fieldname => message.
     * @param integer        $code     Error code.
     * @param Exception|null $previous Passed through to Exception.
     */
    public function __construct(array $messages = [], $code = 0, Exception $previous = null)
    {
        $this->messages = $messages;

        // combine messages into a single message
        $message = '';
        $separator = '';

        foreach (array_values($messages) as $fieldMessages) {
            foreach ($fieldMessages as $m) {
                $message .= $separator . $m;
                $separator = '. ';
            }
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the array of messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get one error message for each field.
     *
     * @return array
     */
    public function getFieldMessages()
    {
        $messages = [];

        foreach ($this->messages as $field => $fieldMessages) {
            // combine messages into a single message
            $message = '';
            $separator = '';

            foreach ($fieldMessages as $m) {
                $message .= $separator . $m;
                $separator = '. ';
            }

            $messages[$field] = $message;
        }

        return $messages;
    }
}
