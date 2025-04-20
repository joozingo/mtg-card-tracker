# MTG Card Tracker

## About This Project

MTG Card Tracker is a web application built with Laravel designed to help Magic: The Gathering players search for cards, manage their personal collection, track wishlists, and build decks. It utilizes the Scryfall API for comprehensive card data.

## Key Features

*   **Card Database:** Browse and search a comprehensive database of MTG cards.
*   **Advanced Search:** Filter cards by name, text, type, set, rarity, color identity, and mana cost.
*   **Collection Management:** Keep track of the cards you own, including quantities and condition (future enhancement).
*   **Wishlist Management:** Maintain a list of cards you want to acquire.
*   **Deck Management:** Build and manage your MTG decks.
*   **User Accounts:** Securely manage your personal collection, wishlists, and decks.
*   **Responsive Design:** Usable interface across desktop, tablet, and mobile devices.
*   **Set Sorting:** Sort card sets by name, code, or card count.
*   **Currency Selector:** Display card prices in the user's chosen currency (USD, EUR, GBP) with daily rate synchronization from Exchange Rate API.

## Technology Stack

*   **Backend:** Laravel 10.x
*   **Frontend:** Blade Templates, Tailwind CSS (via Vite)
*   **Database:** Configurable via Laravel's `.env` (e.g., MySQL, PostgreSQL)
*   **External API:** Scryfall API for card data; Exchange Rate API for currency conversion.

## Setup and Installation

1.  **Clone the repository:**
    ```bash
    git clone <your-repository-url>
    cd mtg-card-tracker
    ```
2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```
3.  **Install Node.js Dependencies:**
    ```bash
    npm install
    ```
4.  **Set up Environment Variables:**
    *   Copy the example environment file: `cp .env.example .env`
    *   Configure your database connection details (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and any other necessary settings (like `APP_KEY`) in the `.env` file.
    *   Generate an application key: `php artisan key:generate`
    *   **Important:** Set `APP_URL` to the address you use to access the application (e.g., `APP_URL=http://127.0.0.1:8000` if using `php artisan serve` defaults). This is crucial for generating correct URLs for cached images.
    *   Set your Exchange Rate API key and base URL in the **.env** file:
      ```bash
      EXCHANGE_RATE_API_KEY=your_api_key_here
      EXCHANGE_RATE_API_BASE_URL=https://v6.exchangerate-api.com/v6
      ```
5.  **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```
6.  **Set Up Queue for Image Caching:**
    *   Choose a queue driver in your `.env` file (e.g., `QUEUE_CONNECTION=database`).
    *   If using the `database` driver, run: `php artisan queue:table` then `php artisan migrate`.
    *   Refer to Laravel documentation for other drivers (Redis, etc.).
7.  **Create Storage Link:** This makes cached images publicly accessible.
    ```bash
    php artisan storage:link
    ```
8.  **Build Frontend Assets:**
    ```bash
    npm run dev
    ```
    (Or `npm run build` for production assets)
9.  **Import Card Data:** (Important!)
    *   To populate the database with the latest card information from Scryfall (Oracle Cards dataset), run the import command. It will automatically download the data file to `storage/app/private/`.
        ```bash
        php artisan app:import-scryfall-data
        ```
    *   *Note: This initial import can take a significant amount of time depending on the Scryfall bulk data size.*
10. **Run Queue Worker:** Start a queue worker to process background jobs like image caching.
    ```bash
    php artisan queue:work --queue=image-caching
    ```
    (You may want to use Supervisor or similar to run this continuously in production).
11. **Sync Exchange Rates:**
    Fetch the initial USD→GBP/EUR rates by running:
    ```bash
    php artisan app:sync-exchange-rates
    ```
12. **Enable Scheduler:**
    Add the following to your server's crontab to ensure daily synchronization:
    ```cron
    * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
    ```
13. **Serve the Application:**
    ```bash
    php artisan serve
    ```
    You can then access the application at the URL specified in your `APP_URL` (e.g., `http://127.0.0.1:8000`).

## Usage

1.  Register for a new account or log in if you already have one.
2.  Use the search bar or advanced search page to find cards.
3.  View card details by clicking on a card image.
4.  Use the buttons on card images (hover on desktop, visible on mobile) or detail pages to add cards to your Collection, Wishlist, or specific Decks.
5.  Manage your Collection, Wishlist, and Decks via the navigation menu.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
