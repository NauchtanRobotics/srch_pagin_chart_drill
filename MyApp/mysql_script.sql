#create at table called demo and uncomment the line below.  If you use a different database name, be sure to reflect that in the cakePHP config/app.php file;
#use demo; 

CREATE TABLE `parts` (
 `part_id` int(11) NOT NULL AUTO_INCREMENT,
 `partname` varchar(50) NOT NULL,
 `created` date NOT NULL,
 `modified` date NOT NULL,
 PRIMARY KEY (`part_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin;
