/*  push_event_to_user: event_id%TYPE, uid%TYPE -> void

    This procedure takes a user and event id, returns nothing, and has the side
    effect of associating that user with that event */

create or replace procedure push_event_to_user(
    desired_event_id Event.event_id%TYPE,
    desired_uid Account.user_id%TYPE) as

begin

    insert into Event_users
    values
    (desired_event_id, desired_uid);

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
(666, '666@satan.gov', 'hell0', 'Satan'); 

insert into Account
values
(667, 'ted@us.gov', 'jamesbond007', 'Ted'); 

insert into Event
values
(999, sysdate, 'The DMV Hoedown');

prompt Before call
select * from Event_users;

prompt calling push_event_to_user(999, 666) 
exec push_event_to_user(999, 666)
select * from Event_users;

prompt calling push_event_to_user(999, 667) 
exec push_event_to_user(999, 667)
select * from Event_users;

rollback;

