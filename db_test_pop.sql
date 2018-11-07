delete from Event_users;
delete from Tasks_in_project;
delete from Task;
delete from Project;
delete from Usergroup;
delete from Event;
delete from Account;

insert into Account
values 
('00000001', 'bob@dobalina.com', 'password123', 'bob dobalina');

insert into Event
values
('00000001', to_date('20181120', 'yyyymmdd'), 'COMPANY MEETING');

insert into Usergroup
values
('00000000', 1, 'Mongooses');

insert into Project
values
('00000001', 'A test project', '00000000', 'TEST PROJECT');

insert into Task
values
('00000001', '00000001', '00000001', 'in progress', 'do this', to_date('20181120', 'yyyymmdd'), 'comment', 'Test Code');

insert into Users_in_group
values
('00000001', '00000000');

insert into Tasks_in_project
values
('00000001', '00000001');

insert into Event_users
values
('00000001', '00000001');
