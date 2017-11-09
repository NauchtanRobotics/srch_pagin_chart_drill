# srch_pagin_chart_drill


How, What and Why?

A CakePHP 3 project web interface which incorporates a d3.js (v4) interactive vertical bar chart, a form and a paginated table all displayed in a index table. The chart shows the created date for parts so could be used to monitor data growth in the early phase of ERP establishment. However, the purpose is to provide sample code for a common combination of analytics tool including search fields, an interactive chart which allows drilling down monthly total counts to see daily counts, and paginated display of records in the search range.  Robust record filtering is provided using friendsOfCake's search.prg plugin.

It is a simple project which centres around just one table 'parts' which has only 4 columns.  In addition to the index page for parts, simple add, edit and view (individual record view) views are also provided.

In the future, this project might grow into a fullblown asset management information system. Stay tuned.


Installation

Create a database called 'demo' and then run the script "mysql_script.sql" to create the table "parts" exactly as assumed in the CakePHP project.
Install Composer in the folder designated for the app (e.g. your webroot or some sub-folder)
Use Composer to install CakePHP 3.x (>=3.5) in the same folder as per CakePHP's installation guide, changing folder permissions where required.
Use Composer to download the plugin "search.prg" from the FriendsOfCake repository on Github to your app directory.
Edit MyApp/config/app.php changing the username and password to those which you used when creating the demo database.



Known Issues

Symbolic links have not been included - will be fixed soon.  Make sure that all of the plugins including search.prg are installed and reference correctly.

Unit tests have not yet been created yet. That is planned for the next phase. A future migration will also change the parts table column partname to part_name so that naming convention is correctly adhered to.

The initial search returns the first 200 records only.  This query should be modified to sort by date descending if you want to chart the most recent records.  The limit of 200 can be increased to whatever you feel is appropriate.  

Excessive objects have been created in the javascript file hierarchicalbarcharts.js.  This is artifact of development and needs to be cleaned up.  It was unnecessary to create a hierarchy object for this type of chart, and has made the code a little more complex.  The intention is to gradually build a flexible API which can handle data which has varying amounts of hierarchical structure.

The user should be warned if there is data missing from the filtered range, as imposed by the select query limit.  

Data is downloaded each time the data is filtered.  This puts unnecessary burden on the server, whereas, it would be best to download the data only once then use javascript to filter the dataset on the client side.  This was a lazy shortcut in truth, however, it has the advantage of allowing the user to restrict/filter the part names, e.g. "holden" thereby increasing the date range that they can download for a given select records limit.
