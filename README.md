# TheKofClient
A PHP client for [Survey Monkey API V3 https://developer.surveymonkey.com/api/v3/](https://developer.surveymonkey.com/api/v3/)
Project started 7/11/2017    
Comments, Patches and Support requests can be sent here, at github.  
Current Version: 0.01  

## Table of Contents
1. Installing and Configuring  
   - Dependencies  
2. Quick introduction to the API  
3. Classes and Methods  
3. Examples  


## Installing and Configuring  

### Dependencies  
TheKofClient uses Zend Framework > 2.x HTTP client to communicate with SurveyMonkey servers.  
Later versions of this client might support a more generic way to communicate.  

While TheKofClient is part of the TalisMS library, It is in name only. It follows the same naming convetions. 
But, it is a stand alone project.  

### Installation  
**Using TalisMS**  
copy source/Talis/Services/TheKofClient folder of this project, and put it under
Talis/Services/ If TalisMS is properly installed in your project. It is done.  

**Use as standalone lib with autoloader**  
Put `source/Talis` in your include path for PHP.  
If you use autoloader, it should translate namespace separators \\ and underscores _ to url path separators /  
and add .php at the end.  
Example: The class `\Talis\Services\TheKofClient\Client` will be included like that:   
```
require_once('Talis/Services/TheKofClient/Client.php');
```

**Use as standalone lib with simple includes**  
For this, copy the file `source/TheKofClientBundle.php` into your project and `require_once`.

