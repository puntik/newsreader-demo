<?php declare(strict_types = 1);

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Clubcard extends Model
{

	protected $table = 'cp_clubcard';

	public $timestamps = false;

	protected $dispatchesEvents = [
		'saved',
	];

	public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Country::class);
	}

	public static function createKey(int $countryId, string $number): string
	{
		return md5(sprintf('clubcard-%d-%d', $countryId, $number));
	}

	public function save(array $options = [])
	{
		$this->key = self::createKey($this->country->id, $this->number);

		parent::save($options);

		event(new \App\Events\Clubcard\SavedEvent($this));
	}

}
