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
  title VARCHAR(30) not null,
  author VARCHAR(30) not null,
  price FLOAT not null,
  image VARCHAR(50),
  link VARCHAR(50) not null,
  editor VARCHAR(30),
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

# review table
drop table if exists review;

create table if not exists review(
  id integer(4) auto_increment unique,
  title VARCHAR(30) not null,
  author VARCHAR(30) not null,
  plot text not null,
  text text not null,
  average FLOAT,
  style FLOAT,
  content FLOAT,
  pleasentness FLOAT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

#
# PROCEDURES CREATION
#
