--Everything here is subject to change or whatever. 

--Account(USER_ID, email_addr, pass)

drop table Account cascade constraints;
create table Account
(USER_ID integer,
email_addr varchar2(32) not null,
password varchar2(64) not null, --Don't know what datatype; temporary
user_name varchar2(64),
primary key (user_id)
);

--Event(EVENT_ID, event_datetime)

drop table Event cascade constraints;
create table Event
(EVENT_ID integer,
event_datetime date not null,
event_name varchar(128),
primary key (event_id)
);

--Group(GROUP_ID, permissions)

drop table Usergroup cascade constraints;
create table Usergroup
(UGROUP_ID integer,
ugroup_name varchar(16),
primary key (UGROUP_ID)
);

--Project(PROJECT_ID, project_name, project_description, group_id)
--    foreign key (group_id) references Group

drop table Project cascade constraints;
create table Project
(PROJECT_ID integer,
project_description varchar2(1024),
ugroup_id integer,
project_name varchar(128),
primary key (project_id),
foreign key (ugroup_id) references Usergroup
);

--Task(TASK_ID, user_id, current_status, task_description, task_data, 
--     user_comment)
--    foreign key (user_id) references Account

drop table Task cascade constraints;
create table Task
(TASK_ID integer,
user_id integer,
project_id integer not null,
current_status varchar2(16) not null,
task_description varchar2(1024),
task_date date,
user_comment varchar2(1024),
task_name varchar2(128),
primary key (task_id),
foreign key (user_id) references Account,
foreign key (project_id) references Project
);

--Users_in_group(USER_ID, GROUP_ID)
--    foreign key (user_id) references Account
--    foreign key (group_id) references Group

drop table Users_in_group cascade constraints;
create table Users_in_group
(USER_ID integer,
UGROUP_ID integer,
permissions integer check(permissions in (0, 1)) not null,
primary key (user_id, ugroup_id),
foreign key (user_id) references Account,
foreign key (ugroup_id) references Usergroup
);

--Tasks_in_project(PROJECT_ID, TASK_ID)
--    foreign key (project_id) references Project
--    foreign key (task_id) references Task

drop table Tasks_in_project cascade constraints;
create table Tasks_in_project
(TASK_ID integer,
PROJECT_ID integer,
primary key (task_id, project_id),
foreign key (task_id) references Task,
foreign key (project_id) references Project
);

--Event_users(EVENT_ID, USER_ID)
--    foreign key (event_id) references Event
--    foreign key (user_id) references Account

drop table Event_users cascade constraints;
create table Event_users
(EVENT_ID integer,
USER_ID integer,
primary key (event_id, user_id),
foreign key (event_id) references Event,
foreign key (user_id) references Account
);

--Chat_message(MESSAGE_ID, user_name, message_text)

drop table Chat_message cascade constraints;
create table Chat_message
(MESSAGE_ID integer not null,
user_name varchar2(64) not null,
message_text varchar2(1024),
primary key (message_id)
);

drop sequence message_id_seq;
create sequence message_id_seq;

create or replace trigger ai_message_id
    before insert 
    on Chat_message
    for each row
begin
    select message_id_seq.nextval
    into :new.message_id
    from dual;
end;
/
show errors

--Hangman_game(HANG_ID, word, current_progress, level)

drop table Hangman cascade constraints;
create table Hangman
(HANG_ID integer not null,
word varchar2(64) not null,
current_progress varchar2(64) not null,
hang_level integer not null,
complete integer not null,
primary key (hang_id)
);

drop sequence hang_id_seq;
create sequence hang_id_seq;

create or replace trigger ai_hang_id
    before insert
    on Hangman
    for each row
begin
    select hang_id_seq.nextval
    into :new.hang_id
    from dual;

    select 0
    into :new.hang_level
    from dual;

    select 0
    into :new.complete
    from dual;
end;
/
show errors
