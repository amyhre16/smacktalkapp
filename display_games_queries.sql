/* THIS ONE IS A WINNER */
SELECT * FROM person_game
    LEFT JOIN people ON person_game.person_id = people.id
 WHERE person_game.game_id IN (SELECT games.id FROM games
    inner join person_game on games.id = person_game.game_id
   inner join people on person_game.person_id = people.id
   where person_game.person_id = '10212631339123286' and games.in_progress = 1) AND person_game.person_id != '10212631339123286';
   
   
SELECT distinct people.id, people.name, people.picture
, opponent.id as opponent_id, opponent.name as opponent_name, opponent.picture as opponent_picture
, games.id as game_id, games.in_progress, games.whose_turn
 , opponent_game.person_id as op, opponent_game.game_id as og
FROM people 
LEFT JOIN person_game ON (people.id = person_game.person_id)
LEFT JOIN games ON (person_game.game_id = games.id)
LEFT JOIN person_game opponent_game ON (games.id = opponent_game.game_id and people.id != opponent_game.person_id)
LEFT JOIN people opponent ON opponent_game.person_id = opponent.id
WHERE games.in_progress = 1;