<?php
    namespace controller;

    require_once './model/DAO.php';
    require_once './model/Book.php';
    require_once './controller/DBManager.php';

    use model\DAO;
    use model\Book;
    use controller\DBManager;

    class BookDAO implements DAO
    {

        private $dbMan;

        private function connect()
        {
            $this->dbMan = new DBManager;
            $this->dbMan->connect();
        }

        /**
         * @param object $entity
         *
         * @return object
         * @throws \Exception
         */
        public function create(object $entity): object
        {
            // check whether object is instance of book
            if (!($entity instanceof Book))
            {
                throw new \Exception('Object not instance of Book!');
            }

            // connect to database
            $this->connect();

            $book = $entity;

            $is_recent = $book->isRecent() ? 1 : 0;

            $instruction = "
                INSERT INTO book (title, author, price, image, link, is_recent)
                VALUES (:title, :author, :price, :image, :link, :is_recent)
            ";
            $params = array(
                ':title' => $book->getTitle(),
                ':author' => $book->getAuthor(),
                ':price' => $book->getPrice(),
                ':image' => $book->getImg(),
                ':link' => $book->getLink(),
                ':is_recent' => $is_recent
            );
            $this->dbMan->execute($instruction, $params);
            $this->dbMan->disconnect();

            // return persisted object
            return $book;
        }

        /**
         * @param object $entity
         *
         * @return object
         * @throws \Exception
         */
        public function retrieveById(object $entity): object
        {
            // check whether object is instance of book
            if (!($entity instanceof Book))
            {
                throw new \Exception('Object not instance of Book!');
            }

            // connect to database
            $this->connect();

            // persist book into database
            $book = $entity;
            $instruction = "
                SELECT * FROM book WHERE id = :id
            ";
            $params = array(
                ':id' => $book->getId()
            );
            $results = $this->dbMan->query($instruction, $params);
            $this->dbMan->disconnect();

            // return persisted object
            if (!$results) // TODO check
            {
                throw new \Exception("Book with id {$book->getId()} not found!");
            }

            $book = new Book (
                $results[0]["title"],
                $results[0]["author"],
                $results[0]["price"],
                $results[0]["image"],
                $results[0]["link"],
                $results[0]['is_recent']
            );
            $book->setId($results[0]['id']);

            return $book;
        }

        public function retrieveByTitle(string $title): array
        {
            $this->connect();

            $title = "%" . $title . "%";

            $instruction = "
                SELECT * FROM book WHERE title LIKE :title
            ";
            $params = array(
                ':title' => $title
            );
            $results = $this->dbMan->query($instruction, $params);
            $this->dbMan->disconnect();

            return $results;
        }

        public function retrieveByAuthor(string $author): array
        {
            $this->connect();

            $author = '%' . $author . '%';

            $instruction = "
                SELECT * FROM book WHERE author LIKE :author
            ";
            $params = array(
                ':author' => $author
            );
            $results = $this->dbMan->query($instruction, $params);
            $this->dbMan->disconnect();

            return $results;
        }

        public function retrieveNew(): array
        {
            $this->connect();

            $is_recent = 1;

            $instruction = "
                SELECT * FROM book WHERE is_recent = :is_recent
            ";
            $params = array(
                ':is_recent' => $is_recent
            );
            $results = $this->dbMan->query($instruction, $params);
            $this->dbMan->disconnect();

            return $results;
        }

        public function update(object $entity): object
        {
            // check whether object is instance of book
            if (!($entity instanceof Book))
            {
                throw new \Exception('Object not instance of Book!');
            }

            // TODO implement method
        }

        public function delete(object $entity): void
        {
            return; // TODO implement method
        }
    }
