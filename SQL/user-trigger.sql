CREATE DEFINER = `root` @`localhost` trigger addStats
after
insert on `user` for each row begin
update ffbad_stat
set user_id = new.id
where (
    new.last_name = ffbad_stat.last_name
    and new.first_name = ffbad_stat.first_name
  );
end;
-- 
-- 
Select *
from usmv_app.ffbad_stat;
-- 
-- 
Select *
from ffbad_data.ffbad_stat;
insert into usmv_app.ffbad_stat (
    id,
    extraction_date,
    rankings_date,
    week_number,
    `year`,
    season,
    api_id,
    license,
    last_name,
    birth_last_name,
    first_name,
    birth_date,
    nationality,
    country,
    country_iso,
    category_global,
    category_short,
    category_long,
    club_reference,
    club_acronym,
    club_id,
    club_name,
    club_department,
    is_player_transferred,
    is_data_player_public,
    is_player_active,
    feather,
    single_cpph,
    single_rank_number,
    single_french_rank_number,
    single_rank_name,
    double_cpph,
    double_rank_number,
    double_french_rank_number,
    double_rank_name,
    mixed_cpph,
    mixed_rank_number,
    mixed_french_rank_number,
    mixed_rank_name,
    cpphsum
  ) (
    select id,
      extraction_date,
      rankings_date,
      week_number,
      `year`,
      season,
      api_id,
      license,
      last_name,
      birth_last_name,
      first_name,
      birth_date,
      nationality,
      country,
      country_iso,
      category_global,
      category_short,
      category_long,
      club_reference,
      club_acronym,
      club_id,
      club_name,
      club_department,
      is_player_transferred,
      is_data_player_public,
      is_player_active,
      feather,
      single_cpph,
      single_rank_number,
      single_french_rank_number,
      single_rank_name,
      double_cpph,
      double_rank_number,
      double_french_rank_number,
      double_rank_name,
      mixed_cpph,
      mixed_rank_number,
      mixed_french_rank_number,
      mixed_rank_name,
      cpphsum
    from ffbad_data.ffbad_stat
  );