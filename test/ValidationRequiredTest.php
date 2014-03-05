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

    $this->assertTrue(is_array($errors),'not is_array error array');
    
    $this->assertTrue(count($errors) == 1,'not single error in array');

    $this->assertArrayHasKey('field1', $errors,'no error for field in array');
    
    //check for field name and error type in array string
    $this->assertRegExp('#\bfield1( | .* )required\b#i',$errors['field1'],'error message format error (array)');

    $str = $this->v->get_error_message('field1');
    
    //check for field name and error type in string
    $this->assertRegExp('#\bfield1( | .* )required\b#i',$str,'error message format error (string)');
    
    //check for delimiters at beginning and end of string
    $this->assertRegExp('#^<p>.*</p>$#i',$str,'error message format error (string delimiters)');

  }
  
  // --------------------------------------------------------------------------

  public function testRunOneFieldWithAliasRequiredFailError()
  {

    $this->v->add_field('field1','XXXX','required');
    $res = $this->v->run(array('field1'=>''));

    $errors =  $this->v->get_all_errors_array();

    //check for alias name and error type in array string
    $this->assertRegExp('#\bXXXX( | .* )required\b#i',$errors['field1'],'error message Alias format error (array)');

  }
    
  // --------------------------------------------------------------------------

  public function testSetCustomFieldErrorMessage()
  {

    $this->v->add_field('field1','','required');
    
    $this->v->add_error_message('required','custom message %s custom message');
    $res = $this->v->run(array('field1'=>''));

    $errors = $this->v->get_all_errors_array();

    $this->assertRegExp('#^custom message field1 custom message$#',$errors['field1']);
    
  }


  /**
   * ValidationRequiredTest::testSetCustomFieldErrorMessageFail1()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetCustomFieldErrorMessageFail1()
  {

    $this->v->add_error_message('','message');

  }

  /**
   * ValidationRequiredTest::testSetCustomFieldErrorMessageFail2()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetCustomFieldErrorMessageFail2()
  {

    $this->v->add_error_message('required','');

  }
  
  /**
   * ValidationRequiredTest::testSetCustomFieldErrorMessageFail3()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetCustomFieldErrorMessageFail3()
  {

    $this->v->add_error_message('required',1);

  }
  
  /**
   * ValidationRequiredTest::testSetCustomFieldErrorMessageFail4()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetCustomFieldErrorMessageFail4()
  {

    $this->v->add_error_message(1,'message');

  }

  // --------------------------------------------------------------------------

  public function testSetErrorTags()
  {

    $this->v->add_field('field1','','required');
    
    $this->v->add_error_message('required','custom message %s custom message');
    $this->v->set_error_tags('<li>','</li>');

    $res = $this->v->run(array('field1'=>''));

    $msg =  $this->v->get_error_message('field1');

    $this->assertRegExp('#^<li>custom message field1 custom message</li>$#',$msg);
    
  }
  
  // --------------------------------------------------------------------------

  /**
   * ValidationRequiredTest::testSetErrorTagsError1()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetErrorTagsError1()
  {
    $this->v->set_error_tags(1,'</li>');
    
  }

  /**
   * ValidationRequiredTest::testSetErrorTagsError2()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetErrorTagsError2()
  {
    $this->v->set_error_tags('<li>',1);
    
  }

  // --------------------------------------------------------------------------

  public function testSetErrorTagsEmpty()
  {
    $res = $this->v->set_error_tags('','');
    
    $this->assertInstanceOf($this->name,$res);
  }

  // --------------------------------------------------------------------------

  public function testErrorMessageWithNoError()
  {

    $this->v->add_field('field1','','required');
    
    $res = $this->v->run(array('field1'=>'value1'));

    $msg =  $this->v->get_error_message('field1');

    $this->assertTrue($msg === '','empty error message not empty string');
    
  }

  
  // --------------------------------------------------------------------------
  
  /**
   * ValidationRequiredTest::testSetDataFail1()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetDataFail1()
  {

    $this->v->set_data();

  }

  /**
   * ValidationRequiredTest::testSetDataFail2()
   * 
   * @expectedException PHPUnit_Framework_Error
   */
  public function testSetDataFail2()
  {

    $this->v->set_data(1);

  }
  
  // --------------------------------------------------------------------------

  public function testSetDataSucceed()
  {

    $o = $this->v->set_data(array('test1'=>'val1'));
    
    $this->assertInstanceOf($this->name,$o);
  }

  public function testGetDataSucceed()
  {
    $td = array('test1'=>'val1','test2'=>'val2','test3'=>'val3');
    $this->v->set_data($td);
    
    $res = $this->v->get_all_data();
     
    $this->assertTrue(count(array_diff_assoc($td,$res)) === 0,'set_data and get_all_data are different');

  }
  
  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
    unset($this->v);
  
  }

  
} //EOC

/* end */
