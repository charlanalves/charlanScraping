docker-compose build
docker run --rm --interactive --tty --volume $PWD:/app composer install
docker-compose run eipriceteste php EiPriceScraping.php
