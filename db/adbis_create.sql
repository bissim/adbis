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
  src VARCHAR(10) not null
);

# audiobook table
drop table if exists audiobook;

create table if not exists audiobook (
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
  expiration_date TIMESTAMP default CURRENT_TIMESTAMP,
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

    delete from audiobook
      where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 30;

    delete from review
      where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 30;
  end //

create procedure update_recents()
  begin
    update book
      set is_recent = 0
      where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 7 AND is_recent = 1;

    update audiobook
      set is_recent = 0
      where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 7 AND is_recent = 1;

    update review
      set is_recent = 0
      where TIMESTAMPDIFF(DAY, `expiration_date`, NOW()) > 7 AND is_recent = 1;
  end //
delimiter ;


#
# EVENTS CREATION
#
create event if not exists pruner
  on schedule every 30 day -- TODO check interval
do
  call prune();

create event if not exists recents_updater
  on schedule every 7 day -- TODO check interval
do
  call update_recents();
