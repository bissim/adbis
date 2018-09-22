<?php
    namespace controller;

    require_once './model/DAO.php';
    require_once './model/Review.php';
    require_once './controller/DBManager.php';

    use model\DAO;
    use model\Review;
    use controller\DBManager;

    class ReviewDAO implements DAO
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
            if (!($entity instanceof Review))
            {
                throw new \Exception('Object not instance of Review!');
            }

            // connect to database
            $this->connect();

            $review = $entity;

            $is_recent = $review->isRecent() ? 1 : 0;

            $instruction = "
                INSERT INTO review (title, author, plot, txt, average, style, content, pleasantness, is_recent)
                VALUES (:title, :author, :plot, :txt, :average, :style, :content, :pleasantness, :is_recent)
            ";
            $params = array(
                ':title' => $review->getTitle(),
                ':author' => $review->getAuthor(),
                ':plot' => $review->getPlot(),
                ':txt' => $review->getText(),
                ':average' => $review->getAvg(),
                ':style' => $review->getStyle(),
                ':content' => $review->getContent(),
                ':pleasantness' => $review->getPleasantness(),
                ':is_recent' => $is_recent
            );
            $this->dbMan->execute($instruction, $params);
            $this->dbMan->disconnect();

            // return persisted object
            return $review;
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
            if (!($entity instanceof Review))
            {
                throw new \Exception('Object not instance of Review!');
            }

            // connect to database
            $this->connect();

            // persist book into database
            $review = $entity;
            $instruction = "
                SELECT * FROM review WHERE id = :id
            ";
            $params = array(
                ':id' => $review->getId()
            );
            $results = $this->dbMan->query($instruction, $params);
            $this->dbMan->disconnect();

            // return persisted object
            if (!$results) // TODO check
            {
                throw new \Exception("Review with id {$review->getId()} not found!");
            }

            $review = new Review (
                $results[0]["title"],
                $results[0]["author"],
                $results[0]["plot"],
                $results[0]["txt"],
                $results[0]["average"],
                $results[0]["content"],
                $results[0]["style"],
                $results[0]["pleasantness"],
                $results[0]['is_recent']
            );
            $review->setId($results[0]['id']);

            return $review;
        }

        public function retrieveByTitle(string $title): array
        {
            $this->connect();

            $title = "%" . $title . "%";

            $instruction = "
                SELECT * FROM review WHERE title LIKE :title
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
                SELECT * FROM review WHERE author LIKE :author
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
                SELECT * FROM review WHERE is_recent = :is_recent
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
            if (!($entity instanceof Review))
            {
                throw new \Exception('Object not instance of Review!');
            }

            // TODO implement method
        }

        public function delete(object $entity): void
        {
            return; // TODO implement method
        }
    }
