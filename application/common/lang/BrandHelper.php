<?php

namespace app\common\lang;


/**
 * Class LangHelper
 * @package app\common\lang
 */
class BrandHelper
{
    /**
     * 单例容器
     * @var
     */
    private static $_instance;

    /**
     * 单例接口
     * @return \app\common\lang\BrandHelper
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    /**
     * 单例
     * BrandHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * 模型语言包
     * @var array
     */
    private static $lang = [
        "1" => [
            "id" => "1",
            "name" => "奥迪",
            "icon" => "/static/uploads/brand/a/1/f2b8e9c22717eb9632bb6d903726f32b.png?t=20170906",
            "letter" => "a",
            "order" => "1",
        ],
        "2" => [
            "id" => "2",
            "name" => "阿斯顿·马丁",
            "icon" => "/static/uploads/brand/a/2/f8dc3319cb2bf7219f7cb27ac6aa32b8.png?t=20170906",
            "letter" => "a",
            "order" => "2",
        ],
        "3" => [
            "id" => "3",
            "name" => "阿尔法·罗密欧",
            "icon" => "/static/uploads/brand/a/3/169bab5402c8ba99be735c9ba001bdbd.png?t=20170906",
            "letter" => "a",
            "order" => "3",
        ],
        "4" => [
            "id" => "4",
            "name" => "艾康尼克",
            "icon" => "/static/uploads/brand/a/4/c14b9adbe3b0e101918d72ad1c8cb7df.png?t=20170906",
            "letter" => "a",
            "order" => "4",
        ],
        "5" => [
            "id" => "5",
            "name" => "Alpina",
            "icon" => "/static/uploads/brand/a/5/4d14e38b7ae941a01b410637ae79887f.png?t=20170906",
            "letter" => "a",
            "order" => "5",
        ],
        "6" => [
            "id" => "6",
            "name" => "宝马",
            "icon" => "/static/uploads/brand/b/6/f5eec9823d7184c545bbd6da441e8480.png?t=20170906",
            "letter" => "b",
            "order" => "1",
        ],
        "7" => [
            "id" => "7",
            "name" => "奔驰",
            "icon" => "/static/uploads/brand/b/7/85a16dd2fdfdfcf4067c77d56acdf6c4.png?t=20170906",
            "letter" => "b",
            "order" => "2",
        ],
        "8" => [
            "id" => "8",
            "name" => "本田",
            "icon" => "/static/uploads/brand/b/8/b18f4f88456c4d74a30ce29d1dbd48fe.png?t=20170906",
            "letter" => "b",
            "order" => "3",
        ],
        "9" => [
            "id" => "9",
            "name" => "别克",
            "icon" => "/static/uploads/brand/b/9/3e444209b6f033fdea638450a39c85bf.png?t=20170906",
            "letter" => "b",
            "order" => "4",
        ],
        "10" => [
            "id" => "10",
            "name" => "标致",
            "icon" => "/static/uploads/brand/b/10/ac103d3954fa15dbbb77ebf38320554d.png?t=20170906",
            "letter" => "b",
            "order" => "5",
        ],
        "11" => [
            "id" => "11",
            "name" => "比亚迪",
            "icon" => "/static/uploads/brand/b/11/92d89f6336af501addd56abb47a43ebc.png?t=20170906",
            "letter" => "b",
            "order" => "6",
        ],
        "12" => [
            "id" => "12",
            "name" => "宝骏",
            "icon" => "/static/uploads/brand/b/12/999df9d8f2aadf1ddaa919569a93a803.png?t=20170906",
            "letter" => "b",
            "order" => "7",
        ],
        "13" => [
            "id" => "13",
            "name" => "奔腾",
            "icon" => "/static/uploads/brand/b/13/f58c0f2bf5b9c217514ca1d62dbe494e.png?t=20170906",
            "letter" => "b",
            "order" => "8",
        ],
        "14" => [
            "id" => "14",
            "name" => "宝沃",
            "icon" => "/static/uploads/brand/b/14/7de28d9cfd594027938bb64306f8457a.png?t=20170906",
            "letter" => "b",
            "order" => "9",
        ],
        "15" => [
            "id" => "15",
            "name" => "北京",
            "icon" => "/static/uploads/brand/b/15/c6ec8e4be184ee8a0c1fd2f5b2d54f04.png?t=20170906",
            "letter" => "b",
            "order" => "10",
        ],
        "16" => [
            "id" => "16",
            "name" => "北汽绅宝",
            "icon" => "/static/uploads/brand/b/16/38eea6f66e3b51d8f635a97d19186133.png?t=20170906",
            "letter" => "b",
            "order" => "11",
        ],
        "17" => [
            "id" => "17",
            "name" => "北汽幻速",
            "icon" => "/static/uploads/brand/b/17/eb7e79d6e232911d5698f199941f51c3.png?t=20170906",
            "letter" => "b",
            "order" => "12",
        ],
        "18" => [
            "id" => "18",
            "name" => "比速汽车",
            "icon" => "/static/uploads/brand/b/18/4be4944699fae8c3072c3a7792004dd8.png?t=20170906",
            "letter" => "b",
            "order" => "13",
        ],
        "19" => [
            "id" => "19",
            "name" => "北汽新能源",
            "icon" => "/static/uploads/brand/b/19/eaac619e20ac67b36c21c14a7b7f5435.png?t=20170906",
            "letter" => "b",
            "order" => "14",
        ],
        "20" => [
            "id" => "20",
            "name" => "北汽威旺",
            "icon" => "/static/uploads/brand/b/20/2879e9fc9242fcf1f38b155dd0220ea5.png?t=20170906",
            "letter" => "b",
            "order" => "15",
        ],
        "21" => [
            "id" => "21",
            "name" => "北汽制造",
            "icon" => "/static/uploads/brand/b/21/c569ddd3fd5256926d802cb76e2736a4.png?t=20170906",
            "letter" => "b",
            "order" => "16",
        ],
        "22" => [
            "id" => "22",
            "name" => "北汽道达",
            "icon" => "/static/uploads/brand/b/22/47526654c7eb813f65793bae060b31ea.png?t=20170906",
            "letter" => "b",
            "order" => "17",
        ],
        "23" => [
            "id" => "23",
            "name" => "保时捷",
            "icon" => "/static/uploads/brand/b/23/73c495aec85a2e569867ad844ce064c3.png?t=20170906",
            "letter" => "b",
            "order" => "18",
        ],
        "24" => [
            "id" => "24",
            "name" => "宾利",
            "icon" => "/static/uploads/brand/b/24/ae65858c70e86795fe7a58c8c5dfbdfa.png?t=20170906",
            "letter" => "b",
            "order" => "19",
        ],
        "25" => [
            "id" => "25",
            "name" => "布加迪",
            "icon" => "/static/uploads/brand/b/25/f5f738a386ec1a902891d94c3f4dbd5a.png?t=20170906",
            "letter" => "b",
            "order" => "20",
        ],
        "26" => [
            "id" => "26",
            "name" => "巴博斯",
            "icon" => "/static/uploads/brand/b/26/e7f33fd05d293550d8a1e26fe147bcfb.png?t=20170906",
            "letter" => "b",
            "order" => "21",
        ],
        "27" => [
            "id" => "27",
            "name" => "长城",
            "icon" => "/static/uploads/brand/c/27/7329c43ee12196d4f888b8e93f4e426a.png?t=20170906",
            "letter" => "c",
            "order" => "1",
        ],
        "28" => [
            "id" => "28",
            "name" => "长安汽车",
            "icon" => "/static/uploads/brand/c/28/539d53a349f5c21ad71a413640303344.png?t=20170906",
            "letter" => "c",
            "order" => "2",
        ],
        "29" => [
            "id" => "29",
            "name" => "长安欧尚",
            "icon" => "/static/uploads/brand/c/29/38415285fdffc37d8ff837c6c524f70e.png?t=20170906",
            "letter" => "c",
            "order" => "3",
        ],
        "30" => [
            "id" => "30",
            "name" => "长安轻型车",
            "icon" => "/static/uploads/brand/c/30/aedb6b9c42230bf775ce8287be95b980.png?t=20170906",
            "letter" => "c",
            "order" => "4",
        ],
        "31" => [
            "id" => "31",
            "name" => "昌河",
            "icon" => "/static/uploads/brand/c/31/3cfa6d4fd79073f199bfa45367547f74.png?t=20170906",
            "letter" => "c",
            "order" => "5",
        ],
        "32" => [
            "id" => "32",
            "name" => "成功汽车",
            "icon" => "/static/uploads/brand/c/32/b0b1557605485ea1dbeb31d0485a5385.png?t=20170906",
            "letter" => "c",
            "order" => "6",
        ],
        "33" => [
            "id" => "33",
            "name" => "长江EV",
            "icon" => "/static/uploads/brand/c/33/2a81b49413cb6093cee85d8d0ff4e148.png?t=20170906",
            "letter" => "c",
            "order" => "7",
        ],
        "34" => [
            "id" => "34",
            "name" => "大众",
            "icon" => "/static/uploads/brand/d/34/34a5735bd7892901783a1958f45d246c.png?t=20170906",
            "letter" => "d",
            "order" => "1",
        ],
        "35" => [
            "id" => "35",
            "name" => "DS",
            "icon" => "/static/uploads/brand/d/35/c1ece4c816ec5a97ee3b077720370492.png?t=20170906",
            "letter" => "d",
            "order" => "2",
        ],
        "36" => [
            "id" => "36",
            "name" => "东南",
            "icon" => "/static/uploads/brand/d/36/aefbef99f6da4212a63578c7ecefce6e.png?t=20170906",
            "letter" => "d",
            "order" => "3",
        ],
        "37" => [
            "id" => "37",
            "name" => "道奇",
            "icon" => "/static/uploads/brand/d/37/b4c3caef45efa6420df09c7930a5ce6b.png?t=20170906",
            "letter" => "d",
            "order" => "4",
        ],
        "38" => [
            "id" => "38",
            "name" => "东风",
            "icon" => "/static/uploads/brand/d/38/83534a03b16ebefccd740ff5780f0df3.png?t=20170906",
            "letter" => "d",
            "order" => "5",
        ],
        "39" => [
            "id" => "39",
            "name" => "东风风行",
            "icon" => "/static/uploads/brand/d/39/50b768da4dc150a178a6a03e5faebd05.png?t=20170906",
            "letter" => "d",
            "order" => "6",
        ],
        "40" => [
            "id" => "40",
            "name" => "东风风神",
            "icon" => "/static/uploads/brand/d/40/7f2c741012249814c16a9d9b65991273.png?t=20170906",
            "letter" => "d",
            "order" => "7",
        ],
        "41" => [
            "id" => "41",
            "name" => "东风启辰",
            "icon" => "/static/uploads/brand/d/41/5e38e76b440d666f2f9f5383d2b687df.png?t=20170906",
            "letter" => "d",
            "order" => "8",
        ],
        "42" => [
            "id" => "42",
            "name" => "东风风度",
            "icon" => "/static/uploads/brand/d/42/6ae6e130a47395767a6a273cd352772e.png?t=20170906",
            "letter" => "d",
            "order" => "9",
        ],
        "43" => [
            "id" => "43",
            "name" => "东风风光",
            "icon" => "/static/uploads/brand/d/43/fc50afcff1bdf7ec2361033ec3b9e486.png?t=20170906",
            "letter" => "d",
            "order" => "10",
        ],
        "44" => [
            "id" => "44",
            "name" => "东风小康",
            "icon" => "/static/uploads/brand/d/44/a7008cdd5471781944a4dbfa2867ef70.png?t=20170906",
            "letter" => "d",
            "order" => "11",
        ],
        "45" => [
            "id" => "45",
            "name" => "Detroit Electric",
            "icon" => "/static/uploads/brand/d/45/4c5267a5c7f16354588103dc70867cc5.png?t=20170906",
            "letter" => "d",
            "order" => "12",
        ],
        "46" => [
            "id" => "46",
            "name" => "大发",
            "icon" => "/static/uploads/brand/d/46/abe607acac2394669943f11590125e6f.png?t=20170906",
            "letter" => "d",
            "order" => "13",
        ],
        "47" => [
            "id" => "47",
            "name" => "电咖汽车",
            "icon" => "/static/uploads/brand/d/47/f4e3a274836836bfd55263054913c72c.png?t=20170906",
            "letter" => "d",
            "order" => "14",
        ],
        "48" => [
            "id" => "48",
            "name" => "丰田",
            "icon" => "/static/uploads/brand/f/48/35c506c310d180f0d0f157d88ecc9cb4.png?t=20170906",
            "letter" => "f",
            "order" => "1",
        ],
        "49" => [
            "id" => "49",
            "name" => "福特",
            "icon" => "/static/uploads/brand/f/49/034275541486aad92822cfb080c19484.png?t=20170906",
            "letter" => "f",
            "order" => "2",
        ],
        "50" => [
            "id" => "50",
            "name" => "菲亚特",
            "icon" => "/static/uploads/brand/f/50/286439ef752298f53b1d12f30885db3b.png?t=20170906",
            "letter" => "f",
            "order" => "3",
        ],
        "51" => [
            "id" => "51",
            "name" => "福田",
            "icon" => "/static/uploads/brand/f/51/12f77c6e6479a8990bf47fb7dfd3b071.png?t=20170906",
            "letter" => "f",
            "order" => "4",
        ],
        "52" => [
            "id" => "52",
            "name" => "法拉利",
            "icon" => "/static/uploads/brand/f/52/4fb0359e3ffe5fbb4bd98c07427a8ad6.png?t=20170906",
            "letter" => "f",
            "order" => "5",
        ],
        "53" => [
            "id" => "53",
            "name" => "福迪",
            "icon" => "/static/uploads/brand/f/53/ebca46cb45d8ca88fe86580019be5ac0.png?t=20170906",
            "letter" => "f",
            "order" => "6",
        ],
        "54" => [
            "id" => "54",
            "name" => "风诺",
            "icon" => "/static/uploads/brand/f/54/7fcd6a4a969d4a4c66fc1b7caf254591.png?t=20170906",
            "letter" => "f",
            "order" => "7",
        ],
        "55" => [
            "id" => "55",
            "name" => "福汽启腾",
            "icon" => "/static/uploads/brand/f/55/693decd3f1a988d1b1d75169d3cb7c2d.png?t=20170906",
            "letter" => "f",
            "order" => "8",
        ],
        "56" => [
            "id" => "56",
            "name" => "观致",
            "icon" => "/static/uploads/brand/g/56/870d9049ed6ce9a5e0853e7c7f925b88.png?t=20170906",
            "letter" => "g",
            "order" => "1",
        ],
        "57" => [
            "id" => "57",
            "name" => "广汽传祺",
            "icon" => "/static/uploads/brand/g/57/408c3b72602094badf5bd06a8fb73b74.png?t=20170906",
            "letter" => "g",
            "order" => "2",
        ],
        "58" => [
            "id" => "58",
            "name" => "广汽吉奥",
            "icon" => "/static/uploads/brand/g/58/87b0c79100db3cb9b22d204c2a5b5476.png?t=20170906",
            "letter" => "g",
            "order" => "3",
        ],
        "59" => [
            "id" => "59",
            "name" => "Genesis",
            "icon" => "/static/uploads/brand/g/59/e60e1dcb4bbe9bf50afd3900fd9c6c93.png?t=20170906",
            "letter" => "g",
            "order" => "4",
        ],
        "60" => [
            "id" => "60",
            "name" => "GMC",
            "icon" => "/static/uploads/brand/g/60/40d6e44a960e83e6ac60f6837b450d46.png?t=20170906",
            "letter" => "g",
            "order" => "5",
        ],
        "61" => [
            "id" => "61",
            "name" => "光冈",
            "icon" => "/static/uploads/brand/g/61/51fd4c0263a22b4da0aa06f7c1418796.png?t=20170906",
            "letter" => "g",
            "order" => "6",
        ],
        "62" => [
            "id" => "62",
            "name" => "GLM",
            "icon" => "/static/uploads/brand/g/62/f3b7009eec79724d8e443ffa90f7c7bd.png?t=20170906",
            "letter" => "g",
            "order" => "7",
        ],
        "63" => [
            "id" => "63",
            "name" => "哈弗",
            "icon" => "/static/uploads/brand/h/63/7efbaafce7537aa7ffe2a2c63f0dd72d.png?t=20170906",
            "letter" => "h",
            "order" => "1",
        ],
        "64" => [
            "id" => "64",
            "name" => "海马",
            "icon" => "/static/uploads/brand/h/64/e41598537f9a71b6aab8c92de51fc53e.png?t=20170906",
            "letter" => "h",
            "order" => "2",
        ],
        "65" => [
            "id" => "65",
            "name" => "华泰",
            "icon" => "/static/uploads/brand/h/65/64f111e21a6b97bceb65aeeeb998a765.png?t=20170906",
            "letter" => "h",
            "order" => "3",
        ],
        "66" => [
            "id" => "66",
            "name" => "华泰新能源",
            "icon" => "/static/uploads/brand/h/66/c16b9a31bd61f2caa571fcf99e46b311.png?t=20170906",
            "letter" => "h",
            "order" => "4",
        ],
        "67" => [
            "id" => "67",
            "name" => "华颂",
            "icon" => "/static/uploads/brand/h/67/3fc6303d8dcf17ff38261049ab7e457c.png?t=20170906",
            "letter" => "h",
            "order" => "5",
        ],
        "68" => [
            "id" => "68",
            "name" => "红旗",
            "icon" => "/static/uploads/brand/h/68/3172958956faa45854cae01ccb7cc805.png?t=20170906",
            "letter" => "h",
            "order" => "6",
        ],
        "69" => [
            "id" => "69",
            "name" => "汉腾",
            "icon" => "/static/uploads/brand/h/69/04fcf1bc199f735b6b77e5fe9fea0db6.png?t=20170906",
            "letter" => "h",
            "order" => "7",
        ],
        "70" => [
            "id" => "70",
            "name" => "哈飞",
            "icon" => "/static/uploads/brand/h/70/1572214eb901c2ec1403f4608f550b9c.png?t=20170906",
            "letter" => "h",
            "order" => "8",
        ],
        "71" => [
            "id" => "71",
            "name" => "黄海",
            "icon" => "/static/uploads/brand/h/71/9e2d8033ea6895580a16448b1f0b6bed.png?t=20170906",
            "letter" => "h",
            "order" => "9",
        ],
        "72" => [
            "id" => "72",
            "name" => "海格",
            "icon" => "/static/uploads/brand/h/72/d5445763f18e2e9e2c628f0bfdf735c9.png?t=20170906",
            "letter" => "h",
            "order" => "10",
        ],
        "73" => [
            "id" => "73",
            "name" => "汇众",
            "icon" => "/static/uploads/brand/h/73/956316b16de813cf7fc17a236223ddd5.png?t=20170906",
            "letter" => "h",
            "order" => "11",
        ],
        "74" => [
            "id" => "74",
            "name" => "悍马",
            "icon" => "/static/uploads/brand/h/74/4c4b70efd2fdbe4c7fe3f51f243bd30e.png?t=20170906",
            "letter" => "h",
            "order" => "12",
        ],
        "75" => [
            "id" => "75",
            "name" => "华普",
            "icon" => "/static/uploads/brand/h/75/1ff06658aa9daf168a4c33d0809e6430.png?t=20170906",
            "letter" => "h",
            "order" => "13",
        ],
        "76" => [
            "id" => "76",
            "name" => "恒天",
            "icon" => "/static/uploads/brand/h/76/1ad493438fd5a1ff49d4a87545679e41.png?t=20170906",
            "letter" => "h",
            "order" => "14",
        ],
        "77" => [
            "id" => "77",
            "name" => "霍顿",
            "icon" => "/static/uploads/brand/h/77/5e775ac6ffcfd1dd189b3b414eb2f8d4.png?t=20170906",
            "letter" => "h",
            "order" => "15",
        ],
        "78" => [
            "id" => "78",
            "name" => "合众汽车",
            "icon" => "/static/uploads/brand/h/78/55fe8abc3b32edf3304d149a33cce7c6.png?t=20170906",
            "letter" => "h",
            "order" => "16",
        ],
        "79" => [
            "id" => "79",
            "name" => "Italdesign",
            "icon" => "/static/uploads/brand/i/79/d8cb2c9ba689e1568ecd96ff41862439.png?t=20170906",
            "letter" => "i",
            "order" => "1",
        ],
        "80" => [
            "id" => "80",
            "name" => "吉利",
            "icon" => "/static/uploads/brand/j/80/c480ec39f43980ee072aa7c320dee06b.png?t=20170906",
            "letter" => "j",
            "order" => "1",
        ],
        "81" => [
            "id" => "81",
            "name" => "江淮",
            "icon" => "/static/uploads/brand/j/81/193e6bf5b1845fb0403ec2ed83482c54.png?t=20170906",
            "letter" => "j",
            "order" => "2",
        ],
        "82" => [
            "id" => "82",
            "name" => "Jeep",
            "icon" => "/static/uploads/brand/j/82/95da7154f45fd1b2dbb561dcb4a00db4.png?t=20170906",
            "letter" => "j",
            "order" => "3",
        ],
        "83" => [
            "id" => "83",
            "name" => "捷豹",
            "icon" => "/static/uploads/brand/j/83/c8bc05ca378789ed8de3029588afe8dc.png?t=20170906",
            "letter" => "j",
            "order" => "4",
        ],
        "84" => [
            "id" => "84",
            "name" => "金杯",
            "icon" => "/static/uploads/brand/j/84/e45c532904adcbc5f861c1c226ca4106.png?t=20170906",
            "letter" => "j",
            "order" => "5",
        ],
        "85" => [
            "id" => "85",
            "name" => "江铃",
            "icon" => "/static/uploads/brand/j/85/a498cc32f1241001857bb490eacb3225.png?t=20170906",
            "letter" => "j",
            "order" => "6",
        ],
        "86" => [
            "id" => "86",
            "name" => "江铃集团轻汽",
            "icon" => "/static/uploads/brand/j/86/b5df905fa52c1fb8abc8d4bf18f9f0c9.png?t=20170906",
            "letter" => "j",
            "order" => "7",
        ],
        "87" => [
            "id" => "87",
            "name" => "江铃集团新能源",
            "icon" => "/static/uploads/brand/j/87/55f2038ccf157af3d353e82b00eeb17d.png?t=20170906",
            "letter" => "j",
            "order" => "8",
        ],
        "88" => [
            "id" => "88",
            "name" => "金龙",
            "icon" => "/static/uploads/brand/j/88/0cac2c813409835539d81630396fc118.png?t=20170906",
            "letter" => "j",
            "order" => "9",
        ],
        "89" => [
            "id" => "89",
            "name" => "金旅",
            "icon" => "/static/uploads/brand/j/89/3701cadc763e29505cefeca4282a73e3.png?t=20170906",
            "letter" => "j",
            "order" => "10",
        ],
        "90" => [
            "id" => "90",
            "name" => "九龙",
            "icon" => "/static/uploads/brand/j/90/ef403f3993429c2733f2fcc1f63e32ef.png?t=20170906",
            "letter" => "j",
            "order" => "11",
        ],
        "91" => [
            "id" => "91",
            "name" => "君马汽车",
            "icon" => "/static/uploads/brand/j/91/71df408f887739880ff5c094f26b832c.png?t=20170906",
            "letter" => "j",
            "order" => "12",
        ],
        "92" => [
            "id" => "92",
            "name" => "凯迪拉克",
            "icon" => "/static/uploads/brand/k/92/23e12d967a32e207d87d09d26e008912.png?t=20170906",
            "letter" => "k",
            "order" => "1",
        ],
        "93" => [
            "id" => "93",
            "name" => "克莱斯勒",
            "icon" => "/static/uploads/brand/k/93/c62898f20622f605cd4c6a59a0ef5f88.png?t=20170906",
            "letter" => "k",
            "order" => "2",
        ],
        "94" => [
            "id" => "94",
            "name" => "凯翼",
            "icon" => "/static/uploads/brand/k/94/d7e3dc54b12969b1ea18a9fdaa62c4e7.png?t=20170906",
            "letter" => "k",
            "order" => "3",
        ],
        "95" => [
            "id" => "95",
            "name" => "开瑞",
            "icon" => "/static/uploads/brand/k/95/2dba5982c254b41ae1d9b8cc75c3269b.png?t=20170906",
            "letter" => "k",
            "order" => "4",
        ],
        "96" => [
            "id" => "96",
            "name" => "康迪全球鹰电动汽车",
            "icon" => "/static/uploads/brand/k/96/b147220d2c50ec6ee49db28d6dfdea1f.png?t=20170906",
            "letter" => "k",
            "order" => "5",
        ],
        "97" => [
            "id" => "97",
            "name" => "卡威",
            "icon" => "/static/uploads/brand/k/97/d97141a81ccca9a25cef5104a7c5e50f.png?t=20170906",
            "letter" => "k",
            "order" => "6",
        ],
        "98" => [
            "id" => "98",
            "name" => "卡升",
            "icon" => "/static/uploads/brand/k/98/0b462d756fb51d5371b261c97c0c7091.png?t=20170906",
            "letter" => "k",
            "order" => "7",
        ],
        "99" => [
            "id" => "99",
            "name" => "卡尔森",
            "icon" => "/static/uploads/brand/k/99/756bc9b94383eab4dc2e901518018ee7.png?t=20170906",
            "letter" => "k",
            "order" => "8",
        ],
        "100" => [
            "id" => "100",
            "name" => "科尼赛克",
            "icon" => "/static/uploads/brand/k/100/cf42f59bce4a26738bde76fbcd2b78c7.png?t=20170906",
            "letter" => "k",
            "order" => "9",
        ],
        "101" => [
            "id" => "101",
            "name" => "KTM",
            "icon" => "/static/uploads/brand/k/101/6ad3d849765ec1cd036cba209727a840.png?t=20170906",
            "letter" => "k",
            "order" => "10",
        ],
        "102" => [
            "id" => "102",
            "name" => "雷克萨斯",
            "icon" => "/static/uploads/brand/l/102/579df5e76b134a7b7408a3e4d8996651.png?t=20170906",
            "letter" => "l",
            "order" => "1",
        ],
        "103" => [
            "id" => "103",
            "name" => "铃木",
            "icon" => "/static/uploads/brand/l/103/10a7e44ea28ecad4f58f3750ab6be4ad.png?t=20170906",
            "letter" => "l",
            "order" => "2",
        ],
        "104" => [
            "id" => "104",
            "name" => "雷诺",
            "icon" => "/static/uploads/brand/l/104/6e34e3dc065decca0ad63565099b49dd.png?t=20170906",
            "letter" => "l",
            "order" => "3",
        ],
        "105" => [
            "id" => "105",
            "name" => "路虎",
            "icon" => "/static/uploads/brand/l/105/7716ad5b9c8441cc47f82d021f07c519.png?t=20170906",
            "letter" => "l",
            "order" => "4",
        ],
        "106" => [
            "id" => "106",
            "name" => "林肯",
            "icon" => "/static/uploads/brand/l/106/3419d234c4980abe953140c4086f8ad9.png?t=20170906",
            "letter" => "l",
            "order" => "5",
        ],
        "107" => [
            "id" => "107",
            "name" => "陆风",
            "icon" => "/static/uploads/brand/l/107/271cdda766bf938511989be1ecf31a34.png?t=20170906",
            "letter" => "l",
            "order" => "6",
        ],
        "108" => [
            "id" => "108",
            "name" => "力帆",
            "icon" => "/static/uploads/brand/l/108/2a57ac05b159a34a4c1789ffb438905d.png?t=20170906",
            "letter" => "l",
            "order" => "7",
        ],
        "109" => [
            "id" => "109",
            "name" => "猎豹汽车",
            "icon" => "/static/uploads/brand/l/109/34c2cd3c83ce4833e5295a90276be565.png?t=20170906",
            "letter" => "l",
            "order" => "8",
        ],
        "110" => [
            "id" => "110",
            "name" => "理念",
            "icon" => "/static/uploads/brand/l/110/71970071dfca30cfacf76504faa9615d.png?t=20170906",
            "letter" => "l",
            "order" => "9",
        ],
        "111" => [
            "id" => "111",
            "name" => "领克",
            "icon" => "/static/uploads/brand/l/111/643942011ddf403d6c18c60fe616c8d1.png?t=20170906",
            "letter" => "l",
            "order" => "10",
        ],
        "112" => [
            "id" => "112",
            "name" => "陆地方舟",
            "icon" => "/static/uploads/brand/l/112/a2e338c255f613bb125c76fb63914767.png?t=20170906",
            "letter" => "l",
            "order" => "11",
        ],
        "113" => [
            "id" => "113",
            "name" => "雷丁",
            "icon" => "/static/uploads/brand/l/113/34d8bd5d4f4a3b4ff4c2ca3616352365.png?t=20170906",
            "letter" => "l",
            "order" => "12",
        ],
        "114" => [
            "id" => "114",
            "name" => "劳斯莱斯",
            "icon" => "/static/uploads/brand/l/114/6a3d7160e733ae1b3319f5d9744c21eb.png?t=20170906",
            "letter" => "l",
            "order" => "13",
        ],
        "115" => [
            "id" => "115",
            "name" => "兰博基尼",
            "icon" => "/static/uploads/brand/l/115/b889166ae8fa68bbd2d7c790f4db18f7.png?t=20170906",
            "letter" => "l",
            "order" => "14",
        ],
        "116" => [
            "id" => "116",
            "name" => "路特斯",
            "icon" => "/static/uploads/brand/l/116/572fbe8318b23bdc858e48ba7c1267d5.png?t=20170906",
            "letter" => "l",
            "order" => "15",
        ],
        "117" => [
            "id" => "117",
            "name" => "LOCAL MOTORS",
            "icon" => "/static/uploads/brand/l/117/fe807cbce6a29c3181f75745af92ccf5.png?t=20170906",
            "letter" => "l",
            "order" => "16",
        ],
        "118" => [
            "id" => "118",
            "name" => "莲花汽车",
            "icon" => "/static/uploads/brand/l/118/fb578311cc10d1be475e573dd4c44905.png?t=20170906",
            "letter" => "l",
            "order" => "17",
        ],
        "119" => [
            "id" => "119",
            "name" => "拉达",
            "icon" => "/static/uploads/brand/l/119/3f70d7adabc47d5b3f51c38533e06470.png?t=20170906",
            "letter" => "l",
            "order" => "18",
        ],
        "120" => [
            "id" => "120",
            "name" => "Lucid Motors",
            "icon" => "/static/uploads/brand/l/120/cb39607208c7beba051944b6558252cf.png?t=20170906",
            "letter" => "l",
            "order" => "19",
        ],
        "121" => [
            "id" => "121",
            "name" => "马自达",
            "icon" => "/static/uploads/brand/m/121/33b46249e073120fd2d4586c8d10314f.png?t=20170906",
            "letter" => "m",
            "order" => "1",
        ],
        "122" => [
            "id" => "122",
            "name" => "MG",
            "icon" => "/static/uploads/brand/m/122/d2e7a175919d4843e7aa17ecb4d43786.png?t=20170906",
            "letter" => "m",
            "order" => "2",
        ],
        "123" => [
            "id" => "123",
            "name" => "MINI",
            "icon" => "/static/uploads/brand/m/123/9b0ef1feef7f387e985f4e47ca8b9af0.png?t=20170906",
            "letter" => "m",
            "order" => "3",
        ],
        "124" => [
            "id" => "124",
            "name" => "玛莎拉蒂",
            "icon" => "/static/uploads/brand/m/124/16ef62ffe05776f2a89deb1c342fc741.png?t=20170906",
            "letter" => "m",
            "order" => "4",
        ],
        "125" => [
            "id" => "125",
            "name" => "迈凯伦",
            "icon" => "/static/uploads/brand/m/125/902e90bfc34066486337d3e99b99e05a.png?t=20170906",
            "letter" => "m",
            "order" => "5",
        ],
        "126" => [
            "id" => "126",
            "name" => "明君汽车",
            "icon" => "/static/uploads/brand/m/126/5cf14437d7fbc9758e4f6a0c8d74ad51.png?t=20170906",
            "letter" => "m",
            "order" => "6",
        ],
        "127" => [
            "id" => "127",
            "name" => "摩根",
            "icon" => "/static/uploads/brand/m/127/49f94629e11c2057fddaf817388a24f2.png?t=20170906",
            "letter" => "m",
            "order" => "7",
        ],
        "128" => [
            "id" => "128",
            "name" => "迈巴赫",
            "icon" => "/static/uploads/brand/m/128/c21f8a6ba13b72cbe8788aa0fd64dc44.png?t=20170906",
            "letter" => "m",
            "order" => "8",
        ],
        "129" => [
            "id" => "129",
            "name" => "纳智捷",
            "icon" => "/static/uploads/brand/n/129/e6ce1aee71927a47ad4aa8bea2f92148.png?t=20170906",
            "letter" => "n",
            "order" => "1",
        ],
        "130" => [
            "id" => "130",
            "name" => "NEVS",
            "icon" => "/static/uploads/brand/n/130/3af13d0d8896da8758a44594c1e039dc.png?t=20170906",
            "letter" => "n",
            "order" => "2",
        ],
        "131" => [
            "id" => "131",
            "name" => "讴歌",
            "icon" => "/static/uploads/brand/o/131/1338f7c6547186e0931152c6ec618283.png?t=20170906",
            "letter" => "o",
            "order" => "1",
        ],
        "132" => [
            "id" => "132",
            "name" => "欧朗",
            "icon" => "/static/uploads/brand/o/132/3d3bc292211ddc6a2ec559dfefa66a12.png?t=20170906",
            "letter" => "o",
            "order" => "2",
        ],
        "133" => [
            "id" => "133",
            "name" => "欧宝",
            "icon" => "/static/uploads/brand/o/133/3e6564b881313c0b84df2032d15896fc.png?t=20170906",
            "letter" => "o",
            "order" => "3",
        ],
        "134" => [
            "id" => "134",
            "name" => "起亚",
            "icon" => "/static/uploads/brand/q/134/678ef8b38bd9d2520425daff88e2388e.png?t=20170906",
            "letter" => "q",
            "order" => "1",
        ],
        "135" => [
            "id" => "135",
            "name" => "奇瑞",
            "icon" => "/static/uploads/brand/q/135/f7527bdb6c2b677a8ca0162d62450216.png?t=20170906",
            "letter" => "q",
            "order" => "2",
        ],
        "136" => [
            "id" => "136",
            "name" => "前途",
            "icon" => "/static/uploads/brand/q/136/1bc5cb42080f6394622c8c581e52b5bb.png?t=20170906",
            "letter" => "q",
            "order" => "3",
        ],
        "137" => [
            "id" => "137",
            "name" => "奇点汽车",
            "icon" => "/static/uploads/brand/q/137/96ebef3bcaa471d924a01f55e31d0ef4.png?t=20170906",
            "letter" => "q",
            "order" => "4",
        ],
        "138" => [
            "id" => "138",
            "name" => "日产",
            "icon" => "/static/uploads/brand/r/138/686e1b59f996d27dd86456c162d268d8.png?t=20170906",
            "letter" => "r",
            "order" => "1",
        ],
        "139" => [
            "id" => "139",
            "name" => "荣威",
            "icon" => "/static/uploads/brand/r/139/fe4187fe6c77fefae14dc7bac63c6f66.png?t=20170906",
            "letter" => "r",
            "order" => "2",
        ],
        "140" => [
            "id" => "140",
            "name" => "瑞麒",
            "icon" => "/static/uploads/brand/r/140/c3f3e8fcb1d8fa996caf648445c0d315.png?t=20170906",
            "letter" => "r",
            "order" => "3",
        ],
        "141" => [
            "id" => "141",
            "name" => "Rezvani",
            "icon" => "/static/uploads/brand/r/141/a3c9fe4ba38c1dcfe62fc1a28859f371.png?t=20170906",
            "letter" => "r",
            "order" => "4",
        ],
        "142" => [
            "id" => "142",
            "name" => "Rimac",
            "icon" => "/static/uploads/brand/r/142/2dfb1acdbcfbfe33c4a662f36a533425.png?t=20170906",
            "letter" => "r",
            "order" => "5",
        ],
        "143" => [
            "id" => "143",
            "name" => "Rinspeed",
            "icon" => "/static/uploads/brand/r/143/8dacfbe97b2e0387f637fa6fab89679b.png?t=20170906",
            "letter" => "r",
            "order" => "6",
        ],
        "144" => [
            "id" => "144",
            "name" => "斯柯达",
            "icon" => "/static/uploads/brand/s/144/2e81272a3e3989b54f7a50aeda2d40dd.png?t=20170906",
            "letter" => "s",
            "order" => "1",
        ],
        "145" => [
            "id" => "145",
            "name" => "三菱",
            "icon" => "/static/uploads/brand/s/145/25f15cced624f7b2697616836a4a857f.png?t=20170906",
            "letter" => "s",
            "order" => "2",
        ],
        "146" => [
            "id" => "146",
            "name" => "斯巴鲁",
            "icon" => "/static/uploads/brand/s/146/f60d7a470bea100d67ee396b0d57f31c.png?t=20170906",
            "letter" => "s",
            "order" => "3",
        ],
        "147" => [
            "id" => "147",
            "name" => "smart",
            "icon" => "/static/uploads/brand/s/147/a80d14f45562f81be813a6f3261aafd2.png?t=20170906",
            "letter" => "s",
            "order" => "4",
        ],
        "148" => [
            "id" => "148",
            "name" => "双龙",
            "icon" => "/static/uploads/brand/s/148/f1bdeb5ffa3c01ff98ee9e4b80658621.png?t=20170906",
            "letter" => "s",
            "order" => "5",
        ],
        "149" => [
            "id" => "149",
            "name" => "SWM斯威汽车",
            "icon" => "/static/uploads/brand/s/149/859affe83a277c49bc3b22ab75040d45.png?t=20170906",
            "letter" => "s",
            "order" => "6",
        ],
        "150" => [
            "id" => "150",
            "name" => "上汽大通",
            "icon" => "/static/uploads/brand/s/150/de525c0393f8bc308d015f67f05101c8.png?t=20170906",
            "letter" => "s",
            "order" => "7",
        ],
        "151" => [
            "id" => "151",
            "name" => "思铭",
            "icon" => "/static/uploads/brand/s/151/411b46d0a58eb36ea3dbefdba6104c2f.png?t=20170906",
            "letter" => "s",
            "order" => "8",
        ],
        "152" => [
            "id" => "152",
            "name" => "双环",
            "icon" => "/static/uploads/brand/s/152/3702e8452d52e02088bfd8ac9d91a3b2.png?t=20170906",
            "letter" => "s",
            "order" => "9",
        ],
        "153" => [
            "id" => "153",
            "name" => "陕汽通家",
            "icon" => "/static/uploads/brand/s/153/0aca2d53230448b5a9f76bbbe209bf7a.png?t=20170906",
            "letter" => "s",
            "order" => "10",
        ],
        "154" => [
            "id" => "154",
            "name" => "TESLA",
            "icon" => "/static/uploads/brand/t/154/389aa2e52955df905e915fea3ece4394.png?t=20170906",
            "letter" => "t",
            "order" => "1",
        ],
        "155" => [
            "id" => "155",
            "name" => "腾势汽车",
            "icon" => "/static/uploads/brand/t/155/c8653b6c241ac531c28974efff0177da.png?t=20170906",
            "letter" => "t",
            "order" => "2",
        ],
        "156" => [
            "id" => "156",
            "name" => "泰克鲁斯·腾风",
            "icon" => "/static/uploads/brand/t/156/9206887e4f085565c99e8ccd33877d02.png?t=20170906",
            "letter" => "t",
            "order" => "3",
        ],
        "157" => [
            "id" => "157",
            "name" => "VLF Automotive",
            "icon" => "/static/uploads/brand/v/157/11a773dd485cb5cfb8d019327e0dcc37.png?t=20170906",
            "letter" => "v",
            "order" => "1",
        ],
        "158" => [
            "id" => "158",
            "name" => "沃尔沃",
            "icon" => "/static/uploads/brand/w/158/5e1c4d00b1f67f87b828c1cd2e79a008.png?t=20170906",
            "letter" => "w",
            "order" => "1",
        ],
        "159" => [
            "id" => "159",
            "name" => "五菱",
            "icon" => "/static/uploads/brand/w/159/07ba27596731821be9874b9abc3b4769.png?t=20170906",
            "letter" => "w",
            "order" => "2",
        ],
        "160" => [
            "id" => "160",
            "name" => "五十铃",
            "icon" => "/static/uploads/brand/w/160/74bf06dc2d910b0f8becb353a93163b9.png?t=20170906",
            "letter" => "w",
            "order" => "3",
        ],
        "161" => [
            "id" => "161",
            "name" => "潍柴",
            "icon" => "/static/uploads/brand/w/161/dcb2f1c857090dce12575b7e01603d1f.png?t=20170906",
            "letter" => "w",
            "order" => "4",
        ],
        "162" => [
            "id" => "162",
            "name" => "WEY",
            "icon" => "/static/uploads/brand/w/162/e2a6c82ebbdec4a6dec439730be40dcf.png?t=20170906",
            "letter" => "w",
            "order" => "5",
        ],
        "163" => [
            "id" => "163",
            "name" => "蔚来",
            "icon" => "/static/uploads/brand/w/163/6831134f0205f3d91987cdb4bb0a4375.png?t=20170906",
            "letter" => "w",
            "order" => "6",
        ],
        "164" => [
            "id" => "164",
            "name" => "威马汽车",
            "icon" => "/static/uploads/brand/w/164/f67357cc452c8c67d693004b775fc82a.png?t=20170906",
            "letter" => "w",
            "order" => "7",
        ],
        "165" => [
            "id" => "165",
            "name" => "威兹曼",
            "icon" => "/static/uploads/brand/w/165/0e072e17eb71e6d0e9ad0338ebfbbdf6.png?t=20170906",
            "letter" => "w",
            "order" => "8",
        ],
        "166" => [
            "id" => "166",
            "name" => "威麟",
            "icon" => "/static/uploads/brand/w/166/11006074219ed784f4ea4ef0913607bd.png?t=20170906",
            "letter" => "w",
            "order" => "9",
        ],
        "167" => [
            "id" => "167",
            "name" => "瓦滋汽车",
            "icon" => "/static/uploads/brand/w/167/9be35b1dc8c8f9d6b22aa1789abcf4a8.png?t=20170906",
            "letter" => "w",
            "order" => "10",
        ],
        "168" => [
            "id" => "168",
            "name" => "雪佛兰",
            "icon" => "/static/uploads/brand/x/168/823ba2e13f5b26d2ebfe36b1a99095cd.png?t=20170906",
            "letter" => "x",
            "order" => "1",
        ],
        "169" => [
            "id" => "169",
            "name" => "现代",
            "icon" => "/static/uploads/brand/x/169/bd4b41a5579b61c14223c661bd5a9057.png?t=20170906",
            "letter" => "x",
            "order" => "2",
        ],
        "170" => [
            "id" => "170",
            "name" => "雪铁龙",
            "icon" => "/static/uploads/brand/x/170/0d83ed5e08b46a808def821ff3869300.png?t=20170906",
            "letter" => "x",
            "order" => "3",
        ],
        "171" => [
            "id" => "171",
            "name" => "西雅特",
            "icon" => "/static/uploads/brand/x/171/2fefa779050f994086d604a4e7d289f5.png?t=20170906",
            "letter" => "x",
            "order" => "4",
        ],
        "172" => [
            "id" => "172",
            "name" => "小鹏汽车",
            "icon" => "/static/uploads/brand/x/172/d905872514a081c58ca7eb55082283ef.png?t=20170906",
            "letter" => "x",
            "order" => "5",
        ],
        "173" => [
            "id" => "173",
            "name" => "英菲尼迪",
            "icon" => "/static/uploads/brand/y/173/70d15258d17b172c2912a04e0ae3eae0.png?t=20170906",
            "letter" => "y",
            "order" => "1",
        ],
        "174" => [
            "id" => "174",
            "name" => "一汽",
            "icon" => "/static/uploads/brand/y/174/e3ece8d059582428e2e5f650111551d4.png?t=20170906",
            "letter" => "y",
            "order" => "2",
        ],
        "175" => [
            "id" => "175",
            "name" => "野马汽车",
            "icon" => "/static/uploads/brand/y/175/59dd29bb89529e3d4e0bc2a1abbddd0b.png?t=20170906",
            "letter" => "y",
            "order" => "3",
        ],
        "176" => [
            "id" => "176",
            "name" => "永源",
            "icon" => "/static/uploads/brand/y/176/3f772f31813fdfa8f2e24b5df22ed0f2.png?t=20170906",
            "letter" => "y",
            "order" => "4",
        ],
        "177" => [
            "id" => "177",
            "name" => "依维柯",
            "icon" => "/static/uploads/brand/y/177/79e512eb8ce481eaf4b4bc5ccbf32087.png?t=20170906",
            "letter" => "y",
            "order" => "5",
        ],
        "178" => [
            "id" => "178",
            "name" => "云度",
            "icon" => "/static/uploads/brand/y/178/6ffd6c43994e65f4540062df835fe81f.png?t=20170906",
            "letter" => "y",
            "order" => "6",
        ],
        "179" => [
            "id" => "179",
            "name" => "众泰",
            "icon" => "/static/uploads/brand/z/179/2d8e9ea6bd519759a54cf4be9b158d10.png?t=20170906",
            "letter" => "z",
            "order" => "1",
        ],
        "180" => [
            "id" => "180",
            "name" => "中华",
            "icon" => "/static/uploads/brand/z/180/ef0142e4fd17806e0a384262e4572a16.png?t=20170906",
            "letter" => "z",
            "order" => "2",
        ],
        "181" => [
            "id" => "181",
            "name" => "中兴汽车",
            "icon" => "/static/uploads/brand/z/181/64c69d60e8b9c2416cbec483ef3e0faa.png?t=20170906",
            "letter" => "z",
            "order" => "3",
        ],
        "182" => [
            "id" => "182",
            "name" => "知豆",
            "icon" => "/static/uploads/brand/z/182/28f156ff52d220a74cb9db42e087c74e.png?t=20170906",
            "letter" => "z",
            "order" => "4",
        ],
        "183" => [
            "id" => "183",
            "name" => "之诺",
            "icon" => "/static/uploads/brand/z/183/7636211f22fd8d9eb6c18fb2b6666858.png?t=20170906",
            "letter" => "z",
            "order" => "5",
        ],
        "184" => [
            "id" => "184",
            "name" => "Zenvo",
            "icon" => "/static/uploads/brand/z/184/a8bc48910a626586dc25c42023068711.png?t=20170906",
            "letter" => "z",
            "order" => "6",
        ],
        "185" => [
            "id" => "185",
            "name" => "正道汽车",
            "icon" => "/static/uploads/brand/z/185/ed3a5867225273324171740c8b48f4f7.png?t=20170906",
            "letter" => "z",
            "order" => "7",
        ],
    ];

    /**
     * @param null $table
     * @return array
     */
    public function get($table = null)
    {
        $ret = [];
        if (is_string($table) && $table != '') {
            $ret = isset(self::$lang[$table]) ? self::$lang[$table] : [];
            if (!is_array($ret)) {
                $ret = [];
            }
        }
        return $ret;
    }

    /**
     * @param null $table
     * @param null $field
     * @return array
     */
    public function getField($table = null, $field = null)
    {
        $ret = [];
        if (is_string($table) && $table != '' && is_string($field) && $field != '') {
            $ret = isset(self::$lang[$table][$field]) ? self::$lang[$table][$field] : [];
            if (!is_array($ret)) {
                $ret = [];
            }
        }
        return $ret;
    }

    /**
     * @param null $table
     * @param null $field
     * @param null $key
     * @return string
     */
    public function getValue($table = null, $field = null, $key = null)
    {
        $ret = '';
        $key = (string)($key);
        if (is_string($table) && $table != '' && is_string($field) && $field != '' && is_string($key) && $key != '') {
            $ret = isset(self::$lang[$table][$field][$key]) ? self::$lang[$table][$field][$key] : '';
            if (!is_string($ret)) {
                $ret = '';
            }
        }
        return $ret;
    }

}

