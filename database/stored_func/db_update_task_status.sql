/*  update_task_status: task_id%TYPE, task_status%TYPE -> void

    This procedure takes a task id and a desired status, returns nothing, and 
    has the side effect of updating the status field of that task entry */

create or replace procedure update_task_status(
    desired_task Task.task_id%TYPE,
    new_status Task.current_status%TYPE) as

begin
    update Task
    set current_status = new_status
    where task_id = desired_task;
end;
/
show errors

/* testing */
set serveroutput on
commit;

insert into Account
values
(999, '666@satan.gov', 'hell0', 'Satan'); 

insert into Usergroup
values
(666, 0, 'The DMV');

insert into Project
values
(999, 'A userfriendly (web)portal to Hell', 666, 'Hell.com');

insert into Task
values
(9999, 999, 999, 'not started', 'Do this at some point', sysdate, ' ',
    'Do This');

prompt Before call
select task_id, current_status from Task;

prompt calling update_task_status(9999, 'in progress')
exec update_task_status(9999, 'in progress')
select task_id, current_status from Task;

rollback;
