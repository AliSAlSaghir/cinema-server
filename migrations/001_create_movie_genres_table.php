<?php
require("../connection/connection.php");

$query = "CREATE TABLE movie_genres (
    movie_id INT,
    genre_id INT,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);
";

if ($mysqli->query($query)) {
  echo "Table `movie_genres` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
