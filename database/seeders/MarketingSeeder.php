<?php

namespace Database\Seeders;

use App\Models\Marketing;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MarketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $replacements = [
            'Antigüo alumno' => 'Ex alumno',
            'Antiguo alumno' => 'Ex alumno',
            'Página web' => 'Página Web',
            'Pasaba por aquí...' => 'Pasaba por aquí',
            'Gallardete' => 'Volante',
        ];

        foreach ($replacements as $oldName => $newName) {
            Marketing::where('name', $oldName)->update(['name' => $newName]);
        }

        $desiredNames = [
            'Volante',
            'Espectacular',
            'Página Web',
            'RRSS (redes sociales)',
            'Pasaba por aquí',
            'Ex alumno',
            'Recomendación',
        ];

        foreach ($desiredNames as $name) {
            Marketing::firstOrCreate(['name' => $name]);
        }

        $fallback = Marketing::where('name', 'Recomendación')->first();
        $obsolete = Marketing::whereNotIn('name', $desiredNames)->get();

        foreach ($obsolete as $marketing) {
            $targetName = $replacements[$marketing->name] ?? 'Recomendación';
            $target = Marketing::where('name', $targetName)->first() ?: $fallback;

            DB::table('reports')
                ->where('marketing_id', $marketing->id)
                ->update(['marketing_id' => $target->id]);

            $marketing->delete();
        }
    }
}
