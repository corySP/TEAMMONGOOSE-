/*  add_user_to_group: uid%TYPE, gid%TYPE -> void

    This procedure takes a user and group id, returns nothing, and has the side
    effect of associating that user with that group */

create or replace procedure add_user_to_group(
    desired_uid Account.user_id%TYPE,
    target_gid Usergroup.ugroup_id%TYPE) as

begin

    insert into Users_in_group
    values
    (desired_uid, target_gid);

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

prompt Before call
select * from Users_in_group;

prompt calling add_user_to_group(999, 666) 
exec add_user_to_group(999, 666)
select * from Users_in_group;

rollback;

