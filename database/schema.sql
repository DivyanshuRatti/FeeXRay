CREATE DATABASE IF NOT EXISTS feexray
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE feexray;

CREATE TABLE users (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    name          VARCHAR(100) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('investor','advisor','admin') NOT NULL DEFAULT 'investor',
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE funds(
    id              INT PRIMARY KEY AUTO_INCREMENT ,
    name            VARCHAR(150) NOT NULL,
    fund_code       VARCHAR(20) NOT NULL UNIQUE,
    provider        VARCHAR(100) NOT NULL, 
    asset_class     VARCHAR(50),
    mer             DECIMAL(5,4) NOT NULL CHECK (mer >= 0 AND mer < 0.10),
    mer_as_of       DATE NOT NULL 
);

CREATE TABLE portfolios(
    id              INT PRIMARY KEY AUTO_INCREMENT,
    user_id         INT NOT NULL, 
    name            VARCHAR(100) NOT NULL,
    account_type    ENUM('RRSP','TFSA','PENSION','NON_REG') NOT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE holdings(
    id              INT PRIMARY KEY AUTO_INCREMENT,
    portfolio_id    INT NOT NULL,
    fund_id         INT,
    custom_name     VARCHAR(150),
    custom_mer      DECIMAL(5,4) CHECK (custom_mer >= 0 AND custom_mer < 0.10),
    amount          DECIMAL (12,2) NOT NULL CHECK(amount>0),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE,
    FOREIGN KEY (fund_id) REFERENCES funds(id),
    CHECK((fund_id IS NOT NULL AND custom_name IS NULL AND custom_mer IS NULL)
    OR (fund_id IS NULL AND custom_name IS NOT NULL AND custom_mer IS NOT NULL))
);

CREATE TABLE projections(
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    portfolio_id        INT NOT NULL,
    years               INT NOT NULL CHECK(years>=1 AND years<=50),
    assumed_return      DECIMAL (5,4) NOT NULL CHECK(assumed_return>=0 AND assumed_return<=0.15),
    projected_with_fees DECIMAL (14,2) NOT NULL,
    projected_low_cost  DECIMAL (14,2) NOT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE,
    UNIQUE (portfolio_id, years, assumed_return)
);