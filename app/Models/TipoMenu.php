<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\VtMenuTipoUsuario;
use App\Models\TipoUsuario;
use App\Models\MenuTipoUsuario;

class TipoMenu extends Model
{
    use HasFactory;
    protected $table = 'CatTipoMenu';
    protected $fillable = ['NomTipoMenu', 'Posicion', 'Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdTipoMenu';

    public function menus()
    {
        return $this->hasMany(Menu::class, 'IdTipoMenu');
    }

    public function tipoUsuario()
    {
        return $this->hasMany(VtMenuTipoUsuario::class, 'IdTipoMenu');
    }

    public function DetalleMenu(){
        return $this->belongsToMany(MenuTipoUsuario::class, Menu::class, 'IdTipoMenu', 'IdMenu', 'IdTipoMenu',  'IdMenu')
                    ->where('IdTipoUsuario', Auth::user()->IdTipoUsuario)
                    ->orderBy('Posicion')
                    ->withPivot('NomMenu', 'Link', 'Icono', 'BgColor')
                    ->as('PivotMenu');
    }

    public function Ordenar(){
        return $this->belongsToMany(MenuTipoUsuario::class, Menu::class, 'IdTipoMenu', 'IdMenu', 'IdTipoMenu',  'IdMenu')
                    ->withPivot('NomMenu', 'Link', 'Icono', 'BgColor')
                    ->as('PivotMenu');
    }


}
