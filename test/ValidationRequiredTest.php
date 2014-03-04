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
   * ValidationRequiredTest::testAddFieldNoAliasInvalidRule()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testAddFieldNoAliasInvalidRule()
  {
    //run test with a non-recognised rule - should throw error
    $res = $this->v->add_field('field1','','requiredX');

    $res = $this->v->run(array('field1'=>'val1'));

    //$this->assertFalse($res);

  }
  
  
  // --------------------------------------------------------------------------

  public function testAddFieldNoAliasRequiredRule()
  {
    unset($this->v);
    $this->v = new Lib\FormValidate();

    $res = $this->v->add_field('field1','','required');

    $this->assertInstanceOf($this->name,$res);

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasRequiredRuleFieldPresentNoData()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>''));

    $this->assertFalse($res);

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasRequiredRuleFieldPresentNoNonespaceData()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>'               '));

    $this->assertFalse($res);

  }

  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasRequiredRuleFieldPresentWithData()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>'val1'));

    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function testRunTwoFieldNoAliasRequiredRuleFieldPresentWithData()
  {
    //2nd field rule with no field in data (this was a bug as 2nd rule shouldn't run- now fixed)
    $this->v->add_field('field1','','required')
            ->add_field('field2','','alpha');
    $res = $this->v->run(array('field1'=>'val1'));

    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function testRunOneFieldNoAliasRequiredFailError()
  {

    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>''));

    $errors =  $this->v->get_all_errors_array();

    $this->assertTrue(is_array($errors));
    
    $this->assertTrue(count($errors) == 1);

    $msg = isset($errors['field1']) ? $errors['field1'] : '';

    $this->assertTrue($msg !== '');

    $this->assertTrue(stripos($msg,'field1') !== false);

  }
  
  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
    unset($this->v);
  
  }

  
} //EOC

/* end */
