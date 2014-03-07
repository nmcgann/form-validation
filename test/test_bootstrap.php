<?php
/**
 * FormValidate - Tests
 * 
 * Test bootstrap setup - pull in the class so autoloader not needed
 * 
 */
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

define('FCPATH', TRUE);

// Include path
set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/'
    . PATH_SEPARATOR . get_include_path()
);

//pull in the Class for testing
require 'FormValidate.php';

/* end */
