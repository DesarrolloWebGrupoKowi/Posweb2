<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Status;
use App\Models\TipoUsuario;
use App\Models\UsuarioTienda;
use App\Models\Tienda;
use App\Models\MenuTipoUsuario;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use illuminate\support\Facades\DB;
use App\Traits\HasTiposdeUsuarioAndMenus;
use App\Models\VtMenuTipoUsuario;
use App\Models\Empleado;


class Usuario extends Authenticatable
{
    use hasFactory, HasApiTokens, Notifiable, HasTiposdeUsuarioAndMenus;
    protected $table = 'CatUsuarios';
    protected $fillable = [
        'NomUsuario',
        'NumNomina',
        'password',
        'Correo',
        'IdTipoUsuario',
        'Status'
    ];

    public $timestamps = false;
    protected $primaryKey = 'IdUsuario';
    protected $hidden = ['password'];

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'IdTipoUsuario');
    }

    public function usuarioTienda()
    {
        return $this->belongsTo(UsuarioTienda::class, 'IdUsuario', 'IdUsuario', 'IdUsuarioTienda');
    }
/*NO USADA AL MOMENTO, SE ESTA USANDO LA VISTA DE ABAJO!!:D
    public function menusTipoUsuario()
    {
        return $this->hasMany(MenuTipoUsuario::class, 'IdTipoUsuario', 'IdTipoUsuario');
    }
*/
    public function vistaMenusTipoUsuario()
    {
        return $this->hasMany(VtMenuTipoUsuario::class, 'IdTipoUsuario', 'IdTipoUsuario');
    }

    public function tiendaUsuario(){
        return $this->belongsToMany(Tienda::class, UsuarioTienda::class, 'IdUsuario', 'IdTienda', 'IdUsuario')
                    //->withPivot('IdArticulo', 'CantArticulo', 'SubTotalArticulo', 'IvaArticulo', 'ImporteArticulo', 'PrecioArticulo')
                    ->as('pivotUsuarioTienda');
    }

    public function Empleado(){
        return $this->hasOne(Empleado::class, 'NumNomina', 'NumNomina');
    }
}
