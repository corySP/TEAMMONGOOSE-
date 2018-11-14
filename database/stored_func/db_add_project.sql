/*  add_project: description%TYPE, ugroup_id%TYPE, name%TYPE -> void

    This procedure requires the project description, name and associated group
    id, it returns nothing, and has the side effect of inserting an entry into 
    the Project table */

create or replace procedure add_project(
    new_project_name Project.project_name%TYPE,
    new_project_description Project.project_description%TYPE,
    new_project_group Project.ugroup_id%TYPE) as

max_pid Project.project_id%TYPE;

begin
    select nvl( max(project_id), 0 )
    into max_pid
    from Project;

    insert into Project
    values
    (max_pid + 1, new_project_description, new_project_group, new_project_name);

exception
    when others then
        dbms_output.put_line('error: ' || sqlcode || '-' || sqlerrm);

end;
/
show errors

/* testing */
set serveroutput on
commit;

insert into Usergroup
values
(999, 1, 'Fish'); 

prompt Before call
select * from Project;

prompt calling add_project('MY WEBSITE', 'Its a website. For you', 999)
exec add_project('MY WEBSITE', 'Its a website. For you', 999)
select * from Project;

prompt calling add_project('Pasta Bridge', 'Build a bridge. Out of uncooked pasta', 999)
exec add_project('Pasta Bridge', 'Build a bridge. Out of uncooked pasta', 999)
select * from Project;

rollback;
