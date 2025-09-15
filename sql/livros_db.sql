CREATE TABLE autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    data_nascimento DATE,
    nacionalidade VARCHAR(50),
    foto VARCHAR(255)
);


CREATE TABLE livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    ano YEAR,
    capa VARCHAR(255)
);


CREATE TABLE autor_livro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT NOT NULL,
    livro_id INT NOT NULL,
    FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE CASCADE,
    FOREIGN KEY (livro_id) REFERENCES livros(id) ON DELETE CASCADE
);


INSERT INTO autores (nome, data_nascimento, nacionalidade, foto) VALUES
('José Saramago', '1922-11-16', 'Português', 'saramago.jpg'),
('Clarice Lispector', '1920-12-10', 'Brasileira', 'clarice.jpg'),
('Albert Camus', '1913-11-07', 'Francês', 'camus.jpg');

INSERT INTO livros (titulo, ano, capa) VALUES
('Ensaio sobre a Cegueira', 1995, 'ensaio.jpg'),
('A Hora da Estrela', 1977, 'estrela.jpg'),
('O Estrangeiro', 1942, 'estrangeiro.jpg'),
('Caim', 2009, 'caim.jpg'),
('A Paixão segundo G.H.', 1964, 'gh.jpg');

INSERT INTO autor_livro (autor_id, livro_id) VALUES
(1, 1), (1, 4),
(2, 2), (2, 5),
(3, 3);
