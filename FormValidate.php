<?php
/**
 * Validation class based on the interface from codeigniter. An original piece of work otherwise :-) 
 * Supports single-nested arrays (e.g. for checkbox arrays)
 *  
 */
namespace Libraries;

defined('FCPATH') OR die('No direct script access.');

/**
 * FormValidate
 * 
 * Validation class. Patterned on the codeigniter one. 
 * 
 * Limitation is this doesn't work on nested arrays more than 1 level deep.
 * Otherwise full-featured and can use functions, lambda functions and class methods (of static
 * or instantiated classes) as custom callbacks.
 * 
 * The "regex" method and "is_unique" aren't implemented as the [] characters are used in the rule
 * string and this limits the regext too much. is_unique assumes knowledge about databases is in the
 * validation class which isn't appropriate. These are very easy to do with lambdas.
 * 
 * @package form-validation
 * @author Neil McGann
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class FormValidate {

  //validation rules. Is an array of field name, field alias and then another array of routines and parameters
  //to be applied to each field.
  protected $rules = array();
  //copy of the data to validate
  protected $data = array();
  //errors
  protected $errors = array();
  //error messages for validation routines
  protected $error_msgs = array(
    /* Default Messages mirroring CodeIgniter's for familiarity. Can be over-written if required */
    'required'            => "The %s field is required.",
    'is_set'              => "The %s field must be present.", 
    'valid_email'         => "The %s field must contain a valid email address.",
    'valid_url'           => "The %s field must contain a valid URL.",
    'valid_ip'            => "The %s field must contain a valid IP.",
    'valid_base64'        => "The %s field must contain only valid Base64 characters.",
    'min_length'          => "The %s field must be at least %s characters in length.",
    'max_length'          => "The %s field can not exceed %s characters in length.",
    'exact_length'        => "The %s field must be exactly %s characters in length.",
    'alpha'               => "The %s field may only contain alphabetical characters.",
    'alpha_numeric'       => "The %s field may only contain alpha-numeric characters.",
    'alpha_dash'          => "The %s field may only contain alpha-numeric characters, underscores, and dashes.",
    'numeric'             => "The %s field must contain only numbers.",
    'is_numeric'          => "The %s field must contain only numeric characters.",
    'integer'             => "The %s field must contain an integer.",
    'matches'             => "The %s field does not match the %s field.",
//    'is_unique'         => "The %s field must contain a unique value.", // requires db access - not appropriate
    'is_natural'          => "The %s field must contain only positive numbers.",
    'is_natural_no_zero'  => "The %s field must contain a number greater than zero.",
    'decimal'             => "The %s field must contain a decimal number.",
    'less_than'           => "The %s field must contain a number less than %s.",
    'greater_than'        => "The %s field must contain a number greater than %s.",
    // special message string for an internal message
    'report_error'        => "No custom error message is set for \"%s\" validating \"%s\" field." 
  );

  //callbacks for external validation functions. These are either in simple function format
  // 'function_1', 'function_2' ..etc or class/method or a mix of both.
  // class method looks like: array('class1','method1'),array('class2','method2')
  // The class/method type need either an instantiated class's method (using the object as the first param)
  // or a static class method. PHP throws a warning otherwise.
  //also supports lambda functions where array('routine_name',{callable lambda function})
  private $callbacks = array();

  //display options (setter function for these)
  private $opening_err_tag = '<p>';
  private $closing_err_tag = '</p>';
    
// ----------------------------------------------------------------------------

  /**
   * FormValidate::__construct()
   * 
   * constructor - optionally copy data into init array
   * 
   * @param mixed $initial_data
   * @return void
   */
  public function __construct($initial_data = array())
  {
    if (is_array($initial_data) && !empty($initial_data))
    {
      $this->data = $initial_data;
    }
  }

   /**
   * FormValidate::__call()
   * 
   * magic method to call custom validation methods (callbacks). Note that there
   * is a limitation of this trick which means that the callbacks can only
   * get public methods easily. The workround is pretty complicated and not a problem
   * here anyway (look at http://www.garfieldtech.com/blog/php-magic-call for the full story).
   * 
   * @param mixed $method
   * @param mixed $args
   * @return
   */
  public function __call($method, $args)
  {
    //see if callback exists in table
    if(isset($this->callbacks[$method]))
    {
      //Yes!
      
      //add the $this object as the first arg so the callback can access the object variables/methods
      //needs this to be able to add the custom error message.
      array_unshift($args, $this);
    
      //note: callability of callbacks is tested when they are added, so they can just be called
      //without re-checking here.
      if(empty($this->callbacks[$method]))
      {
        //standard function - no array parameters
        return call_user_func_array($method, $args);
      }
      else
      {
        $callback = $this->callbacks[$method]; //get the callback array
      
        //class method ([0] is class name, [1] is method name)
        if($method == $callback[1])
        {
          //calls with the class/method array
          return call_user_func_array($callback, $args); 
        }
        //test for anonymous/lambda function method - $callback[0] is the name and $callback[1] is the anon function
        elseif ($method == $callback[0]) //still a redundant check?
        {
          //calls with the lambda case (not an array)
          return call_user_func_array($callback[1], $args); 
        }
      }
    }
  
    //if we get to here it is an unrecognised callback which is an error.
    trigger_error('Call to non-existing validation method/function: '.$method, E_USER_ERROR);
    //return false; //implicit fail of test routine
  }

  /**
   * FormValidate::debug()
   * 
   * routine to dump all internal array data for testing only
   * 
   * @return
   */
  protected function debug() 
  {
    $str = '<pre>';
    $str .= '<b style="color:red;">Rules:</b><br />';
    $str .= var_export($this->rules,true);
    $str .= '<br /><b style="color:red;">Data to Validate (may have had prep run on it - depends when debug() called):</b><br />';
    $str .= var_export($this->data,true);
    $str .= '<br /><b style="color:red;">Errors:</b><br />';
    $str .= var_export($this->errors,true);
    $str .= '<br /><b style="color:red;">Error Messages:</b><br />';
    $str .= var_export($this->error_msgs,true);
    $str .= '<br /><b style="color:red;">External Validation Callbacks:</b><br />';
    $str .= var_export($this->callbacks,true);
    $str .= '</pre>';

    return $str;
  }

  /**
   * FormValidate::add_validation_rule()
   * 
   * add a new validation routine as a callback. Can overwrite an existing one with
   * the same name - no problem.
   * 
   * @param mixed $name
   * @param mixed $func
   * @return
   */
  public function add_validation_rule($name = null, $func = null) 
  {
    //check for parameter errors and illegal names that clash with existing methods
    if($name !== null && is_string($name) && $name != '' &&
      !method_exists($this, $name)) 
    {
    
      if($func === null)
      {
        if (is_callable($name))
        {
          //simple function - looks good & callable
          $this->callbacks[$name] = array();
          return $this;
        }
        
        //not callable - drop through
      }
      else if (is_callable($func))
      {
        //there is a 2nd callable parameter
        // yes - anonymous function case
       $this->callbacks[$name] = array($name, $func);
        return $this;
      }
      else if(is_callable(array($func,$name)))
      {
        //2nd param is not callable - but a class/name version ($func is the class)
        $this->callbacks[$name] = array($func,$name);
        return $this;
        
      }
    }
    //bad name, clash or is not callable
    trigger_error("Error in rule format for \"$name\" or name clash with existing rule.", E_USER_ERROR);
  }

  /**
   * FormValidate::add_error_message()
   * 
   * add a new error message for a custom validation routine
   * this can also overwrite existing messages so they can be changed
   * 
   * @param mixed $routine
   * @param mixed $msg
   * @return
   */
  public function add_error_message($routine,$msg) 
  {
    if (!is_string($routine) || !is_string($msg) || $routine == '' || $msg == '')
    { 
      trigger_error("Error in parameters for add_error_message.", E_USER_ERROR);
    }
    
    $this->error_msgs[$routine] = $msg;
    
    return $this;
  }

  /**
   * FormValidate::set_error_tags()
   * 
   * set error tags enclosing error messages returned by get_error_message
   * 
   * @param mixed $opening
   * @param mixed $closing
   * @return
   */
  public function set_error_tags($opening,$closing) 
  {
    if (!is_string($opening) || !is_string($closing)) 
    {
      trigger_error("Error in parameters for set_error_tags.", E_USER_ERROR);
    }
    $this->opening_err_tag = $opening;
    $this->closing_err_tag = $closing;
    
    return $this;
  }
  
  /**
   * FormValidate::set_data()
   * 
   * add validation data
   * 
   * @param mixed $data
   * @return
   */
  public function set_data ($data=null)
  {
    if($data === null || !is_array($data))
    {
      trigger_error("Error in parameters for set_data.", E_USER_ERROR);
       
    } 
    
    $this->data = $data;
    
    return $this;
  }

  /**
   * FormValidate::get_all_data()
   * 
   * get all data set for validation
   * 
   * @return
   */
  public function get_all_data ()
  {
    return $this->data;
  }

  /**
   * FormValidate::get_data()
   * 
   * get data for 1 field. Return $default if not found (or found data is null).
   * 
   * @param mixed $field
   * @param mixed $default
   * @return
   */
  public function get_data ($field=null,$default=null)
  {
    if($field === null) 
      return $default;
    
    return (isset($this->data[$field]) ? $this->data[$field] : $default);
  }

 /**
   * FormValidate::get_all_errors_array()
   * 
   * get all errors as an array
   * 
   * @return
   */
  public function get_all_errors_array ()
  {
    return $this->errors;
  }
  
  /**
   * FormValidate::get_error_message()
   * 
   * get one error and return as html with the set tags. If no errors or unrecognised
   * field return empty string.
   * 
   * @param mixed $field
   * @return
   */
  public function get_error_message($field)
  {
    if(isset($this->errors[$field]))
    {
      $msg = $this->opening_err_tag.$this->errors[$field].$this->closing_err_tag;
    }
    else
    {
      $msg = '';
    }
    
    return $msg;
  }

  /**
   * FormValidate::get_rules()
   * 
   * get rules - testing purposes only
   * 
   * @return
   */
  protected function get_rules ()
  {
    return $this->rules;
  }

  /**
   * FormValidate::add_field()
   * 
   * add a single field with an optional alias and a list of rules
   * 
   * @param mixed $field
   * @param string $alias
   * @param string $rule_list
   * @return
   */
  public function add_field ($field, $alias='', $rule_list='')
  {
    //sanity check    
    if(!is_string($field) || !is_string($alias) || !is_string($rule_list))
    {
      //parameter errors, just return
      trigger_error("Error in parameters for add_field", E_USER_ERROR);
    }
    
    if($field == '') return $this; //no field, just return $this so chainable
    
    //build the new rule. rule_array is an array of rules and their parameters (if any)
    $rule = array('field'=>$field, 'alias' => $alias, 'rule_array' => array());
       
    if($rule_list == '')
    {
      //empty rule list - still ok to add in as an empty rule (gets a possible field alias in there)
      //also just having the field name can be required.
      $this->rules[] = $rule;
      return $this;
    }
    
    $elements = explode('|',str_replace(' ','',$rule_list)); //blow up at the separators (remove all spaces first)

    if (!empty($elements))
    {
      $found = false; //flag
      
      //not empty, so analyse the elements
      // rule routine must start with a letter or underscore and then be alpha numerics or underscores 
      foreach($elements as $element)
      {
        $found = false;
        //see if we can pull a valid format function name plus a parameter out (match with 2 = no param, 
        //match with 4 is with param). $match [1] is function name, [3] is parameter
        $res = preg_match('#^([a-zA-Z_][a-zA-Z0-9_]*)(\[(.*)\])?$#',$element,$matches);
        
        if ($res && count($matches) == 2) //no param
        {
          $rule['rule_array'][] = array($matches[1]);
          $found = true;
        }
        else if ($res && count($matches) == 4) //param
        {
          // multi-parameter handling - note, not processed further, just collects parameters for now
          //room for future expansion to multi-parameter tests if required.
          //$rule['rule_array'][] = array($matches[1],$matches[3]);
          $p = array($matches[1]);
          $found = true;
          
          $params = explode(',',$matches[3]);
          
          foreach ($params as $param)
          {
            $p[] = $param;
          }
          //save parameter(s)
          $rule['rule_array'][] = $p;
          
        }
        else
        {
          //nothing found here - bad rule
          trigger_error("Error in rule format for $element", E_USER_ERROR);
        }
        // we can't check if these rules are callable yet as custom validation
        //routines may get added after the rules. Check when we try and run them.
      }
      
      //if a valid rule string add the whole ruleset to the array, else discard it.
      //(redundant test when trigger_error() used)
      if($found) 
      {
        $this->rules[] = $rule;
      }
    }
    //chainable method
    return $this;
  }
  
  /**
   * FormValidate::add_field_list()
   * 
   * add a list of rules
   * 
   * @param mixed $field_list
   * @return
   */
  public function add_field_list ($field_list=array())
  {
    if(!is_array($field_list))
    {
      trigger_error("Error in data format for add_field_list", E_USER_ERROR);
    }
    
    foreach(new \ArrayIterator($field_list) as $field)
    {
      if(!is_array($field) || !isset($field['field'],$field['alias'],$field['rule_list']))
      {
        trigger_error("Error in data format for add_field_list", E_USER_ERROR);
      }
      
      $this->add_field ($field['field'], $field['alias'], $field['rule_list']);
    
    }
    
    return $this;
  }

  /**
   * FormValidate::restrict_data_fields()
   * 
   * restrict data fields to ones that have a rule set for the field. Delete the rest.
   * 
   * @return
   */
  private function restrict_data_fields ()
  {
    //extract the rule fields as keys of an array (values are irrelvant)
    $rule_fields = array_flip(array_map(function ($e) {return $e['field'];}, $this->rules));
    //save only fields in the input data that have rules
    $this->data = array_intersect_key($this->data,$rule_fields);
    
  }

  /**
   * FormValidate::validate_one()
   * 
   * validate a single data item (called multiple times for things like checkbox arrays)
   * 
   * @param mixed $method
   * @param mixed $data
   * @param mixed $param
   * @return
   */
  private function validate_one($method, &$data, &$param)
  {
    if(/*$data === null ||*/ $method == 'required' || $method == 'is_set')
    {
      //needs to run if no data (or forced by required rule)to generate the error for empty field
      $result = $this->{$method}($data,$param);
      
    }
    else if($data === null || $data === '') //added null here
    {
      //empty field - always true (unless "required")
      $result = true;
      
    }
    else
    {
      //non-empty field, call the normal validation routine
      $result = $this->{$method}($data,$param);
    }         
  
    return $result; 
  }

  /**
   * FormValidate::run()
   * 
   * main run validate function
   * 
   * @param array $val_data
   * @param bool $only_with_rules
   * @return
   */
  public function run ($val_data = null, $only_with_rules=false)
  {
    //run the validate & return false if any fails, or true if ok
    $passed = true;
    //clear any errors from previous runs
    $this->errors = array();
    
    //if data to validate passed then copy in
    if($val_data !== null && is_array($val_data))
      $this->data = $val_data;
    
    if($only_with_rules) $this->restrict_data_fields (); //remove fields without rules if enabled
    
    //run through the rule list for the data array
    foreach(new \ArrayIterator($this->rules) as $rule)
    {
      //check for presence of field. if field is missing create it with a null value in the array.
      if(!isset($this->data[$rule['field']]))
      {
        $this->data[$rule['field']] = null;
      }
      
      //run the rule-set for field
      foreach(new \ArrayIterator($rule['rule_array']) as $term)
      {
        if(is_callable(array($this,$term[0])))
        {
          //there is a callable method - check if a parameter and set a null if not
          $method = $term[0];
          $param = isset($term[1]) ? $term[1] : null;

          //check if data is array
          if(is_array($this->data[$rule['field']]))
          {
            //handle case with empty array - this is always an error
            //if not empty then tests need to pass for all elements to return true
            (!empty($this->data[$rule['field']])) ? $result = true : $result = false;
            
            //run through each array element applying the rule
            foreach ($this->data[$rule['field']] as &$val)
            {
              if(is_scalar($val))
              {
                //data needs to not be an array by here!
                $r = $this->validate_one($method, $val, $param);
              }
              else
              {
                //it was an array or object - fail!
                $r = false;
              }
              
              $result = $result && $r ; //any false $r and $result stays false.
            }
          }
          else
          {
            //normal scalar
            $result = $this->validate_one($method, $this->data[$rule['field']], $param);
          }
          
          //report the error if present. Note that there is a trick where validation
          //routines access 2 fields and need to show a message using both their aliased names.
          //The 2nd field name in $param is modified by reference and then the modified name
          //is used by the report error routine.
          if(!$result)
          {
            $this->report_error($method,$rule['field'],$rule['alias'],$param);
            $passed = false;
            break;
          }
          
        }
        else
        {
          //not a callable function - internal error
            trigger_error("Validation function {$term[0]} not callable.", E_USER_ERROR);
          //throw new \ErrorException("Validation function {$term[0]} not callable.");
          break;
        }
      }
    
    }
    
    //did anything fail validation??
    return $passed;

  }
  
  /**
   * FormValidate::report_error()
   * 
   * report error from array of error messages keyed by the routine name
   * handles field names with optional aliases and cases with a parameter.
   * 
   * @param mixed $routine_name
   * @param mixed $field_name
   * @param mixed $alias
   * @param mixed $param
   * @return void
   */
  private function report_error($routine_name,$field_name,$alias,$param)
  {
    //check if an error message exists and isn't empty
    if(isset($this->error_msgs[$routine_name]) && $this->error_msgs[$routine_name] != '')
    {
      if($param === null)
      {
        //1 variable to print
        $this->errors[$field_name] = sprintf($this->error_msgs[$routine_name],($alias == '') ? $field_name : $alias);
      }
      else
      {
        //2 to print
        $this->errors[$field_name] = sprintf($this->error_msgs[$routine_name],($alias == '') ? $field_name : $alias, $param);
      }
    }
    else
    {
      //default message if nothing specific can be found - check message present
//      $this->errors[$field_name] = 'No error message is set for '.$routine_name.' validating '.$field_name.' field.';
      if(isset($this->error_msgs['report_error']) && $this->error_msgs['report_error'] != '')
      {
        //do recursive call to handle "no custom error message" message
        $this->report_error('report_error',$routine_name,'',$field_name);
      }
      else
      {
        //when there isn't an error message-message it is a fatal error
        trigger_error("No default missing field error message when custom message not supplied.", E_USER_ERROR);
       }
    
    }
  }

// ----------------------------------------------------------------------------
/**
 * Validation and data prep routines
 * 
 * There are lots of these!
 * 
 * 
 */
  protected function required ($str)
  {
    //routine to test for empty field (field present is tested separately)
    
      if($str === null) return false; //always a fail
      
      return (trim($str) == '') ? false : true;

  }
  
// ----------------------------------------------------------------------------

  protected function is_set ($str)
  {
    //routine to test for field present - if comes with null then the field was missing
    //$str is empty field is ok, as is anything else in the field.
    
      if($str === null) return false; //always a fail
      
      return true; //anything else is ok

  }
  
// ----------------------------------------------------------------------------
  
  protected function trim (&$val)
  {
    //prep routine to trim  
    $val = trim($val);
    return true;
  }
  
// ----------------------------------------------------------------------------
  //prep routine to lowercase 
  protected function to_lower (&$val)
  {
    $val = strtolower($val);
    
    return true;
  }
  
// ----------------------------------------------------------------------------
  //prep routine to uppercase
  protected function to_upper (&$val)
  {
    $val = strtoupper($val);
    
    return true;
  }
  
// ----------------------------------------------------------------------------
  //prep routine to safely encode html tags
  protected function html_clean (&$val)
  {
    $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
    
    return true;
  }

// ----------------------------------------------------------------------------

  //one field matches another - this routine edits the $field name to insert the human-readable version
  //This is fine as long as the function isn't passed a pointer to the original value which will then get modified. 
  protected function matches($str, &$field)
  {
    if (!isset($this->data[$field]) || $field === null)
    {
      //no second field specified - fail
      return false;
    }

    $val = $this->data[$field];
    
    //set 2nd field name to Alias (called by reference), or leave as original if there is no alias
    $field = $this->find_alias_for_field($field);

    return ($str !== $val) ? false : true;
  }
  
// ----------------------------------------------------------------------------
  //helper function to get a field alias for a field
  protected function find_alias_for_field($field)
  {
    //find field alias for name
    foreach(new \ArrayIterator($this->rules) as $rule)
    {
      if($rule['field'] == $field && $rule['alias'] != '')
      {
        return $rule['alias']; //the alias

      }
    }
    
    return $field; //return the original so no test required on return val

  }
  
// ----------------------------------------------------------------------------

  //validation routine for email format
  protected function valid_email ($str)
  {
    //modified routine to require a dot in the domain part (see 
    //http://www.electrictoolbox.com/php-email-validation-filter-var-updated/)
    return (filter_var($str, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $str)) !== false;  
  }
  
// ----------------------------------------------------------------------------

  //validation routine for url format
  protected function valid_url ($str)
  {
    return (filter_var($str, FILTER_VALIDATE_URL)) !== false; 
  }
  
// ----------------------------------------------------------------------------

  //validation routine for ip format
  protected function valid_ip ($str)
  {
    return (filter_var($str, FILTER_VALIDATE_IP)) !== false;  
  }
  
// ----------------------------------------------------------------------------

  //validation routine for base64 format
  protected function valid_base64 ($str)
  {
    return (base64_encode(base64_decode($str)) === $str); 
  }
  
// ----------------------------------------------------------------------------

  //validation routine for min string length
  protected function min_length ($str,$val=null)
  {
  
    if ($val === null || preg_match("/[^0-9]/", $val))
    {
      return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
      return (mb_strlen($str) < $val) ? FALSE : TRUE;
    }

    return (strlen($str) < $val) ? FALSE : TRUE;
    
  }
  
// ----------------------------------------------------------------------------

  //validation routine for max string length
  protected function max_length ($str,$val=null)
  {

    if ($val===null || preg_match("/[^0-9]/", $val))
    {
      return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
      return (mb_strlen($str) > $val) ? FALSE : TRUE;
    }

    return (strlen($str) > $val) ? FALSE : TRUE;

  }
  
// ----------------------------------------------------------------------------

  //validation routine for exact string length
  protected function exact_length($str, $val=null)
  {
    if ($val === null || preg_match("/[^0-9]/", $val))
    {
      return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
      return (mb_strlen($str) != $val) ? false : true;
    }

    return (strlen($str) != $val) ? false : true;
  }

// ----------------------------------------------------------------------------

  protected function alpha ($str)
  {

    return (!preg_match("/^([a-z])+$/i", $str)) ? false : true;
  }

// ----------------------------------------------------------------------------

  protected function alpha_numeric ($str)
  {

    return (!preg_match("/^([a-z0-9])+$/i", $str)) ? false : true;
  }

// ----------------------------------------------------------------------------

  protected function alpha_dash ($str)
  {

    return (!preg_match("/^([-a-z0-9_-])+$/i", $str)) ? false : true;
  }

// ----------------------------------------------------------------------------

  protected function numeric ($str)
  {
  
    return (bool) preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
  }

// ----------------------------------------------------------------------------

  protected function is_numeric($val)
  {
    return (!is_numeric($val)) ? false : true;
  }

// ----------------------------------------------------------------------------

  protected function integer($val)
  {
    return (bool) preg_match('/^[\-+]?[0-9]+$/', $val);
  }

// ----------------------------------------------------------------------------

  protected function decimal($val)
  {
    return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $val);
  }


// ----------------------------------------------------------------------------

  protected function greater_than($val, $min=null)
  {
    if (!is_numeric($val) || $min===null)
    {
      return false;
    }
    
    return $val > $min;
  }

// ----------------------------------------------------------------------------

  protected function less_than($val, $max=null)
  {
    if (!is_numeric($val)|| $max===null)
    {
      return false;
    }
    
    return $val < $max;
  }

// ----------------------------------------------------------------------------

  protected function is_natural($str)
  {
    return (bool) preg_match( '/^[0-9]+$/', $str);
  }


// ----------------------------------------------------------------------------

  protected function is_natural_no_zero($str)
  {
    if (!preg_match( '/^[0-9]+$/', $str))
    {
      return false;
    }

    return ($str != 0);
    
  }

// ----------------------------------------------------------------------------

} //EOC


/* end */