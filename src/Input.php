<?php

namespace AdvancedLearning\InputValidator;

/**
 * Provides functions for handling input data
 */
class Input
{
    /**
     * Key/value array of data.
     *
     * @var array
     */
    protected $data;

    /**
     * Set the data.
     *
     * @param array $data Array of key/value pairs.
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Gets the data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Pick only the keys wanted in a key/value array.
     *
     * @param array $data         Key/value pairs.
     * @param array $fieldsToPick The keys to keep in the array.
     *
     * @return Input
     */
    public static function pick(array $data, array $fieldsToPick)
    {
        $input = new Input();
        $data = array_intersect_key($data, array_combine($fieldsToPick, $fieldsToPick));
        return $input->setData($data);
    }

    /**
     * Validate the input data.
     *
     * @param InputValidator $validator Validator to use to validate data.
     * @return static
     */
    public function validate(InputValidator $validator)
    {
        $validator->valid($this->data);
        return $this;
    }
}
