drop table reviews;
drop table posts;

create table posts
(
    id          int(11)      not null auto_increment,
    author      varchar(50)  not null,
    title       text         not null,
    date        datetime     not null,
    info        text         null,
    ingredients text         null,
    recipe      text         not null,
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
    primary key (id, post_id),
    constraint fk_post_id
        foreign key (post_id) references posts (id) on delete cascade
) engine = InnoDB
  default charset = utf8mb4
  collate = utf8mb4_general_ci;


