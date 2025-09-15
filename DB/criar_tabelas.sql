CREATE TABLE livros ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    titulo VARCHAR(40) NOT NULL, 
    anos YEAR NOT NULL, 
    capa VARCHAR(255) NOT NULL 
);

CREATE TABLE autores( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    nome VARCHAR(40) NOT NULL, 
    nacionalidade VARCHAR(40) NOT NULL, 
    nascimento DATE NOT NULL, 
    morte DATE , 
    foto VARCHAR(255) NOT NULL 
);

CREATE TABLE autor_livro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT NOT NULL,
    livro_id INT NOT NULL,
    FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE CASCADE,
    FOREIGN KEY (livro_id) REFERENCES livros(id) ON DELETE CASCADE
);

