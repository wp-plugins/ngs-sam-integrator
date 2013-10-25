=== Plugin Name ===
Contributors: netguysteve
Donate link: http://www.netguysteve.com/sam-integrator
Tags: SAM Broadcaster, Internet Radio, Streaming, Broadcasting
Requires at least: 3.5.0
Tested up to: 3.7
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plug-in to integrate SAM Broadcaster with your Wordpress site

== Description ==
The NGS SAM Integrator is a WordPress Plug-In designed for users of Spacial Audio’s 
SAM Broadcaster software.   It allows you to set up a section on your WordPress 
site which integrates directly with your SAM Broadcaster Pro software, allowing 
your visitors to make requests whether you are DJing live or not.

It has been tested with and confirmed to work with SAM Broadcaster installations
using the MySQL database system.  Other database systems have not been tested,
nor can this plug-in be verified to support them.  If you find the plug-in
works as-is with other the other database systems which SAM supports, please
let me know.

== Installation ==

= BEFORE YOU INSTALL THE PLUG-IN =
Before you install the WordPress plug-in, you will need to configure both SAM 
Broadcaster and your mySQL database to allow your webserver to access them.  
It is highly recommended that you set up a mySQL user specifically for use by 
the plug-in.  This user only requires SELECT access on the songlist, requestlist, 
and queuelist tables of your SAM database.  It does not require any global access 
privileges, nor does it need to be able to modify any data in your database.   

For security reasons, the plug-in will NOT allow you to use the “root” user, even 
if you have configured it to be allowed external access.  For instructions on how 
to add a new mySQL user, please see http://dev.mysql.com/doc/refman/5.1/en/adding-users.html.

It is also recommended that you take steps to secure your installation of mySQL 
before allowing external access by this, or any other application or user.   For 
information on how to secure your mySQL installation, please see 
http://www.mysql.com/why-mysql/white-papers/a-guide-to-securing-mysql-on-windows/.

There are lot of different options you configure in SAM Broadcaster related to how 
it handles incoming requests.  Most of these are fairly self explanatory and can 
(and should) be adjusted over time to best suit your own needs as well as those 
of your visitors.   I will only cover those settings which are necessary to allow 
the plug-in to function.

1. In SAM Broadcaster, click **File > Config** and select **Request Policy**.
2. Make sure that **Enable Requests** is checked.
3. You will see a list entitled **Only accept requests from these IP addresses**.   
   The default IP addresses in that list are mostly associated with Audiorealm, 
   which is the site that Spacial Audio uses to process requests if you choose not 
   to process them yourself.  This plug-in processes all request internally so you 
   will need to add your webserver’s IP address to this list.  It is recommended, 
   when possible, that you use the IP address rather than the domain name here.   

For information on the additional Request Policy settings in SAM Broadcaster, 
please see your SAM Broadcaster documentation or integrated help files.

You will also need to ensure that your firewall and/or router are configured to 
allow incoming connections on the proper ports for MySQL and SAM Broadcaster.  By 
default, these ports are 1221 for SAM Broadcaster and 3306 for MySQL.

Once this is done, the hard part is out of the way.  Installation and configuration 
of the plug-in itself is very easy and straight forward.

= Manual Installation Instructions =
1.  Upload the NGS SAM Integrator Plug-In to your Wordpress Site and Activate it
2.  Open the NGS SAM Integrator Settings
3.  Enter Your SAM Broadcaster details including the host address and port 
    where the SAM Broadcaster client is running.
3.  Enter the Connection Details for your SAM Broadcaster Database.

    For security reasons, the "root" user should never be used for this or any 
    other web based application.  For this reason, the plug-in will not allow
    to use "root" as the database user name.  

    It is recommended that you configure a new database user specifically for
    use by the plug-in and only give it SELECT access on your SAM Database.  
    The plug-in does not need, nor would it make use of, any other privileges.
4.  The option for "Show Queue Time" will display a message to inform one of
    seven different messages to give your visitors a general idea of how long
    the queue is, but not a specific queue time.  (Default is YES)
5.  You may manually specify the post ID's of pages you have already set up to
    hold your play list and top requests list.  If these are left blank (or if
    the specified posts do not exist), new posts will automatically be created
    containing the proper shortcodes when you save your options.

== Frequently Asked Questions ==

= Will I need to make CSS modifications in order for the plug-in to work with my Theme? =

The plug-in minimizes the use of internal formatting, allowing your theme to 
determine as much about the appearance as possible.  This should allow the plug-in
to look nicely integrated with your site, regardless of the theme used, providing
the theme conforms with the standards for Wordpress themes, and makes proper use
of hooks.

All NGS SAM Integrator pages which appear on the front-end of your site are
contained with a div wrapper with the class "ngssamintegrator".  You can modify
the included CSS file to your liking to format these pages, if you desire.

= Does the plug-in support saving configurations for more than one DJ? =

While this is planned for a future version, the current version only supports
saving the details for a single SAM Broadcaster client.


= I am getting the message "Authorization failed. IP not in allowed list." =

Believe it or not, this message is a good thing.  It means the plug-in is 
properly configured and working.  This message is generated by the SAM 
Broadcaster client, so if you're seeing it, it means that your webserver and 
SAM Broadcaster client are successfully talking to one another.  

For security reasons, SAM Broadcaster will only accept incoming connections from 
IP addresses you permit.  You need to add the IP address of your webserver 
(which will be included in the error message) to SAM Broadcaster's allowed IP 
list.  You can find this under "File->Config->Request Policy" in your SAM 
Broadcaster client.

== Changelog ==

= 1.0.1 =
Initial Public Release

= 1.0.2 =
Removed some preliminary test code that was inadvertently included in the initial
release.  The code in question generated a blank widget.  Widgets are still being
worked for a later release (Likely the 1.1 release).

== Upgrade Notice ==

= 1.0.1 =
Initial Stable Release

= 1.0.2 =
NON-CRITICAL : Removal of Blank Widget