create database if not exists nutrigo;
use nutrigo;

create table nutricionista(
id_nutricionista int primary key auto_increment,
usuario varchar(50),
pass varchar(255),
nombre varchar(50),
apellido varchar(50),
email varchar(60),
sesion varchar(255),
tipo int);

create table paciente(
id_paciente int primary key auto_increment,
usuario varchar(50),
pass varchar(255),
nombre varchar(50),
apellido varchar(50),
email varchar(60),
tipo int,
id_nutricionista int,
foreign key(id_nutricionista) references nutricionista(id_nutricionista) );

create table receta(
id_receta int primary key auto_increment,
nombre varchar(50),
ingredientes text,
calorias int,
id_nutricionista int,
foreign key(id_nutricionista) references nutricionista(id_nutricionista) );

create table menu_semanal (
    id_menu int primary key auto_increment,
    id_paciente int,
    id_nutricionista int,
    id_receta int,
    dia_semana enum('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
    comida enum('Desayuno', 'Almuerzo', 'Cena'),
    foreign key (id_paciente) references paciente(id_paciente),
    foreign key (id_nutricionista) references nutricionista(id_nutricionista),
    foreign key (id_receta) references receta(id_receta),
    unique (id_paciente, dia_semana, comida) );

create table medidas_paciente (
	id_progreso int primary key auto_increment,
    id_paciente int,
    fecha_registro date,
    altura decimal(5,2),
    peso decimal(5,2),
    grasa_corporal decimal(5,2),
    musculo decimal(5,2),
    imc decimal(5,2) as (peso / (altura * altura)),
    foreign key (id_paciente) references paciente(id_paciente) );

create table objetivos_paciente (
	id_objetivo int primary key auto_increment,
    id_paciente int,
    id_nutricionista int,
    objetivo_peso decimal(5,2),
    objetivo_grasa_corporal decimal(5,2),
    objetivo_musculo decimal(5,2),
    foreign key (id_paciente) references paciente(id_paciente),
    foreign key (id_nutricionista) references nutricionista(id_nutricionista),
    unique (id_paciente) );

create table citas(
id_citas int primary key auto_increment,
fecha date not null,
hora time not null,
paciente int,
nutricionista int,
foreign key (paciente) references paciente(id_paciente),
foreign key (nutricionista) references nutricionista(id_nutricionista));

 insert into nutricionista(usuario, pass, nombre, apellido, email, tipo) values ("admin", "$2y$10$HOKoILE97x0Om4f5IIuE1u.cv1vm3NTfPUtr0zNncNwEQwga5vFeS", "admin", "admin", "admin@gmail", 3);
 insert into nutricionista(usuario, pass, nombre, apellido, email, tipo) values ("caro", "caro1234", "Casimiro", "Aroca Henares", "arocahenares@gmail.com", 1);
 insert into nutricionista(usuario, pass, nombre, apellido, email, tipo) values ("pepe", "pepe1234", "Jose", "Sanchez Lopez", "sanchezlopez@gmail.com", 1);
 
 insert into paciente(usuario, pass, nombre, apellido, email, tipo, id_nutricionista) values ("torrente", "$2y$10$Jmdca9UXTVxVP/DlqoLiJ.WVeUhRpigI84mgIEOqp5HS/6Qrbmpz6", "Santiago", "Segura Martin", "seguramartin@gmail.com", 2, 1);
 insert into paciente(usuario, pass, nombre, apellido, email,id_nutricionista) values ("maca", "maca1234", "Mario", "Casas Sierra", "casassierra@gmail.com", 1);
 insert into paciente(usuario, pass, nombre, apellido, email,id_nutricionista) values ("joselu", "joselu1234", "Jose Luis", "Pajares Paz", "pajarespaz@gmail.com", 1);
 
 insert into receta(nombre, ingredientes, calorias, id_nutricionista) values ("Paella", "Arroz con marisco , pollo o mixta", 400, 2);
 insert into receta(nombre, ingredientes, calorias, id_nutricionista) values ("Lasaña", "Carne picada con verduras , entre láminas finas de pasta y queso gratinado", 450, 3);
 insert into receta(nombre, ingredientes, calorias, id_nutricionista) values ("Arroz con leche", "Postre de arroz cocinado con leche , azucar y canela", 250, 3);
 
 insert into menu_semanal(id_paciente, id_nutricionista, id_receta, dia_semana, comida) values (1, 1, 1, 'Lunes', 'Desayuno');
 insert into menu_semanal(id_paciente, id_nutricionista, id_receta, dia_semana, comida) values (1, 1, 2, 'Lunes', 'Almuerzo');
 insert into menu_semanal(id_paciente, id_nutricionista, id_receta, dia_semana, comida) values (1, 1, 3, 'Lunes', 'Cena'); 
 
 insert into medidas_paciente (id_paciente, altura, peso, grasa_corporal, musculo) values (1, 1.75, 70.5, 15.5, 40.2);
 insert into medidas_paciente (id_paciente, altura, peso, grasa_corporal, musculo) values (2, 60, 60.5, 13.5, 38.2);
 
 insert into objetivos_paciente (id_paciente, id_nutricionista, objetivo_peso, objetivo_grasa_corporal, objetivo_musculo) values (1, 1, 65.0, 12.0, 45.0);
 select * from nutricionista;
 select * from paciente;
 drop database nutrigo;