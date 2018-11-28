/*
by: Mabel Houle
last modified: 2018-11-14

confirm_reg: string, string -> int
purpose: confirms that a new account created by registration has been added
to the database
*/

create or replace function confirm_reg(new_email string, new_name string, new_pwd string) 
                                      return integer as registered integer;
begin
     select count(USER_ID)
     into registered
     from Account
     where email_addr = new_email
	   and user_name = new_name
	   and password = new_pwd;

     if user_count = 1 then
          return 1;
     else if user_count = 0 then
          return 0;
     else
          return -1;
     end if;
end;
