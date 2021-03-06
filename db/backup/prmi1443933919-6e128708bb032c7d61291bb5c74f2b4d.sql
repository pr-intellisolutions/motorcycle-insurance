

CREATE TABLE `activations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `token` varchar(256) NOT NULL,
  `request` date NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  CONSTRAINT `activations_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO activations VALUES("1","50807","kjnkjkjkjjkjkjk","2015-10-03","1");





CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(64) NOT NULL,
  `site_desc` varchar(128) NOT NULL,
  `site_host` varchar(64) NOT NULL,
  `site_module` varchar(64) NOT NULL,
  `user_minlen` int(11) NOT NULL,
  `user_maxlen` int(11) NOT NULL,
  `user_complexity` enum('alphanumeric','alphanumeric with spacers','','') NOT NULL,
  `pass_minlen` int(11) NOT NULL,
  `pass_maxlen` int(11) NOT NULL,
  `pass_complexity` enum('simple','normal','strong','') NOT NULL,
  `pass_expiration` int(11) NOT NULL,
  `max_login_attempts` int(11) NOT NULL,
  `activation_req` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO config VALUES("1","Puerto Rico Motorcycle Road Assistance Services","","127.0.0.1","/motorcycle-insurance/","5","24","alphanumeric with spacers","4","32","normal","60","4","0");





CREATE TABLE `login` (
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `user` varchar(32) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `email` varchar(128) NOT NULL,
  `role` enum('admin','provider','user') DEFAULT 'user',
  `regdate` datetime NOT NULL,
  `lastvisit` datetime NOT NULL,
  `lastip` varchar(32) NOT NULL,
  `lastbrowser` varchar(128) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `browser` varchar(128) NOT NULL,
  `session` varchar(256) NOT NULL,
  `token` varchar(256) NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL,
  `passchg` tinyint(1) NOT NULL DEFAULT '0',
  `passdate` datetime NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT '0',
  `permissions` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO login VALUES("7816","qwerty","$2y$11$rImxCU0KbEq.cuM0Qgd2N.5osl4.feKg9Mk5pRp9SyUTTqhC8l20i","qwerty","user","2015-09-22 20:43:17","2015-09-22 20:43:18","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","jp7fll4ef897884abvpfjaoaj4","","0","0","1","0","2015-11-21 20:43:17","0","none");
INSERT INTO login VALUES("12854","admin","$2y$11$dFeJ1Fu3kLdCU0pd.0kVP.j0y2UhKO/P8rGZClyPqzJpMdZ1MWscy","dennis.borrerotorres@gmail.com","admin","2015-02-02 23:37:12","2015-10-03 23:40:26","127.0.0.1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","127.0.0.1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","qmkebed37192dtuomaea2hd1j6","d4c41c9df9297866756b42514a4806c224c93541","0","0","1","0","2015-11-12 15:32:04","0","all");
INSERT INTO login VALUES("39824","test","$2y$11$jcWj3x8AxQdATeO4Gj4oYuLG0vybEbAllN/FSonjFNk6JiTBaVYYG","test@gmail.com","user","2015-08-09 19:56:07","2015-09-29 19:28:27","127.0.0.1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","127.0.0.1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","pcmc15b6a7u5sjj4gh48e5m8p1","27dd2f6dc6b3d94c36e33e86c1a12947b97982dc","0","0","1","0","2015-10-08 19:56:07","0","none");
INSERT INTO login VALUES("40910","test1","$2y$11$nugNWYRQ.HMnJ764NOi4Euqp8uzngPhIDJ5Aa..fUYOSBllRv4o7O","test","user","2015-09-22 20:49:31","2015-09-22 20:49:31","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0","kohtv0n82olbljivhj7a99rq32","","0","0","1","0","2015-11-21 20:49:31","0","none");
INSERT INTO login VALUES("48752","dborrero","$2y$11$7NI64MwCZf6doF4H/qAiJO9gN6kIHZaRx5hC4fgTfLmHBVEvbumi2","my@email.com","admin","2015-08-08 09:37:50","2015-08-08 13:12:50","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","j8t3dkvvraku0722e7p0kbvhs4","","0","0","1","0","2015-10-07 19:14:23","3","all");
INSERT INTO login VALUES("50807","provider","$2y$11$8Bp/psg/cyA25Chr87pkju.VPWwYzzMcryNqwHrLAu5TUdj/UAKdu","provider","provider","2015-08-08 18:00:45","0000-00-00 00:00:00","","","::1","Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko","j8t3dkvvraku0722e7p0kbvhs4","","0","0","1","0","2015-10-07 18:00:45","0","none");





CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) unsigned NOT NULL,
  `provider_id` int(11) unsigned NOT NULL,
  `vehicle_id` int(11) unsigned NOT NULL,
  `description` varchar(128) NOT NULL,
  `area` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `order_date` datetime NOT NULL,
  `dest_area` varchar(64) NOT NULL,
  `dest_city` varchar(64) NOT NULL,
  `estimated_miles` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`,`provider_id`),
  KEY `provider_id` (`provider_id`),
  KEY `vehicle_id` (`vehicle_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO orders VALUES("10","39824","11","3","Se quedo sin gasolina.","","","2015-10-04 00:15:24","","","0");





CREATE TABLE `plans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` varchar(128) NOT NULL,
  `num_occurrences` int(11) NOT NULL,
  `num_miles` int(11) NOT NULL,
  `num_vehicles` int(11) NOT NULL,
  `plan_price` decimal(5,2) NOT NULL,
  `mile_price` decimal(5,2) NOT NULL,
  `extend_price` decimal(5,2) NOT NULL,
  `term` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `date_entered` datetime NOT NULL,
  `last_modify` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

INSERT INTO plans VALUES("14","basico","Servicio B&aacute;sico","Incluye 4 ocurrencias en adici&oacute;n 50 millas de remolque","4","50","3","60.00","3.00","30.00","12","1","2015-07-28 21:37:01","2015-09-30 18:18:18");
INSERT INTO plans VALUES("15","extendido","Servicio Extendido","Incluye 4 ocurrencias en adici&oacute;n 100 millas de remolque","4","100","3","90.00","3.00","45.00","12","1","2015-07-28 21:45:00","2015-09-30 18:18:32");





CREATE TABLE `profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `first` varchar(32) NOT NULL,
  `middle` varchar(32) NOT NULL,
  `last` varchar(32) NOT NULL,
  `maiden` varchar(32) NOT NULL,
  `phone` varchar(18) NOT NULL,
  `address1` varchar(64) NOT NULL,
  `address2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `zip` varchar(18) NOT NULL,
  `country` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_ibfk_1` (`userid`),
  CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1039 DEFAULT CHARSET=latin1;

INSERT INTO profile VALUES("1016","12854","Dennis","J.","Borrero","Torres","(224) 321-7628","Urb. Santa Teresita","6109 Calle San Claudio","Ponce","PR","00731","Puerto Rico");
INSERT INTO profile VALUES("1020","48752","Dennis","J","Borrero","Torres","","","","","","","");
INSERT INTO profile VALUES("1030","50807","Rubeli","","Ortiz","","","","","","","","");
INSERT INTO profile VALUES("1031","39824","cuenta","de","prueba","","","","","","","","");
INSERT INTO profile VALUES("1037","7816","","","","","","","","","","","");
INSERT INTO profile VALUES("1038","40910","test","","test","","test","","","","","","");





CREATE TABLE `providers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `profile_id` int(11) unsigned NOT NULL,
  `companyName` varchar(64) NOT NULL,
  `companyPhone` varchar(18) NOT NULL,
  `companyEmail` varchar(128) NOT NULL,
  `area` enum('central','este','norte','noreste','noroeste','oeste','sur','sureste','suroeste') NOT NULL,
  `companyAddress1` varchar(64) NOT NULL,
  `companyAddress2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `zip` varchar(18) NOT NULL,
  `country` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_id` (`profile_id`),
  UNIQUE KEY `userid` (`userid`),
  CONSTRAINT `providers_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `providers_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO providers VALUES("11","50807","1030","Transporte Rubeli Ortiz","787 698 4545","rubeli.ortiz@gmail.com","norte","Urb. Lomas Verdes","Pepe","Bayamon","009322","Puerto Rico");





CREATE TABLE `reset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `token` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid_2` (`userid`),
  KEY `userid` (`userid`),
  CONSTRAINT `reset_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;






CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `plan_id` int(11) unsigned NOT NULL,
  `transaction` varchar(128) NOT NULL,
  `method` enum('PayPal','Visa','Mastercard','Check') NOT NULL,
  `amount` decimal(5,2) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`plan_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

INSERT INTO sales VALUES("1","12854","14","0BR99752Y1905570X","PayPal","60.00","2015-09-24 16:07:18");
INSERT INTO sales VALUES("11","39824","14","5D130364R19716613","PayPal","60.00","2015-09-27 14:56:30");
INSERT INTO sales VALUES("12","39824","14","94J89708662348150","PayPal","60.00","2015-09-28 16:45:11");
INSERT INTO sales VALUES("16","39824","15","1L5517513K5927159","PayPal","135.00","2015-09-29 20:08:45");
INSERT INTO sales VALUES("17","39824","14","1Y762571TK691962F","PayPal","60.00","2015-09-29 20:09:39");
INSERT INTO sales VALUES("18","39824","15","2EC795260R249471F","PayPal","135.00","2015-09-29 20:11:17");
INSERT INTO sales VALUES("19","39824","15","8RK98484LS281323X","PayPal","90.00","2015-09-29 20:12:19");
INSERT INTO sales VALUES("20","39824","15","8MJ81644DH987081G","PayPal","135.00","2015-09-29 20:23:38");
INSERT INTO sales VALUES("21","39824","15","9GC263306A4615315","PayPal","90.00","2015-09-29 20:24:28");
INSERT INTO sales VALUES("22","12854","15","5G904858NU9923321","PayPal","180.00","2015-09-30 18:19:24");





CREATE TABLE `services` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `plan_id` int(11) unsigned NOT NULL,
  `occurrence_counter` int(11) NOT NULL,
  `miles_counter` int(11) NOT NULL,
  `vehicle_counter` int(11) NOT NULL,
  `max_vehicles` int(11) NOT NULL,
  `renewal` tinyint(1) NOT NULL,
  `reg_date` date NOT NULL,
  `exp_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `services_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `services_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

INSERT INTO services VALUES("26","39824","14","3","0","2","2","0","0000-00-00","2016-09-26");
INSERT INTO services VALUES("33","39824","14","0","0","1","1","0","2015-09-28","2016-09-28");
INSERT INTO services VALUES("34","39824","15","0","0","0","2","0","2015-09-29","2016-09-29");
INSERT INTO services VALUES("35","39824","15","0","0","0","2","0","2015-09-29","2016-09-29");
INSERT INTO services VALUES("36","39824","15","0","0","0","1","0","2015-09-29","2016-09-29");
INSERT INTO services VALUES("37","12854","15","0","0","1","3","0","2015-09-30","2016-09-30");





CREATE TABLE `vehicles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `service_id` int(11) unsigned NOT NULL,
  `type` enum('carro','motora','lancha','bote') NOT NULL,
  `model` varchar(64) NOT NULL,
  `brand` varchar(64) NOT NULL,
  `year` int(11) NOT NULL,
  `plate` varchar(8) NOT NULL,
  `serial` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicles_ibfk_1` (`userid`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO vehicles VALUES("3","39824","26","motora","Yokohama","SD-232","2014","123-DFD","qwertyu6543wsdfg");
INSERT INTO vehicles VALUES("4","39824","26","motora","Yokohama","WQWE-21","1998","643-121","BNM53jklsdfJK");
INSERT INTO vehicles VALUES("5","39824","33","motora","Ducatti","Italia-21","2015","453-3232","wqaefsgjkfs123");
INSERT INTO vehicles VALUES("6","12854","37","motora","Ducatti","Italia-4452","2015","123-DFR","QWERXFVB567");



