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
   git clone git@gitlab.com:andrey_schedrin/test_task.git
   ```
2. Build the project
   ```sh
   cd test_task
   make build
   ```
3. Run migrations
   ```sh
   make migrate
   ```
4. Fetch episodes from the API
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
