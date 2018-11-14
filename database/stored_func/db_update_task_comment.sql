/*  update_task_comment: task_id%TYPE, task_comment%TYPE -> void

    This procedure takes a task id and a desired comment, returns nothing, and 
    has the side effect of updating the comment field of that task entry */

create or replace procedure update_task_comment(
    desired_task Task.task_id%TYPE,
    new_comment Task.user_comment%TYPE) as

begin
    update Task
    set user_comment = new_comment
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
select task_id, user_comment from Task;

prompt calling update_task_comment(9999, 'I hate this task, I do not want to finish it!')
exec update_task_comment(9999, 'I hate this task, I do not want to finish it!')
select task_id, user_comment from Task;

rollback;
