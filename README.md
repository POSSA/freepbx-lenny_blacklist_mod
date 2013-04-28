freepbx-lenny_blacklist_mod
===========================

Modifies the FreePBX blacklist to redirect blacklisted calls to lenny@itslenny.com

Apr 26, 2013
0.0.3 basic functionality in place and tested on 2.10 and 2.11 with no know issues. 

Installation:
=============
System requirements: FreePBX 2.10 or later, and the Blacklist module must be installed. Download from [here](https://github.com/POSSA/freepbx-lenny_blacklist_mod/raw/tarball/lenny-0.0.2.tgz)
and upload the tarball using the "Upload Modules" button under "Module Admin" then install the module when it
appears in the list of local modules. The configuration page will appear under the Other tab.

Usage:
======
Under the "Other" tab, a new entry for Lenny Blacklist Mod will appear. Default settings enable all blacklisted
calls to go to Lenny and local recordings are made. The user can disable the recording, change the URI that callers
are directed to or disable the redirect and restore default Blacklist behavior.
