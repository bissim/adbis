<?php
    namespace controller;

    require_once './model/Book.php';
    require_once './model/AudioBook.php';
    require_once './model/Review.php';

    use \Exception;
    use \PDO;
    use \model\Book;
    use \model\AudioBook;
    use \model\Review;

    class DBManager
    {
        private $serverName;
        private $username;
        private $password;
        private $dbName;
        private $conn;
        private $port;

        public function __construct()
        {
            // retrieve configurations from file
            $configs = parse_ini_file(
                './db/dbconfig.ini',
                true
            );

            // set DB connection parameters
            $this->serverName = $configs['connection']['server'];
            $this->username = $configs['connection']['username'];
            $this->password = $configs['connection']['password'];
            $this->dbName = $configs['connection']['database'];
            $this->port = $configs['connection']['port'];
        }

        private function connect()
        {
            try
            {
                $this->conn = new \PDO(
                    "mysql:host=$this->serverName;dbname=$this->dbName",
                    $this->username,
                    $this->password
                );

                // set the PDO error mode to exception
                $this->conn->setAttribute(
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                );
            }
            catch (\PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        private function disconnect()
        {
            $this->conn = null;
        }
        
        public function getAllBooks(): array
        {
            $this->connect();
            $sql = "SELECT * FROM book";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0)
            {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC))
                {
                    array_push(
                        $books,
                        new Book(
                            $row["title"],
                            $row["author"],
                            $row["price"],
                            $row["img"],
                            $row["link"],
                            $row["source"]
                        )
                    );
                }
            }

            $this->disconnect();
            return $books;
        }

        public function getNewBooks(): array
        {
            $this->connect();
            $sql = "SELECT * FROM book WHERE is_recent=1";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0)
            {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC))
                {
                    array_push(
                        $books,
                        new Book(
                            $row["title"],
                            $row["author"],
                            $row["price"],
                            $row["img"],
                            $row["link"],
                            $row["source"]
                        )
                    );
                }
            }

            $this->disconnect();
            return $books;
        }

        public function addBooks(array $books)
        {
            $this->connect();
            $stmt = $this->conn->prepare(
                "INSERT INTO book (title,author,price,img,link,is_recent,source)
                VALUES (:title,:author,:price,:img,:link,:is_recent,:source)"
            );
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':img', $img);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':is_recent', $isRecent);
            $stmt->bindParam(':source', $source);

            foreach ($books as $book)
            {
                $title = $book->getTitle();
                $author = $book->getAuthor();
                $price = $book->getPrice();
                $img = $book->getImg();
                $link = $book->getLink();
                $isRecent = $book->isRecent() ? 1 : 0;
                $source = $book->getSource();

                $stmt->execute();
            }

            $this->disconnect();
        }

        public function getAllAudioBooks(): array
        {
            $this->connect();
            $sql = "SELECT * FROM audioBook";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0)
            {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC))
                {
                    array_push(
                        $books,
                        new AudioBook(
                            $row["title"],
                            $row["author"],
                            $row["voice"],
                            $row["img"],
                            $row["link"]
                        )
                    );
                }
            }

            $this->disconnect();
            return $books;
        }

        public function getNewAudioBooks(): array
        {
            $this->connect();
            $sql = "SELECT * FROM audioBook WHERE is_recent=1";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0)
            {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC))
                {
                    array_push(
                        $books,
                        new AudioBook(
                            $row["title"],
                            $row["author"],
                            $row["voice"],
                            $row["img"],
                            $row["link"]
                        )
                    );
                }
            }

            $this->disconnect();
            return $books;
        }

        public function addAudioBooks(array $books)
        {
            $this->connect();
            $stmt = $this->conn->prepare(
                "INSERT INTO audioBook (title,author,voice,img,link,is_recent)
                VALUES (:title,:author,:voice,:img,:link,:is_recent)"
            );
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':voice', $voice);
            $stmt->bindParam(':img', $img);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':is_recent', $isRecent);

            foreach ($books as $book)
            {
                $title = $book->getTitle();
                $author = $book->getAuthor();
                $voice = $book->getVoice();
                $img = $book->getImg();
                $link = $book->getLink();
                $isRecent = $book->isRecent() ? 1 : 0;

                $stmt->execute();
            }

            $this->disconnect();
        }

        public function getAllReviews(): array
        {
            $this->connect();
            $sql = "SELECT * FROM review";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0)
            {
                while ($row = $result->fetch(\PDO::FETCH_ASSOC))
                {
                    array_push(
                        $books,
                        new Review(
                            $row["title"],
                            $row["author"],
                            $row["plot"],
                            $row["txt"],
                            $row["average"],
                            $row["style"],
                            $row["content"],
                            $row["pleasantness"]
                        )
                    );
                }
            }

            $this->disconnect();
            return $books;
        }

        public function getNewReviews(): array
        {
            $this->connect();
            $sql = "SELECT * FROM review WHERE is_recent=1";
            $result = $this->conn->query($sql);

            $books = array();
            if ($result->rowCount() > 0)
            {
                while ($row = $result->fetch(PDO::FETCH_ASSOC))
                {
                    array_push(
                        $books,
                        new Review(
                            $row["title"],
                            $row["author"],
                            $row["plot"],
                            $row["txt"],
                            $row["average"],
                            $row["style"],
                            $row["content"],
                            $row["pleasantness"]
                        )
                    );
                }
            }

            $this->disconnect();
            return $books;
        }

        public function addReviews(array $reviews)
        {
            $this->connect();
            $this->connect();
            $stmt = $this->conn->prepare(
                "INSERT INTO review (title,author,plot,txt,average,style,content,pleasantness,is_recent)
                VALUES (:title,:author,:plot,:txt,:average,:style,:content,:pleasantness,:is_recent)"
            );
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':plot', $plot);
            $stmt->bindParam(':txt', $txt);
            $stmt->bindParam(':average', $average);
            $stmt->bindParam(':style', $style);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':pleasantness', $pleasantness);
            $stmt->bindParam(':is_recent', $isRecent);

            foreach ($reviews as $review)
            {
                $title = $review->getTitle();
                $author = $review->getAuthor();
                $plot = $review->getPlot();
                $txt = $review->getText();
                $average = $review->getAvg();
                $style = $review->getStyle();
                $content = $review->getContent();
                $pleasantness = $review->getPleasantness();
                $isRecent = $review->isRecent() ? 1 : 0;

                $stmt->execute();
            }

            $this->disconnect();
        }
    }
