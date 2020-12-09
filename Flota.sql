CREATE DATABASE FlotaPW;
USE FlotaPW;

CREATE TABLE IF NOT EXISTS Empleado(
dni int not null,
nombre varchar(50) not null,
apellido varchar(50) not null,
email varchar(50) not null,
password varchar(64) not null,
fnac date,
rol varchar(15),
estado boolean DEFAULT 0,
fechaBaja date,
PRIMARY KEY(dni)
);

CREATE TABLE IF NOT EXISTS Chofer(
dniChof int,
tipoLicencia varchar(10),
disponibilidad boolean DEFAULT 1,
PRIMARY KEY(dniChof),
FOREIGN KEY(dniChof) REFERENCES Empleado(dni)
);

CREATE TABLE IF NOT EXISTS Mecanico(
dniMec int,
matricula int,
disponibilidad boolean DEFAULT 1,
PRIMARY KEY(dniMec),
FOREIGN KEY(dniMec) REFERENCES Empleado(dni)
);

CREATE TABLE IF NOT EXISTS Cliente(
cuit bigint not null,
denominacion varchar(100),
direccion varchar(255),
telefono int,
email varchar(50),
contacto1 int,
contacto2 int,
PRIMARY KEY(cuit)
);

CREATE TABLE IF NOT EXISTS Vehiculo(
patente varchar(7) not null,
nroChasis int not null,
marca varchar(20),
modelo varchar(50),
kmTotales float,
anoFabricacion year,
fechaService date,
tipo varchar(15),
estado boolean,
PRIMARY KEY(patente)
);

CREATE TABLE IF NOT EXISTS Tractor(
patTractor varchar(7) not null,
nroMotor int,
consumo double,
PRIMARY KEY(patTractor),
FOREIGN KEY(patTractor) REFERENCES Vehiculo(patente)
);

CREATE TABLE IF NOT EXISTS Arrastrado(
patArrastrado varchar(7) not null,
tipoCarga varchar(10),
PRIMARY KEY(patArrastrado),
FOREIGN KEY(patArrastrado) REFERENCES Vehiculo(patente)
);

CREATE TABLE IF NOT EXISTS Viaje(
codViaje int not null AUTO_INCREMENT,
origen varchar(255),
latOrigen decimal(10,6),
longOrigen decimal(11,8),
destino varchar(255),
latDestino decimal(10,6),
longDestino decimal(11,8),
estado varchar(15) DEFAULT 'en carga',
eta timestamp,
etd timestamp,
fcarga date,
fllegada date,
kmEstimado double,
kmTotales double,
consumoEstimado double,
consumoTotal double,
chofer int,
tractor varchar(7),
cliente bigint,
arrastrado varchar(7),
PRIMARY KEY(codViaje),
FOREIGN KEY(chofer) REFERENCES Empleado(dni) ON DELETE SET NULL,
FOREIGN KEY(tractor) REFERENCES Vehiculo(patente) ON DELETE SET NULL,
FOREIGN KEY(cliente) REFERENCES Cliente(cuit) ON DELETE SET NULL,
FOREIGN KEY(arrastrado) REFERENCES Vehiculo(patente) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS ImoClass(
idClase int,
especialidad varchar(100),
PRIMARY KEY(idClase)
);

CREATE TABLE IF NOT EXISTS ImoSubclass(
idSubclase int,
nroSubclase int,
especialidadSub varchar(100),
idClase int,
PRIMARY KEY(idSubclase),
FOREIGN KEY(idClase) REFERENCES imoClass(idClase)
);
CREATE TABLE IF NOT EXISTS Carga(
codCarga int not null AUTO_INCREMENT,
viaje int not null,
tipo varchar(15),
hazard boolean,
reefer boolean,
temperatura double,
imoClass int,
imoSubclass int,
pesoNeto double,
PRIMARY KEY(codCarga),
FOREIGN KEY(viaje) REFERENCES Viaje(codViaje),
FOREIGN KEY(imoClass) REFERENCES ImoClass(idClase),
FOREIGN KEY(imoSubclass) REFERENCES ImoSubclass(idSubclase)
);
CREATE TABLE IF NOT EXISTS Costeo(
codCosteo int not null AUTO_INCREMENT,
tipo varchar(10) not null,
km double DEFAULT 0,
consumo double DEFAULT 0,
viaticos double DEFAULT 0,
peajes double DEFAULT 0,
pesajes double DEFAULT 0,
extras double DEFAULT 0,
hazard double DEFAULT 0,
reefer double DEFAULT 0,
fee double DEFAULT 0,
desvio double DEFAULT 0,
viaje int,
PRIMARY KEY(codCosteo),
FOREIGN KEY(viaje) REFERENCES Viaje(codViaje)
);

CREATE TABLE IF NOT EXISTS Ubicacion(
codUbicacion int not null AUTO_INCREMENT,
latitud decimal(10,6),
longitud decimal(11,8),
viaje int,
kmRecorridos double,
viaticos double DEFAULT 0,
peajes double DEFAULT 0,
pesajes double DEFAULT 0,
extras double DEFAULT 0,
fecha timestamp,
consumo double DEFAULT 0,
PRIMARY KEY(codUbicacion),
FOREIGN KEY(viaje) REFERENCES Viaje(codViaje)
);

CREATE TABLE IF NOT EXISTS Combustible(
codCombustible int not null AUTO_INCREMENT,
numViaje int,
latComb decimal(10,6),
longComb decimal(11,8),
cantidad double,
importe double,
PRIMARY KEY(codCombustible),
FOREIGN KEY(numViaje) REFERENCES Viaje(codViaje)
);

CREATE TABLE IF NOT EXISTS Service(
codigo int not null AUTO_INCREMENT,
intext boolean,
fechaInicio date,
fechaFin date,
costo double,
mecanico int,
vehiculo varchar(7),
observaciones longtext,
PRIMARY KEY(codigo),
FOREIGN KEY(mecanico) REFERENCES Empleado(dni) ON DELETE SET NULL,
FOREIGN KEY(vehiculo) REFERENCES Vehiculo(patente) ON DELETE SET NULL
);

INSERT INTO Empleado (dni, nombre, apellido, email, password, fnac, rol, estado) VALUES
(12345678, 'User', 'Admin', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1931-09-18', 'administrador', 1),
(11111111, 'User', 'Supervisor', 'supervisor@gmail.com', 'b59c67bf196a4758191e42f76670ceba', '1986-09-22', 'supervisor', 1),
(22222222, 'User', 'Mecanico', 'mecanico@gmail.com', '934b535800b1cba8f96a5d72f72f1611', '1975-09-23', 'mecanico', 1),
(33333333, 'User1', 'Chofer', 'chofer@gmail.com', '2be9bd7a3434f7038ca27d1918de58bd', '1990-10-12', 'chofer', 1),
(44444444, 'User2', 'Chofer', 'sinrol@gmail.com', 'dbc4d84bfcfe2284ba11beffb853a8c4', '1950-12-05', 'chofer', 1),
(55555555, 'User3', 'Chofer', 'chofer@gmail.com', '6074c6aa3488f3c2dddff2a7ca821aab', '1985-12-12', 'chofer', 1),
(66666666, 'User4', 'Chofer', 'chofer@gmail.com', 'e9510081ac30ffa83f10b68cde1cac07', '1995-10-04', 'chofer', 1),
(77777777, 'User5', 'Chofer', 'chofer@gmail.com', 'd79c8788088c2193f0244d8f1f36d2db', '1994-10-31', 'chofer', 1);

INSERT INTO Mecanico (dniMec, matricula, disponibilidad)
VALUES (22222222, 1, 1);

INSERT INTO Chofer (dniChof, tipoLicencia, disponibilidad)
VALUES (33333333, 'A', 1),
(44444444, 'B', 1),
(55555555, 'C', 1),
(66666666, 'D', 1),
(77777777, 'E', 1);

INSERT INTO Vehiculo (patente, nroChasis, marca, modelo,kmTotales, anoFabricacion, fechaService, tipo, estado) VALUES
('AA100AS', 585822, 'IVECO', 'Cursor', 13000, 2005, '2020-12-10', 'arrastrado', 1),
('AA124DC', 69904367, 'IVECO', 'Cursor', 30, 2005, '2020-09-07', 'tractor', 1),
('AA150QW', 82039512, 'SCANIA', 'G310', 70, 2015, '2020-05-05', 'tractor', 1),
('AB230AS', 678666, 'IVECO', 'Cursor', 2000, 2005, '2020-09-03', 'arrastrado', 1),
('AB318AD', 595287, 'SCANIA', 'G460', 100, 2016, '2021-02-01', 'arrastrado', 1),
('AB390QD', 32041290, 'SCANIA', 'G460', 321312, 2010, '2020-05-02', 'tractor', 1),
('AB582QW', 17800122, 'M.BENZ', 'Actros 1846', 0, 2017, '2021-03-02', 'tractor', 1),
('AB966QD', 32632699, 'M.BENZ', 'Actros 1846', 0, 2018, '2021-01-06', 'tractor', 1),
('AC125AD', 605737, 'IVECO', 'Cursor', 0, 2019, '2021-06-06', 'arrastrado', 1),
('AC208AG', 642287, 'IVECO', 'Cursor', 150, 2016, '2021-11-16', 'arrastrado', 1),
('AC246QD', 62500687, 'SCANIA', 'G460', 213123, 2016, '2021-02-02', 'tractor', 1),
('AC383AD', 535330, 'SCANIA', 'G310', 4000, 2015, '2020-12-05', 'arrastrado', 1),
('AC452WE', 28204636, 'IVECO', 'Cursor', 322, 2010, '2020-12-31', 'tractor', 1),
('AD200XS', 57193968, 'IVECO', 'Cursor', 60, 2009, '2020-08-01', 'tractor', 1),
('AD678QD', 23849041, 'M.BENZ', 'Actros 1846', 1500, 2016, '2021-01-31', 'tractor', 1);

INSERT INTO Tractor (`patTractor`, nroMotor, consumo)
VALUES('AA124DC',69904367, 30.0),
('AA150QW',82039512, 30.0),
('AB390QD',32041290, 30.0),
('AB582QW',17800122, 30.0),
('AB966QD',32632699, 30.0),
('AC246QD',62500687, 30.0),
('AC452WE',28204636, 30.0),
('AD200XS',57193968, 30.0),
('AD678QD',23849041, 30.0);

INSERT INTO Arrastrado (patArrastrado, tipoCarga)
VALUES('AA100AS','Araña'),
('AB230AS','Araña'),
('AB318AD','Jaula'),
('AC125AD','Araña'),
('AC383AD','Jaula'),
('AC208AG','Araña');

INSERT INTO ImoClass VALUES (1, 'Explosivos'),
(2, 'Gases'),
(3, 'Liquidos inflamables'),
(4, 'Solidos inflamables'),
(5, 'Comburentes y peroxidos organicos'),
(6, 'Toxicos'),
(7, 'Material radioactivo'),
(8, 'Corrosivos'),
(9, 'Objetos peligrosos diversos');

INSERT INTO ImoSubclass VALUES (1, 1, 'Objetos con riesgo de explosión de toda la masa', 1),
(2, 2, 'Representan riesgo de proyección, pero no de explosión de toda la masa', 1),
(3, 3, 'Objetos con riesgo de explosión de toda la masa', 1),
(4, 1, 'Gases inflamables', 2),
(5, 2, 'Gases no inflamables, no tóxicos. Desplazan el oxígeno provocando asfixia', 2),
(6, 3, 'Gases tóxicos', 2),
(7, 1, 'Liquidos inflamables', 3),
(8, 1, 'Materias sólidas inflamables, autorreactivas o explosivas desensibilizadas', 4),
(9, 2, 'Sustancias espontáneamente inflamables', 4),
(10, 3, 'Sustancias que al contacto con el agua desprenden gases inflamables', 4),
(11, 1, 'Comburentes. Líquidos o sólidos que favorecen la combustión', 5),
(12, 2, 'Peróxidos orgánicos', 5),
(13, 1, 'Sustancias tóxicas', 6),
(14, 2, 'Sustancias infecciosas', 6),
(15, 1, 'Máximo nivel de radiación en la superficie de 0.5 milirem/h', 7),
(16, 2, 'Nivel de radiación en la superficie mayor a 0.5 milirem/h, sin exceder los 50 milirem/h', 7),
(17, 3, 'Nivel máximo de radiación en superficie de 200 milirem/h', 7),
(18, 4, 'Materiales fisionables', 7),
(19, 1, 'Corrosivos', 8),
(20, 1, 'Objetos peligrosos diversos', 9);

DELIMITER //
CREATE TRIGGER t_actualizarEstadosPorViajeCreado
AFTER INSERT ON Viaje FOR EACH ROW
BEGIN
    UPDATE Chofer
    SET Chofer.disponibilidad = 0
    WHERE Chofer.dniChof = NEW.chofer;
    UPDATE Vehiculo
    SET Vehiculo.estado = 0
    WHERE Vehiculo.patente IN (NEW.tractor, NEW.arrastrado);
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER t_actualizarEstadoPorService
AFTER INSERT ON Service FOR EACH ROW
BEGIN
    UPDATE Vehiculo
    SET Vehiculo.estado = 0
    WHERE Vehiculo.patente = NEW.vehiculo;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER t_actualizarEstadoPorFinService
AFTER UPDATE ON Service FOR EACH ROW
BEGIN
IF((NEW.fechaFin IS NOT NULL) AND (OLD.fechaFin IS NULL)) THEN
    UPDATE Vehiculo SET Vehiculo.estado = 1
    WHERE Vehiculo.patente = NEW.vehiculo;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER t_invalidarEstadosPorBajaEmpleado
AFTER UPDATE ON Empleado FOR EACH ROW
BEGIN
IF((NEW.fechaBaja IS NOT NULL) AND (OLD.fechaBaja IS NULL)) THEN
    IF EXISTS (SELECT * FROM Chofer WHERE Chofer.dniChof=NEW.dni) THEN
    UPDATE Chofer SET Chofer.disponibilidad = 0
    WHERE Chofer.dniChof = NEW.dni;
    END IF;
    IF EXISTS (SELECT * FROM Mecanico WHERE dniMec=NEW.dni) THEN
    UPDATE Mecanico SET Mecanico.disponibilidad = 0
    WHERE Mecanico.dniMec = NEW.dni;
    END IF;
END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER t_actualizarEstadoPorFinViaje
AFTER UPDATE ON Viaje FOR EACH ROW
BEGIN
IF((NEW.fllegada IS NOT NULL AND OLD.fllegada IS NULL)
    OR (NEW.estado LIKE 'cancelado' AND OLD.estado NOT LIKE 'cancelado')) THEN
    UPDATE Chofer
    SET Chofer.disponibilidad = 1
    WHERE Chofer.dniChof = NEW.chofer;
    UPDATE Vehiculo
    SET Vehiculo.estado = 1
    WHERE Vehiculo.patente IN (NEW.tractor, NEW.arrastrado);
END IF;
END //
DELIMITER ;