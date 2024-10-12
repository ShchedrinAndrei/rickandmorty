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
   git clone git@gitlab.com:andrey_schedrin/clinic_crawler.git
   ```
2. Build the project
   ```sh
   cd clinic_crawler
   make build
   ```
3. Run migrations
   ```sh
   make migrate
   ```
   
### Before push

1. Run tests
   ```sh
   make test
   ```
2. Run phpstan
   ```sh
    make stat-analysis
    ```
3. Run php-cs-fixer
    ```sh
     make codestyle
     ```
