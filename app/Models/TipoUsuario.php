<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuTipoUsuario;
use App\Models\TipoMenu;
use App\Models\Usuario;
use App\Models\vtMenuTipoUsuario;
use App\Models\Menu;


class TipoUsuario extends Model
{

    use hasFactory;
    protected $table = 'CatTipoUsuarios';
    protected $fillable = ['NomTipoUsuario','Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdTipoUsuario';

    
    public function menusTipoUsuario()
    {
        return $this->hasMany(MenuTipoUsuario::class, 'IdTipoUsuario');
    }

    public function menus()
    {
        return $this->hasMany(vtMenuTipoUsuario::class, 'IdTipoUsuario');
    }

    public function DetalleMenu()
    {
        return $this->belongsToMany(Menu::class, MenuTipoUsuario::class, 'IdTipoUsuario', 'IdMenu');
                    //->withPivot('NomTipoMenu');
                    //->as('pivotDetalle');
    }

}
