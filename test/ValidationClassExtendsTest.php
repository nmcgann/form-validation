<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

class CustomValidate extends Lib\FormValidate {
  
  protected function is_funny($str)
  {
    $this->add_error_message('is_funny','The %s field must contain humour.');
     
    return $str === 'is_funny';
  }  
}

  // --------------------------------------------------------------------------
  
class ValidationClassExtendsTest extends \PHPUnit_Framework_TestCase
{
  public $v;
  public $name = 'Libraries\FormValidate';
  
  public function setUp() 
  {
   $this->v = new CustomValidate();
  
  }
  
  // --------------------------------------------------------------------------
  
  public function testExtendsClassFail()
  {

   //test that will fail
    $this->v->add_field('field1','','is_funny');
    $res = $this->v->run(array('field1'=>'not_funny'));

    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for message in string
    $this->assertRegExp('#The field1 field must contain humour.#i',$str,'custom message not returned correctly');

  }
  
  // --------------------------------------------------------------------------
  
  public function testExtendsClassPass()
  {
    $this->v->add_field('field1','','is_funny');
    $res = $this->v->run(array('field1'=>'is_funny'));

    $this->assertTrue($res);

  }
  // --------------------------------------------------------------------------
   
  public function testExtendsClassReplaceTest()
  {

   //test that will fail
    $this->v->add_field('field1','','is_funny');
    $res = $this->v->run(array('field1'=>'not_funny'));

    $this->assertFalse($res);
    
   //replace the test for the field
    $this->v->add_field('field1','','alpha');
    $res = $this->v->run(array('field1'=>'abcdefghijklmnopqrstuvwxyz'));

    $this->assertTrue($res,'Failed to replace test rule');
    
}

  // --------------------------------------------------------------------------
  public function TearDown() 
  {
   unset($this->v);
  
  }
  
} //EOC

/* end */
