drop table users;
drop table reviews;
drop table posts;
drop table savedposts;

create table users
(
    id       int(11)      not null auto_increment,
    login    varchar(50)  not null,
    password varchar(255) not null,
    primary key (id)
) engine = InnoDB
  default charset = utf8mb4
  collate = utf8mb4_general_ci;

create table posts
(
    id          int(11)      not null auto_increment,
    author      varchar(50)  not null,
    title       text         not null,
    date        datetime     not null,
    info        text         null,
    ingredients text         null,
    recipe      text         not null,
    recommended bool         null,
    picture     varchar(400) null,
    primary key (id)
) engine = InnoDB
  default charset = utf8mb4
  collate = utf8mb4_general_ci;

create table reviews
(
    id            int(11)     not null auto_increment,
    post_id       int(11)     not null,
    review_author varchar(50) not null,
    rating        int(1)      not null default (0),
    review_text   text        null,
    date          datetime    not null,
    primary key (id, post_id),
    constraint fk_post_id
        foreign key (post_id) references posts (id) on delete cascade
) engine = InnoDB
  default charset = utf8mb4
  collate = utf8mb4_general_ci;


create table savedposts
(
    id          int(11)     not null auto_increment,
    post_id     int(11)     not null,
    save_author varchar(50) not null,
    primary key (id, post_id),
    constraint fk_post_id2
        foreign key (post_id) references posts (id) on delete cascade
) engine = InnoDB
  default charset = utf8mb4
  collate = utf8mb4_general_ci;


