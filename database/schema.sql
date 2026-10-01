CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;
-- =========================================================
-- USERS
-- =========================================================
CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'visitor') NOT NULL DEFAULT 'visitor',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email),
    INDEX idx_users_role (role)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- =========================================================
-- CATEGORIES
-- =========================================================
CREATE TABLE categories (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_name (name),
    UNIQUE KEY uq_categories_slug (slug)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- =========================================================
-- TECHNOLOGIES
-- =========================================================
CREATE TABLE technologies (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_technologies_name (name),
    UNIQUE KEY uq_technologies_slug (slug)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- =========================================================
-- PORTFOLIOS
-- =========================================================
CREATE TABLE portfolios (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL,
    description TEXT NOT NULL,
    thumbnail VARCHAR(500) DEFAULT NULL,
    /*
     * upload = project uploaded and extracted locally
     * url    = external live project
     */
    project_type ENUM('upload', 'url') NOT NULL,
    /*
     * For uploaded projects:
     *
     * Example:
     * projects/my-project
     *
     * For URL projects this can remain NULL.
     */
    project_path VARCHAR(500) DEFAULT NULL,
    /*
     * For uploaded projects:
     *
     * Example:
     * index.html
     *
     * For URL projects this can remain NULL.
     */
    entry_path VARCHAR(500) DEFAULT NULL,
    /*
     * For external projects:
     *
     * Example:
     * https://example.com/project/
     *
     * For uploaded projects this remains NULL.
     */
    external_url VARCHAR(1000) DEFAULT NULL,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_portfolios_slug (slug),
    INDEX idx_portfolios_category (category_id),
    INDEX idx_portfolios_featured (is_featured),
    INDEX idx_portfolios_published (is_published),
    INDEX idx_portfolios_sort_order (sort_order),
    CONSTRAINT fk_portfolios_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- =========================================================
-- PORTFOLIO <-> TECHNOLOGY
-- MANY-TO-MANY PIVOT
-- =========================================================
CREATE TABLE portfolio_technology (
    portfolio_id BIGINT UNSIGNED NOT NULL,
    technology_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (portfolio_id, technology_id),
    INDEX idx_portfolio_technology_technology (technology_id),
    CONSTRAINT fk_portfolio_technology_portfolio FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_portfolio_technology_technology FOREIGN KEY (technology_id) REFERENCES technologies(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- =========================================================
-- PROFILE
-- =========================================================
CREATE TABLE profile (
    id TINYINT UNSIGNED NOT NULL DEFAULT 1,
    full_name VARCHAR(150) NOT NULL,
    headline VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    skills TEXT DEFAULT NULL,
    experience TEXT DEFAULT NULL,
    education TEXT DEFAULT NULL,
    profile_image VARCHAR(500) DEFAULT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- =========================================================
-- SETTINGS
-- =========================================================
CREATE TABLE settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_settings_key (setting_key)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;