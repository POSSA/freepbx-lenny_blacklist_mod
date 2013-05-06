freepbx-lenny_blacklist_mod
===========================

Modifies the FreePBX blacklist to redirect banned callers to SIP/lenny@itslenny.com or to any other user specifed SIP URI.


Installation:
=============
System requirements: FreePBX 2.10 or later, and the Blacklist module must be installed. Download from [here](http://pbxossa.org/files/lenny/)
and upload the tarball using the "Upload Modules" button under "Module Admin" then install the module when it
appears in the list of local modules.

Usage:
======
Under the "Other" tab, a new entry for Lenny Blacklist Mod will appear. Default settings enable all blacklisted
calls to go to Lenny and local recordings are made. The user can disable the recording, change the URI that callers
are directed to or disable the redirect and restore default Blacklist behavior. The user must actively enable this module 
via the checkbox and also certify compliance with the receiving party's TOS.
