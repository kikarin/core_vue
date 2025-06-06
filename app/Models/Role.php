<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
	use HasFactory, LogsActivity, SoftDeletes, Blameable;
	public $guard_name = 'web';

	const BG_PRIMARY   = 'bg-primary';
	const BG_WARNING   = 'bg-warning';
	const BG_INFO      = 'bg-info';
	const BG_DANGER    = 'bg-danger';
	const BG_SECONDARY = 'bg-secondary';
	const BG_SUCCESS   = 'bg-success';
	const BG_DARK      = 'bg-dark';
	const BG_LIGHT     = 'bg-light text-dark';

	const INIT_PAGE_PROFILE        = 'profile';
	const INIT_PAGE_PROFIL_SEKOLAH = 'profil-sekolah';
	const INIT_PAGE_DASHBOARD      = 'dashboard';
	const INIT_PAGE_USERS          = 'users';

	public function listBg(): array
	{
		return [
			self::BG_PRIMARY   => self::BG_PRIMARY,
			self::BG_WARNING   => self::BG_WARNING,
			self::BG_INFO      => self::BG_INFO,
			self::BG_DANGER    => self::BG_DANGER,
			self::BG_SECONDARY => self::BG_SECONDARY,
			self::BG_SUCCESS   => self::BG_SUCCESS,
			self::BG_DARK      => self::BG_DARK,
			self::BG_LIGHT     => self::BG_LIGHT,
		];
	}

	public function listInitPage()
	{
		return [
			self::INIT_PAGE_PROFILE        => self::INIT_PAGE_PROFILE,
			self::INIT_PAGE_PROFIL_SEKOLAH => self::INIT_PAGE_PROFIL_SEKOLAH,
			self::INIT_PAGE_DASHBOARD      => self::INIT_PAGE_DASHBOARD,
			self::INIT_PAGE_USERS          => self::INIT_PAGE_USERS,
		];
	}

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty()->setDescriptionForEvent(fn(string $eventName) => "Role");
	}

	// Todo: Relation

	public function created_by_user()
	{
		return $this->belongsTo(User::class, 'created_by')->select(['id', 'name', 'file']);
	}

	public function updated_by_user()
	{
		return $this->belongsTo(User::class, 'updated_by')->select(['id', 'name']);
	}
	// Todo: End Relation


	// Todo: Attribute

	public function getBgBadgeAttribute()
	{
		return "<span class='badge $this->bg'>$this->bg</span>";
	}

	public function getIsAllowLoginBadgeAttribute()
	{
		return convertTrueFalse($this->is_allow_login, ["Tidak", "Ya"]);
	}

	public function getIsVerticalMenuBadgeAttribute()
	{
		return convertTrueFalse($this->is_vertical_menu, ["Horizontal", "Vertical"]);
	}
	// Todo: End Attribute


	// Todo: Scope
	public function scopeIdInNotIn($query, $data)
	{
		if (isset($data["id_not_in"])) {
			$query->whereNotIn("id", $data["id_not_in"]);
		}
		if (isset($data["id_in"])) {
			$query->whereIn("id", $data["id_in"]);
		}
	}

	public function scopeFilter($query, $data)
	{
		return $query;
	}
	// Todo: End Scope
}
