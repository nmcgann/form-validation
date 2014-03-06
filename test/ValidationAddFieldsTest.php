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
   * @expectedExceptionCode 256
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
   * @expectedExceptionCode 256
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
   * @expectedExceptionCode 256
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

  /**
   * ValidationAddFieldsTest::testAddFieldListWithError()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddFieldListWithError()
  {

    $this->v->add_field_list('a string');


  }
    
  // --------------------------------------------------------------------------
  
  public function testAddFieldListWithNoErrorInArray()
  {

    $data = array();
    
    $o = $this->v->add_field_list($data);

    $this->assertInstanceOf($this->name,$o);

  }
  
  // --------------------------------------------------------------------------

  /**
   * ValidationAddFieldsTest::testAddFieldListWithErrorInArray1()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddFieldListWithErrorInArray1()
  {

    $data = array(array('field'=>'test1','alias'=>''/*,'rule_list'=>''*/));
    
    $o = $this->v->add_field_list($data);

  }

  /**
   * ValidationAddFieldsTest::testAddFieldListWithErrorInArray2()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddFieldListWithErrorInArray2()
  {

    $data = array(array('field'=>'test1'/*,'alias'=>''*/,'rule_list'=>''));
    
    $o = $this->v->add_field_list($data);

  }
  
  /**
   * ValidationAddFieldsTest::testAddFieldListWithErrorInArray3()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddFieldListWithErrorInArray3()
  {

    $data = array(array(/*'field'=>'test1',*/'alias'=>'','rule_list'=>''));
    
    $o = $this->v->add_field_list($data);

  }
  
  /**
   * ValidationAddFieldsTest::testAddFieldListWithErrorInArray4()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddFieldListWithErrorInArray4()
  {

    $data = array(array(1));
    
    $o = $this->v->add_field_list($data);

  }

  // --------------------------------------------------------------------------
  
  public function testAddFieldListTwoFields()
  {

    $data = array(array('field'=>'test1','alias'=>'','rule_list'=>''),
                  array('field'=>'test2','alias'=>'','rule_list'=>'')
            );
    
    $o = $this->v->add_field_list($data);
    
    $this->assertInstanceOf($this->name,$o);

  }
 
  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
    unset($this->v);
  
  }

  
} //EOC

/* end */
