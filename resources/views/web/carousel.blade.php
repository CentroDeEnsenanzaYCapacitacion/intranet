@extends('layout.mainLayout')
@section('title','Carrusel web')
@section('content')
@if (session('success'))
<div id="success" class="alert alert-success" style="margin-top: 100px;">
    {{ session('success') }}
</div>
@endif
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3 mb-3">
            <div class="col">
                <h1>Carrusel Inicial</h1>
            </div>
        </div>
        <div class="content ml-5">
            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <form id="form" action="{{ route('web.carousel.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <table class="table table-borderless" width="100%">
                    <thead>
                        <tr class="table" style="background-color: #ff6a00;color:white;">
                            <th>
                                #
                            </th>
                            <th>
                                Imagen
                            </th>
                            <th>
                                &nbsp;
                            </th>
                            <th>
                                Título
                            </th>
                            <th>
                                Descripción
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carousels as $index => $carousel)
                        <tr>
                            <td width="5%" style="vertical-align: middle;">
                                {{ $index + 1 }}
                            </td>
                            <td width="15%">
                                <img width="90%" src="{{ asset('assets/img/carousel/' . ($index + 1) . '.jpg') }}" onerror="replace_image(this);" />
                            </td>
                            <td width="15%" style="text-align: left;vertical-align: middle;">
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" accept="image/jpeg, image/png" name="img_{{ $index + 1 }}" id="img_{{ $index + 1 }}">
                                    <label class="custom-file-label" for="img_{{ $index + 1 }}">Seleccionar imagen</label>
                                    @if ($errors->has('img_' . ($index + 1)))
                                        <div class="text-danger">
                                            {{ $errors->first('img_' . ($index + 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td width="15%" style="text-align: left;vertical-align: middle;">
                                <input class="form-control" type="text" name="title{{ $index + 1 }}" value="{{ old('title' . ($index + 1), $carousel->title) }}">
                                @if ($errors->has('title' . ($index + 1)))
                                    <div class="text-danger">
                                        {{ $errors->first('title' . ($index + 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td width="15%" style="text-align: left;vertical-align: middle;">
                                <textarea class="form-control" name="description{{ $index + 1 }}" rows="5" cols="25">{{ old('description' . ($index + 1), $carousel->description) }}</textarea>
                                @if ($errors->has('description' . ($index + 1)))
                                    <div class="text-danger">
                                        {{ $errors->first('description' . ($index + 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td width="5%">
                                &nbsp;
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <table width="100%">
                    <tr>
                        <td style="text-align: center;">
                            <button class="btn bg-orange text-white" onclick="showLoader()" type="submit">Guardar datos</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection
