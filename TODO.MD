- add the school_id fieldin the student and localadmin table so we can get the proper school if there are any duplicate names

- when adding or editing a student or localadmin, we need to take the school_name, county and state_province and find that exact school from the db, get the id of that school and insert it in the "school_id" field in the student and localadmin table