create database nutrigo;
use nutrigo;

create table usuario(
id_usuario int primary key auto_increment,
usuario varchar(50),
pass varchar(255),
nombre varchar(50),
apellido varchar(50),
email varchar(60),
tipo int);

create table receta(
id_receta int primary key auto_increment,
nombre varchar(50),
ingredientes text,
calorias int);

create table calendario(
id_calendario int primary key auto_increment,
dia varchar(50),
usuario int ,
receta int ,
foreign key (usuario) references usuario(id_usuario));

create table lista(
id_lista int primary key auto_increment,
usuario int,
plato int,
foreign key (usuario) references usuario(id_usuario),
foreign key (plato) references receta(id_receta));

 insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("admin", "admin1234", "admin", "admin", "admin@gmail", 3);