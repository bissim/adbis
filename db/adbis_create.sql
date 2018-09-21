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
  id integer(4) auto_increment unique,
  title VARCHAR(400) not null,
  author VARCHAR(150) not null,
  price FLOAT not null,
  image VARCHAR(250),
  link VARCHAR(250) not null,
  expiration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# review table
drop table if exists review;

create table if not exists review(
  id integer(4) auto_increment unique,
  title VARCHAR(200) not null,
  author VARCHAR(150) not null,
  plot text not null,
  txt text not null,
  average FLOAT,
  style FLOAT,
  content FLOAT,
  pleasantness FLOAT,
  expiration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

#
# PROCEDURES CREATION
#
delimiter //
create procedure prune()
  begin
    delete from book
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