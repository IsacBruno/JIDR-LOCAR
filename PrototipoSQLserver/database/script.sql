CREATE DATABASE jidr_locar;
USE jidr_locar;

CREATE TABLE Cliente(
id_cliente INT PRIMARY KEY IDENTITY(1,1),
nome VARCHAR(150) NOT NULL,
cpf CHAR(11) NOT NULL,
telefone VARCHAR(20) NOT NULL,
email VARCHAR(150) NOT NULL,
endereco VARCHAR(200) NOT NULL,
status_conta VARCHAR(15) NOT NULL,
cnh VARCHAR(10) NOT NULL,
senha_login VARCHAR(8) NOT NULL,
genero VARCHAR(10) NOT NULL
);

CREATE TABLE Reserva(
id_reserva INT PRIMARY KEY IDENTITY(1,1),
data_inicio DATE NOT NULL,
data_fim DATE NOT NULL,
valor_total NUMERIC(18,2) NOT NULL,
status_rese VARCHAR(10) NOT NULL,
placa_fk VARCHAR,
id_cliente_fk INT,
KM_inicial INT,
CONSTRAINT fk_placa FOREIGN KEY(placa_fk) REFERENCES Veiculo(placa),
CONSTRAINT fk_cliente FOREIGN KEY(id_cliente_fk) REFERENCES Cliente(id_cliente)
);

CREATE TABLE Veiculo(
placa VARCHAR PRIMARY KEY,
marca VARCHAR(30) NOT NULL,
modelo VARCHAR(20),
status_Veic VARCHAR(12) NOT NULL,
valor_diaria NUMERIC(18,2) NOT NULL,
id_categoria_fk INT NOT NULL,
ano_vei DATE NOT NULL,
CONSTRAINT fk_categoria FOREIGN KEY(id_categoria_fk) REFERENCES Categoria(id_categoria) 
);

CREATE TABLE Categoria(
id_categoria INT PRIMARY KEY IDENTITY(1,1),
nome_cate VARCHAR(30),
descricao VARCHAR(150)
);

CREATE TABLE Pagamento(
id_pagamento INT PRIMARY KEY IDENTITY(1,1),
valor_pago NUMERIC(18,2),
data_pagamento DATE,
status_pagamento VARCHAR(15),
id_reserva_fk INT,
forma_pagamento VARCHAR(15),
valor_rembolsado NUMERIC(18,2),
CONSTRAINT fk_reserva_pagamen FOREIGN KEY(id_reserva_fk) REFERENCES Reserva(id_reserva)
);

CREATE TABLE Multa(
id_multa INT PRIMARY KEY IDENTITY(1,1),
descricao VARCHAR(200) NOT NULL,
valor_multa NUMERIC(18,2) NOT NULL,
status_multa VARCHAR(20) NOT NULL,
id_reserva_fk INT NOT NULL,
CONSTRAINT fk_multa_reserva FOREIGN KEY(id_reserva_fk) REFERENCES Reserva(id_reserva)
);

CREATE TABLE Devolucao(
id_devolucao INT PRIMARY KEY IDENTITY(1,1),
data_devolucao DATE NOT NULL,
KM_final INT NOT NULL,
valor_multas NUMERIC(18,2) NOT NULL,
observacoes VARCHAR(500),
status_devolucao VARCHAR(20) NOT NULL,
id_reserva_fk INT NOT NULL,
CONSTRAINT fk_devo_reserva FOREIGN KEY(id_reserva_fk) REFERENCES Reserva(id_reserva)
);

ALTER TABLE Cliente 
ADD  data_nasc DATE NOT NULL;

SELECT * FROM Cliente;

ALTER TABLE Cliente
ALTER COLUMN endereco VARCHAR(200) NULL;

ALTER TABLE Cliente
ALTER COLUMN senha_login VARCHAR(8) NULL;

ALTER TABLE Veiculo
ALTER COLUMN placa VARCHAR(7);

INSERT INTO Categoria VALUES
('Hatchbacks', 'Os hatchbacks são conhecidos por sua praticidade, versatilidade e economia, especialmente em áreas urbanas.'),
('Sedans', 'Essa configuração oferece mais espaço interno, especialmente para os passageiros e um porta-malas generoso.'),
('SUVs', 'SUVs novos: espaço, conforto e tecnologia para suas aventuras. Ideal para família e viagens. Descubra seu próximo 0KM agora!'),
('Elétricos','futuro sustentável e tecnologia avançada. Dirija com zero emissões, economia e inovação. Sua jornada elétrica começa aqui!')

SELECT * FROM Categoria


INSERT INTO Veiculo VALUES
('GTR4D29', 'Volkswagen', 'Polo', 'Disponível', 84.00, 1, '2024-02-12'),
('MNL1B78', 'Fiat', 'Argo', 'Disponível', 92.00, 1, '2022-05-17'),
('RQP7F52', 'Chevrolet', 'Onix-plus', 'Manutenção', 110.00, 1, '2023-04-29'),
('LBC2E90', 'Nissan', 'Sentra', 'Manutenção', 150.00, 2, '2023-11-04'),
('DZX3A14', 'Nissan', 'Versa', 'Manutenção', 170.00, 2, '2022-01-26'),
('VJW8C61', 'Fiat', 'Cronos', 'Manutenção', 180.00, 2, '2025-02-19'),
('HKY6B37', 'Fiat', 'Fastback', 'Manutenção', 250.00, 3, '2023-07-24'),
('ZUM5E84', 'Fiat', 'Pulse', 'Manutenção', 210.00, 3, '2024-09-04'),
('TLA9C72', 'Jeep', 'Comander', 'Manutenção', 280.00, 3, '2022-03-15'),
('BNX1D58', 'BYD', 'Han', 'Manutenção', 300.00, 4, '2023-10-09'),
('CRQ3F91', 'BYD', 'Tan', 'Manutenção', 360.00, 4, '2025-08-16'),
('WKE7A03', 'BYD', 'Atto 3', 'Manutenção', 400.00, 4, '2024-06-23');

SELECT * FROM Veiculo;

ALTER TABLE Reserva
DROP CONSTRAINT fk_placa;

ALTER TABLE Veiculo
DROP CONSTRAINT PK__Veiculo__0C057424F237940F;

ALTER TABLE Veiculo
ALTER COLUMN placa VARCHAR(7) NOT NULL;

ALTER TABLE Veiculo
ADD CONSTRAINT PK_placa_veiculo PRIMARY KEY (placa);

ALTER TABLE Reserva 
ADD CONSTRAINT FK_placa FOREIGN KEY (placa_fk) REFERENCES Veiculo(placa);

ALTER TABLE Reserva
ALTER COLUMN placa_fk VARCHAR(7);

ALTER TABLE Reserva
ADD cidade_retirada VARCHAR(20);

SELECT * FROM Reserva;
SELECT * FROM Cliente;
SELECT * FROM Veiculo;
SELECT * FROM Categoria;

ALTER TABLE Cliente
ALTER COLUMN cnh VARCHAR(11);

ALTER TABLE Reserva
DROP COLUMN id_reserva;

SELECT 
    c.nome,
    c.cpf,
    c.telefone,
    c.email,
    r.data_inicio,
    r.data_fim,
    r.valor_total
FROM 
    cliente c
JOIN 
    reserva r ON c.id_cliente = r.id_cliente_fk;
	SELECT * FROM Veiculo;

ALTER TABLE Pagamento
DROP CONSTRAINT fk_reserva_pagamen;

ALTER TABLE Multa 
DROP CONSTRAINT fk_multa_reserva;

ALTER TABLE Devolucao
DROP CONSTRAINT fk_devo_reserva;

ALTER TABLE Reserva
DROP CONSTRAINT PK__Reserva__423CBE5DE6FAB591;

ALTER TABLE Reserva
DROP COLUMN id_reserva;

DELETE FROM Reserva;

ALTER TABLE Reserva ADD id_reserva INT PRIMARY KEY IDENTITY(1,1);

ALTER TABLE Reserva
DROP CONSTRAINT fk_cliente;

ALTER TABLE Cliente
DROP CONSTRAINT PK__Cliente__677F38F54CD1AEA2;

ALTER TABLE Cliente 
DROP COLUMN id_cliente;

DELETE FROM Cliente;

ALTER TABLE Cliente
ADD id_cliente INT PRIMARY KEY;

ALTER TABLE Reserva 
ADD CONSTRAINT fk_cliente_reserva
FOREIGN KEY (id_cliente_fk) REFERENCES Cliente(id_cliente);

ALTER TABLE Pagamento 
ADD CONSTRAINT fk_reserva_pagamen 
FOREIGN KEY(id_reserva_fk) REFERENCES Reserva(id_reserva);

ALTER TABLE Multa 
ADD CONSTRAINT fk_multa_reserva 
FOREIGN KEY(id_reserva_fk) REFERENCES Reserva(id_reserva);

ALTER TABLE Devolucao 
ADD CONSTRAINT fk_devo_reserva 
FOREIGN KEY(id_reserva_fk) REFERENCES Reserva(id_reserva);

SELECT * FROM Reserva;
SELECT * FROM Cliente;
SELECT * FROM Categoria;
SELECT * FROM Veiculo;

ALTER TABLE Cliente --Você não precisa apagar atributos que não são chaves primárias para fazer alguma alteralçao no tamanho ou na restrição do campo;
ALTER COLUMN cpf char(14) NOT NULL;
