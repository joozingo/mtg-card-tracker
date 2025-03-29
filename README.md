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

## Technology Stack

*   **Backend:** Laravel 10.x
*   **Frontend:** Blade Templates, Tailwind CSS (via Vite)
*   **Database:** Configurable via Laravel's `.env` (e.g., MySQL, PostgreSQL)
*   **External API:** Scryfall API for card data

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
5.  **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```
6.  **Build Frontend Assets:**
    ```bash
    npm run dev
    ```
    (Or `npm run build` for production assets)
7.  **Import Card Data:** (Important!)
    *   To populate the database with card information from Scryfall, run the import command:
        ```bash
        php artisan app:import-cards
        ```
    *   *Note: This initial import can take a significant amount of time depending on the Scryfall bulk data size.*
8.  **Serve the Application:**
    ```bash
    php artisan serve
    ```
    You can then access the application at `http://localhost:8000` (or the specified port).

## Usage

1.  Register for a new account or log in if you already have one.
2.  Use the search bar or advanced search page to find cards.
3.  View card details by clicking on a card image.
4.  Use the buttons on card images (hover on desktop, visible on mobile) or detail pages to add cards to your Collection, Wishlist, or specific Decks.
5.  Manage your Collection, Wishlist, and Decks via the navigation menu.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
