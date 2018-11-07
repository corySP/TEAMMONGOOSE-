delete from Account;
delete from Event;
delete from Usergroup;
delete from Project;
delete from Task;
delete from Users_in_group;
delete from Tasks_in_project;
delete from Event_users;

insert into Account
values 
('00000001', 'bob@dobalina.com', 'password123');

insert into Event
values
('00000001', to_date('20181120', 'yyyymmdd'));

insert into Usergroup
values
('00000000', 1);

insert into Project
values
('00000001', 'A test project', '00000000');

insert into Task
values
('00000001', '00000001', '00000001', 'in progress', 'do this', to_date('20181120', 'yyyymmdd'), 'comment');

insert into Users_in_group
values
('00000001', '00000000');

insert into Tasks_in_project
values
('00000001', '00000001');

insert into Event_users
values
('00000001', '00000001');
