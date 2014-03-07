FormValidate
============

PHP Form Validation Class


Loosely patterned on the interface from the Codeigniter form validation library, but written from scratch in PHP 5.3+. No dependencies. Includes a full set of unit tests in PhpUnit format.

Originally written to use with various Micro-frameworks as they (generally) don't have this included and I couldn't find a no-dependencies library that I liked that someone else had written.

I didn't include tests like "is_unique" that introduce awkward dependencies. These are very easy to add using closure callbacks and then they can be tailored exactly to the application.
