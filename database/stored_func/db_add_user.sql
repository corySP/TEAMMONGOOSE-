/* add_user: email%TYPE, pass%TYPE, name%TYPE -> void

   Expects the eamil, password, and name of a user to be added to the
   accounts table. Returns nothing but has the side effect of adding
   that user to the table with a unique id number */

create or replace procedure add_user(new_email Account.email_addr%TYPE,
    new_pass Account.password%TYPE,
    new_name Account.user_name%TYPE) as

max_id_num Account.user_id%TYPE;

begin
    select nvl( max(user_id), 0 )
    into max_id_num
    from Account;

    insert into Account
    values
    (max_id_num + 1, new_email, new_pass, new_name);

exception
    when others then
        dbms_output.put_line('error: ' || sqlcode || '-' || sqlerrm);

end;
/
show errors

/* testing for add_user */
set serveroutput on

commit;

prompt Before
prompt

select * from Account;

prompt Calling add_user('Jim@pp.le', 'password', 'Jim Apple')
prompt should insert a user called Jim with a user_id
prompt

exec add_user('Jim@pp.le', 'password', 'Jim Apple')
select * from Account;

prompt \n Calling add_user ('Y@hoo.com', 'yahoo123', 'Yahoo Dotcom')
prompt should insert a user with an id one more than Jims id
prompt

exec add_user ('Y@hoo.com', 'yahoo123', 'Yahoo Dotcom')
select * from Account;

rollback;


