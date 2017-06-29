<?php

// 表单类型 :  single | single&input | check | check&input | content

$diaocha_arrs = array(
    'banben'=> '1',
    'title' => '《探墓风云》用户游戏社交行为调查',
    'desc'  => '您好！非常感谢您在百忙中抽时间参与本次的问卷调研！
                为了解您对网络游戏的情况制作本问卷。绝对不会泄露您的个人信息或转为其他用途,
                非常感谢您填写问卷！',
    'forms' => array(
        array(
            'title'   => '1.您是在哪里了解到《探墓风云》的？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:朋友安利',
                'B:游戏媒体报道',
                'C:参加过内网测试',
                'D:各种应用商店/AppStore',
                'E:其他'
            ),
        ),

        array(
            'title' => '2.您每天游戏中主要做什么？【任选3个】*',
            'type'  => 'check',
            'selects' => array(
                'A:做试炼任务',
                'B:刷探墓副本',
                'C:准点抢世界BOSS',
                'D:参与决战地宫',
                'E:世界/家族闲聊',
                'F:去其他国家边境约架、偷BOSS',
                'G:参与国战'
            ),
        ),

        array(
            'title' => '3.您在游戏中的乐趣是什么？【任选3个】*',
            'type'  => 'check',
            'selects' => array(
                'A:聊天、交朋友和好朋友一起玩',
                'B:通过PK战胜对手',
                'C:争做区服最强者、受人崇拜',
                'D:强迫症，每天必须把所有任务做完',
                'E:寻找游戏策略，如何搭配宠物达到低战碾压高战',
                'F:喜欢培养宠物，让所有宠物都毕业',
                'G:闲着无聊，进去打发时间'
            ),
        ),

        array(
            'title'   => '4.您每天花在《探墓风云》上的时间是多少？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:1-2小时',
                'B:3-6小时',
                'C:7-10小时',
                'D:12小时以上',
            ),
        ),

        array(
            'title'   => '5.您喜欢什么类型的游戏？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:动作类（ACT）',
                'B:模拟游戏（SLG）',
                'C:角色扮演（RPG）',
                'D:休闲类',
                'E:我几乎不玩手游',
            ),
        ),

        array(
            'title' => '6.您每天什么时间段玩游戏？【任选3个】*',
            'type'  => 'check',
            'selects' => array(
                'A:早上',
                'B:上午',
                'C:中午',
                'D:下午',
                'E:晚上',
                'F:通宵',
            ),
        ),

        array(
            'title' => '7.您经常看直播么？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:从不看',
                'B:偶尔看',
                'C:天天看',
            ),
        ),

        array(
            'title' => '8.您喜欢看什么类型的小说？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:玄幻',
                'B:仙侠',
                'C:都市',
                'D:历史',
                'E:言情',
            ),
        ),
        array(
            'title'   => '9.您经常使用什么APP看视频？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:腾讯视频',
                'B:爱奇艺',
                'C:乐视TV',
                'D:优酷',
                'E:芒果TV',
                'F:A站',
                'G:B站',
            ),
        ),
        array(
            'title'   => '10.您假期喜欢做什么？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:睡觉',
                'B:逛街',
                'C:约朋友出去浪',
                'D:没朋友，宅在家里玩游戏',
                'E:出去旅游',
            ),
        ),
        array(
            'title'   => '11.如果喜欢上一件东西，但是没有钱买，您会怎么办？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:没钱买什么买，不买！',
                'B:没钱借钱买，任性！',
                'C:信用卡！',
                'D:蚂蚁花呗',
            ),
        ),
        array(
            'title'   => '12.您的性别？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:男',
                'B:女',
                'C:其他',
            ),
        ),
        array(
            'title'   => '13.您的年龄？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:12岁以下',
                'B:12-18岁',
                'C:19-25岁',
                'D:25-29岁',
                'E:30岁以上',
            ),
        ),
        array(
            'title'   => '14.您的职业是？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:学生',
                'B:公司职员',
                'C:自由职业/个体',
                'D:企业管理人员',
                'E:事业单位人员',
                'F:其他',
            ),
        ),
        array(
            'title'   => '15.您月收入多少？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:3000以内',
                'B:3000-5000',
                'C:5000-8000',
                'D:8000-15000',
                'E:15000-30000',
                'F:30000以上',
            ),
        ),
        array(
            'title'   => '16.您现在财产大权谁掌管？【单选】*',
            'type'    => 'single',
            'selects' => array(
                'A:媳妇，老婆最大！',
                'B:老公，老公最有主见',
                'C:男朋友，额……男朋友该换了',
                'D:女朋友，就是这么宠爱',
                'E:单身狗飘过，自己掌管',
                'F:父母大人',
            ),
        ),
        array(
            'title' => '17.你还有哪些想吐槽或者建议？【选做】',
            'type'  => 'content',
        ),

    ),
);
