<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/Review.php';

    use \Exception;
    use \PDO;
    use \model\Book;
    use \model\Review;

    class DBManager
    {
        private $serverName;
        private $username;
        private $password;
        private $dbName;
        private $conn;

        public function __construct()
        {
            // retrieve configurations from file
            $configs = parse_ini_file('./db/dbconfig.ini', true);

            // set DB connection parameters
            $this->serverName = $configs['connection']['server'];
            $this->username = $configs['connection']['username'];
            $this->password = $configs['connection']['password'];
            $this->dbName = $configs['connection']['database'];
            $this->port = $configs['connection']['port'];
        }

        private function connect() {
            try {
                $this->conn = new PDO("mysql:host=$this->serverName;dbname=$this->dbName",
                                $this->username, $this->password);
                // set the PDO error mode to exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
            catch(PDOException $e)
                {
                echo $e->getMessage();
                }
        }

        private function disconnect() {
            $this->conn = null;
        }
        
        public function getAllBooks(): array {
            $this->connect();
            $sql = "SELECT * FROM book";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($books,
                                new Book($row["title"],$row["author"],$row["price"],$row["image"],$row["link"])
                    );
                }
            }
            $this->disconnect();
            return $books;
        }

        public function getNewBooks(): array {
            $this->connect();
            $sql = "SELECT * FROM book WHERE is_recent=1";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($books,
                                new Book($row["title"],$row["author"],$row["price"],$row["img"],$row["link"])
                    );
                }
            }
            $this->disconnect();
            return $books;
        }

        public function addBooks(array $books) {
            $this->connect();
            $this->conn->beginTransaction();
            foreach($books as $book)
                $isRecent = $book->isRecent() ? 1 : 0;
                $this->conn->exec("INSERT INTO book(title,author,price,img,link,is_recent)
                                    VALUES ('{$book->getTitle()}' , '{$book->getAuthor()}' ,
                                            '{$book->getPrice()}' , '{$book->getImg()}' ,
                                            '{$book->getLink()}', '$isRecent')");
            $this->conn->commit();
        }

        public function getAllReviews(): array {
            $this->connect();
            $sql = "SELECT * FROM review";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($books,
                                new Review($row["title"],$row["author"],$row["plot"],$row["txt"],
                                            $row["average"],$row["style"],$row["content"],$row["pleasantness"])
                    );
                }
            }
            $this->disconnect();
            return $books;
        }

        public function getNewReviews(): array {
            $this->connect();
            $sql = "SELECT * FROM review WHERE is_recent=1";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    array_push($books,
                                new Review($row["title"],$row["author"],$row["plot"],$row["txt"],
                                            $row["average"],$row["style"],$row["content"],$row["pleasantness"])
                    );
                }
            }
            $this->disconnect();
            return $books;
        }

        public function addReviews(array $reviews) {
            $this->connect();
            $this->conn->beginTransaction();
            foreach($reviews as $review)
                $isRecent = $review->isRecent() ? 1 : 0;
                $this->conn->exec("INSERT INTO review(title,author,plot,txt,average,style,content,pleasantness,is_recent)
                                    VALUES ('{$review->getTitle()}' , '{$review->getAuthor()}' ,
                                            '{$review->getPlot()}' , '{$review->getText()}' ,
                                            '{$review->getAvg()}' ,  '{$review->getStyle()}' , 
                                            '{$review->getContent()}' , '{$review->getPleasantness()}' ,
                                            '$isRecent')");
            $this->conn->commit();
        }
    }