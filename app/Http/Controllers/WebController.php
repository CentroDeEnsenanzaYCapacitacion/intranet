<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebCarouselRequest;
use App\Http\Requests\WebMvvRequest;
use App\Models\WebCarousel;
use App\Models\WebMvv;
use Illuminate\Http\Request;

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

        foreach ($images as $key => $image) {
            $data = [
                'title' => $titles[$key],
                'description' => $descriptions[$key],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($image) {
                $filename = ($key + 1) . '.jpg';
                $image->move(public_path('assets/img/carousel/'), $filename);
            }

            WebCarousel::updateOrCreate(
                ['id' => $key + 1],
                $data
            );
        }

        return redirect()->back()->with('success', 'Datos guardados exitosamente');
    }

}
