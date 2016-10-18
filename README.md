# dataplotting

This repo is for keeping my go-to templates for HTML Data plotting. Most of them are implementations of the charts located at http://nvd3.org/ modified to match up with my own data sources. 

For now, the only one up is Generic_Graph_From_Mysql.html which is a multi line chart with a view finder. The important part is that it comes with a PHP script that can pull data from mysql server and format in a way that nvd3 graphs like. 

<pre>
If your data is formated like this: 
+---------------------+-------------+---------+
| tdate               | temperature | zone    |
+---------------------+-------------+---------+
| 2016-09-02 05:32:51 |    24.20000 | ambient |
| 2016-09-01 19:16:42 |    24.20000 | ambient |
| 2016-07-15 14:28:21 |    26.30000 | ambient |
| 2016-09-08 18:04:23 |    24.20000 | ambient |
| 2016-09-02 06:56:57 |    24.20000 | ambient |
| 2016-09-01 23:46:14 |    24.20000 | ambient |
| 2016-10-02 01:17:51 |    24.25000 | engine  |
| 2016-09-29 08:16:09 |    24.75000 | engine  |
| 2016-09-02 13:39:25 |    26.75000 | engine  |
| 2016-09-08 12:20:02 |    25.25000 | engine  |
+---------------------+-------------+---------+
</pre>
then the php and html file pair will create two lines for both "ambient" and "engine". Configuration details are included in the comments of each file 



For my purposes these charts are normally hosted on a Raspberry Pi with lighttpd. 
To setup a pi with these files run these commands in order:
sudo apt-get update
sudo apt-get install lighttpd
sudo apt-get install php5-common php5-cgi php5
sudo apt-get install php5-mysql
sudo lighty-enable-mod fastcgi-php
sudo service lighttpd force-reload

