-- {{{ table user_avatar 用户头像表

--
-- 用户头像表
--
-- user_id
--    关联 acct_key.acct_id
-- avatar
--    [头像] 头像图片存储在 filed 中的唯一 id
--

CREATE TABLE `user_avatar` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(128) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table user_basic 用户基础数据表

-- 用户基础数据表
--
-- user_id
--      用户id
-- user_name
--      用户名
-- password
--      用户密码
-- admin_type
--      管理员类型 此用户是否为管理员, 以及管理员的级别.
--      不是管理员: 0 | 系统超级管理员: 1 | 系统管理员: 2 | 域管理员: 3 | 组管理员: 4.
-- level
--      用户级别
-- is_auth
--      是否认证用户
-- description
--      个人描述
--
CREATE TABLE `city_basic` (
`user_id` int(11) NOT NULL DEFAULT '0',
`user_name` varchar(64) NOT NULL DEFAULT '',
`password` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
`admin_type` tinyint(4) NOT NULL DEFAULT '0',
`level` tinyint(4) NOT NULL DEFAULT '0',
`is_auth` tinyint(4) NOT NULL DEFAULT '0',
`description` varchar(320) NOT NULL DEFAULT '',
PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table user_personal 用户个人信息表

--
-- 用户个人信息表
--
-- user_id
--    关联 acct_key.acct_id
-- real_name
--    [真实姓名]
-- gender
--    [性别] 男: 0 | 女: 1
-- birthday
--    [生日] UNIX 时间戳.
-- mobile
--    [手机号码]
-- is_mobile_bound
--    手机号码是否已绑定
-- tel_home
--    [家庭电话]
-- tel_work
--    [工作电话]
-- fax_home
--    [工作传真]
-- fax_work
--    [家庭传真]
-- org_name
--    [单位] 组织/单位名称
-- org_unit
--    [部门] 组织/单位的部门名称
-- role
--    [职务]
--

CREATE TABLE `user_personal` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `real_name` varchar(128) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '0',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `mobile` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `is_mobile_bound` tinyint(4) NOT NULL DEFAULT '0',
  `tel_home` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `tel_work` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `fax_home` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `fax_work` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `org_name` varchar(128) NOT NULL DEFAULT '',
  `org_unit` varchar(128) NOT NULL DEFAULT '',
  `role` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table user_dynamic 用户常用数据表

--
-- 用户常用数据表
-- 存储用户中容易频繁变更的数据
--
-- user_id
--      城市id
-- login_days
--      登录天数
-- last_login_date
--      最后登录日期
-- last_login_ip
--      最后登录ip
-- continuous_login_days
--      连续登录天数
--
CREATE TABLE `city_dynamic` (
`user_id` bigint(20) NOT NULL DEFAULT '0' AUTO_INCREMENT,
`login_days` tinyint(2) NOT NULL DEFAULT '0',
`people_num` bigint(20) NOT NULL DEFAULT '0',
`food_num` bigint(20) NOT NULL DEFAULT '0',
`gold_num` bigint(20) NOT NULL DEFAULT '0',
`tax` int(4) NOT NULL DEFAULT '20',
PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table user_login_log 用户登录日志表

--
-- 用户登录日志表
--
-- auth_type
--    登录类型.
--    0: other
--    1: web_user
--    2: web_admin
--    3: mobile_client
--    4: pc_client
--    7: api
-- user_id
--    关联 user_basic.user_id
-- acct_type
--    关联 user_basic.acct_type
-- user_name
--    关联 user_basic.user_name
-- admin_type
--    关联 user_basic.admin_type
-- client_ip
--    客户端的 IP. 是 pack hex 形式的 ip.
-- server_name
--    服务端的机器名或 IP
-- auth_time
--    登录验证的时间. UNIX 时间戳.
-- result
--    登录验证的结果. 成功: 0 | 失败: 非 0.
--    失败的状态:
--    1: 域不存在
--    2: 用户不存在
--    3: 不允许用户别名或者域别名登录
--    4: 密码错误
--    5: 域过期
--    6: 用户过期
--    7: 用户被锁定
--    8: 超出密码重试次数被锁定
--    9: 第一次登录必须修改密码
--    10:长时间没有修改密码
--

CREATE TABLE `user_login_log` (
  `auth_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `acct_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_name` varchar(64) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `admin_type` tinyint(4) NOT NULL DEFAULT '0',
  `client_ip` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `server_name` varchar(320) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `auth_time` int(11) NOT NULL DEFAULT '0',
  `result` tinyint(4) NOT NULL DEFAULT '0',
  KEY `ik_0` (`acct_id`),
  KEY `ik_1` (`auth_time`,`auth_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table user_following 用户关注表

--
-- 用户关注表
--
-- user_id
--    关联 acct_key.acct_id
-- following
--    用户关注的用户ID
--

CREATE TABLE `user_following` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `following` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}

-- {{{ table photo_basic 照片基础信息表

-- 照片的基础信息表
--
-- photo_id
--      照片id
-- user_id
--      用户id
-- photo_title
--      照片标题
-- upload_date
--      上传日期
-- photo_type
--      照片类型
-- photo_src
--      照片存放位置
--
CREATE TABLE `city_basic` (
`photo_id` int(11) NOT NULL DEFAULT '0',
`user_id` int(11) NOT NULL DEFAULT '0',
`content` varchar(320) NOT NULL DEFAULT '',
`upload_date` int(11) NOT NULL DEFAULT '0',
`photo_type` tinyint(4) NOT NULL DEFAULT '0',
`photo_src` varchar(320) NOT NULL DEFAULT '',
PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table photo_dynamic 照片动态信息表

-- 照片的动态信息表
--
-- photo_id
--      照片id
-- hot
--      热度
-- visits
--      访问次数, 每访问一次都增加 1.
--
CREATE TABLE `city_basic` (
`photo_id` int(11) NOT NULL DEFAULT '0',
`hot` int(11) NOT NULL DEFAULT '0',
`visits` int(11) NOT NULL DEFAULT '0', 
PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}
-- {{{ table photo_comment 照片评论表

-- 照片用户评论表
--
-- comment_id
--      评论ID
-- photo_id
--      照片id
-- user_id
--      用户id
-- type
--      评论类型 0 原始评论 | 1 回复型评论
-- content
--      评论内容
-- date
--      评论日期
-- hot
--      评论热度
-- origin_id
--      原始评论ID
--
CREATE TABLE `city_basic` (
`comment_id` int(11) NOT NULL DEFAULT '0',
`photo_id` int(11) NOT NULL DEFAULT '0',
`user_id` int(11) NOT NULL DEFAULT '0',
`type` tinyint(4) NOT NULL DEFAULT '0',
`content` varchar(320) NOT NULL DEFAULT '',
`date` int(11) NOT NULL DEFAULT '0',
`hot` int(11) NOT NULL DEFAULT '0',
`origin_id` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- }}}

