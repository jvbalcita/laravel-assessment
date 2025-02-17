## Setup and Installation

This project uses Laravel with Inertia.js, Vue.js, Tailwind CSS, and Laravel Sail for local development.

### Prerequisites

- Docker
- Docker Compose

### Installation

1. **Clone the repository**:
    ```sh
    git clone https://git@github.com:jvbalcita/laravel-assessment.git
    cd larave-assessment
    ```

2. **Install dependencies**:
    ```sh
    composer install
    npm install
    ```

3. **Copy the `.env.example` file to `.env`**:
    ```sh
    cp .env.example .env
    ```

4. **Generate the application key**:
    ```sh
    php artisan key:generate
    ```

5. **Start the development environment**:
    ```sh
    ./vendor/bin/sail up -d
    ```

6. **Run the database migrations**:
    ```sh
    ./vendor/bin/sail artisan migrate
    ```

7. **Build the front-end assets**:
    ```sh
    npm run dev
    ```

The application should now be up and running on [http://localhost:8082](http://localhost:8082).

## Completed Levels

- [x] Level 1
- [x] Level 2
- [x] Level 3

## Bonus Points

- [x] All Bonus Points
