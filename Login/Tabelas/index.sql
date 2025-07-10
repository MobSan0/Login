-- Active: 1747870552069@@127.0.0.1@3306@samueldb
CREATE TABLE `users` (
 `user_id` int(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `firstname` varchar(30) NOT NULL,
 `lastname` varchar(30) NOT NULL,
 `address` varchar(150) NOT NULL,
 `contact` varchar(20) NOT NULL
) ;


-- User)
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Inserir um usuário admin padrão (senha: admin123)
INSERT INTO `admin_users` (`username`, `password`) VALUES ('admin', 'admin123');

ALTER TABLE users ADD COLUMN (
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Atualize o usuário admin existente (se houver)
UPDATE users SET role = 'admin' WHERE user_id = 1;

INSERT INTO users (firstname, lastname, address, contact, username, password, role) 
VALUES ('Admin', 'User', 'Admin Address', '123456789', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

ALTER TABLE users DROP COLUMN `address`;

DROP TABLE IF EXISTS `users`;








CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` char(2) NOT NULL,
  `cep` varchar(10) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;







INSERT INTO `users` (
  `firstname`, 
  `lastname`, 
  `rua`, 
  `numero`, 
  `bairro`, 
  `cidade`, 
  `estado`, 
  `cep`, 
  `contact`, 
  `username`, 
  `password`, 
  `role`
) VALUES (
  'Admin', 
  'Sistema', 
  'Rua Principal', 
  '123', 
  'Centro', 
  'São Paulo', 
  'SP', 
  '01001000', 
  '11999999999', 
  'admin', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
  'admin'
);




ALTER TABLE users 
ADD COLUMN `rua` VARCHAR(100),
ADD COLUMN `numero` VARCHAR(20),
ADD COLUMN `bairro` VARCHAR(50),
ADD COLUMN `cidade` VARCHAR(50),
ADD COLUMN `estado` CHAR(2),
ADD COLUMN `cep` VARCHAR(10);



ALTER TABLE users
ADD COLUMN IF NOT EXISTS `rua` VARCHAR(100),
ADD COLUMN IF NOT EXISTS `numero` VARCHAR(20),
ADD COLUMN IF NOT EXISTS `bairro` VARCHAR(50),
ADD COLUMN IF NOT EXISTS `cidade` VARCHAR(50),
ADD COLUMN IF NOT EXISTS `estado` CHAR(2),
ADD COLUMN IF NOT EXISTS `cep` VARCHAR(10);

DROP TABLE `admin_users`;