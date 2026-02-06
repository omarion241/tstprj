-- Base de données: gestion_stock
CREATE DATABASE IF NOT EXISTS gestion_stock;
USE gestion_stock;

-- Table utilisateurs.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

-- Table entrepôts.
CREATE TABLE warehouses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(150) NOT NULL
);

-- Table articles.
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(100) NOT NULL,
    designation VARCHAR(150) NOT NULL,
    category VARCHAR(150) NOT NULL,
    purchase_price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) NOT NULL,
    min_stock INT NOT NULL,
    unit VARCHAR(50) NOT NULL
);

-- Table stock par entrepôt.
CREATE TABLE stock_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    warehouse_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- Table mouvements stock.
CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    warehouse_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    movement_type VARCHAR(20) NOT NULL,
    ref_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- Table achats.
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier VARCHAR(150) NOT NULL,
    bl_number VARCHAR(100) NOT NULL,
    payment_status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table détail achats.
CREATE TABLE purchase_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (purchase_id) REFERENCES purchases(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- Table ventes.
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client VARCHAR(150) DEFAULT NULL,
    is_counter_sale TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table détail ventes.
CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- Table paiements.
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    party_type VARCHAR(50) NOT NULL,
    party_name VARCHAR(150) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_modes VARCHAR(255) NOT NULL,
    note VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Données exemple.
INSERT INTO users (email, password_hash) VALUES ('admin@demo.com', '$2y$12$6cCWHd2DI4/fxqL3Ou/NfeNqC6VQbZhFanVCSKMylSknHCQgElMwC');
INSERT INTO warehouses (name, location) VALUES ('Dépôt Nord', 'Casablanca'), ('Dépôt Sud', 'Rabat');
INSERT INTO items (reference, designation, category, purchase_price, sale_price, min_stock, unit)
VALUES ('REF-001', 'Clavier', 'Informatique', 80, 120, 5, 'pièce'),
       ('REF-002', 'Souris', 'Informatique', 40, 60, 10, 'pièce');
