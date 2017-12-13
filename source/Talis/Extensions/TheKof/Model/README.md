Model folder
============

Each class here is used to translate the raw response from Survey Monkey  
into a concrete object that can be worked on.  

Surveys
Collectors

The response is always a collection object. If, in your code you know   
to expect exactly one response from the API, you can use the `first()` method of the collection  
to get the element you queried for.

The collection object also provide easy ways to page the request (i.e. get next/get previous,get first etc)  
It does that by generating a DryRequest for the call, call it and return a new collection object

Same for parent items. For example, a Survey Model can be asked to fetch one of it's collectors (need ID to do it).

