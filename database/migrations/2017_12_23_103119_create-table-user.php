<?php

use App\Database\Greenprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUser extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create('users', function (Blueprint $table) {

			$table->engine    = 'InnoDB';
			$table->charset   = 'utf8';
			$table->collation = 'utf8_czech_ci';

			$table->increments('id');
			$table->string('username', 255);
			$table->string('password', 255);
			$table->string('name', 255);
			$table->boolean('active')->default(true);

			$table->rememberToken();
			$table->timestamps();
			$table->softDeletes();

			$table->unique('username');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
