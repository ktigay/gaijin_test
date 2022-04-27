drop  table if exists posts;
create table posts
(
    id   int(11) unsigned auto_increment,
    user_id    int(11) unsigned not null comment 'users.id',
    title     varchar(255),
    text      text,
    create_at timestamp default current_timestamp,
    update_at timestamp default current_timestamp on update current_timestamp,
    primary key (id)
) ENGINE = InnoDB
  CHARACTER SET = UTF8MB4 comment 'Посты';

drop  table if exists comments;
create table comments
(
    id int(11) unsigned auto_increment,
    user_id    int(11) unsigned not null comment 'users.id',
    post_id    int(11) unsigned not null comment 'posts.id',
    parent_id  int(11) unsigned default null comment 'comments.id',
    level int(5) unsigned default 0,
    text       text,
    deleted boolean default false,
    create_at  timestamp        default current_timestamp,
    update_at  timestamp        default current_timestamp on update current_timestamp,
    primary key (id),
    key post_parent (post_id, parent_id)
) ENGINE = InnoDB
  CHARACTER SET = UTF8MB4 comment 'Комментарии';

drop  table if exists users;
create table users (
                       id int(11) unsigned auto_increment,
                       name varchar(100),
                       create_at  timestamp        default current_timestamp,
                       update_at  timestamp        default current_timestamp on update current_timestamp,
                       primary key (id)
) ENGINE = InnoDB
  CHARACTER SET = UTF8MB4 comment 'Пользователи';