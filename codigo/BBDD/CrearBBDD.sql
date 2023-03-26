CREATE DATABASE IF NOT EXISTS Easycom;
USE Easycom;

CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  nombre VARCHAR(255) NOT NULL,
  contraseña VARCHAR(255) NOT NULL,
  es_admin BOOLEAN NOT NULL DEFAULT FALSE,
  fecha_creacion DATE NOT NULL,
  fecha_modificacion DATE,
  fecha_eliminacion DATE
);

CREATE TABLE IF NOT EXISTS producto (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  barcode VARCHAR(255) NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  descripcion VARCHAR(255),
  url_img VARCHAR(255),
  especificaciones_tecnicas VARCHAR(255),
  fecha_creacion DATE NOT NULL,
  fecha_modificacion DATE,
  fecha_eliminacion DATE
);

CREATE TABLE IF NOT EXISTS precio (
  id_precio INT AUTO_INCREMENT PRIMARY KEY,
  id_producto INT NOT NULL,
  precio DOUBLE,
  tienda VARCHAR(255) NOT NULL,
  url_producto VARCHAR(255) NOT NULL,
  fecha_creacion DATE NOT NULL,
  fecha_modificacion DATE,
  fecha_eliminacion DATE,
  FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

CREATE TABLE IF NOT EXISTS historial (
  id_usuario INT NOT NULL,
  id_producto INT NOT NULL,
  es_favorito BOOLEAN NOT NULL DEFAULT FALSE,
  fecha_creacion DATE NOT NULL,
  fecha_modificacion DATE,
  fecha_eliminacion DATE,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
  FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);
