create database if not exists nutrigo;
use nutrigo;

create table nutricionista(
id_nutricionista int primary key auto_increment,
usuario varchar(50),
pass varchar(255),
nombre varchar(50),
apellido varchar(50),
email varchar(60),
tipo int);

create table paciente(
id_paciente int primary key auto_increment,
usuario varchar(50),
pass varchar(255),
nombre varchar(50),
apellido varchar(50),
email varchar(60),
id_nutricionista int,
foreign key(id_nutricionista) references nutricionista(id_nutricionista) );

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
foreign key (usuario) references nutricionista(id_nutricionista));

create table lista(
id_lista int primary key auto_increment,
paciente int,
plato int,
foreign key (paciente) references paciente(id_paciente),
foreign key (plato) references receta(id_receta));

create table citas(
id_citas int primary key auto_increment,
fecha date not null,
hora time not null,
paciente int,
nutricionista int,
foreign key (paciente) references paciente(id_paciente),
foreign key (nutricionista) references nutricionista(id_nutricionista));

 insert into nutricionista(usuario, pass, nombre, apellido, email, tipo) values ("admin", "admin1234", "admin", "admin", "admin@gmail", 3);
 insert into nutricionista(usuario, pass, nombre, apellido, email, tipo) values ("caro", "caro1234", "Casimiro", "Aroca Henares", "arocahenares@gmail.com", 1);
 insert into nutricionista(usuario, pass, nombre, apellido, email, tipo) values ("pepe", "pepe1234", "Jose", "Sanchez Lopez", "sanchezlopez@gmail.com", 1);
 
 insert into paciente(usuario, pass, nombre, apellido, email,id_nutricionista) values ("torrente", "torrente1234", "Santiago", "Segura Martin", "seguramartin@gmail.com", 1);
 insert into paciente(usuario, pass, nombre, apellido, email,id_nutricionista) values ("maca", "maca1234", "Mario", "Casas Sierra", "casassierra@gmail.com", 1);
 insert into paciente(usuario, pass, nombre, apellido, email,id_nutricionista) values ("joselu", "joselu1234", "Jose Luis", "Pajares Paz", "pajarespaz@gmail.com", 1);
 
 insert into receta(nombre, ingredientes, calorias) values ("Paella", "Arroz con marisco , pollo o mixta", 400);
 insert into receta(nombre, ingredientes, calorias) values ("Lasaña", "Carne picada con verduras , entre láminas finas de pasta y queso gratinado", 450);
 insert into receta(nombre, ingredientes, calorias) values ("Arroz con leche", "Postre de arroz cocinado con leche , azucar y canela", 250);
 
 select usuario , nombre , apellido , email , id_nutricionista from nutricionista ;
 select n.usuario from nutricionista n join citas c on n.id_nutricionista = c.nutricionista; 
 select * from citas;
 select usuario , nombre , apellido , email from nutricionista where tipo = 1 like "aro";
 select usuario , nombre , apellido , email , id_nutricionista from paciente;
 select * from nutricionista;
 drop table citas;
 drop database nutrigo;