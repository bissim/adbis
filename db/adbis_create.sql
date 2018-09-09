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
  id integer(4) auto_increment unique
);

# review table
drop table if exists review;

create table if not exists review(
  id integer(4) auto_increment unique
);

#
# PROCEDURES CREATION
#
