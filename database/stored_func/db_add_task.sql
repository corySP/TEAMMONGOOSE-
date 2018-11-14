/*  add_task: u_id%TYPE, p_id%TYPE, name%TYPE, desc%TYPE -> void

    This procedure takes a user id, a project id, a task name and task 
    description, and returns nothing, but has the side effect of inserting
    the task into the table */

create or replace procedure add_task(
    task_uid Account.user_id%TYPE,
    task_pid Project.project_id%TYPE,
    new_task_name Task.task_name%TYPE,
    new_task_desc Task.task_description%TYPE,
    new_task_date date) as

max_tid Task.task_id%TYPE;

begin
    select nvl( max(task_id), 0 )
    into max_tid
    from Task;

    insert into Task
    values
    (max_tid + 1, task_uid, task_pid, 'not started', new_task_desc,
        new_task_date, ' ', new_task_name);

exception
    when others then
        dbms_output.put_line('error: ' || sqlcode || '-' || sqlerrm);

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

prompt Before call
select task_id, task_name from Task;

prompt calling add_task(999, 999, 'Do this', 'You have to do this', sysdate) 
exec add_task(999, 999, 'Do this', 'You have to do this', sysdate)
select task_id, task_name from Task;

prompt calling add_task(999, 999, 'Do that', 'You have to do that', sysdate) 
exec add_task(999, 999, 'Do that', 'You have to do that', sysdate)
select task_id, task_name from Task;

rollback;

