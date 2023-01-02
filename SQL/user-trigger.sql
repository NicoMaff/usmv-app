CREATE DEFINER = `root` @`localhost` trigger addStats
after
insert on `user` for each row begin
update ffbad_stat
set user_id = new.id
where (
    new.last_name = ffbad_stat.last_name
    and new.first_name = ffbad_stat.first_name
  );
end