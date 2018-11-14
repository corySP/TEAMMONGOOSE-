/*  user_login: user_id%TYPE, password%TYPE -> integer

    This function queries the database for the user and password and if
    the user exists with the same password it will return 1 else it will
    return a 0 */

create or replace function user_login(
    desired_uid Account.user_id%TYPE,
    attempted_pass Account.password%TYPE)
return integer as

user_count integer;

begin

    select count(*)
    into user_count
    from Account
    where user_id = desired_uid and
        password = attempted_pass;

    if user_count = 1 then
        return 1;
    else
        return 0;
    end if;
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

prompt calling user_login(666, 'hel1')
prompt should return 0
select user_login(666, 'hel1') from dual;

prompt calling user_login(667, 'jamesbond007')
prompt should return 1
select user_login(667, 'jamesbond007') from dual;

rollback;

    
