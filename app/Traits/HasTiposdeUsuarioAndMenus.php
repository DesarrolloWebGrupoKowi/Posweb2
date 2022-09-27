<?php

namespace App\Traits;

use App\Models\MenuPosweb;
use App\Models\TipoUsuario;
use App\Models\Usuario;

trait HasTiposdeUsuarioAndMenus
{
        /**
         * The roles that belong to the HasTiposdeUsuarioAndMenus
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
         */
        public function TiposUsuario()
        {
            return $this->belongsToMany(TipoUsuario::class, 'CatTipoUsuarios', 'IdTipoUsuario', 'CatUsuarios');
        }

    public function Menus(){
        return $this->belongsToMany(MenuPosweb::class, 'CatMenuPosWeb');
    }
}