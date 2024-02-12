@extends('layout.mainLayout')
@section('title','Nuevo curso')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1><?php echo isset($course) ? "Modificar Curso" : "Nuevo Curso" ?></h1>
    </div>
</div>
<form action="{{ route('admin.catalogues.courses.insert') }}" method="post">
    @csrf
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="form-group">
                <label for="namer"><b>Nombre(s):</b></label>
                <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)" name="name" <?php echo isset($course) ? "value='$course->name'" : "" ?>>
                <span class="error-message" id="name-error"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="crew_id"><b>Plantel</b></label>
            <select class="form-control text-uppercase" name="crew_id" id="crew_id">
            <?php
                foreach($crews as $crew) {
                    echo '<option value="'.$crew->id.'"'.(isset($course) ? (($course->crew_id == $crew->id) ? " selected" : "") : "").'>'.$crew->name.'</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <button id="saveCourse" type="submit" class="btn bg-orange text-white w-25"><?php echo isset($course) ? "Modificar Curso" : "Guardar Curso" ?></button><br><br>
            <?php if(isset($course)) { ?>
                <input type="hidden" name="editCourseId" value="<?php echo $course->id ?>">
                <a href="adminCourses.php"><button type="button" class="btn btn-outline-orange text-white w-25">Volver</button></a>
            <?php } ?>
        </div>
    </div>
</form>
@endsection