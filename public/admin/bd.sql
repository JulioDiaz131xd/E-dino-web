-- Crear base de datos
CREATE DATABASE e_dino;

-- Seleccionar la base de datos
USE e_dino;

-- Tabla para roles de usuario (admin, alumno)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Insertar roles predefinidos
INSERT INTO roles (nombre) VALUES ('admin'), ('alumno');

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    numero_control VARCHAR(20) UNIQUE, -- Número de control solo para alumnos
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Tabla de clases
CREATE TABLE clases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de progreso de clases
CREATE TABLE progreso_clases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    clase_id INT NOT NULL,
    progreso INT DEFAULT 0, -- Progreso de 0 a 100
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (clase_id) REFERENCES clases(id)
);

-- Insertar algunos usuarios de ejemplo
INSERT INTO usuarios (nombre, email, password, rol_id, numero_control)
VALUES 
('Admin1', 'admin1@edino.com', '$2y$10$e0MYzXyjpJS2Wxh.1P2VX.Q5T/YkCv3PTLiUiP0tr/XdqPssVYFN.', 1, NULL), -- Password hashed: password123
('Alumno1', 'alumno1@edino.com', '$2y$10$e0MYzXyjpJS2Wxh.1P2VX.Q5T/YkCv3PTLiUiP0tr/XdqPssVYFN.', 2, '2024A001'), -- Password hashed: password123
('Alumno2', 'alumno2@edino.com', '$2y$10$e0MYzXyjpJS2Wxh.1P2VX.Q5T/YkCv3PTLiUiP0tr/XdqPssVYFN.', 2, '2024A002'); -- Password hashed: password123

-- Insertar algunas clases de ejemplo
INSERT INTO clases (nombre, descripcion)
VALUES 
('Matemáticas Básicas', 'Clase introductoria sobre matemáticas básicas'),
('Historia Universal', 'Clase sobre la historia del mundo desde la antigüedad hasta la era moderna');

-- Insertar algunos progresos de clases de ejemplo
INSERT INTO progreso_clases (usuario_id, clase_id, progreso)
VALUES 
(2, 1, 50),  -- Alumno1 ha completado el 50% de Matemáticas Básicas
(2, 2, 30),  -- Alumno1 ha completado el 30% de Historia Universal
(3, 1, 80);  -- Alumno2 ha completado el 80% de Matemáticas Básicas

-- Tabla para relacionar usuarios con clases
CREATE TABLE clases_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    clase_id INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (clase_id) REFERENCES clases(id)
);
