<?php

namespace AliAkel\Admin\Auth\Database;

use AliAkel\Admin\Traits\DefaultDatetimeFormat;
use AliAkel\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 */
class Menu extends Model
{
    use DefaultDatetimeFormat;
    use ModelTree {
        ModelTree::boot as treeBoot;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'titles', 'icon', 'uri', 'permission'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.menu_table'));

        parent::__construct($attributes);
    }

    /**
     * A Menu belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $pivotTable = config('admin.database.role_menu_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'menu_id', 'role_id');
    }

    /**
     * @return array
     */
    public function allNodes(): array
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $orderColumn = DB::connection($connection)->getQueryGrammar()->wrap($this->orderColumn);

        $byOrder = 'ROOT ASC,'.$orderColumn;

        $query = static::query();

        if (config('admin.check_menu_roles') !== false) {
            $query->with('roles');
        }

        return collect($query->selectRaw('*, '.$orderColumn.' ROOT')->orderByRaw($byOrder)->get()->toArray())
            ->map(function ($node) {
                $node['title'] = admin_menu_title($node);

                return $node;
            })
            ->all();
    }

    /**
     * @param mixed $value
     *
     * @return array<string, string>
     */
    public function getTitlesAttribute($value): array
    {
        $titles = admin_menu_titles(['titles' => $value, 'title' => '']);

        if ($titles === [] && !empty($this->attributes['title'])) {
            $titles[admin_menu_primary_locale()] = $this->attributes['title'];
        }

        return $titles;
    }

    /**
     * @param mixed $value
     */
    public function setTitlesAttribute($value): void
    {
        $titles = admin_menu_titles(['titles' => $value]);
        $primary = admin_menu_primary_locale();
        $title = $titles[$primary] ?? (reset($titles) ?: ($this->attributes['title'] ?? ''));

        $this->attributes['titles'] = json_encode($titles, JSON_UNESCAPED_UNICODE);
        $this->attributes['title'] = mb_substr((string) $title, 0, 50);
    }

    /**
     * determine if enable menu bind permission.
     *
     * @return bool
     */
    public function withPermission()
    {
        return (bool) config('admin.menu_bind_permission');
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        static::treeBoot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
