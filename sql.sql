CREATE DATABASE IF NOT EXISTS inlinetest;

DROP TABLE IF EXISTS posts, comments;

CREATE TABLE posts
(
    id integer NOT NULL UNIQUE PRIMARY KEY,
    user_id integer NOT NULL,
    title char(255) NOT NULL UNIQUE,
    body text NOT NULL
);

CREATE TABLE comments
(
    id integer NOT NULL PRIMARY KEY,
    post_id integer NOT NULL REFERENCES posts (id),
    name char(255) NOT NULL,
    email char(70) NOT NULL,
    body text NOT NULL
);

