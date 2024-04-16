create table esp.table_esp
(
    id          int auto_increment
        primary key,
    time        timestamp    not null,
    encrypted_t varchar(255) not null,
    encrypted_h varchar(255) not null
);

