**BIG GOALS**
- fix the bug where we cant pull the csv file from the input request

- put all filtering in a try and catch block

- finish the mass import for schools and students

- fix any other bugs that arise

- implement new requirements given by Jordan

- make a section where the pending accounts for local admins can be accepted or rejected from super admin dashboard 

**Steps for the pending account feature for super admin**

1. create the ViewPendingLocaladminScreen

2. make the layout

3. query all the local admins who have a "account_status" of 0

4. allow the super admin to select accounts to accept or deny

5. if super admin accepts local admin, update the local admins permissions in the users table to {"platform.index":true} and set 'account_status' to 1

6. if super admin denies the pending localadmin, delete the account(s) from the database