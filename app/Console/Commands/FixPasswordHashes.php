<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixPasswordHashes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:fix-double-hash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetea la contraseña del superadmin a la del .env en caso de doble encriptación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando y corrigiendo hash de contraseña del superadmin...');

        // Obtener el superadmin
        $superadmin = User::where('username', 'superadmin')->first();

        if (!$superadmin) {
            $this->error('No se encontró el usuario superadmin');
            return 1;
        }

        // Obtener la contraseña del .env
        $passwordFromEnv = env('SEED_ADMIN_PASSWORD');

        if (!$passwordFromEnv) {
            $this->error('No se encontró SEED_ADMIN_PASSWORD en el .env');
            return 1;
        }

        // Verificar si la contraseña actual es válida
        try {
            if (Hash::check($passwordFromEnv, $superadmin->password)) {
                $this->info('La contraseña del superadmin ya es correcta. No se requieren cambios.');
                return 0;
            }
        } catch (\Exception $e) {
            $this->warn('La contraseña actual tiene un hash inválido. Procediendo a corregir...');
        }

        // Regenerar el hash correctamente
        $superadmin->password = Hash::make($passwordFromEnv);
        $superadmin->save();

        $this->info('✓ Contraseña del superadmin regenerada correctamente');
        $this->info('Ahora puedes iniciar sesión con la contraseña del .env');

        return 0;
    }
}
