<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

//simple test function in global scope

function custom_test_fn ($obj,$str)
{
    return $str === 'a_valid_value';
  
}

//simple test class with static function

class test_class {

  public static function custom_test_method ($obj,$str)
  {
    return $str === 'a_valid_value';
    
  }  
}

  // --------------------------------------------------------------------------
  
class ValidationAddRule extends \PHPUnit_Framework_TestCase
{
  public $v;
  public $name = 'Libraries\FormValidate';
  
  public function setUp() 
  {
   $this->v = new Lib\FormValidate();

  }
  
  // --------------------------------------------------------------------------

  /**
   * ValidationAddRule::testAddRuleWithErrorInFormat1()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithErrorInFormat1()
  {
    $this->v->add_validation_rule(null,null);
    
    $this->assertTrue(false); //shouldn't get here

  }

  /**
   * ValidationAddRule::testAddRuleWithErrorInFormat2()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithErrorInFormat2()
  {
    $this->v->add_validation_rule(1,5);
    
    $this->assertTrue(false); //shouldn't get here

  }
  
  /**
   * ValidationAddRule::testAddRuleWithErrorInFormat3()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithErrorInFormat3()
  {
    $this->v->add_validation_rule('','');
    
    $this->assertTrue(false); //shouldn't get here

  }
  
  /**
   * ValidationAddRule::testAddRuleWithErrorInFormat4()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithErrorInFormat4()
  {
    //name clash
    $this->v->add_validation_rule('required','Test\custom_test_fn');
    
    $this->assertTrue(false); //shouldn't get here

  }
  
  /**
   * ValidationAddRule::testAddRuleWithErrorNotCallable()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithErrorNotCallable()
  {
    //not callable
    $this->v->add_validation_rule('test_rule','THIS_IS_NOT_A_CALLABLE_FUNCTION');
    
    $this->assertTrue(false); //shouldn't get here

  }

  /**
   * ValidationAddRule::testAddRuleWithErrorNotCallable2()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithErrorNotCallable2()
  {
    //not callable
    $this->v->add_validation_rule('a_new_rule',array($this,'xxrequiredxx'));
 
    $this->assertTrue(false);

  }
  
  /**
   * ValidationAddRule::testAddRuleWithErrorInFormat5()
   * 
   * @expectedException PHPUnit_Framework_Error
   * @expectedExceptionCode 256
   */
  public function testAddRuleWithWithErrorInFormat5()
  {
    //name clash
    $this->v->add_validation_rule('required',function(){});
 
    $this->assertTrue(false);

  }
  
  // --------------------------------------------------------------------------
  
  public function testAddRuleSimpleClosure()
  {
    
    $o = $this->v->add_validation_rule('custom_test',function($obj,$str){
      return $str === 'a_valid_value';
    });
 
    $this->assertInstanceOf($this->name,$o);

  }
  
  // --------------------------------------------------------------------------
  
  public function testAddRuleMethod()
  {
    
    $o = $this->v->add_validation_rule('custom_test',array($this,'custom_test'));
 
    $this->assertInstanceOf($this->name,$o);

  }

  public function custom_test ($obj,$str)
  {
      return $str === 'a_valid_value';
    
  }
  
  // --------------------------------------------------------------------------
  
  public function testAddRuleStaticMethod()
  {
    
    $o = $this->v->add_validation_rule('custom_test',array('Test\test_class','custom_test_method'));
 
    $this->assertInstanceOf($this->name,$o);

  }

  // --------------------------------------------------------------------------
  
  public function testAddRuleFunction()
  {
    
    $this->assertTrue(is_callable('Test\custom_test_fn'),'not callable');
    
    $o = $this->v->add_validation_rule('test_rule','Test\custom_test_fn');
   
    $this->assertInstanceOf($this->name,$o);

  }
  
  // --------------------------------------------------------------------------
 
  public function testAddRuleSimpleClosureAndCall()
  {
    
    $o = $this->v->add_validation_rule('custom_test',function($obj,$str){
      return $str === 'a_valid_value';
    });
 
    $this->v->add_field('field1','','custom_test');
    
    $res = $this->v->run(array('field1'=>'value1'));
    
    $this->assertFalse($res,'validation passed, should have failed');
 
    $res = $this->v->run(array('field1'=>'a_valid_value'));

    $this->assertTrue($res,'validation failed, should have passed');
   
    $res = $this->v->run(array('field1'=>'value1')); //fail
    
    $this->assertFalse($res,'validation passed, should have failed');

    $msg =  $this->v->get_error_message('field1');

    $this->assertRegExp('#No custom error message is set for "custom_test" validating "field1" field.#',$msg,'error message not generic format');

  }
 
  // --------------------------------------------------------------------------
 
  public function testAddRuleSimpleClosureAndCallWithMessage()
  {
    
    $o = $this->v->add_validation_rule('custom_test',function($obj,$str){
    
       $obj->add_error_message('custom_test','The %s field must contain a_valid_value.');
       
      return $str === 'a_valid_value';
    });

    $this->v->add_field('field1','FIELD NUMBER 1','custom_test'); //with alias
    
    $res = $this->v->run(array('field1'=>'value1'));
    
    $this->assertFalse($res,'validation passed, should have failed');
 
    $msg = $this->v->get_error_message('field1');

    $this->assertRegExp('#The FIELD NUMBER 1 field must contain a_valid_value.#',$msg,'error message not correct custom format');
 
   }
  // --------------------------------------------------------------------------
 
  public function testAddRuleSimpleFunctionAndCall()
  {
    
    $o = $this->v->add_validation_rule('test_fn','Test\custom_test_fn');
 
    $this->v->add_field('field1','','test_fn');
    
    $res = $this->v->run(array('field1'=>'value1'));
    
    $this->assertFalse($res,'validation passed, should have failed');
 
    $res = $this->v->run(array('field1'=>'a_valid_value'));

    $this->assertTrue($res,'validation failed, should have passed');
   
    $res = $this->v->run(array('field1'=>'value1')); //fail
    
    $this->assertFalse($res,'validation passed, should have failed');

    $msg =  $this->v->get_error_message('field1');

    $this->assertRegExp('#No custom error message is set for "test_fn" validating "field1" field.#',$msg,'error message not generic format');

  }
  // --------------------------------------------------------------------------
 
  public function testAddRuleMethodAndCall()
  {
    
    $o = $this->v->add_validation_rule('custom_test',array($this,'custom_test'));
 
    $this->v->add_field('field1','','custom_test');
    
    $res = $this->v->run(array('field1'=>'value1'));
    
    $this->assertFalse($res,'validation passed, should have failed');
 
    $res = $this->v->run(array('field1'=>'a_valid_value'));

    $this->assertTrue($res,'validation failed, should have passed');
   
    $res = $this->v->run(array('field1'=>'value1')); //fail
    
    $this->assertFalse($res,'validation passed, should have failed');

    $msg =  $this->v->get_error_message('field1');

    $this->assertRegExp('#No custom error message is set for "custom_test" validating "field1" field.#',$msg,'error message not generic format');
    
  }    
  // --------------------------------------------------------------------------
 
  public function testAddRuleStaticMethodAndCall()
  {
    
    $o = $this->v->add_validation_rule('custom_test',array('Test\test_class','custom_test_method'));
 
    $this->v->add_field('field1','','custom_test');
    
    $res = $this->v->run(array('field1'=>'value1'));
    
    $this->assertFalse($res,'validation passed, should have failed');
 
    $res = $this->v->run(array('field1'=>'a_valid_value'));

    $this->assertTrue($res,'validation failed, should have passed');
   
    $res = $this->v->run(array('field1'=>'value1')); //fail
    
    $this->assertFalse($res,'validation passed, should have failed');

    $msg =  $this->v->get_error_message('field1');

    $this->assertRegExp('#No custom error message is set for "custom_test" validating "field1" field.#',$msg,'error message not generic format');
    
  }    
  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
   unset($this->v);
  
  }

  
} //EOC


/* end */
