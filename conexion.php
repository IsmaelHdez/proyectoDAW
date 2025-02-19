<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "cocinathor";

function conectar(){
    $con = mysqli_connect($GLOBALS["host"],$GLOBALS["username"],$GLOBALS["password"]) or die("Error en la conexión a la base de datos.");
    crear_bdd($con);
    mysqli_select_db($con, $GLOBALS["dbname"]);
    crear_tabla_registro($con);
    crear_tabla_recetas($con);
    crear_tabla_calendario($con);
    return $con;
  }
  $con = conectar();
  crear_bdd($con);
  crear_tabla_registro($con);
  crear_tabla_recetas($con);
  crear_tabla_calendario($con);

function crear_bdd($con){
	mysqli_query($con, "create database if not exists cocinathor;");
}

function crear_tabla_registro($con){
    mysqli_query($con, "create table if not exists registro(id_usuario int primary key auto_increment, nombre varchar(100),pass varchar(100),tipo int CHECK(tipo IN (0,1,2)))");
}

function crear_tabla_recetas($con){
    mysqli_query($con, "create table if not exists recetas(id_receta int primary key auto_increment, nombre varchar(100),ingredientes text(500),calorias int)");
    
}

function crear_tabla_calendario($con){
	mysqli_query($con, "create table if not exists calendario(id_calendario int primary key auto_increment, dia varchar(100), usuario int, receta int, foreign key (usuario) references registro(id_usuario),foreign key (receta) references recetas(id_receta))");
}
?>