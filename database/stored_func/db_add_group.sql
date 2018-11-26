/* add_group: Usergroup.ugroup_name%TYPE Usergroup.permissions%TYPE -> void
   
   This takes a time and a name of an event, returns nothing, and has the
   side effect of inserting that event into the event table */

create or replace procedure add_group(
    new_group_name Usergroup.ugroup_name%TYPE,
    new_group_permissions Usergroup.permissions%TYPE) as

max_gid Usergroup.ugroup_id%TYPE;

begin
    select nvl( max(ugroup_id), 0 )
    into max_gid
    from Usergroup;

    insert into Usergroup
    values
    (max_gid + 1, new_group_permissions, new_group_name);

exception
    when others then
        dbms_output.put_line('error: ' || sqlcode || '-' || sqlerrm);

end;
/
show errors

/* testing */
set serveroutput on
commit;

prompt TESTING ADD_GROUP
prompt

prompt Before calling add_group

select * from Usergroup;

prompt Calling add_group('Wombats', 1)
prompt 

exec add_group('Wombats', 1)
select * from Usergroup;

prompt Calling add_group('Team 2', 0)
prompt Should see Team 2 having a gid that is one greater than Wombats gid
prompt

exec add_group('Team 2', 0)
select * from Usergroup;

rollback;
