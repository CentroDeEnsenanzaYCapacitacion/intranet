@extends('layout.mainLayout')
@section('title','Modificar usuario')
@section('content')
<form id="editUserForm" method="POST" action="">
    <input type="hidden" name="userId" id="hiddenUserId">
</form>

<div class="row d-flex text-center mt-5">
    <div class="col">
        <h1>Modificar Usuario</h1>
    </div>
</div>
<form id="userForm" action="../../msrvs/users/saveUser.php" method="post">
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <div class="form-group">
                <label for="name"><b>Nombre(s):</b></label>
                <input type="text" class="form-control text-uppercase" id="name" placeholder="Nombre(s)" name="name" <?php echo isset($user) ? "value='$user->name'" : "" ?>>
                <span class="error-message" id="name-error"></span>
            </div>
            <div class="form-group">
                <label for="surnames"><b>Apellidos:</b></label>
                <input type="text" class="form-control text-uppercase" id="surnames" placeholder="Apellidos" name="surnames" <?php echo isset($user) ? "value='$user->surnames'" : "" ?>>
                <span class="error-message" id="surnames-error"></span>
            </div>
            <div class="form-group">
                <label for="email"><b>Correo Electrónico:</b></label>
                <input type="email" class="form-control text-uppercase" id="email" placeholder="Correo Electrónico" name="email" <?php echo isset($user) ? "value='$user->email'" : "" ?>>
                <span class="error-message" id="mail-error"></span>
            </div>
            <div class="form-group">
                <label for="phone"><b>Teléfono:</b></label>
                <input type="text" class="form-control text-uppercase" id="phone" placeholder="Teléfono" name="phone" <?php echo isset($user) ? "value='$user->phone'" : "" ?>>
                <span class="error-message" id="phone-error"></span>
            </div>
            <div class="form-group">
                <label for="cel_phone"><b>Celular:</b></label>
                <input type="text" class="form-control text-uppercase" id="cel_phone" placeholder="Celular" name="cel_phone" <?php echo isset($user) ? "value='$user->cel_phone'" : "" ?>>
                <span class="error-message" id="cel-phone-error"></span>
            </div>
        </div>
        <div class="col">
            <b>Género:</b><br>
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" value="H" class="btn-check" name="genre" id="male" autocomplete="off"  <?php echo isset($user) ? (($user->genre == 'H') ? "checked" : "") : "checked"; ?>>
                    <label class="btn btn-outline-orange text-uppercase" for="male">Hombre</label>

                    <input type="radio" value="M" class="btn-check" name="genre" id="female" autocomplete="off" <?php echo isset($user) ? (($user->genre == 'M') ? "checked" : "") : ""; ?>>
                    <label class="btn btn-outline-orange text-uppercase" for="female">Mujer</label>

                    <input type="radio" value="NB" class="btn-check" name="genre" id="nobinary" autocomplete="off" <?php echo isset($user) ? (($user->genre == 'NB') ? "checked" : "") : ""; ?>>
                    <label class="btn btn-outline-orange text-uppercase" for="nobinary">No binario</label>
                </div>
            </div>
            <div class="form-group">
                <label for="role_id"><b>Rol</b></label>
                <select class="form-control text-uppercase" name="role_id" id="role_id">
                    <?php
                foreach($roles as $role) {
                    echo '<option value="'.$role->id.'"'.(isset($user) && $user->role_id == $role->id ? " selected" : "").'>'.$role->name.'</option>';
                }
?>
                </select>
            </div>
            <div class="form-group">
                <label for="crew_id"><b>Plantel</b></label>
                <select class="form-control text-uppercase" name="crew_id" id="crew_id">
                    <?php
foreach($crews as $crew) {
    if($crew->id > 1) {
        echo '<option value="'.$crew->id.'"'.(isset($user) ? (($user->crew_id == $crew->id) ? " selected" : "") : "").'>'.$crew->name.'</option>';
    }
}
?>
                </select>
            </div>
        </div>
    </div>
    <div class="row d-flex text-center mt-5">
        <div class="col">
            <button id="saveUser" type="submit" class="btn bg-orange text-white w-25"><?php echo isset($user) ? "Modificar Usuario" : "Guardar Usuario" ?></button><br><br>
            <?php if(isset($user)) { ?>
                <input type="hidden" name="editUserId" value="<?php echo $user->id ?>">
                <a href="adminUsers.php"><button type="button" class="btn btn-outline-orange text-white w-25">Volver</button></a>
            <?php } ?>
        </div>
    </div>
</form>
@endsection