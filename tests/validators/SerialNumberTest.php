<?php

class SerialNumberTest extends ControllerTestCase
{
    public function testRegexPatternIsValid()
    {
        // If the regex is invalid, an exception will be thrown on construction
        // And assertTrue won't be reached.
        $validator = new Recovery_Validators_SerialNumber();
        $this->assertTrue(true);
    }

    public function testSerialNumberWithOldItemNumberLengthIsValid()
    {
        $validator = new Recovery_Validators_SerialNumber();
        $testString = str_repeat('9', Recovery_Validators_SerialNumber::OLD_ITEM_NUMBER_LENGTH) . '-' . str_repeat('9', Recovery_Validators_SerialNumber::UNIQUE_ID_LENGTH);
        $this->assertTrue(
            $validator->isValid($testString),
            "Serial number $testString should be valid but isn't."
        );
    }

    public function testSerialNumberWithNewItemNumberLengthIsValid()
    {
        $validator = new Recovery_Validators_SerialNumber();
        $testString = str_repeat('9', Recovery_Validators_SerialNumber::ITEM_NUMBER_LENGTH) . '-' . str_repeat('9', Recovery_Validators_SerialNumber::UNIQUE_ID_LENGTH);
        $this->assertTrue(
            $validator->isValid($testString),
            "Serial number $testString should be valid but isn't."
        );
    }

    public function testSerialNumberWithBadItemNumberLengthIsNotValid()
    {
        $validator = new Recovery_Validators_SerialNumber();
        $testString = str_repeat('9', 5) . '-' . str_repeat('9', Recovery_Validators_SerialNumber::UNIQUE_ID_LENGTH);
        $this->assertFalse(
            $validator->isValid($testString),
            "Serial number $testString should be invalid but is valid."
        );
    }

    public function testSerialNumberWithBadSerialLengthIsNotValid()
    {
        $validator = new Recovery_Validators_SerialNumber();
        $testString = str_repeat('9', Recovery_Validators_SerialNumber::ITEM_NUMBER_LENGTH) . '-' . str_repeat('9', 2);
        $this->assertFalse(
            $validator->isValid($testString),
            "Serial number $testString should be invalid but is valid."
        );
    }

    public function testSerialNumberWithoutDashIsNotValid()
    {
        $validator = new Recovery_Validators_SerialNumber();
        $testString = str_repeat('9', Recovery_Validators_SerialNumber::ITEM_NUMBER_LENGTH) . str_repeat('9', Recovery_Validators_SerialNumber::UNIQUE_ID_LENGTH);
        $this->assertFalse(
            $validator->isValid($testString),
            "Serial number $testString should be invalid but is valid."
        );
    }

    public function testErrorMessageForNotMatch()
    {
        $validator = new Recovery_Validators_SerialNumber();
        $testString = str_repeat('9', Recovery_Validators_SerialNumber::ITEM_NUMBER_LENGTH) . str_repeat('9', Recovery_Validators_SerialNumber::UNIQUE_ID_LENGTH);
        $validator->isValid($testString);
        $messages = $validator->getMessages();
        $this->assertArrayHasKey(Recovery_Validators_SerialNumber::NOT_MATCH, $messages);
        $this->assertEquals(Recovery_Validators_SerialNumber::NOT_MATCH_MESSAGE, $validator->getMessages()[Recovery_Validators_SerialNumber::NOT_MATCH]);
    }
}
