--
-- MySQL database dump
-- Created by DbManage class, Power By yanue. 
-- http://yanue.net 
--
-- 主机: localhost
-- 生成日期: 2020 年  06 月 12 日 07:21
-- MySQL版本: 5.6.47
-- PHP 版本: 7.2.30

--
-- 数据库: `account_db`
--

-- -------------------------------------------------------

--
-- 表的结构acc_account
--

DROP TABLE IF EXISTS `acc_account`;
CREATE TABLE `acc_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '账号id',
  `acc_desc` varchar(128) DEFAULT '' COMMENT '描述',
  `user` varchar(128) DEFAULT '' COMMENT '用户名',
  `passwd` varchar(256) DEFAULT '' COMMENT '密码',
  `address` varchar(300) DEFAULT '' COMMENT '登录地址',
  `plat_id` int(11) DEFAULT '0' COMMENT '所属平台',
  `remark` varchar(500) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='账号表';

--
-- 转存表中的数据 acc_account
--

INSERT INTO `acc_account` VALUES('7','GSuite账号','fengzyu@wds.sg','B8S+jgRAQJJKb2aM6jpbhiwNI7COPPZAV+hPyBsjYGOrYFuY27BD6lzmDCFX24twEUr/9j9Hn2Lcq/+U50i/j1r9V58AOI2CqqXKb6bmBgbFRABux8w9a36oKy6Ll5R4vlGVD0g2KLv6L4Fssvhr6L2+kChdoZdkkDL5OohzoTo=','https://www.google.com','20','购买GSuite账号，内含无限空间谷歌云盘');
INSERT INTO `acc_account` VALUES('8','谷歌主账号','chenhuiyiyoung@gmail.com','eOxPDynLzNvfthOJ2Dl7bdp0U21l1yOZOYiA/E8l8QeCPCs4p4lRBSvI81/j5I8DRuOyVKxnMMSWqqJT+F6oCVSMVSbp6ZcCVJBHAIdvasypA9OjGfGMvtYa6PiQhwEBw75QPsEJHqt7x/x8UZUdzF6C0C824GNNN6peqogzOdY=','https://www.google.com','20','主要使用谷歌账号');
INSERT INTO `acc_account` VALUES('9','谷歌小号1','chenhuiyisir@gmail.com','Vo4ew8VRM9MdBNSPEAEoy+40an2GS+wLvsNDAnRicSl9mTneNlUG9W54v5/I33IUYNNXXQbJGzNs6s3uOsInO31hVvg1oBIQuSCxQQyow8CJpg4ULciE0QpQxYZOkMbn1R/yTkh5UGXUGvPlve9PbfiiVB8I/BmUBvu0D2PFMDQ=','https://www.google.com','20','谷歌小号');
INSERT INTO `acc_account` VALUES('10','谷歌小号2','yiaohehe@gmail.com','DKAPG78VazAJhh3bLdhlfhFHhrWQnDPimcCz4Vj/eeX1ou9Dlyt0Kq564635zk4Zn+Dj5jaEyyMAHZ2kIbFV7Hx7ySQH7rl+3JkD7dV+7oUGrStQcndHNmntHorv7hnqjlRwDp9AtsAbSGeeYlOm+AC0okXMwWc0WvfCt8+1xqU=','https://www.google.com','20','谷歌小号');
INSERT INTO `acc_account` VALUES('11','谷歌小号3','fengzyuer@gmail.com','TD7A7zNsAAswlTyXd/Wefr9uFKu3KjxCvydbQyuaxRgowdTlf0s12H25HjGFFffVoICs77cGTIHx1PhgSy3RYatTFkxlx+pPn2xIg5oE9jsm40tKXhW8w7aJqfxQaa79S7qXvxT0ZIgVQUGoYaFJRPgFgeMOSikzE6r0Eed1Qr0=','https://www.google.com','20','谷歌小号');
INSERT INTO `acc_account` VALUES('12','苹果主账号','18749627168@163.com','HnVh0NuIjffGaaVzj9ziotgOYJ4KpQCeUR56TybLoIxL8DZM4dY0E7Yu1MxWbCmPLn1gRmDADfIwUVaYF7IGKX4DQcyalgMRRyBbaVg4vE2l0die3LyKFEHw6//L0DomKkGCL+vh4NkSUSRIA81cPXoJEfqdF4fb4TfPR3dzdmo=','https://www.apple.com.cn','21','苹果主账号');
INSERT INTO `acc_account` VALUES('13','苹果小号1','chenhuiyiyoung@163.com','QlxKB6xXFPy4T2N+xjsIy48CvlE/VB04Ij6SsUdy9Tnw8vTy6njCWTXAtKQRzyt75kUXOF1wjXODFN2OLR2VGo9d/e60laOAunctFEWsUk5xPazXVwQSW8PF7GgsaIfZwquSGMRdQKOvlYoR+n1zaXE3+0jTqyInwXAy4BZ1RXM=','https://www.apple.com.cn','21','苹果小号');
INSERT INTO `acc_account` VALUES('14','苹果小号2','chenhuiyisir@163.com','p4cKo6UvZOvhp2Z2cvmC0KEJbfMdoEll7jtJhGNkr0eZcFcX89o2LvNYAjqTMPTOr7Qicj25MpIJ5XabR/DuvLWBlNqzw+uKPogc5ym2zCCr16Fl6YLPYQ/+xgBCbc/z+kv43Wi5iIsq+xpn8uEy6e2wIMsvYQadaMPHJfOI4hM=','https://www.apple.com.cn','21','苹果小号');
--
-- 表的结构acc_category
--

DROP TABLE IF EXISTS `acc_category`;
CREATE TABLE `acc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `cat_icon` varchar(64) DEFAULT '' COMMENT '分类图标',
  `cat_title` varchar(300) DEFAULT '' COMMENT '分类标题',
  `platform_list` varchar(300) DEFAULT '' COMMENT '平台列表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='分类表';

--
-- 转存表中的数据 acc_category
--

INSERT INTO `acc_category` VALUES('14','gugegoogle114','谷歌','20');
INSERT INTO `acc_category` VALUES('15','apple','苹果','21');
INSERT INTO `acc_category` VALUES('16','xt_weiruan','微软','');
INSERT INTO `acc_category` VALUES('17','kaifa','开发','');
INSERT INTO `acc_category` VALUES('18','13','社交','');
--
-- 表的结构acc_passwd
--

DROP TABLE IF EXISTS `acc_passwd`;
CREATE TABLE `acc_passwd` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '密码id',
  `pass_desc` varchar(128) DEFAULT '' COMMENT '密码描述',
  `passwd` varchar(256) DEFAULT '' COMMENT '密码值',
  `pass_level` int(11) DEFAULT '0' COMMENT '密码安全级别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='密码表';

--
-- 转存表中的数据 acc_passwd
--

INSERT INTO `acc_passwd` VALUES('12','备用密码1','hEaFetFsG7Ckjv+so9sOhyFPWnlwQVrFx+TWMzCui+Z/NpaTl93XobS7ulmo1u1SmVIvWjiiLWiJxrCabETUicRsIi1O4duhmnzIyhTLY+q+0AzUZOAamnboYRrDnhrK1j5lhrd+5Q/CcltjsCZ2xWI2XVHsBdPSCy3YVnJjbsc=','1');
INSERT INTO `acc_passwd` VALUES('13','备用密码2','satLydfwxWfSMaQz5wxP9bkjt8WZe2+mIhHgzmpKYTNZ6InVBYh0tEdRjWwiiC0C6mD01fPlIFQoW5ibNe6Qn6aWuYVPOOhttzlcP1J2jScSRsv5ErlRa1rqDcd+q/fNaK1Vb4RNplxie5F+ZCOZ9cY1CrnGsVzzG0+wxjCWz2A=','2');
INSERT INTO `acc_passwd` VALUES('14','备用密码3','R5WrGBUtgT/TY7jSJZg02uIssJVN8guZnyOw45G/TXakJ+uZo6D8yGG8cpxoyE7LrZmY2B/nIPRfgLH5BwPqbGoSqk64A030/GBovz9SnbiOR3qE/pWmO5ZwV2a9IDPiMUR0FujWlwGCH9sDnfZGb964xn4w2gYg87+ok6bM18w=','3');
INSERT INTO `acc_passwd` VALUES('15','备用密码4','HVVpEUOxO/mcdD5U0fQU4I9H7s4TfDh1sDYCDZD+EtBTeQFj+p1c3xn7g0Q1DlepvbPtmUIlJeoIKiIs6YcaozmNCpWRHqV/EgpcBSbewBYC732ArPdaq2bb0usySG+nLEPVzMMl694A/NGd/vfgxgYZhKSwdYcjtRXZmShlgKk=','4');
INSERT INTO `acc_passwd` VALUES('17','登录密码','QDAoTKf4iGADBGSjt4VXXElC7eanPD3gS9sn3DRZHTBjVpbm/ZQ7Y5a2KEYujU6cjXFJdMudNB06Y1UalS6Gd5ThiYd+EcwKcPsT1Xp5xHdDtJL0lWyirZhRwdOHPQ/P/Xzc0wArFP2hjccJAlucpc8FpN+oOvfAzojzL0/liYQ=','4');
--
-- 表的结构acc_platform
--

DROP TABLE IF EXISTS `acc_platform`;
CREATE TABLE `acc_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '平台id',
  `plat_name` varchar(300) DEFAULT '' COMMENT '平台名称',
  `acc_list` varchar(300) DEFAULT '' COMMENT '账号列表',
  `cat_id` int(11) DEFAULT '0' COMMENT '所属分类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='平台表';

--
-- 转存表中的数据 acc_platform
--

INSERT INTO `acc_platform` VALUES('20','谷歌账号','7,8,9,10,11','14');
INSERT INTO `acc_platform` VALUES('21','苹果账号','12,13,14','15');
