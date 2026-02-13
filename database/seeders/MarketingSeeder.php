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
        $desiredNames = [
            'Volante',
            'Espectacular',
            'Página Web',
            'RRSS (redes sociales)',
            'Pasaba por aquí',
            'Ex alumno',
            'Recomendación',
        ];

        $replacements = [
            'Antigüo alumno' => 'Ex alumno',
            'Antiguo alumno' => 'Ex alumno',
            'Página web' => 'Página Web',
            'Pasaba por aquí...' => 'Pasaba por aquí',
            'Gallardete' => 'Volante',
        ];

        $canonical = collect();

        foreach ($desiredNames as $name) {
            $current = Marketing::where('name', $name)->orderBy('id')->first();

            if (!$current) {
                $current = Marketing::create(['name' => $name]);
            }

            if ($current->name !== $name) {
                $current->name = $name;
                $current->save();
            }

            $canonical->put($name, $current);

            $duplicates = Marketing::where('name', $name)
                ->where('id', '!=', $current->id)
                ->get();

            foreach ($duplicates as $duplicate) {
                DB::table('reports')
                    ->where('marketing_id', $duplicate->id)
                    ->update(['marketing_id' => $current->id]);

                $duplicate->delete();
            }
        }

        $fallback = $canonical->get('Recomendación');
        $obsolete = Marketing::whereNotIn('id', $canonical->pluck('id')->all())->get();

        foreach ($obsolete as $marketing) {
            $targetName = $replacements[$marketing->name] ?? 'Recomendación';
            $target = $canonical->get($targetName) ?: $fallback;

            DB::table('reports')
                ->where('marketing_id', $marketing->id)
                ->update(['marketing_id' => $target->id]);

            $marketing->delete();
        }
    }
}
