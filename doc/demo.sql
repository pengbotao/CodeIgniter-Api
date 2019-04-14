
-- test库。对应config/database.php中的default配置
CREATE TABLE `user_list` (
  `user_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态|0冻结，1正常，2已删除',
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户列表';

-- demo库。对应config/database.php中的demo配置
CREATE TABLE `order_list` (
  `order_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_title` varchar(100) NOT NULL DEFAULT '' COMMENT '订单标题',
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单列表';

-- 初始化数据。
INSERT INTO user_list (username, `status`) VALUES ("demo", 1);
INSERT INTO order_list (user_id, order_title) VALUES (1, "订单标题");
