create table if not exists USER
(
  ID integer primary key
);

create table if not exists MESSAGE
(
  ID        integer primary key,
  USER_ID   integer,
  TIMESTAMP varchar(50),
  MESSAGE   TEXT,
  foreign key (USER_ID) references USER (ID)
);
create index if not exists USER_ID_INDEX on MESSAGE (USER_ID);
