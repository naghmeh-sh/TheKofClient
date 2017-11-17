HTTP client wrappers
====================

right now, The Kof is using ZFW2 HTTP Client.  
That is nice, but you might want to use another client, of your own choosing.  
I am implementing here the Adapter design pattern.  
There is an adapter for Zend client, and you can easilly write an adapter for other libraries (and donate them, if you so choose).  
"All" the new adapters have to do is to extend the abstract Adapter class.  
The adapter class knows how to translate TheKof DryRequest into the relevant HTTP Request and return meaningful data.  
I know it can probably be abstracted to support other things, but then we loose the good encapsulating this Plugin have.  

TODO: Write instruction on how to properly extend the wrapper.
