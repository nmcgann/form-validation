<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

  // --------------------------------------------------------------------------
  
class ValidationBasicTest extends \PHPUnit_Framework_TestCase
{
  public $v;
  
  public function setUp() {
   $this->v = new Lib\FormValidate();
  
  }
  
  // --------------------------------------------------------------------------
  
  public function testCreateValidationObject()
  {
  
   $this->assertInstanceOf('Libraries\FormValidate',$this->v);
  }
  
  // --------------------------------------------------------------------------
  
  public function test_add_validation_ruleExistsInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'add_validation_rule'), 
      'Class does not have method add_validation_rule'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_add_error_messageExistsInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'add_error_message'), 
      'Class does not have method add_error_message'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_set_error_tagsExistsInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'set_error_tags'), 
      'Class does not have method set_error_tags'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_set_dataExistsInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'set_data'), 
      'Class does not have method set_data'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_get_all_dataInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'get_all_data'), 
      'Class does not have method get_all_data'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_get_dataInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'get_data'), 
      'Class does not have method get_data'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_get_all_errors_arrayInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'get_all_errors_array'), 
      'Class does not have method get_all_errors_array'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_get_error_messageInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'get_error_message'), 
      'Class does not have method get_error_message'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_add_fieldInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'add_field'), 
      'Class does not have method add_field'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_add_field_listInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'add_field_list'), 
      'Class does not have method add_field_list'
    );  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_runExistsInValidationObject()
  {
  
    $this->assertTrue(
      method_exists($this->v, 'run'), 
      'Class does not have method run'
    );  
  }
  
  // --------------------------------------------------------------------------

  public function testRunNoDataOrRulesValidationObject()
  {
    $this->assertTrue($this->v->run());
  }

  // --------------------------------------------------------------------------

  public function testRunEmptyDataNoRulesValidationObject()
  {
    $this->assertTrue($this->v->run(array()));
  }

  // --------------------------------------------------------------------------
  public function TearDown() {
   unset($this->v);
  
  }

  
} //EOC

/* end */
