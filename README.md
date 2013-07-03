FreePBX Lenny Blacklist Mod
===========================

Modifies the FreePBX blacklist to redirect banned callers to SIP/lenny@sip.itslenny.com or to any other user specifed SIP URI.


Installation:
=============
System requirements: FreePBX version 2.10 or later with the Blacklist module installed and enabled. Download from [here](http://pbxossa.org/files/lenny/)
and upload the tarball using the "Upload Modules" button under "Module Admin" then install the module when it
appears in the list of local modules.

Usage:
======
Under the "Other" tab (or directly on the Blacklist page with later versions of FreePBX), a new entry for Lenny Blacklist Mod 
will appear. Default settings redirect blacklisted callers to Lenny and local recordings are made. The user can disable
the recording, change the URI that callers are directed to or disable the redirect and restore default Blacklist behavior. The 
user must actively enable this module via the checkbox and also certify compliance with the receiving party's TOS. To direct
blacklisted callers to an internal resource such as an announcement or voicemail, configure the destination as:
`local/<digits>@from-internal`
