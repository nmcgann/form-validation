<?php
/**
 * FormValidate - Tests
 * 
 * Phpunit tests for validation class
 * 
 */
namespace Test;
use \Libraries as Lib;

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
    
  public function testMultipleRulesAndFields()
  {

  $this->v->add_field('test1','Field 1','required|trim|alpha_numeric')
  	->add_field('test2','Field 2','required|trim|alpha_numeric')
  	->add_field('test3','Field 3','required|trim|alpha_numeric')
  	->add_field('test4','Field 4','required|trim|alpha_numeric')
  	->add_field('test5','Field 5','required|trim|alpha_numeric')
  	->add_field('test6','Field 6','required|trim|alpha_numeric')
  	->add_field('test7','Field 7','required|trim|alpha_numeric')
  	->add_field('test8','Field 8','required|trim|alpha_numeric')
  	->add_field('test9','Field 9','required|trim|alpha_numeric')
  	->add_field('test10','Field 10','required|trim|alpha_numeric')
  	->add_field('test11','Field 11','required|trim|alpha_numeric')
  	->add_field('test12','Field 12','required|trim|alpha_numeric')
  	->add_field('test13','Field 13','required|trim|alpha_numeric')
  	->add_field('test14','Field 14','required|trim|alpha_numeric')
  	->add_field('test15','Field 15','required|trim|alpha_numeric')
  	->add_field('test16','Field 16','required|trim|alpha_numeric')
  	->add_field('test17','Field 17','required|trim|alpha_numeric')
  	->add_field('test18','Field 18','required|trim|alpha_numeric')
  	->add_field('test19','Field 19','required|trim|alpha_numeric')
  	->add_field('test20','Field 20','required|trim|alpha_numeric');

    $td = array(	'test1'=>' abc&ef ',
            'test2'=>' abc&ef ',
            'test3'=>' abc&ef ',
            'test4'=>' abc&ef ',
            'test5'=>' abc&ef ',
            'test6'=>' abc&ef ',
            'test7'=>' abc&ef ',
            'test8'=>' abc&ef ',
            'test9'=>' abc&ef ',
            'test10'=>' abc&ef ',
            'test11'=>' abc&ef ',
            'test12'=>' abc&ef ',
            'test13'=>' abc&ef ',
            'test14'=>' abc&ef ',
            'test15'=>' abc&ef ',
            'test16'=>' abc&ef ',
            'test17'=>' abc&ef ',
            'test18'=>' abc&ef ',
            'test19'=>' abc&ef ',
            'test20'=>' abc&ef '
          );

    $this->v->set_data($td);

    $res = $this->v->run();

    $this->assertFalse($res);

    $a = $this->v->get_all_errors_array();

    $this->assertTrue(count($a) == 20,'Incorrect number of errors.');

    //remove errors from data array and re-run
    array_walk($td,function(&$val){ $val = str_replace('&','',$val);});

    $this->v->set_data($td);
    
    $res = $this->v->run();

    $this->assertTrue($res);

    $a = $this->v->get_all_errors_array();
    
    $this->assertTrue(count($a) == 0,'Should be zero errors.');

  }

  // --------------------------------------------------------------------------

  public function testArrayWithEmptyRule()
  {

    $this->v->add_field('test','','');
    $this->v->set_data(array('test'=>array('0'=>'val1','1'=>'val2','2'=>'val3',)));

    $res = $this->v->run();
    
    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function testArrayWithRules()
  {
    $this->v->add_field('test','','required');
    
    $this->v->set_data(array('test'=>array('0'=>'val1','1'=>'','2'=>'',))); //fail

    $res = $this->v->run();
    $this->assertFalse($res);
    
    $this->v->set_data(array('test'=>array('0'=>'','1'=>'','2'=>'',))); //fail
    $res = $this->v->run();
    $this->assertFalse($res);
    
    $this->v->set_data(array('test'=>array())); //fail
    $res = $this->v->run();
    $this->assertFalse($res);

    $this->v->set_data(array());
    $res = $this->v->run();
    $this->assertFalse($res);
    
    $this->v->set_data(array('test'=>array('val'=>1))); //pass
    $res = $this->v->run();
    $this->assertTrue($res);
  }

  // --------------------------------------------------------------------------

  public function testArrayWithRulesMore()
  {
    $this->v->add_field('test','','required|trim');
    
    $this->v->set_data(array('test'=>array('0'=>'    ','1'=>'     ','2'=>'    ',))); //fail
    $res = $this->v->run();
    $this->assertFalse($res);

    $this->v->set_data(array('test'=>array('0'=>'  val1  ','1'=>'     ','2'=>'    ',))); //fail
    $res = $this->v->run();
    $this->assertFalse($res);

    $this->v->set_data(array('test'=>array('0'=>'  val1  ','1'=>'  val2   ','2'=>'  val3  ',))); //pass
    $res = $this->v->run();
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function testArrayWithRulesFurther()
  {

    $this->v->add_field('test','','is_set');
    
    $this->v->set_data(array('test'=>array('0'=>'','1'=>'','2'=>'',))); //pass
    $res = $this->v->run();
    $this->assertTrue($res);

    $this->v->set_data(array('test'=>array())); //fail
    $res = $this->v->run();
    $this->assertFalse($res);

    $this->v->set_data(array()); //fail
    $res = $this->v->run();
    $this->assertFalse($res);

  }

  // --------------------------------------------------------------------------
  public function TearDown() 
  {
   unset($this->v);
  
  }

  
} //EOC

/* end */
