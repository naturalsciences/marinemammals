<?php return unserialize('a:2:{i:0;O:26:"Doctrine\\ORM\\Mapping\\Table":5:{s:4:"name";s:7:"PERSONS";s:6:"schema";N;s:7:"indexes";a:1:{i:0;O:26:"Doctrine\\ORM\\Mapping\\Index":2:{s:4:"name";s:12:"psn_ite_fk_i";s:7:"columns";a:1:{i:0;s:9:"ITE_SEQNO";}}}s:17:"uniqueConstraints";a:2:{i:0;O:37:"Doctrine\\ORM\\Mapping\\UniqueConstraint":2:{s:4:"name";s:12:"psn_email_uk";s:7:"columns";a:1:{i:0;s:5:"EMAIL";}}i:1;O:37:"Doctrine\\ORM\\Mapping\\UniqueConstraint":2:{s:4:"name";s:6:"psn_uk";s:7:"columns";a:2:{i:0;s:10:"FIRST_NAME";i:1;s:9:"LAST_NAME";}}}s:7:"options";a:0:{}}i:1;O:27:"Doctrine\\ORM\\Mapping\\Entity":2:{s:15:"repositoryClass";s:45:"AppBundle\\Entity\\Repository\\PersonsRepository";s:8:"readOnly";b:0;}}');