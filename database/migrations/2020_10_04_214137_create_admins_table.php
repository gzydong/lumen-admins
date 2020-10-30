<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 30)->unique()->comment('管理员账号');
            $table->string('password', 100)->default('')->comment('登录密码');
            $table->string('email', 50)->default('')->comment('邮箱');
            $table->string('avatar')->default('')->comment('头像');
            $table->string('nickname')->default('', 30)->comment('昵称');
            $table->boolean('status')->default(10)->comment('账号状态[-1:已删除;0:已禁用;10:正常;]');
            $table->dateTime('last_login_time')->comment('最后登录时间');
            $table->string('last_login_ip', 20)->default('')->comment('最后登录IP');
            $table->tinyInteger('is_delete')->default(0)->comment('是否已删除[0:否;1:是]');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });

        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}admins` comment '管理员信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
