<div class="modal fade" id="usuarioModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="frmUsuario" class="modal-content shadow rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="tituloModal">Nuevo Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-4 py-3">
        <input type="hidden" name="Id" id="Id">

        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre</label>
          <input type="text" name="Nombre" class="form-control shadow-sm" required maxlength="255">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Usuario</label>
          <input type="text" name="Usuario" class="form-control shadow-sm" required maxlength="20">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Correo</label>
          <input type="email" name="Correo" class="form-control shadow-sm" required maxlength="100">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Teléfono</label>
          <input type="text" name="Telefono" class="form-control shadow-sm" required maxlength="10">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Edad</label>
          <input type="number" name="Edad" class="form-control shadow-sm" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Contraseña</label>
          <input type="text" name="Contrasena" class="form-control shadow-sm" required maxlength="20">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Rol</label>
          <select name="Rol" class="form-select shadow-sm" required>
            <option value="Administrador">Administrador</option>
            <option value="Supervisor">Supervisor</option>
            <option value="Piloto">Piloto</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Estado</label>
          <select name="Status" class="form-select shadow-sm" required>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>

      </div>

      <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
