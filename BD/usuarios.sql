create database usuarios;
use usuarios;

create table usuario(
id_usuario int primary key auto_increment,
usuario varchar(50),
pass varchar(50),
nombre varchar(50),
apellido varchar(50),
email varchar(60),
tipo int);

insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("admin", "admin1234", "admin", "admin", "admin@gmail", 0);