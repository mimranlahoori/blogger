# Blogger 📝

A simple blog application built with **Laravel** and **TailwindCSS**.

This repository contains all the source code for running the blog — including a dedicated admin panel, post creation/editing, categories/tags management, user authentication, and more.

## 🌟 Features

* **Post Management:** Create, edit, and delete posts.
* **Taxonomy:** Categories and tags support for organization.
* **Content:** Rich text content rendering (via Blade + HTML).
* **Media:** Upload **featured images** for posts.
* **Admin Panel:** Comprehensive dashboard for managing posts, categories, tags (and optionally users).
* **Workflow:** Draft / published / archived post statuses.
* **Summaries:** Optional post excerpt for summaries in listings.
* **Scheduling:** Ability to change post status and **schedule** a future publish date/time.
* **UI/UX:** Clean, responsive UI using **TailwindCSS** & **Vite**.

---

## 🧰 Tech Stack

* **Backend:** PHP 8.x, Laravel (e.g., **11.x**)
* **Frontend:** TailwindCSS, Vite, Blade templating
* **Database:** MySQL / MariaDB (or any relational database supported by Laravel)
* **Storage:** Local filesystem (for images/uploads)
* **Optional:** Additional Laravel packages as defined in `composer.json` / `package.json`

---

## 🚀 Installation & Setup

1.  **Clone the repository**

    ```bash
    git clone [https://github.com/mimranlahoori/blogger.git](https://github.com/mimranlahoori/blogger.git)
    cd blogger
    ```

2.  **Configure Environment**

    Copy `.env.example` to `.env` and configure your environment variables (database credentials, application name, etc.).

    ```bash
    cp .env.example .env
    ```

3.  **Install PHP dependencies** via Composer

    ```bash
    composer install
    ```

4.  **Install JS dependencies & build assets**

    ```bash
    npm install
    npm run dev  # For local development (watches for changes)
    # OR
    npm run build  # For production deployment (optimized, one-time build)
    ```

5.  **Generate application key**

    ```bash
    php artisan key:generate
    ```

6.  **Run migrations** (and optionally seed database)

    ```bash
    php artisan migrate
    # php artisan db:seed   (Run this ONLY if you have provided initial seeders)
    ```

7.  **Start Laravel development server**

    ```bash
    php artisan serve
    ```

    Then open `http://127.0.0.1:8000` in your browser.

    > **Note:** For production environments, it's recommended to configure a web server (like Nginx or Apache) to serve the `public/` directory.

---

## 📝 Usage

* **Access Admin:** Login to the admin area, typically at `/login` or `/admin/login` (if configured).
* **Manage Posts:** Go to the "Posts" section in the dashboard.
* **Post Details:** When creating/editing a post, you can upload a featured image, manage categories & tags, set the status (draft/published/archived), and optionally schedule a publish date/time.
* **Summaries:** Use the **excerpt** field for concise post summaries displayed in blog listings.
* **Deletion:** Posts can be deleted using the “Delete Post” button (with confirmation). **This action is irreversible.**

---

## 🛠️ Customization & Extending

You can easily extend the project by:

* Adding **user roles and permissions** (e.g., admin / editor / guest).
* Implementing **comments** or reactions on posts.
* Adding **post search or filters** by category/tag/status.
* Integrating **SEO metadata** (meta title/description/keywords).
* Converting the frontend to an **SPA** or integrating an API for **headless** usage.

---

## 🤝 Contribution

Contributions are welcome! If you find a bug or want to add a feature:

1.  Fork the repository.
2.  Create a new branch: `git checkout -b feature-name`
3.  Make your changes & commit with a meaningful message.
4.  Submit a **pull request** describing your changes and their purpose.

Please ensure your code follows the existing coding style (Laravel conventions, Tailwind for UI, etc.).

---

## 📄 License

This project is open-source and available under the terms of the **[MIT License](LICENSE)**.

---

## 📧 Contact / Issues

If you face any issues or have suggestions, feel free to **open an issue** in this repository or contact the maintainer directly.
