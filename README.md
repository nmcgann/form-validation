FormValidate
============

PHP Form Validation Class


Loosely patterned on the interface from the Codeigniter form validation library, but written from scratch in PHP 5.3+. No dependencies. Includes a full set of unit tests in PhpUnit format.

Originally written to use with various Micro-frameworks as they (generally) don't have this included and I couldn't find a no-dependencies library that I liked that someone else had written.

I didn't include tests like "is_unique" that introduce awkward dependencies. These are very easy to add using closure callbacks and then they can be tailored exactly to the application.

Basic usage:
```php
<?php
namespace Test; //whatever this namespace is
use \Libraries as Lib; //FormValidation lives in "Libraries" as standard

//pull in the Class for testing
define('FCPATH', TRUE); //defeat check to prevent direct script execution
require 'FormValidate.php';

//create validator
$v = new Lib\FormValidate();
//add field(s) - params are field name, an optional alias, an optional list of rules
//with the "|" separator.
$v->add_field('name_field','Name','required|alpha');
//add more fields as required...

//the data to validate - any array of key=>value pairs
//(this example will fail)
$data = array('name_field' => '0123');

//run validation and do something with the results
if($v->run($data))
{
 //passed validation - do stuff
 echo "Pass!<br />";
}
else
{
 //failed validation - do other stuff
 $array_of_errors = $v->get_all_errors_array();
 
 //....or per-field with default html tags (returns empty string if no error)
 echo $v->get_error_message('name_field');
}

```
