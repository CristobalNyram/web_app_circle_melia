-- Creación de la base de datos
CREATE DATABASE ventas_competencias;
USE ventas_competencias;

-- Tabla de equipos
CREATE TABLE equipos (
    idEquipo INT AUTO_INCREMENT PRIMARY KEY,
    nombreEquipo VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Contraseña a nivel de equipo
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'vendedor') NOT NULL, -- Tipo de usuario (admin, vendedor, etc.)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de relación equipo_usuarios (muchos a muchos)
CREATE TABLE equipo_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idEquipo INT,
    idUsuario INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idEquipo) REFERENCES equipos(idEquipo),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario)
);

-- Tabla de ventas
CREATE TABLE ventas (
    idVenta INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT,
    idEquipo INT,
    monto DECIMAL(10, 2) NOT NULL,
    estatus ENUM('activo', 'standby', 'cancelado') DEFAULT 'activo',
    fechaVenta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario),
    FOREIGN KEY (idEquipo) REFERENCES equipos(idEquipo)
);

-- Tabla de competencias
CREATE TABLE competencias (
    idCompetencia INT AUTO_INCREMENT PRIMARY KEY,
    nombreCompetencia VARCHAR(255) NOT NULL,
    metaVentas DECIMAL(10, 2) NOT NULL, -- Meta de ventas para alcanzar la competencia
    fechaInicio DATE,
    fechaFin DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de relación competencias_equipo (muchos a muchos)
CREATE TABLE competencias_equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idCompetencia INT,
    idEquipo INT,
    ventasAcumuladas DECIMAL(10, 2) DEFAULT 0, -- Ventas acumuladas por el equipo en esta competencia
    fechaActualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idCompetencia) REFERENCES competencias(idCompetencia),
    FOREIGN KEY (idEquipo) REFERENCES equipos(idEquipo)
);
INSERT INTO equipos (nombreEquipo, password) VALUES
('Equipo Alpha', 'alpha2024'),
('Equipo Beta', 'beta2024'),
('Equipo Gamma', 'gamma2024');
INSERT INTO usuarios (nombreUsuario, tipo) VALUES
('Carlos Pérez', 'vendedor'),
('María Gómez', 'vendedor'),
('Ana Ramírez', 'vendedor'),
('Juan López', 'admin'),
('Sofía Martínez', 'vendedor');
INSERT INTO competencias (nombreCompetencia, metaVentas, fechaInicio, fechaFin) VALUES
('Competencia Q4', 1000.00, '2024-10-01', '2024-12-31'),
('Competencia Anual', 5000.00, '2024-01-01', '2024-12-31');

INSERT INTO equipo_usuarios (idEquipo, idUsuario) VALUES
(1, 1), -- Carlos Pérez en Equipo Alpha
(1, 2), -- María Gómez en Equipo Alpha
(2, 3), -- Ana Ramírez en Equipo Beta
(3, 5), -- Sofía Martínez en Equipo Gamma
(2, 4); -- Juan López (admin) en Equipo Beta
INSERT INTO ventas (idUsuario, idEquipo, monto, estatus) VALUES
(1, 1, 150.00, 'activo'), -- Venta de Carlos Pérez en Equipo Alpha
(2, 1, 200.00, 'standby'), -- Venta de María Gómez en Equipo Alpha
(3, 2, 100.00, 'cancelado'), -- Venta de Ana Ramírez en Equipo Beta
(4, 2, 500.00, 'activo'), -- Venta de Juan López en Equipo Beta
(5, 3, 300.00, 'activo'); -- Venta de Sofía Martínez en Equipo Gamma
INSERT INTO competencias_equipo (idCompetencia, idEquipo, ventasAcumuladas) VALUES
(1, 1, 350.00), -- Equipo Alpha en Competencia Q4 con 350.00 acumulados
(1, 2, 600.00), -- Equipo Beta en Competencia Q4 con 600.00 acumulados
(2, 3, 300.00); -- Equipo Gamma en Competencia Anual con 300.00 acumulados
