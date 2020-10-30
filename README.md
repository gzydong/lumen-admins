# Lumen Admin 轻量级后台管理系统
 
#### 项目介绍
Lumen Admin 是一个轻量级的后台管理系统，也可以作为一个通用的后台管理框架使用。集成了用户管理、权限管理、日志管理等后台管理框架的通用功能。


#### 环境要求
- PHP 7.2.5+
- Mysql 5.7+

#### 功能模块
- 用户认证 —— 注册、登录、退出；
- 权限管理；

#### 安装流程
克隆源代码
```bash
> git@github.com:gzydong/lumen-admin.git
```

安装 Composer 依赖包
```bash
> cd lumen-template
> composer install
```

赋予storage目录权限
```bash
> chmod -R 755 storage
```

拷贝 .env 文件
```bash
> cp .env.example .env
```

数据库迁移
```bash
> php artisan migrate 
```

添加数据
```bash
> php artisan db:seed
```

#### 使用说明

1. 前端请移步 [https://github.com/gzydong/lumen-admin-vue](https://github.com/gzydong/lumen-admin-vue)
2. xxxx
3. xxxx

#### 参与贡献

1. Fork 本项目
2. 新建 Feat_xxx 分支
3. 提交代码
4. 新建 Pull Request
