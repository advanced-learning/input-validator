<?php

use AdvancedLearning\InputValidator\Exceptions\InputValidationException;
use AdvancedLearning\InputValidator\InputValidator;
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
            'LastName' => '',
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
        }

        $this->assertEquals(4, count($errors), 'There should be four errors');
        $this->assertNotEmpty($errors['FirstName'], 'FirstName should be in validation errors');
        $this->assertEquals(
            $errors['FirstName'][0],
            'Please enter a value',
            'Not blank message should have been updated'
        );
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
            'LastName' => v::notBlank()->setName('Last Name'),
            'NHI' => v::notBlank()->setName('NHI'),
            'DateOfBirth' => v::date('Y-m-d')
                ->notEmpty()
                ->between('1900-01-01', (new \DateTime('2017-08-01'))->format('Y-m-d'))
                ->setName('Date of Birth')
        ];
    }
}
