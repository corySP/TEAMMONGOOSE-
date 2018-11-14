/*  user_check: email%TYPE -> integer

    This function queries the database for the email to check if it
    is in use */

create or replace function user_check(
    d_email Account.email_addr%TYPE)
return integer as

user_count integer;

begin

    select count(*)
    into user_count
    from Account
    where email_addr = d_email;

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
select user_check('666@satan.gov') from dual;

prompt calling user_login(667, 'jamesbond007')
prompt should return 1
select user_check('hello@world.com') from dual;

rollback;

    
