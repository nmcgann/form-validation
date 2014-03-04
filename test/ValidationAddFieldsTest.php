<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

  // --------------------------------------------------------------------------
  
class ValidationAddFieldsTest extends \PHPUnit_Framework_TestCase
{
  public $v;
  public $name = 'Libraries\FormValidate';
  
  public function setUp() 
  {
    $this->v = new Lib\FormValidate();
  
  }
  
  // --------------------------------------------------------------------------
  
  public function testCreateValidationObject()
  {
  
   $this->assertInstanceOf($this->name,$this->v);
  }
  
  // --------------------------------------------------------------------------

  public function testAddEmptyField()
  {
    //should return $this and no error
    $this->assertInstanceOf($this->name,$this->v->add_field(''));

  }

  // --------------------------------------------------------------------------

  /**
   * ValidationAddFieldsTest::testAddInvalidField()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testAddInvalidField()
  {
    //should fail
    $this->assertInstanceOf($this->name,$this->v->add_field(0));

  }

  /**
   * ValidationAddFieldsTest::testAddInvalidAlias()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testAddInvalidAlias()
  {
    //should fail
    $this->assertInstanceOf($this->name,$this->v->add_field('',0));

  }
  
  /**
   * ValidationAddFieldsTest::testAddInvalidRuleList()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testAddInvalidRuleList()
  {
    //should fail
    $this->assertInstanceOf($this->name,$this->v->add_field('','',0));

  }
  
  // --------------------------------------------------------------------------

  public function testAddFieldNoAliasOrRule()
  {

    $res = $this->v->add_field('field1','','');

    $this->assertInstanceOf($this->name,$res);

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasOrRuleOrData()
  {

    $this->v->add_field('field1','','');
    $res = $this->v->run();

    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasOrRuleFieldPresentInData()
  {

    $this->v->add_field('field1','','');
    $res = $this->v->run(array('field1'=>''));

    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasOrRuleFieldPresentInDataWithValue()
  {

    $this->v->add_field('field1','','');
    $res = $this->v->run(array('field1'=>'value1'));

    $this->assertTrue($res);

  }
  
  
  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
    unset($this->v);
  
  }

  
} //EOC

/* end */
