### Tools
1. Php 8.3
2. Postgres 15
3. Symfony 7.1
4. Docker-compose
5. Phpstan
6. php-cs-fixer

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/ShchedrinAndrei/rickandmorty.git
   ```
2. Copy env file
   ```sh
   cp .env.dist .env
   ```
3. Build the project
   ```sh
   cd test_task
   make build
   ```
4. Run migrations
   ```sh
   make migrate
   ```
5. Fetch episodes from the API
   ```sh
   php bin/console app:fetch_episodes
   ```
   
### Before push

1. Run tests
   ```sh
   make test
   ```
2. Run phpstan
   ```sh
    make static-analysis
    ```
3. Run php-cs-fixer
    ```sh
     make codestyle
     ```
