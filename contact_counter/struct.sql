CREATE TABLE /*TABLE_PREFIX*/t_item_counter (
    fk_i_item_id INT UNSIGNED NOT NULL,
    i_contacts INT(6) UNSIGNED,

        PRIMARY KEY (fk_i_item_id),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_item_counter_detail (
    fk_i_item_id INT UNSIGNED NOT NULL,
    dt_date DATETIME DEFAULT NULL,

        PRIMARY KEY (fk_i_item_id, dt_date),
        FOREIGN KEY (fk_i_item_id) REFERENCES /*TABLE_PREFIX*/t_item (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';