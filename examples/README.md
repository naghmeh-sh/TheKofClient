Examples instructions.
======================

1. To make the examples work, you need to use your own access token.  
Access token is generated on the Survey Monkey app dashboard and looks something like   
**P4BCgR2bIBdtj10AKrCX9sVRx.DHaoYcMgKFMAROePyn.IxS5H8Bovv4pj98M3N0xvIKVxW00o12at-mSgIzGiRR3TSPcVks4TBHp3nCxyd9Kv6Z9OFlrKD1O8UXFsXb**  

2. `mv examples/env_example.php examples/env.php`  

3. Put the access token from step 1 in the env.php in the right location

4. For now, I work directly with ZFW2 http client, make sure ZFW2 is auto loaded. The env_example.php has an autoload function, modify the paths there.

5. You can eiter run the scripts from the cli or put them under web server and browse to them.