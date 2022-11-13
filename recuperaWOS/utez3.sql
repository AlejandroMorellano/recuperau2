drop database if exists utez3;
create database utez3;
use utez3;

create table docente (
   numeroEmpleado int primary key auto_increment,
   nombre varchar(50) not null,
   apellidos varchar(50) not null,
   fechaNac date not null,
   curp varchar(50) not null
);

create table alumno (
   matricula int primary key auto_increment,
   nombre varchar(50) not null,
   apellidos varchar(50) not null,
   fechaNac date not null,
   curp varchar(50) not null
   /*matricula varchar(50) primary key not null*/
);

create table calificacion (
   id bigint primary key auto_increment,
   materia varchar(50) not null,
   calificacion int(2)not null,
   matriculaAlumno int,
   
   constraint fk_alumno foreign key (matriculaAlumno) references alumno (matricula)
);