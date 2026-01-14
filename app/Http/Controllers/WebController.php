<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebCarouselRequest;
use App\Http\Requests\WebMvvRequest;
use App\Http\Requests\WebOpinionRequest;
use App\Models\WebCarousel;
use App\Models\WebMvv;
use App\Models\WebOpinion;

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

    public function webOpinions()
    {
        $opinions = WebOpinion::orderBy('id')->get();

        $missingCount = 3 - $opinions->count();
        if ($missingCount > 0) {
            for ($i = 0; $i < $missingCount; $i++) {
                WebOpinion::create([
                    'name' => null,
                    'course' => null,
                    'rating' => null,
                    'description' => null,
                    'image_extension' => null,
                ]);
            }

            $opinions = WebOpinion::orderBy('id')->get();
        }

        $opinions->each(function ($opinion) {
            $extension = $opinion->image_extension;
            $filename = $extension ? $opinion->id . '.' . $extension : null;
            $path = $filename ? public_path('assets/img/opinions/' . $filename) : null;
            $hasImage = $path && file_exists($path);

            if (!$hasImage) {
                foreach (['jpg', 'jpeg', 'png'] as $candidate) {
                    $candidateFilename = $opinion->id . '.' . $candidate;
                    $candidatePath = public_path('assets/img/opinions/' . $candidateFilename);
                    if (file_exists($candidatePath)) {
                        $extension = $candidate;
                        $filename = $candidateFilename;
                        $path = $candidatePath;
                        $hasImage = true;
                        break;
                    }
                }
            }

            $opinion->has_image = $hasImage;
            $opinion->image_url = $hasImage
                ? asset('assets/img/opinions/' . $filename) . '?v=' . $opinion->updated_at->timestamp
                : asset('assets/img/nophoto.jpg');
        });

        return view('web.opinions', compact('opinions'));
    }

    public function webOpinionsAdd()
    {
        WebOpinion::create([
            'name' => null,
            'course' => null,
            'rating' => null,
            'description' => null,
            'image_extension' => null,
        ]);

        return redirect()->route('web.opinions')->with('success', 'Nueva opinion agregada.');
    }

    public function webOpinionsDelete(WebOpinion $opinion)
    {
        if (WebOpinion::count() <= 3) {
            return redirect()->back()->withErrors(['opinion' => 'Debe quedar al menos tres opiniones.']);
        }

        $opinionId = $opinion->id;

        $intranetPath = public_path('assets/img/opinions/');

        $webPath = null;
        $basePath = base_path();

        if (app()->environment('production')) {

            $webPath = dirname($basePath) . '/public_html/intranet/assets/img/opinions/';
        } elseif (app()->environment('development')) {

            $webPath = dirname($basePath) . '/public_html/intranet_dev/assets/img/opinions/';
        }

        $extensions = $opinion->image_extension ? [$opinion->image_extension] : ['jpg', 'jpeg', 'png'];

        foreach ($extensions as $extension) {
            $filename = $opinionId . '.' . $extension;
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
        }

        $opinion->delete();

        return redirect()->route('web.opinions')->with('success', 'Opinion eliminada.');
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

    public function webOpinionsPost(WebOpinionRequest $request)
    {
        $request->validated();

        $intranetPath = public_path('assets/img/opinions/');

        $webPath = null;
        $basePath = base_path();

        if (app()->environment('production')) {

            $webPath = dirname($basePath) . '/public_html/intranet/assets/img/opinions/';
        } elseif (app()->environment('development')) {

            $webPath = dirname($basePath) . '/public_html/intranet_dev/assets/img/opinions/';
        }

        if (!file_exists($intranetPath)) {
            mkdir($intranetPath, 0775, true);
        }
        if ($webPath && !file_exists($webPath)) {
            mkdir($webPath, 0775, true);
        }

        $destinationPath = $intranetPath;

        $images = $request->file('img', []);
        $names = $request->input('name', []);
        $courses = $request->input('course', []);
        $ratings = $request->input('rating', []);
        $descriptions = $request->input('description', []);

        $opinions = WebOpinion::orderBy('id')->get();

        foreach ($opinions as $opinion) {
            $opinionId = $opinion->id;
            $image = $images[$opinionId] ?? null;

            $existingFile = $opinion->image_extension
                ? $intranetPath . $opinionId . '.' . $opinion->image_extension
                : null;

            $hasExistingImage = $existingFile && file_exists($existingFile);

            if (!$image && !$hasExistingImage) {
                return redirect()->back()
                    ->withErrors(['img' => 'Debe subir una imagen para todas las opiniones.'])
                    ->withInput();
            }
        }

        foreach ($opinions as $opinion) {
            $opinionId = $opinion->id;
            $opinion->name = $names[$opinionId] ?? $opinion->name;
            $opinion->course = $courses[$opinionId] ?? $opinion->course;
            $opinion->rating = $ratings[$opinionId] ?? $opinion->rating;
            $opinion->description = $descriptions[$opinionId] ?? $opinion->description;

            $image = $images[$opinionId] ?? null;

            if ($image) {
                $extension = strtolower($image->getClientOriginalExtension());
                $filename = $opinionId . '.' . $extension;

                $previousExtension = $opinion->image_extension;
                if ($previousExtension && $previousExtension !== $extension) {
                    $previousFilename = $opinionId . '.' . $previousExtension;
                    $previousIntranetFile = $destinationPath . $previousFilename;
                    if (file_exists($previousIntranetFile)) {
                        unlink($previousIntranetFile);
                    }

                    if ($webPath) {
                        $previousWebFile = $webPath . $previousFilename;
                        if (file_exists($previousWebFile)) {
                            unlink($previousWebFile);
                        }
                    }
                }

                $image->move($destinationPath, $filename);
                $opinion->image_extension = $extension;

                if ($webPath) {
                    $sourcePath = $destinationPath . $filename;
                    $targetPath = $webPath . $filename;

                    if (file_exists($sourcePath)) {
                        copy($sourcePath, $targetPath);
                        Log::info('Opinion image copied', [
                            'from' => $sourcePath,
                            'to' => $targetPath
                        ]);
                    }
                }

                $opinion->updated_at = now();
            }

            $opinion->save();
        }

        return redirect()->back()->with('success', 'Datos guardados exitosamente');
    }

}
