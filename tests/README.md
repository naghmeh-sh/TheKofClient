THE TESTSTES
============
1. I am using PHPUnit to run the tests  
2. I am using the explicit include (including explicitly all the files) and not an auto loader
3. I am using my own credentials, which are included from an env file in this same folder.  
   To make the tests work on your machine, put a file `env.php` in this folder, with the following content:  
   ```  
   <?php
   class Env{
       static public $access_token = 'your own app access token';
   }
   
   ```
   
