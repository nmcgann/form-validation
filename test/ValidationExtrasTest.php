<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

  // --------------------------------------------------------------------------
  
class ValidationExtrasTest extends \PHPUnit_Framework_TestCase
{
  public $v;
  public $name = 'Libraries\FormValidate';
  
  public function setUp() 
  {
   $this->v = new Lib\FormValidate();
  
  }
  
  // --------------------------------------------------------------------------
  
  public function testReplaceMessages()
  {
   unset($this->v);  

   $msgs = array('required'=>'Special Custom Required Message %s');
   
   $this->v = new Lib\FormValidate($msgs);
   
   $this->assertInstanceOf($this->name,$this->v);

   //test that will fail
    $this->v->add_field('field1','','required');
    $res = $this->v->run(array('field1'=>''));

    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for message in string
    $this->assertRegExp('#Special Custom Required Message field1#i',$str,'custom message not returned correctly');

  }
  
  // --------------------------------------------------------------------------
  
  public function testReplaceMessagesMissing()
  {
   unset($this->v);  

   $msgs = array('required'=>'Special Custom Required Message %s');
   
   $this->v = new Lib\FormValidate($msgs);
   
   $this->assertInstanceOf($this->name,$this->v);

   //test that will fail and that don't have a message (we have zapped them)
    $this->v->add_field('field1','','alpha');
    $res = $this->v->run(array('field1'=>'0123'));

    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for message in string
    $this->assertRegExp('#No custom error message is set for \"alpha\" validating \"field1\" field.#i',$str,'default message not returned correctly');

  }
  // --------------------------------------------------------------------------
  

  // --------------------------------------------------------------------------
  public function TearDown() 
  {
   unset($this->v);
  
  }

  
} //EOC

/* end */
