# STAY TUNED, FIRST VERSION WILL COME BY DECEMBER 2017. 

# TheKofClient
A PHP client for [Survey Monkey API V3 https://developer.surveymonkey.com/api/v3/](https://developer.surveymonkey.com/api/v3/)
Project started 7/11/2017    
Comments, Patches and Support requests can be sent here, at github.  
Current Version: 0.01  

## Table of Contents
1. Installing and Configuring  
   - Dependencies  
   - Installation
   
2. Quick introduction to the API
   - Intro  
   - Survey
   - Collectors 
3. Classes and Methods  
3. Examples  


## Installing and Configuring  

### Dependencies 
1. **TheKofClient** uses Zend Framework > 2.x HTTP client to communicate with SurveyMonkey servers.  
Later versions of this client might support a more generic way to communicate.  

2. While **TheKofClient** is part of the TalisMS library, It is in name only. It follows the same naming convetions. 
But, it is a stand alone project.  

3. An account at SurveyMonkey with permissions to build apps.

4. An app defined on SurveyMonkey with the permissions you need it to have (I suggest familiarizing yourself with SurveyMonkey APP and API usage before using this client).  

5. **Access Token**, which is copied from your app setting screen, and looks something like this `P4BCgR2bIBdtj10AKrCX9sVRx.DHaoYcMgKFMAROePyn.IxS5H8Bovv4pj98M3N0xvIKVxW00o12at-mSgIzGiRR3TSPcVks4TBHp3nCxyd9Kv6Z9OFlrKD1O8UXFsXb`

### Installation  
**Using TalisMS**  
copy `source/Talis/Services/TheKofClient` folder of this project, and put it under
`Talis/Services/` If TalisMS is properly installed in your project. It is done.
```
mv path/to/source/Talis/Services/TheKofClient path/to/your/Talis/Services/.
```

**Use as standalone lib with autoloader**  
Put `source/Talis` in your include path for PHP.  
If you use autoloader, it should translate namespace separators \\ and underscores _ to url path separators /  
and add .php at the end.  
Example: The class `\Talis\Services\TheKofClient\Client` will be included like that:   
```
require_once('Talis/Services/TheKofClient/Client.php');
```

**Use as standalone lib with simple includes**  
For this, copy the file `source/TheKofClientBundle.php` into your project and `require_once(path/to/TheKofClientBundle.php)`.


## Quick introduction to the API

### Intro
*TheKofClient* aims at emulating the API itself as closely as possible, be self documenting as much as possible, and have a simple one point
of entry. The examples following this are quick examples of the Client usage and a (very) short explanation of what the do and return.  

### Survey
*fetch 