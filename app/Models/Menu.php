<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\MenuTipoUsuario;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'CatMenus';
    protected $fillable = ['NomMenu','IdTipoMenu','Link', 'Icono', 'BgColor','Status'];
    public $timestamps = false;
    protected $primaryKey = 'IdMenu'; 

    public function tipoMenu()
    {
        return $this->belongsTo(TipoMenu::class, 'IdTipoMenu');
    }

    public function menuTipoUsuario()
    {
        return $this->hasMany(MenuTipoUsuario::class, 'IdMenu');
    }

}
