<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTags extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function (Blueprint $table) {
			$table->engine    = 'InnoDB';
			$table->charset   = 'utf8';
			$table->collation = 'utf8_czech_ci';

			$table->increments('id');
			$table->string('title', 126);
			$table->text('query')->nullable();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->nullable();
			$table->softDeletes();

			$table->index('title', 'idx_tags_title');
		});

		Schema::create('feed_tag', function (Blueprint $table) {
			$table->engine    = 'InnoDB';
			$table->charset   = 'utf8';
			$table->collation = 'utf8_czech_ci';

			$table->unsignedInteger('feed_id');
			$table->unsignedInteger('tag_id');

			$table->index(['feed_id', 'tag_id']);

			$table->foreign('feed_id')
				  ->references('id')
				  ->on('feed');

			$table->foreign('tag_id')
				  ->references('id')
				  ->on('tags');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('feed_tag');
		Schema::dropIfExists('tags');
	}
}
