# Stable release v0.2.5 is out 

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

2. While **TheKofClient** is part of the TalisMS library, It is so in name only. It follows the same naming convetions. 
But, it is a stand alone project.  

3. An account at SurveyMonkey with permissions to build apps.

4. An app defined on SurveyMonkey with the permissions you need it to have (I suggest familiarizing yourself with SurveyMonkey APP and API usage before using this client).  

5. **Access Token**, which is copied from your app setting screen, and looks something like this `P4BCgR2bIBdtj10AKrCX9sVRx.DHaoYcMgKFMAROePyn.IxS5H8Bovv4pj98M3N0xvIKVxW00o12at-mSgIzGiRR3TSPcVks4TBHp3nCxyd9Kv6Z9OFlrKD1O8UXFsXb`

### Installation  
**Using TalisMS**  
copy `source/Talis/Extensions/TheKof` folder of this project, and put it under
`Talis/Extensions/` If TalisMS is properly installed in your project. It is done.
```
mv path/to/source/Talis/Extensions/TheKof path/to/your/Talis/Extensions/.
```

**Use as standalone lib with autoloader**  
Put `source/Talis` in your include path for PHP.  
If you use autoloader, it should translate namespace separators \\ and underscores _ to url path separators /  
and add .php at the end.  
Example: The class `\Talis\Extensions\TheKof\SurveyMonkeyClient` will be included like that:   
```
require_once('Talis/Extensions/TheKof/SurveyMonkeyClient.php');
```

**Use as standalone lib with simple includes**  
For this, copy the file `boundle/thekofclient.php` into your project and `require_once(path/to/thekofclient.php)`.


## Quick introduction to the API

### Intro
**TheKofClient** aims at emulating the API itself as closely as possible, be self documenting as much as possible, and have a simple one point
of entry. The examples following this are quick examples of the Client usage and a (very) short explanation of what they do and return. **Those are not working examples, Intention
is to show API only.** For working examples check the `examples` folder. `...` Means various parameters.  

### Survey
**fetch** All your surveys. Make sure you have setup the right permission in the APP dashboard on Surveymonkey (Scope: View Surveys)  

```  
use \Talis\Extensions\TheKof;
$Client = new SurveyMonkeyClient(...);
$surveys_list = $Client->surveys()->get(); //returns a collection (iterable) of your surveys. Defaults to page size of 100 (i.e. the first 100 surveys you own).
$surveys_list = $Client->surveys()->get(2,10); //returns a collection (iterable) of your surveys. Page 2 where page size is 10 surveys
$one_survey   = $Client->surveys(survey_id)->get();//return survey object for survey id=survey_id

$collectors_list = $Client->surveys(survey_id)->collectors()->get();//return collection of Survey Collectors for survey id=survey_id, again, same paging rules as above apply
$one_collector   = $Client->surveys(survey_id)->collectors(collector_id)->get(); //return a collector object for collector id = collector_id  
```  


**dry** Each method has a `*_dry()` version which can be used without an HTTP client, and will return a data structure represnting the request (url/headers/body)  
```  
$Client = new SurveyMonkeyClient(...);
$surveys_list_request_data = $Client->surveys()->get_dry();
$surveys_list_request_data = $Client->surveys()->get_dry(2,10);
$one_survey_request_data   = $Client->surveys(survey_id)->get_dry();

$collectors_list_request_data = $Client->surveys(survey_id)->collectors()->get_dry();
$one_collector_request_data   = $Client->surveys(survey_id)->collectors(collector_id)->get_dry();
```  

