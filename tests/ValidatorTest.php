<?php

use AdvancedLearning\InputValidator\Exceptions\InputValidationException;
use AdvancedLearning\InputValidator\Input;
use AdvancedLearning\InputValidator\InputValidator;
use AdvancedLearning\InputValidator\Interfaces\MappableModel;
use Respect\Validation\Validator as v;

/**
 * Test validator
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testErrors()
    {
        $data = [
            'FirstName' => '',
            'LastName' => 'T',
            'DateOfBirth' => '2017-09-20'
        ];

        $validator = new TestInputValidator();
        $validator->setMessages([
            'notBlank' => 'Please enter a value'
        ]);

        try {
            $validator->valid($data);
        } catch (InputValidationException $e) {
            $errors = $e->getMessages();
            $fieldMessages = $e->getFieldMessages();
        }

        $this->assertEquals(4, count($errors), 'There should be four errors');
        $this->assertNotEmpty($errors['FirstName'], 'FirstName should be in validation errors');
        $this->assertEquals(2, count($errors['LastName']), 'LastName should have 2 errors');
        $this->assertTrue(is_string($fieldMessages['LastName']), 'Last Name should have a single message');
        $this->assertEquals(
            $errors['FirstName'][0],
            'Please enter a value',
            'Not blank message should have been updated'
        );
    }

    public function testMappableModel()
    {
        $model = new MappableObject();
        $validator = new TestInputValidator();

        try {
            $validator->validateModel($model);
        } catch (InputValidationException $e) {
            $errors = $e->getMessages();
        }

        $this->assertEquals(4, count($errors), 'There should be four errors');
    }

    public function testInput()
    {
        $data = [
            'FirstName' => '',
            'LastName' => 'T',
            'DateOfBirth' => '2017-09-20'
        ];

        $input = Input::pick($data, ['FirstName']);
        $newData = $input->getData();

        $this->assertEquals(1, count($newData), 'Data should only have 1 values');
        $this->assertTrue(isset($newData['FirstName']), 'Only FirstName key should be present');

        try {
            $input->validate(new TestInputValidator());
        } catch (InputValidationException $e) {
            $errors = $e->getMessages();
        }

        $this->assertEquals(4, count($errors), 'Should have 4 errors');
    }

}

class TestInputValidator extends InputValidator
{
    /**
     * @inheritdoc
     */
    public function getRules()
    {
        return [
            'FirstName' => v::notBlank()->setName('First Name'),
            'LastName' => v::notBlank()
                ->lowercase()
                ->length(2)
                ->setName('Last Name'),
            'NHI' => v::notBlank()->setName('NHI'),
            'DateOfBirth' => v::date('Y-m-d')
                ->notEmpty()
                ->between('1900-01-01', (new \DateTime('2017-08-01'))->format('Y-m-d'))
                ->setName('Date of Birth')
        ];
    }
}

class MappableObject implements MappableModel
{
    public function toMap()
    {
        return [
            'FirstName' => '',
            'LastName' => 'T',
            'DateOfBirth' => '2017-09-20'
        ];
    }
}