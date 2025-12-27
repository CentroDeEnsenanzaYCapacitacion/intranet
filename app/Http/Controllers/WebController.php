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
        $carousels = WebCarousel::orderBy('id')->get();
        return view('web.carousel', compact('carousels'));
    }

    public function webCarouselAdd()
    {
        WebCarousel::create([
            'title' => null,
            'description' => null,
        ]);

        return redirect()->route('web.carousel')->with('success', 'Nueva imagen agregada.');
    }

    public function webCarouselDelete(WebCarousel $carousel)
    {
        if (WebCarousel::count() <= 1) {
            return redirect()->back()->withErrors(['carousel' => 'Debe quedar al menos una imagen.']);
        }

        $carouselId = $carousel->id;

        $intranetPath = public_path('assets/img/carousel/');

        $webPath = null;
        $basePath = base_path();

        if (app()->environment('production')) {

            $webPath = dirname($basePath) . '/public_html/intranet/assets/img/carousel/';
        } elseif (app()->environment('development')) {

            $webPath = dirname($basePath) . '/public_html/intranet_dev/assets/img/carousel/';
        }

        $filename = $carouselId . '.jpg';
        $intranetFile = $intranetPath . $filename;

        if (file_exists($intranetFile)) {
            unlink($intranetFile);
        }

        if ($webPath) {
            $webFile = $webPath . $filename;
            if (file_exists($webFile)) {
                unlink($webFile);
            }
        }

        $carousel->delete();

        return redirect()->route('web.carousel')->with('success', 'Imagen eliminada.');
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

        $intranetPath = public_path('assets/img/carousel/');

        $webPath = null;
        $basePath = base_path();

        if (app()->environment('production')) {

            $webPath = dirname($basePath) . '/public_html/intranet/assets/img/carousel/';
        } elseif (app()->environment('development')) {

            $webPath = dirname($basePath) . '/public_html/intranet_dev/assets/img/carousel/';
        }

        if (!file_exists($intranetPath)) {
            mkdir($intranetPath, 0775, true);
        }
        if ($webPath && !file_exists($webPath)) {
            mkdir($webPath, 0775, true);
        }

        Log::info('Carousel upload paths', [
            'base_path' => $basePath,
            'intranet' => $intranetPath,
            'web' => $webPath,
            'environment' => app()->environment(),
            'public_path_result' => public_path('assets/img/carousel/')
        ]);

        $destinationPath = $intranetPath;

        $images = $request->file('img', []);
        $titles = $request->input('title', []);
        $descriptions = $request->input('description', []);

        $carousels = WebCarousel::orderBy('id')->get();

        foreach ($carousels as $carousel) {
            $carouselId = $carousel->id;
            $carousel->title = $titles[$carouselId] ?? $carousel->title;
            $carousel->description = $descriptions[$carouselId] ?? $carousel->description;

            $image = $images[$carouselId] ?? null;

            if ($image) {
                $filename = $carouselId . '.jpg';

                $image->move($destinationPath, $filename);

                if ($webPath) {
                    $sourcePath = $destinationPath . $filename;
                    $targetPath = $webPath . $filename;

                    if (file_exists($sourcePath)) {
                        copy($sourcePath, $targetPath);
                        Log::info('Carousel image copied', [
                            'from' => $sourcePath,
                            'to' => $targetPath
                        ]);
                    }
                }

                $carousel->updated_at = now();
            }

            $carousel->save();
        }

        return redirect()->back()->with('success', 'Datos guardados exitosamente');
    }

}
