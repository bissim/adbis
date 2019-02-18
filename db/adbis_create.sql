#############################
#							#
#		DATA DEFINITION		#
#							#
#############################


#
# DATABASE CREATION
#
drop schema if exists adbis_db;

create schema if not exists adbis_db;

use adbis_db;


#
# TABLES CREATION
#

# book table
drop table if exists book;

create table if not exists book (
  id INTEGER(4) auto_increment unique,
  title VARCHAR(400) not null,
  author VARCHAR(150) not null,
  price FLOAT not null,
  img VARCHAR(250),
  link VARCHAR(250) not null,
  expiration_date TIMESTAMP default CURRENT_TIMESTAMP,
  is_recent TINYINT(1) default 0,
  source VARCHAR(10) not null
);

# book table
drop table if exists audioBook;

create table if not exists audioBook (
  id INTEGER(4) auto_increment unique,
  title VARCHAR(400) not null,
  author VARCHAR(150) not null,
  voice VARCHAR(150) not null,
  img VARCHAR(250),
  link VARCHAR(250) not null,
  expiration_date TIMESTAMP default CURRENT_TIMESTAMP,
  is_recent TINYINT(1) default 0
);

# review table
drop table if exists review;

create table if not exists review (
  id INTEGER(4) auto_increment unique,
  title VARCHAR(200) not null,
  author VARCHAR(150) not null,
  plot TEXT not null,
  txt TEXT not null,
  average FLOAT,
  style FLOAT,
  content FLOAT,
  pleasantness FLOAT,
  expiration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_recent TINYINT(1) default 0
);

#
# PROCEDURES CREATION
#
delimiter //
create procedure prune()
  begin
    delete from book
    where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 30;

    delete from audioBook
    where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 30;

    delete from review
    where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 30;
  end//

delimiter ;

#
# EVENTS CREATION
#
create event if not exists pruner
  on schedule every 30 second
do
  call prune();