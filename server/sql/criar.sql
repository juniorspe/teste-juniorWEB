DROP TABLE IF EXISTS filho;
DROP TABLE IF EXISTS pessoa;

CREATE TABLE pessoa (
  pessoa_id int NOT NULL AUTO_INCREMENT,
  nome varchar(255),
  PRIMARY KEY (pessoa_id)
);

CREATE TABLE filho (
  filho_id int NOT NULL AUTO_INCREMENT,
  pessoa_id int,
  nome varchar(255),
  FOREIGN KEY(pessoa_id) REFERENCES pessoa(pessoa_id),
  PRIMARY KEY (filho_id)
);
