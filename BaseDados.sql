DROP DATABASE IF EXISTS SecondLife;
CREATE DATABASE SecondLife;
USE SecondLife;

Create Table Vendas
(
    Num_Venda int(11) Not Null Auto_Increment Primary Key,
    Data_Venda date NOT NULL DEFAULT (CURRENT_DATE),
    Num_Utilizador int(10) Not Null,
    Telemovel int(9) Not Null,
    Quantidade_venda int(11) NOT NULL,
    Nome varchar(30) Not Null,
    Apelido varchar(30) Not Null,
    Email_Cliente varchar(40) Not Null,
    Cod_Equipamento int(15) Not Null,
    Morada varchar(30) Not Null,
    CodPostal varchar(8) Not Null,
    Localidade varchar(20) Not Null,
    Distrito varchar(20) Not Null
);

Create Table Categorias
(
    Id_Categoria int(11) Not Null Auto_Increment Primary Key,
    Nome_Categoria varchar(30) Not Null
);

Create Table Equipamentos
(
    Cod_Equipamento int(15) Not Null Auto_Increment Primary Key,
    Nome_Equipamento varchar(45) Not Null,
    Num_Utilizador int(10) Not Null,
    Preco DECIMAL(10, 2) NOT NULL,
    Descricao varchar(90) Not Null,
    Total       int(11) unsigned NOT NULL,
    Quantidade int(11) unsigned NOT NULL,
    Emprestimo_Ativo  int(11) unsigned NOT NULL,
    Imagem varchar(100) Not Null
);

Create Table Categorias_Equipamentos
(
    Id_Categoria int(11) Not Null,
    Cod_Equipamento int(11) Not Null 
);


Create Table Utilizadores
(
    Num_Utilizador int(10) Not Null Primary Key Auto_Increment,
    Nome_Utilizador varchar(30) Not Null,
    Email varchar(40) Not Null,
    Senha varchar (50) Not Null UNIQUE,
    TokenRecuperacao VARCHAR(100) DEFAULT NULL,
    User_Admin varchar(3) Not Null,
    User_Client varchar(3) Not Null,
    Imagem varchar(100) Not Null
);

CREATE TABLE remember_me (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expire INT NOT NULL,
    INDEX idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES Utilizadores(Num_Utilizador) ON DELETE CASCADE
);

Alter Table Vendas 
ADD Constraint fk_utili_01
Foreign Key (Num_Utilizador)
References Utilizadores (Num_Utilizador)
ON DELETE CASCADE
ON UPDATE CASCADE;

Alter Table Vendas
ADD Constraint fk_emp_01
Foreign Key (Cod_Equipamento)
References Equipamentos (Cod_Equipamento)
ON DELETE CASCADE
ON UPDATE CASCADE;

Alter Table Categorias_Equipamentos
ADD Constraint fk_cat_emp_01
Foreign Key (Id_categoria)
References Categorias (Id_categoria)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD Constraint fk_cat_emp_02
Foreign Key (Cod_Equipamento)
References Equipamentos (Cod_Equipamento)
ON DELETE CASCADE
ON UPDATE CASCADE;

Insert Into Utilizadores VALUES 
('1', 'admin','admin@admin.com',sha1('admin'), null, 'sim','nao', '124359115649154444harrison-broadbent-f2S93diaVn0-unsplash.jpg'),
('2','eduardo','eduardo.m.a.cunhavsc@gmail.com',sha1('eduardo'), null, 'nao', 'sim',null);

Insert Into Categorias VALUES ('1','Informática');
Insert Into Categorias VALUES ('2','Eletrónica');
Insert Into Categorias VALUES ('3','Mecatrónica');
Insert Into Categorias VALUES ('4','Outros');


INSERT INTO equipamentos (Cod_Equipamento, Nome_Equipamento, Num_Utilizador, Preco, Descricao, Total, Quantidade, Emprestimo_Ativo, Imagem) VALUES
(1, 'Arduino',1, 15.20, 'Arduino', 3, 0, 3, '124359115649154444harrison-broadbent-f2S93diaVn0-unsplash.jpg'),
(2, 'Siemens',2,13.00, 'Siemens', 4, 4, 0, '130000093602dois-modulos-logicos-para-pequenos-projetos-siemens-em-fundo-bra.png'),
(3, 'Arduino MEGA',2,22.99, 'Arduino MEGA', 2, 2, 0, '101252093519arduinomega.jpg');

INSERT INTO `Vendas` (`Num_Venda`, `Data_Venda`, `Num_Utilizador`, `Telemovel`, `Quantidade_venda`, `Nome`, `Apelido`, `Email_Cliente`, `Cod_Equipamento`, `Morada`, `CodPostal`, `Localidade`, `Distrito`) VALUES
(1,'2021-03-25', 1,918754162, 1, 'Eduardo', 'Cunha', 'eduardo.m.a.cunhavsc@gmail.com', 1, 'Rua', '4581-152', 'Guimaraes', 'Braga'),
(2,'2021-03-25',1,918754162, 1, 'Joao', 'Teixeira', 'joao@gmail.com', 2, 'Rua', '4581-152', 'Guimaraes', 'Braga'),
(3,'2021-03-25',1,918754162, 1, 'Manuel', 'Carvalho', 'manuel@gmail.com', 1, 'Rua', '4581-152', 'Guimaraes', 'Braga');

select cast(Data_Venda as date) from Vendas;

CREATE FULLTEXT Index FT_Equipamento ON Equipamentos(Nome_Equipamento);