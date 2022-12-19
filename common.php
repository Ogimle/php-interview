<?php
$source = [
  ['id' => 1, 'date' => "12.01.2020", 'name' => "test1"],
  ['id' => 2, 'date' => "02.05.2020", 'name' => "test2"],
  ['id' => 4, 'date' => "08.03.2020", 'name' => "test4"],
  ['id' => 1, 'date' => "22.01.2020", 'name' => "test1"],
  ['id' => 2, 'date' => "11.11.2020", 'name' => "test4"],
  ['id' => 3, 'date' => "06.06.2020", 'name' => "test3"],
];

/*
 * выделить уникальные записи (убрать дубли) в отдельный массив.
 * в конечном массиве не должно быть элементов с одинаковым id.
 */
$data = array_column($source, null, 'id');

/*
 * отсортировать многомерный массив по ключу (любому)
 */
usort($source, function($a, $b) {
    return $a['id'] - $b['id'];
});

usort($source, function($a, $b) {
    $d1 = DateTime::createFromFormat("d.m.Y H:i:s", $a['date'] . ' 00:00:00')
        ->getTimestamp();
    $d2 = DateTime::createFromFormat("d.m.Y H:i:s", $b['date'] . ' 00:00:00')
        ->getTimestamp();

    return $d1 - $d2;
});

usort($source, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

/*
 * вернуть из массива только элементы, удовлетворяющие внешним условиям
 * (например элементы с определенным id)
 */
$id = 1;
$data = array_filter($source, function($item) use ($id) {
    return $item['id'] == $id;
});

/*
 * изменить в массиве значения и ключи
 * (использовать name => id в качестве пары ключ => значение)
 */
$data = array_column($source, 'id', 'name');

$data = array_reduce($source, function ($acc, $item) {
    $acc[ $item['name'] ] = $item['id'];
    return $acc;
}, []);

/*
 * В базе данных имеется таблица
 * с товарами goods (id INTEGER, name TEXT),
 * с тегами tags (id INTEGER, name TEXT)
 * со связями товаров и тегов
 *   goods_tags (tag_id INTEGER, goods_id INTEGER, UNIQUE(tag_id, goods_id)).
 * Выведите id и названия всех товаров, которые имеют все возможные теги в этой базе.
 */

$sql = <<<SQL
select 
  g.id, g.name, t.name
from
  goods g
  left join goods_tags gt on (g.id = gt.goods_id)
  inner join tags t on (gt.tag_id = t.id)
order by
  g.name, t.name; 
SQL;

/*
 * Выбрать без join-ов и подзапросов все департаменты, в которых есть мужчины,
 * и все они (каждый) поставили высокую оценку (строго выше 5).
 *
   create table evaluations
   (
    respondent_id uuid primary key, -- ID респондента
    department_id uuid,             -- ID департамента
    gender        boolean,          -- true — мужчина, false — женщина
    value         integer	    -- Оценка
   );
 */

$sql = <<<SQL
select 
  department_id
where
  gender = true -- все мужчины
  and value > 5 -- поставившие больше 5
group by
  department_id; -- убираем повторяющиеся id

-- или так 
 
select 
  distinct department_id -- убираем повторяющиеся id
where
  gender = true -- все мужчины
  and value > 5; -- поставившие больше 5
SQL;
