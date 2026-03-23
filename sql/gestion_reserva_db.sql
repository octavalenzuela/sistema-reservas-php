--Script para crear base de datos y sus tablas!

CREATE DATABASE IF NOT EXISTS gestion_reservas;
USE gestion_reservas;

CREATE TABLE IF NOT EXISTS mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ubicacion ENUM('A', 'B', 'C', 'D') NOT NULL,
    numero_mesa INT NOT NULL,
    capacidad INT NOT NULL,
    seccion VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    cantidad_personas INT NOT NULL,
    estado ENUM('confirmada', 'cancelada') DEFAULT 'confirmada'
);

CREATE TABLE IF NOT EXISTS reserva_mesa (
    reserva_id INT,
    mesa_id INT,
    PRIMARY KEY (reserva_id, mesa_id),
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON DELETE CASCADE
);

INSERT INTO mesas (ubicacion, numero_mesa, capacidad, seccion) VALUES 
('A', 1, 2, 'Salon'), ('A', 2, 2, 'Salon'), ('A', 3, 4, 'Salon'),
('B', 4, 2, 'Terraza'), ('B', 5, 4, 'Terraza'),
('C', 6, 2, 'VIP'), ('D', 7, 6, 'Patio');