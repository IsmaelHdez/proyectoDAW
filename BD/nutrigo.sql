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
 insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("caro", "caro1234", "Casimiro", "Aroca Henares", "arocahenares@gmail.com", 1);
 insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("pepe", "pepe1234", "Jose", "Sanchez Lopez", "sanchezlopez@gmail.com", 1);
 
 insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("torrente", "torrente1234", "Santiago", "Segura Martin", "seguramartin@gmail.com", 2);
 insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("maca", "maca1234", "Mario", "Casas Sierra", "casassierra@gmail.com", 2);
 insert into usuario(usuario, pass, nombre, apellido, email, tipo) values ("joselu", "joselu1234", "Jose Luis", "Pajares Paz", "pajarespaz@gmail.com", 2);
 
 insert into receta(nombre, ingredientes, calorias) values ("Paella", "Arroz con marisco , pollo o mixta", 400);
 insert into receta(nombre, ingredientes, calorias) values ("Lasaña", "Carne picada con verduras , entre láminas finas de pasta y queso gratinado", 450);
 insert into receta(nombre, ingredientes, calorias) values ("Arroz con leche", "Postre de arroz cocinado con leche , azucar y canela", 250);
 
 select usuario , nombre , apellido , email from usuario where tipo = 1;
 select * from receta;