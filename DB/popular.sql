INSERT INTO autores (nome, data_nascimento, nacionalidade, foto) VALUES
('José Saramago', '1922-11-16', NULL, 'Português', 'uploads/pictures/saramago.jpg'),
('Clarice Lispector', '1920-12-10', NULL, 'Brasileira', 'uploads/pictures/clarice.jpg'),
('Albert Camus', '1913-11-07', NULL, 'Francês', 'uploads/pictures/camus.jpg');

INSERT INTO livros (titulo, anos, capa) VALUES
('Ensaio sobre a Cegueira', 1995, 'ensaio.jpg'),
('A Hora da Estrela', 1977, 'estrela.jpg'),
('O Estrangeiro', 1942, 'estrangeiro.jpg'),
('Caim', 2009, 'caim.jpg'),
('A Paixão segundo G.H.', 1964, 'gh.jpg');

INSERT INTO autor_livro (autor_id, livro_id) VALUES
(1, 1), (1, 4),
(2, 2), (2, 5),
(3, 3);