<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebCarouselRequest;
use App\Http\Requests\WebMvvRequest;
use App\Models\WebCarousel;
use App\Models\WebMvv;

use Illuminate\Support\Facades\Log;

class WebController extends Controller
{
    public function webMenu()
    {
        return view('web.menu');
    }

    public function webMvv()
    {
        $data = WebMvv::all();
        return view('web.mvv', compact('data'));
    }

    public function webCarousel()
    {
        $carousels = WebCarousel::all();
        return view('web.carousel', compact('carousels'));
    }

    public function webMvvPost(WebMvvRequest $request)
    {
        $validatedData = $request->validated();

        $data = WebMvv::all();

        if (isset($data[0])) {
            $data[0]->description = $validatedData['description1'] ?? '';
        }
        if (isset($data[1])) {
            $data[1]->description = $validatedData['description2'] ?? '';
        }
        if (isset($data[2])) {
            $data[2]->description = $validatedData['description3'] ?? '';
        }
        if (isset($data[3])) {
            $data[3]->description = $validatedData['description4'] ?? '';
        }

        foreach ($data as $item) {
            $item->save();
        }

        return redirect()->route('web.mvv')->with('success', 'Datos guardados correctamente.');
    }

    public function webCarouselPost(WebCarouselRequest $request)
    {

        $images = [
            $request->file('img_1'),
            $request->file('img_2'),
            $request->file('img_3'),
            $request->file('img_4')
        ];

        $titles = [
            $request->input('title1'),
            $request->input('title2'),
            $request->input('title3'),
            $request->input('title4')
        ];

        $descriptions = [
            $request->input('description1'),
            $request->input('description2'),
            $request->input('description3'),
            $request->input('description4')
        ];

        $destinationPath = public_path('assets/img/carousel/');
        Log::info('Destination Path: ' . $destinationPath);

        foreach ($images as $key => $image) {
            $data = [
                'title' => $titles[$key],
                'description' => $descriptions[$key],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($image) {
                $filename = ($key + 1) . '.jpg';
                Log::info('Processing image: ' . $filename);

                try {
                    $image->move($destinationPath, $filename);
                    Log::info('Image moved: ' . $filename);
                } catch (\Exception $e) {
                    Log::error('Error moving image: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['error' => 'Error al mover la imagen: ' . $e->getMessage()]);
                }
            } else {
                Log::info('No image found for key: ' . $key);
            }

            try {
                WebCarousel::updateOrCreate(
                    ['id' => $key + 1],
                    $data
                );
                Log::info('Database updated for key: ' . $key);
            } catch (\Exception $e) {
                Log::error('Error updating database: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Error al actualizar la base de datos: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Datos guardados exitosamente');
    }

}
