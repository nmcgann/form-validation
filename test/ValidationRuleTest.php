<?php
namespace Test;
use \Libraries as Lib;
//Phpunit tests for validation class

  // --------------------------------------------------------------------------
  
class ValidationRuleTest extends \PHPUnit_Framework_TestCase
{
  public $v;
  public $name = 'Libraries\FormValidate';
  
  public function setUp() 
  {
   $this->v = new Lib\FormValidate();
  
  }
  
  // --------------------------------------------------------------------------
  
  public function test_is_setFail()
  {

    $this->v->add_field('field1','','is_set');
    $res = $this->v->run(array('field2'=>''));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_is_setPass()
  {

    $this->v->add_field('field1','','is_set');
    $res = $this->v->run(array('field1'=>''));
    //should pass
    $this->assertTrue($res);

  }
  // --------------------------------------------------------------------------
  
  public function test_valid_emailFail()
  {

    $this->v->add_field('field1','','valid_email');
    $res = $this->v->run(array('field1'=>'fred@example.com.'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_valid_emailPass()
  {

    $this->v->add_field('field1','','valid_email');
    $res = $this->v->run(array('field1'=>'fred@example.com'));
    //should pass
    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function test_valid_urlFail()
  {

    $this->v->add_field('field1','','valid_url');
    $res = $this->v->run(array('field1'=>'http://www%example.com'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_valid_urlPass()
  {

    $this->v->add_field('field1','','valid_url');
    $res = $this->v->run(array('field1'=>'http://www.example.com'));
    //should pass
    $this->assertTrue($res);

  }

  // --------------------------------------------------------------------------

  public function test_valid_ipFail()
  {

    $this->v->add_field('field1','','valid_ip');
    $res = $this->v->run(array('field1'=>'129.168.256.199'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_valid_ipPass()
  {

    $this->v->add_field('field1','','valid_ip');
    $res = $this->v->run(array('field1'=>'123.254.200.1'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_valid_base64Fail()
  {

    $this->v->add_field('field1','','valid_base64');
    $res = $this->v->run(array('field1'=>'Y3Jvd3NhbmRmaXNoYm90aGVhdHNvY2tz.'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_valid_base64Pass()
  {

    $this->v->add_field('field1','','valid_base64');
    $res = $this->v->run(array('field1'=>'Y3Jvd3NhbmRmaXNoYm90aGVhdHNvY2tz'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_min_lengthFail()
  {

    $this->v->add_field('field1','','min_length[5]');
    $res = $this->v->run(array('field1'=>'0123'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_min_lengthPass()
  {

    $this->v->add_field('field1','','min_length[5]');
    $res = $this->v->run(array('field1'=>'01234'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_max_lengthFail()
  {

    $this->v->add_field('field1','','max_length[5]');
    $res = $this->v->run(array('field1'=>'012345'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_max_lengthPass()
  {

    $this->v->add_field('field1','','max_length[5]');
    $res = $this->v->run(array('field1'=>'01234'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_exact_lengthFailOver()
  {

    $this->v->add_field('field1','','exact_length[5]');
    $res = $this->v->run(array('field1'=>'012345'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }
  
  public function test_exact_lengthFailUnder()
  {

    $this->v->add_field('field1','','exact_length[5]');
    $res = $this->v->run(array('field1'=>'0123'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_exact_lengthPass()
  {

    $this->v->add_field('field1','','exact_length[5]');
    $res = $this->v->run(array('field1'=>'01234'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_alphaFail()
  {

    $this->v->add_field('field1','','alpha');
    $res = $this->v->run(array('field1'=>'0123456789'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_alphaPass()
  {

    $this->v->add_field('field1','','alpha');
    $res = $this->v->run(array('field1'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_alpha_numericFail()
  {

    $this->v->add_field('field1','','alpha_numeric');
    $res = $this->v->run(array('field1'=>':|\\,.<>?/@~#!"£$%^&*()_-+={}[]'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_alpha_numericPass()
  {

    $this->v->add_field('field1','','alpha_numeric');
    $res = $this->v->run(array('field1'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_alpha_dashFail()
  {

    $this->v->add_field('field1','','alpha_dash');
    $res = $this->v->run(array('field1'=>':|\\,.<>?/@~#!"£$%^&*()+={}[]'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_alpha_dashPass()
  {

    $this->v->add_field('field1','','alpha_dash');
    $res = $this->v->run(array('field1'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_numericFail1()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'+abc'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }
  
  public function test_numericFail2()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'+-1'));
    //should fail
    $this->assertFalse($res);

  }

  public function test_numericFail3()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'+1..2'));
    //should fail
    $this->assertFalse($res);

  }

  public function test_numericFail4()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'+1.'));
    //should fail
    $this->assertFalse($res);

  }

  public function test_numericPass1()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'-01234.56789'));
    //should pass
    $this->assertTrue($res);

  }
  
  public function test_numericPass2()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'+0123456789'));
    //should pass
    $this->assertTrue($res);

  }
  
  public function test_numericPass3()
  {

    $this->v->add_field('field1','','numeric');
    $res = $this->v->run(array('field1'=>'.0123456789'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------
  public function TearDown() 
  {
   unset($this->v);
  
  }

  
} //EOC

/* end */
