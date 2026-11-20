ALTER TABLE portfolios
    MODIFY account_type ENUM('RRSP','TFSA','PENSION','NON_REG','RESP') NOT NULL;
