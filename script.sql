-- Создается база данных для сайта КиноГрад
drop database if exists kinograd;
create database kinograd;
use kinograd;

-- Создается таблица с кинотеатрами, представленными на сайте
create table theaters(
    id serial primary key,
    name varchar(50) not null unique comment 'Название кинотеатра',
    tel varchar(20) not null comment 'Номер телефона',
    addr varchar(255) not null comment 'Адрес'
) comment 'Таблица с кинотеатрами';

-- Создается таблица с фильмами
create table movies(
    id serial primary key,
    name varchar(50) not null comment 'Название фильма',
    show_since date not null comment 'Дата начала проката фильма',
    show_until date not null comment 'Дата окончания проката фильма',
    short_description text comment 'Краткое описание сюжета фильма',
    img varchar(50) comment 'Путь к файлу, в котором сохранен постер к фильму'
) comment 'Таблица со всеми фильмами';

-- Создается таблица с расписанием сеансов фильмов
create table timetable(
    id serial primary key,
    theater_id bigint unsigned not null comment 'Вторичный ключ - ссылка на кинотеатр',
    movie_id bigint unsigned not null comment 'Вторичный ключ - ссылка на фильм',
    start_time datetime not null comment 'Время начала показа фильма в соответствующем кинотеатре',
    foreign key (theater_id) references theaters (id) on delete cascade on update cascade,
    foreign key (movie_id) references movies (id) on delete cascade on update cascade
) comment 'Таблица с расписанием всех фильмов во всех кинотеатрах';

-- Создается представление с расписанием, кинотеатрами и фильмами
create view timetable_view as
    select theaters.name as theater_name, movies.name as movie_name, start_time from timetable
    left join theaters on timetable.theater_id=theaters.id
    left join movies on timetable.movie_id=movies.id;

-- Заполним таблицы в базе данных сайта
-- Имеются два кинотеатра: Кинотеатр_1 и Кинотеатр_2
insert into theaters values
    (null, 'Кинотеатр_1', '+7 (999) 999-99-98', 'г. Город, ул. Улица, д. 1'),
    (null, 'Кинотеатр_2', '+7 (999) 999-99-97', 'г. Город, ул. Улица, д. 2');

-- Заполяем таблицу с фильмами: всего три фильма
insert into movies values
    (null, 'Вторжение', '2019-12-20', '2020-02-01', 'Падение инопланетного объекта разделило жизни на до и после. Обычная девушка из московского Чертанова - Юлия Лебедева - вынуждена смириться с ролью подопытного кролика в лаборатории, ведь она единственная была в контакте с пришельцем. Ученые и военные разбирают на атомы её чувства, эмоции и переживания, пытаясь разгадать природу растущей в ней силы. Но страшнее всего, что её сверхъестественные способности волнуют не только землян. Над планетой в буквальном смысле нависла угроза вторжения. И победить в грядущем столкновении можно только одним способом: найти в себе силы остаться людьми. Когда каждый ради общего спасения должен сделать выбор, от которого зависит жизнь и судьба миллионов, - смогут ли любовь, верность и милосердие стать сильнее безжалостной силы и инопланетных технологий?', 'img/vtorzhenie.jpg'),
    (null, 'Полицейский с Рублевки', '2019-11-18', '2020-01-18', 'Близится новый год, и сотрудники отдела полиции Барвихи планируют праздновать его за городом в тёплой компании старых друзей и коллег. Но непредвиденные обстоятельства в лице преступников, ограбивших крупное ювелирное предприятие, ставят праздник под угрозу. Смогут ли рублёвские полицейские вернуть украденные драгоценные камни стоимостью миллионы долларов и спасти свой праздник до того, как часы пробьют полночь?', 'img/politseyskiy-s-rublyovki.jpg'),
    (null, 'Джуманджи: Новый уровень', '2019-11-26', '2020-01-26', 'Чтобы спасти одного из приятелей, остальным приходится вернуться в игру. К их удивлению, правила Джуманджи изменились, и все идет наперекосяк. Чтобы выжить друзьям предстоит отправиться в путешествие по самым неизведанным и таинственным уголкам игры — от засушливой пустыни до заснеженных гор.', 'img/jumanji.jpg');

-- Создаем расписание для показа фильмов в кинотеатрах
insert into timetable values
    (null, 1, 1, '2019-12-30 10:00'),
    (null, 1, 2, '2019-12-30 14:00'),
    (null, 1, 3, '2019-12-30 18:00'),
    (null, 2, 1, '2019-12-30 11:00'),
    (null, 2, 2, '2019-12-30 15:00'),
    (null, 2, 3, '2019-12-30 19:00');

-- Процедура для заполнения расписания на месяц в обоих кинотеатрах
delimiter //
create procedure timetable_fill()
BEGIN
    declare i, j, n int;
    set i=1;
    set j=7;
    set n=30;
    -- Первый фильм в первом кинотеатре расписан на месяц
    while i<=n do
        insert into timetable
            select j, theater_id, movie_id, interval i day + start_time from timetable where id=1;
        set i=i+1;
        set j=j+1;
    end while;
    -- Второй фильм в первом кинотеатре расписан на месяц
    set i=1;
    while i<=n do
        insert into timetable
            select j, theater_id, movie_id, interval i day + start_time from timetable where id=2;
        set i=i+1;
        set j=j+1;
    end while;
    -- Третий фильм в первом кинотеатре расписан на месяц
    set i=1;
    while i<=n do
        insert into timetable
            select j, theater_id, movie_id, interval i day + start_time from timetable where id=3;
        set i=i+1;
        set j=j+1;
    end while;
    -- Первый фильм во втором кинотеатре расписан на месяц
    set i=1;
    while i<=n do
        insert into timetable
            select j, theater_id, movie_id, interval i day + start_time from timetable where id=4;
        set i=i+1;
        set j=j+1;
    end while;
    -- Второй фильм во втором кинотеатре расписан на месяц
    set i=1;
    while i<=n do
        insert into timetable
            select j, theater_id, movie_id, interval i day + start_time from timetable where id=5;
        set i=i+1;
        set j=j+1;
    end while;
    -- Третий фильм во втором кинотеатре расписан на месяц
    set i=1;
    while i<=n do
        insert into timetable
            select j, theater_id, movie_id, interval i day + start_time from timetable where id=6;
        set i=i+1;
        set j=j+1;
    end while;
END//
delimiter ;

call timetable_fill();

-- Создается учетная запись для администратора сайта, который сможет делать запросы к таблицам базы данных kinograd.
-- Имя учетной записи kinograd_admin, пароль 1234
drop user if exists 'kinograd_admin'@'localhost';
create user 'kinograd_admin'@'localhost' identified by '1234';
-- Учетной записи позволено делать запросы select, вставлять данные в таблицы, изменять и удалять их из таблиц базы данных calendar
grant select, insert, update, delete
    on kinograd.* to 'kinograd_admin'@'localhost';