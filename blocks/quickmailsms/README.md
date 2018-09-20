This is an unabashed hack of the Original Quickmail block from Louisiana State University.  

All credit and kudos goes to their team.  I only changed a few items to enable SMS functionality.

***BE SURE TO DELETE ANY EXISTING INSTALLATION OF THE QUICKMAILSMS BLOCK BEFORE UPGRADING***

Go to Blocks->mangage blocks->Delete, next to quickmailsmssms

______________________________________________________________________

INSTALLATION

Installation is like any other block - upload it to moodle/blocks and go to Admin->Notifications.  Basically, it works just like the old quickmail block:  
______________________________________________________________________

CONFIGURATION - BLOCK WILL NOT WORK WITHOUT THE FOLLOWING

You create three custom profile fields with the following specifications:

1.

type - checkbox
shortname - opt
name - whatever you feel appropriate, such as "Allow texts sent to your phone"
required - no
locked - no
unique - no
display on signup - yes
Who is the field visible to? - visible to user
checked by default - No

2.

type - dropdown menu
shortname - mobileprovider
name - whatever you feel appropriate, such as "Cellular Provider"
required - no
locked - no
unique - no
display on signup - yes
Who is the field visible to? - visible to user
menu options -
Please select one...
AT&T ~@txt.att.net~
All Tell ~@@message.alltel.com~
Boost ~@myboostmobile.com~
Cellular South ~@csouth1.com~
Centennial Wireless ~@cwemail.com~
Cincinnati Bell ~@gocbw.com~
Cricket Wireless ~@sms.mycricket.com~
Metro PCS ~@mymetropcs.com~
Powertel ~@ptel.net~
Qwest ~@qwestmp.com~
Rogers ~@pcs.rogers.com~
Sprint ~@messaging.sprintpcs.com~
T-Mobile ~@tmomail.net~
Suncom ~@tms.suncom.com~
Telus ~@msg.telus.com~
U.S. Cellular ~@email.uscc.net~
Verizon ~@vtext.com~
Virgin Mobile USA ~@vmobl.com~

Default value - Please select one...

3

type - text input
shortname - mobilephone
nrequired - no
locked - no
unique - no
display on signup - yes
Who is the field visible to? - visible to user
Display size - 10
Maximum Length - 10

______________________________________________________________________

NOTES:

1 - Additional Providers
If you think your students have other providers that are not listed, find their email-to-sms address at http://www.emailtextmessages.com/ and add them in with the format

Name ~@address~

2 - Profile Field Category
I find it easier to create a new Profile Field Category called "SMS Messages" and put the three fields in there.

Following is the original Quickmail readme file contents:

# Quickmail

Quickmail is a Moodle block that provides selective, bulk emailing within courses.

## Features

* Multiple attachments
* Drafts
* Signatures
* Filter by Role
* Filter by Groups
* Optionally allow Students to email people within their group.
* Alternate sending email
* Embed images and other content in emails and signatures

## Download

Visit [Quickmail's Github page][quickmailsms_github] to either download a package or clone the git repository.

## Installation

QuickmailSMS should be installed like any other block. See [the Moodle Docs page on block installation][block_doc].

## Contributions

Contributions of any form are welcome. Github pull requests are preferred.

File any bugs, improvements, or feature requiests in our [issue tracker][issues].

## License

QuickmailSMS adopts the same license that Moodle does.

## Screenshots

![Block][block]

---

![Email][email]

---

![Signatures][signature]

---

![Configuration][config]

[quickmail_github]: https://github.com/lsuits/quickmail
[block_doc]: http://docs.moodle.org/20/en/Installing_contributed_modules_or_plugins#Block_installation
[block]: https://tigerbytes2.lsu.edu/users/pcali1/work/block.png
[config]: https://tigerbytes2.lsu.edu/users/pcali1/work/config.png
[signature]: https://tigerbytes2.lsu.edu/users/pcali1/work/signature.png
[email]: https://tigerbytes2.lsu.edu/users/pcali1/work/email.png
[issues]: https://github.com/lsuits/quickmailsms/issues
