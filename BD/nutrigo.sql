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
sesion varchar(255),
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
 
INSERT INTO receta (nombre, ingredientes, calorias, id_nutricionista) VALUES 
('Tostadas con aguacate', 'Pan tostado con aguacate y tomate', 300, 3),
('Ensalada de pollo', 'Pollo a la plancha, lechuga, tomate y aliño', 450, 3),
('Yogur con frutos secos', 'Yogur natural con almendras y miel', 250, 3),
('Salmón al horno con verduras', 'Salmón, calabacín, pimientos y especias', 500, 3),
('Batido de plátano y avena', 'Plátano, avena, leche y canela', 320, 3),
('Lentejas con verduras', 'Lentejas, zanahoria, cebolla y pimiento', 500, 3),
('Tostadas con mermelada casera', 'Pan integral con mermelada de frutos rojos', 280, 3),
('Pechuga de pollo con ensalada', 'Pechuga a la plancha con ensalada mixta', 450, 3),
('Cereales con leche', 'Copos de avena con leche y miel', 350, 3),
('Pasta integral con atún', 'Pasta, atún, tomate natural y especias', 520, 3),
('Fruta y frutos secos', 'Manzana, almendras y nueces', 200, 3),
('Merluza al horno con patatas', 'Merluza, patatas y aceite de oliva', 480, 3),
('Tostadas con queso fresco', 'Pan integral con queso fresco y tomate', 290, 3),
('Arroz con verduras', 'Arroz, zanahoria, calabacín y soja', 480, 3),
('Batido de frutos rojos', 'Fresas, arándanos, yogur y miel', 270, 3),
('Tortilla de espinacas', 'Huevos, espinacas y queso', 460, 3),
('Bizcocho de avena', 'Avena, plátano, huevo y miel', 330, 3),
('Garbanzos con espinacas', 'Garbanzos, espinacas y ajo', 500, 3),
('Tostadas con crema de cacahuete', 'Pan integral con crema de cacahuete natural', 310, 3),
('Dorada a la plancha con ensalada', 'Dorada, lechuga, tomate y aceite de oliva', 490, 3),
('Pudding de chía', 'Chía, leche de almendra y frutos secos', 320, 3),
('Berenjenas rellenas', 'Berenjenas, carne picada, tomate y queso', 530, 3),
('Yogur con granola', 'Yogur natural con granola casera', 260, 3),
('Hamburguesa casera', 'Carne de ternera, pan integral, tomate y lechuga', 520, 3),
('Tostadas con miel y nueces', 'Pan integral, miel y nueces', 310, 3),
('Paella de mariscos', 'Arroz, gambas, mejillones y calamares', 550, 3),
('Smoothie de mango', 'Mango, yogur y semillas de chía', 290, 3),
('Pechuga de pavo con verduras', 'Pechuga de pavo a la plancha con verduras al vapor', 480, 3),
('Gachas de avena', 'Avena, leche, canela y manzana', 340, 3),
('Ensalada de garbanzos', 'Garbanzos, pimientos, cebolla y aliño', 460, 3),
('Queso fresco con miel', 'Queso fresco, miel y nueces', 280, 3),
('Lubina a la plancha con arroz', 'Lubina, arroz y espárragos', 500, 3),
('Batido de cacao y plátano', 'Plátano, cacao puro, leche y dátiles', 330, 3),
('Sopa de verduras', 'Zanahoria, puerro, apio y patata', 390, 3),
('Tostadas con ricotta y miel', 'Pan integral, ricotta y miel', 300, 3),
('Pollo al curry con arroz', 'Pollo, curry, arroz y leche de coco', 520, 3);

 
 insert into menu_semanal(id_paciente, id_nutricionista, id_receta, dia_semana, comida) values 
 (1, 1, 1, 'Lunes', 'Desayuno'), (1, 1, 2, 'Lunes', 'Almuerzo'), (1, 1, 3, 'Lunes', 'Cena'),
 (1, 1, 4, 'Martes', 'Desayuno'), (1, 1, 5, 'Martes', 'Almuerzo'), (1, 1, 6, 'Martes', 'Cena'),
 (1, 1, 7, 'Miercoles', 'Desayuno'), (1, 1, 8, 'Miercoles', 'Almuerzo'), (1, 1, 9, 'Miercoles', 'Cena'),
 (1, 1, 10, 'Jueves', 'Desayuno'), (1, 1, 11, 'Jueves', 'Almuerzo'), (1, 1, 12, 'Jueves', 'Cena'),
 (1, 1, 13, 'Viernes', 'Desayuno'), (1, 1, 14, 'Viernes', 'Almuerzo'), (1, 1, 15, 'Viernes', 'Cena'),
 (1, 1, 16, 'Sabado', 'Desayuno'), (1, 1, 17, 'Sabado', 'Almuerzo'), (1, 1, 18, 'Sabado', 'Cena'),
 (1, 1, 19, 'Domingo', 'Desayuno'), (1, 1, 20, 'Domingo', 'Almuerzo'), (1, 1, 21, 'Domingo', 'Cena');
 

 
 insert into medidas_paciente (id_paciente, altura, peso, grasa_corporal, musculo) values (1, 1.75, 70.5, 15.5, 40.2);
 insert into medidas_paciente (id_paciente, altura, peso, grasa_corporal, musculo) values (2, 60, 60.5, 13.5, 38.2);
 
 insert into objetivos_paciente (id_paciente, id_nutricionista, objetivo_peso, objetivo_grasa_corporal, objetivo_musculo) values (1, 1, 65.0, 12.0, 45.0);
 select * from nutricionista;
 select * from paciente;
 select * from medidas_paciente where id_paciente = 1;
 drop database nutrigo;