# RELEASE NOTE FOR REL-

## RELEASE NOTE FOR REL REL-0021

### Features

- FEAT-ADMIN-COMP


### Pasos

- Entorno
- BackUp DB
- Backup /pages
- Cambios en DB

CREATE TABLE `adm_dnl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `adm_cmp_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_periodo` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `dias` float DEFAULT NULL,
  `origen` varchar(1) DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `adm_cmp_guardias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_balance` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `fecha_desde` datetime NOT NULL,
  `fecha_hasta` datetime NOT NULL,
  `minutos_efectivos` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `lote` int(11) NOT NULL,
  `justificacion` varchar(255) DEFAULT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `adm_cmp_lote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `id_persona` int(11) NOT NULL,
  `nombre_archivo` varchar(50) NOT NULL,
  `borrado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `adm_cmp_periodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_desde` datetime NOT NULL,
  `fecha_hasta` datetime NOT NULL,
  `estatus` int(11) NOT NULL DEFAULT '0',
  `borrado` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


// CARGA INICIAL

insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-10-12','Dia de la raza',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-10-14','Puente',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-11-18','Soberania',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-12-24','Navidad',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-12-25','Navidad',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-12-31','Anio Nuevo',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2019-01-01','Anio Nuevo',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2020-02-24','Carnaval',0);
insert into `adm_dnl`(`fecha`,`descripcion`,`borrado`) values ('2020-02-25','Carnaval',0);


insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2019-10-11 00:00:00','2019-11-10 23:59:59',0,0);
insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2019-11-11 00:00:00','2019-12-10 23:59:59',0,0);
insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2019-12-11 00:00:00','2020-01-10 23:59:59',0,0);
insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2020-01-11 00:00:00','2020-02-10 00:00:00',0,0);
insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2020-02-11 00:00:00','2020-03-10 00:00:00',0,0);
insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2020-03-11 00:00:00','2020-04-10 00:00:00',0,0);
insert into `adm_cmp_periodos`(`fecha_desde`,`fecha_hasta`,`estatus`,`borrado`) values ('2020-04-11 00:00:00','2020-05-10 00:00:00',0,0);



- Cambios en src
    N[.php]
    M[pages/.php]
