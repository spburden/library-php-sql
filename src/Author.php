<?php
    class Author
    {
        private $id;
        private $name;

        function __construct($name, $id = null)
        {
           $this->name = $name;
           $this->id = $id;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->name}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query('SELECT * FROM authors;');
            $author_array = array();
            foreach($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($author_array, $new_author);
            }
            return $author_array;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        function updateName($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->id};");
            $this->name = $new_name;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->id};");
        }

        static function find($search)
        {
            $authors = Author::getAll();
            $found_author = null;
            foreach ($authors as $author)
            {
                if ($author->getId() == $search || $author->getName() == $search ){
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        function addBook($new_book)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$new_book->getId()}, {$this->id});");
        }

        function addBookById($book_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (book_id, author_id) VALUES ({$book_id}, {$this->id});");
        }

        function getAuthorsBooks()
        {
            $returned_authors_books = $GLOBALS['DB']->query("SELECT books.* FROM authors
                JOIN authors_books ON (authors_books.author_id = authors.id)
                JOIN books ON (authors_books.book_id = books.id)
                WHERE authors.id = {$this->getId()};");
            $books = array();
            foreach($returned_authors_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }
    }
?>
