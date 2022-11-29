<?php
$yhendus=new mysqli("localhost", "imerkulova21", "123456", "imerkulova21");
$yhendus->set_charset('utf8');
/*
 * create table tantsud(
    id int primary key auto_increment,
    tantsupaar varchar(25) not null,
    punktid int,
    kommentaarid text,
    avalik int default 1,
    avaliku_paev datetime
);
 */