delete from Event_users;
delete from Tasks_in_project;
delete from Users_in_group;
delete from Task;
delete from Project;
delete from Usergroup;
delete from Event;
delete from Account;

insert into Account
values 
(00000001, 'bob@dobalina.com', 'password123', 'bob dobalina');
insert into Account
values 
(00000002, 'dave@gmail.com', 'daveiscool', 'dave');
insert into Account
values 
(00000003, 'jan@gmail.com', 'jan111', 'J. Jenson');
insert into Account
values 
(00000004, 'ryan@gmail.com', 'ryry', 'RYAN');
insert into Account
values 
(00000005, 'satan@hell.com', 'hail666', 'Satan420');

insert into Event
values
(00000001, to_date('20181220', 'yyyymmdd'), 'COMPANY MEETING');
insert into Event
values
(00000002, to_date('20181221', 'yyyymmdd'), 'ANOTHER MEETING');
insert into Event
values
(00000003, to_date('20181225', 'yyyymmdd'), 'OFFICE XMAS PARTY');
insert into Event
values
(00000004, to_date('20181230', 'yyyymmdd'), 'MEETING ABOUT MEETINGS');
insert into Event
values
(00000005, to_date('20181231', 'yyyymmdd'), 'NEW YEARS EVE PARTY');

insert into Usergroup
values
(00000000, 'Mongooses');

insert into Project
values
(00000001, 'A test project. This project is for testing. It will be tested.', 00000000, 'TEST PROJECT');

insert into Task
values
(00000001, 00000001, 00000001, 'in progress', 'do this', to_date('20181120', 'yyyymmdd'), 'comment', 'Test Code');

insert into Users_in_group
values
(00000001, 00000000, 1);

insert into Tasks_in_project
values
(00000001, 00000001);

insert into Event_users
values
(00000001, 00000001);
