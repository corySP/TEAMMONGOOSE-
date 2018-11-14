/*  add_event: date Event.event_name%TYPE -> void

    This procedure expects a date for the time of the event and a name, returns
    nothing, and has the side effect of adding that event to the event table */

create or replace procedure add_event(
    new_event_date date,
    new_event_name Event.event_name%TYPE) as

max_eid Event.event_id%TYPE;

begin
    select nvl( max(event_id), 0 )
    into max_eid
    from Event;

    insert into Event
    values
    (max_eid + 1, new_event_date, new_event_name);

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

prompt Before call

select * from Event;

prompt Calling add_event(sysdate, "Pie sale")
prompt

exec add_event(sysdate, 'Pie Sale')
select * from Event;

prompt Calling add_event(sysdate, 'Cake Walk')
prompt Should have a event id that is 1 above the Pie Sale event id
prompt

exec add_event(sysdate, 'Cake Walk')
select * from Event;

rollback;
