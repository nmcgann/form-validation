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

  public function test_is_numericFail()
  {

    $this->v->add_field('field1','','is_numeric');
    $res = $this->v->run(array('field1'=>'012abcd'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_is_numericPass()
  {

    $this->v->add_field('field1','','is_numeric');
    $res = $this->v->run(array('field1'=>'+0123.45e6'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_integerFail()
  {

    $this->v->add_field('field1','','integer');
    $res = $this->v->run(array('field1'=>'+1e6'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_integerPass()
  {

    $this->v->add_field('field1','','integer');
    $res = $this->v->run(array('field1'=>'+012345'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_decimalFail()
  {

    $this->v->add_field('field1','','decimal');
    $res = $this->v->run(array('field1'=>'+123.'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_decimalPass()
  {

    $this->v->add_field('field1','','decimal');
    $res = $this->v->run(array('field1'=>'+012.456'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_greater_thanFail()
  {

    $this->v->add_field('field1','','greater_than[5]');
    $res = $this->v->run(array('field1'=>'4.5'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_greater_thanPass()
  {

    $this->v->add_field('field1','','greater_than[5]');
    $res = $this->v->run(array('field1'=>'5.5'));
    //should pass
    $this->assertTrue($res);

  }
  // --------------------------------------------------------------------------

  public function test_less_thanFail()
  {

    $this->v->add_field('field1','','less_than[5]');
    $res = $this->v->run(array('field1'=>'5.5'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_less_thanPass()
  {

    $this->v->add_field('field1','','less_than[5]');
    $res = $this->v->run(array('field1'=>'4.5'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_is_naturalFail()
  {

    $this->v->add_field('field1','','is_natural');
    $res = $this->v->run(array('field1'=>'235.56'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_is_naturalPass()
  {

    $this->v->add_field('field1','','is_natural');
    $res = $this->v->run(array('field1'=>'44589'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_is_natural_no_zeroFail()
  {

    $this->v->add_field('field1','','is_natural_no_zero');
    $res = $this->v->run(array('field1'=>'0'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_is_natural_no_zeroPass()
  {

    $this->v->add_field('field1','','is_natural_no_zero');
    $res = $this->v->run(array('field1'=>'123'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_matches()
  {

    $this->v->add_field('field1','','matches[field2]');
    $this->v->add_field('field2','','');
    $res = $this->v->run(array('field1'=>'val1','field2'=>'val2'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name in standard error string
    $this->assertRegExp('#\bfield1\b#i',$str,'error message format error (string)');

  }

  public function test_matchesPass()
  {

    $this->v->add_field('field1','','matches[field2]');
    $res = $this->v->run(array('field1'=>'val1','field2'=>'val1'));
    //should pass
    $this->assertTrue($res);

  }
  
  // --------------------------------------------------------------------------

  public function test_matchesWithAlias()
  {

    $this->v->add_field('field1','The Field One','matches[field2]');
    $this->v->add_field('field2','','');
    $res = $this->v->run(array('field1'=>'val1','field2'=>'val2'));
    //should fail
    $this->assertFalse($res);

    $str = $this->v->get_error_message('field1');
    
    //check for field name alias in standard error string
    $this->assertRegExp('#\bThe Field One\b#i',$str,'error message format error - missing alias)');

  }
  
  // --------------------------------------------------------------------------

  public function test_trim()
  {
    
    $this->v->add_field('field1','','trim');
    $res = $this->v->run(array('field1'=>'    val1    '));
    //should pass
    $this->assertTrue($res);

    $data = $this->v->get_data('field1');

    $this->assertSame($data,'val1');
  
  }

  // --------------------------------------------------------------------------

  public function test_to_lower()
  {
    
    $this->v->add_field('field1','','to_lower');
    $res = $this->v->run(array('field1'=>' TEST UPPER CASE '));
    //should pass
    $this->assertTrue($res);

    $data = $this->v->get_data('field1');

    $this->assertSame($data,' test upper case ');
  
  }

  // --------------------------------------------------------------------------

  public function test_to_upper()
  {
    
    $this->v->add_field('field1','','to_upper');
    $res = $this->v->run(array('field1'=>' test upper case '));
    //should pass
    $this->assertTrue($res);

    $data = $this->v->get_data('field1');

    $this->assertSame($data,' TEST UPPER CASE ');
  
  }

  // --------------------------------------------------------------------------

  public function test_html_clean()
  {
    
    $this->v->add_field('field1','','html_clean');
    $res = $this->v->run(array('field1'=>'<>"\'&'));
    //should pass
    $this->assertTrue($res);

    $data = $this->v->get_data('field1');

    $this->assertSame($data,'&lt;&gt;&quot;&#039;&amp;');
  
  }

  // --------------------------------------------------------------------------
  
  public function TearDown() 
  {
   unset($this->v);
  
  }

  
} //EOC

/* end */
