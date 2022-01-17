<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($menu) { // before delete() method call this
             $menu->sub_menus()->delete();
             // do the rest of the cleanup...
        });
    }

    public function sub_menus()
    {
        return $this->hasMany(SubMenu::class);
    }
}
