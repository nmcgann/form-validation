<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

  // --------------------------------------------------------------------------
  
class ValidationRequiredTest extends \PHPUnit_Framework_TestCase
{
  public $v;
  public $name = 'Libraries\FormValidate';
  
  public function setUp() 
  {
    $this->v = new Lib\FormValidate();
  
  }
  
  // --------------------------------------------------------------------------

  /**
   * ValidationRequiredTest::testAddFieldInvalidRule()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testAddFieldInvalidRule()
  {
    //run test with a non-recognised rule - should throw error
    $res = $this->v->add_field('field1','','requiredX');

    $res = $this->v->run(array('field1'=>'val1'));

    //$this->assertFalse($res);

  }
  
  
  // --------------------------------------------------------------------------

  public function testAddFieldRequiredRule()
  {
    unset($this->v);
    $this->v = new Lib\FormValidate();

    $res = $this->v->add_field('field1','','required');

    $this->assertInstanceOf($this->name,$res);

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldRequiredRuleFieldPresentNoData()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>''));

    $this->assertFalse($res);

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldRequiredRuleFieldPresentNoNonespaceData()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>'               '));

    $this->assertFalse($res);

  }

  // --------------------------------------------------------------------------

  public function testRunOneFieldRequiredRuleFieldPresentWithData()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>'val1'));

    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function testRunOneFieldNonRequiredRuleFieldNotPresent()
  {
    //non-required rule with no field in data (this was a bug - now fixed)
    $this->v->add_field('field1','','alpha');
    $res = $this->v->run();

    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function testRunOneFieldRequiredFailError()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>''));

    $errors =  $this->v->get_all_errors_array();

    $this->assertTrue(is_array($errors),'not error array');
    
    $this->assertTrue(count($errors) == 1,'not single error');

    $msg = isset($errors['field1']) ? $errors['field1'] : '';

    $this->assertTrue($msg !== '','error empty string','no error message');
    //chedk for field name and error type
    $this->assertRegExp('#\bfield1( | .* )required\b#i',$msg,'error message format error');

  }
  
  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
    unset($this->v);
  
  }

  
} //EOC

/* end */
