--Everything here is subject to change or whatever. 

--Account(USER_ID, email_addr, pass)

drop table Account cascade constraints;
create table Account
(USER_ID integer,
email_addr varchar2(32) not null,
password varchar2(64) not null, --Don't know what datatype; temporary
user_name varchar2(32),
primary key (user_id)
);

--Event(EVENT_ID, event_datetime)

drop table Event cascade constraints;
create table Event
(EVENT_ID integer,
event_datetime date not null,
event_name varchar(32),
primary key (event_id)
);

--Group(GROUP_ID, permissions)

drop table Usergroup cascade constraints;
create table Usergroup
(UGROUP_ID integer,
permissions integer check(permissions in (0, 1)) not null,
ugroup_name varchar(16),
primary key (UGROUP_ID)
);

--Project(PROJECT_ID, project_name, project_description, group_id)
--    foreign key (group_id) references Group

drop table Project cascade constraints;
create table Project
(PROJECT_ID integer,
project_description varchar2(280),
ugroup_id integer,
project_name varchar(16),
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
task_description varchar2(280),
task_date date,
user_comment varchar2(140),
task_name varchar2(16),
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
